<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class READ_model extends CI_Model
{

    /*
     *
     * Player related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function create($insert_columns, $external_sync = false)
    {

        //Set some defaults:
        if (!isset($insert_columns['read__source']) || intval($insert_columns['read__source']) < 1) {
            $insert_columns['read__source'] = 0;
        }

        //Only require link type:
        if (detect_missing_columns($insert_columns, array('read__type'), $insert_columns['read__source'])) {
            return false;
        }

        //Clean metadata is provided:
        if (isset($insert_columns['read__metadata']) && is_array($insert_columns['read__metadata'])) {
            $insert_columns['read__metadata'] = serialize($insert_columns['read__metadata']);
        } else {
            $insert_columns['read__metadata'] = null;
        }

        //Set some defaults:
        if (!isset($insert_columns['read__message'])) {
            $insert_columns['read__message'] = null;
        }


        if (!isset($insert_columns['read__time']) || is_null($insert_columns['read__time'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $insert_columns['read__time'] = $d->format("Y-m-d H:i:s.u");
        }

        if (!isset($insert_columns['read__status'])|| is_null($insert_columns['read__status'])) {
            $insert_columns['read__status'] = 6176; //Link Published
        }

        //Set some zero defaults if not set:
        foreach(array('read__right', 'read__left', 'read__down', 'read__up', 'read__reference', 'read__external', 'read__sort') as $dz) {
            if (!isset($insert_columns[$dz])) {
                $insert_columns[$dz] = 0;
            }
        }

        //Lets log:
        $this->db->insert('mench_read', $insert_columns);


        //Fetch inserted id:
        $insert_columns['read__id'] = $this->db->insert_id();


        //All good huh?
        if ($insert_columns['read__id'] < 1) {

            //This should not happen:
            $this->READ_model->create(array(
                'read__type' => 4246, //Platform Bug Reports
                'read__source' => $insert_columns['read__source'],
                'read__message' => 'create() Failed to create',
                'read__metadata' => array(
                    'input' => $insert_columns,
                ),
            ));

            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($insert_columns['read__up'] > 0) {
                update_algolia('en', $insert_columns['read__up']);
            }

            if ($insert_columns['read__down'] > 0) {
                update_algolia('en', $insert_columns['read__down']);
            }

            if ($insert_columns['read__left'] > 0) {
                update_algolia('in', $insert_columns['read__left']);
            }

            if ($insert_columns['read__right'] > 0) {
                update_algolia('in', $insert_columns['read__right']);
            }
        }


        //SOURCE SYNC Status
        if(in_array($insert_columns['read__type'] , $this->config->item('sources_id_12401'))){
            if($insert_columns['read__down'] > 0){
                $source__id = $insert_columns['read__down'];
            } elseif($insert_columns['read__up'] > 0){
                $source__id = $insert_columns['read__up'];
            }
            $this->SOURCE_model->match_read_status($insert_columns['read__source'], array(
                'source__id' => $source__id,
            ));
        }

        //IDEA SYNC Status
        if(in_array($insert_columns['read__type'] , $this->config->item('sources_id_12400'))){
            if($insert_columns['read__right'] > 0){
                $idea__id = $insert_columns['read__right'];
            } elseif($insert_columns['read__left'] > 0){
                $idea__id = $insert_columns['read__left'];
            }
            $this->IDEA_model->match_read_status($insert_columns['read__source'], array(
                'idea__id' => $idea__id,
            ));
        }

        //Do we need to check for source tagging after read success?
        if(in_array($insert_columns['read__type'] , $this->config->item('sources_id_6255')) && in_array($insert_columns['read__status'] , $this->config->item('sources_id_7359')) && $insert_columns['read__left'] > 0 && $insert_columns['read__source'] > 0){


            //AUTO COMPLETES?
            $ideas_next_autoscan = array();
            $ins = $this->IDEA_model->fetch(array(
                'idea__id' => $insert_columns['read__left'],
            ));

            if(in_array($ins[0]['idea__type'], $this->config->item('sources_id_7712'))){

                //IDEA TYPE SELECT NEXT
                $ideas_next_autoscan = $this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_7704')) . ')' => null, //READ ANSWERED
                    'read__source' => $insert_columns['read__source'],
                    'read__left' => $ins[0]['idea__id'],
                    'read__right>' => 0, //With an answer
                    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                    'idea__type IN (' . join(',', $this->config->item('sources_id_12330')) . ')' => null, //IDEA TYPE COMPLETE IF EMPTY
                ), array('idea_next'), 0);

            } elseif(in_array($ins[0]['idea__type'], $this->config->item('sources_id_13022'))){

                //IDEA TYPE ALL NEXT
                $ideas_next_autoscan = $this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'read__left' => $ins[0]['idea__id'],
                    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                    'idea__type IN (' . join(',', $this->config->item('sources_id_12330')) . ')' => null, //IDEA TYPE COMPLETE IF EMPTY
                ), array('idea_next'), 0);

            }

            foreach($ideas_next_autoscan as $idea_next){
                //IS IT EMPTY?
                if(
                    //No Messages
                    !count($this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__type' => 4231, //IDEA NOTES Messages
                        'read__right' => $idea_next['idea__id'],
                    ))) &&

                    //No Next
                    !count($this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                        'read__left' => $idea_next['idea__id'],
                    ))) &&

                    //Not Already Completed:
                    !count($this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
                        'read__source' => $insert_columns['read__source'],
                        'read__left' => $idea_next['idea__id'],
                    )))){

                    //Mark as complete:
                    $this->READ_model->is_complete($idea_next, array(
                        'read__type' => 4559, //READ MESSAGES
                        'read__source' => $insert_columns['read__source'],
                        'read__left' => $idea_next['idea__id'],
                    ));

                }
            }




            //SOURCE APPEND?
            $detected_read_type = read_detect_type($insert_columns['read__message']);
            if ($detected_read_type['status']) {

                foreach($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type' => 7545, //SOURCE APPEND
                    'read__right' => $ins[0]['idea__id'],
                    'read__up >' => 0, //Source to be tagged for this Idea
                )) as $read_tag){

                    //Generate stats:
                    $links_added = 0;
                    $links_edited = 0;
                    $links_deleted = 0;


                    //Assign tag if parent/child link NOT previously assigned:
                    $existing_links = $this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                        'read__up' => $read_tag['read__up'],
                        'read__down' => $insert_columns['read__source'],
                    ));

                    if(count($existing_links)){

                        //Link previously exists, see if content value is the same:
                        if($existing_links[0]['read__message'] == $insert_columns['read__message'] && $existing_links[0]['read__type'] == $detected_read_type['read__type']){

                            //Everything is the same, nothing to do here:
                            continue;

                        } else {

                            $links_edited++;

                            //Content value has changed, update the link:
                            $this->READ_model->update($existing_links[0]['read__id'], array(
                                'read__message' => $insert_columns['read__message'],
                            ), $insert_columns['read__source'], 10657 /* Player Link Updated Content  */);

                            //Also, did the link type change based on the content change?
                            if($existing_links[0]['read__type'] != $detected_read_type['read__type']){
                                $this->READ_model->update($existing_links[0]['read__id'], array(
                                    'read__type' => $detected_read_type['read__type'],
                                ), $insert_columns['read__source'], 10659 /* Player Link Updated Type */);
                            }

                        }

                    } else {

                        //See if we need to delete single selectable links:
                        foreach($this->config->item('sources_id_6204') as $single_select_source__id){
                            $single_selectable = $this->config->item('sources_id_'.$single_select_source__id);
                            if(is_array($single_selectable) && count($single_selectable) && in_array($read_tag['read__up'], $single_selectable)){
                                //Delete other siblings, if any:
                                foreach($this->READ_model->fetch(array(
                                    'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                                    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                                    'read__up IN (' . join(',', $single_selectable) . ')' => null,
                                    'read__up !=' => $read_tag['read__up'],
                                    'read__down' => $insert_columns['read__source'],
                                )) as $single_selectable_siblings_preset){
                                    $links_deleted += $this->READ_model->update($single_selectable_siblings_preset['read__id'], array(
                                        'read__status' => 6173, //Link Deleted
                                    ), $insert_columns['read__source'], 10673 /* Player Link Unpublished */);
                                }
                            }
                        }

                        //Create link:
                        $links_added++;
                        $this->READ_model->create(array(
                            'read__type' => $detected_read_type['read__type'],
                            'read__message' => $insert_columns['read__message'],
                            'read__source' => $insert_columns['read__source'],
                            'read__up' => $read_tag['read__up'],
                            'read__down' => $insert_columns['read__source'],
                        ));

                    }

                    //Track Tag:
                    $this->READ_model->create(array(
                        'read__type' => 12197, //Tag Player
                        'read__source' => $insert_columns['read__source'],
                        'read__up' => $read_tag['read__up'],
                        'read__down' => $insert_columns['read__source'],
                        'read__left' => $ins[0]['idea__id'],
                        'read__message' => $links_added.' added, '.$links_edited.' edited & '.$links_deleted.' deleted with new content ['.$insert_columns['read__message'].']',
                    ));

                    if($links_added>0 || $links_edited>0 || $links_deleted>0){
                        //See if Session needs to be updated:
                        $session_en = superpower_assigned();
                        if($session_en && $session_en['source__id']==$insert_columns['read__source']){
                            //Yes, update session:
                            $this->SOURCE_model->activate_session($session_en, true);
                        }
                    }
                }
            }
        }


        //See if this link type has any subscribers:
        if(in_array($insert_columns['read__type'] , $this->config->item('sources_id_5967')) && $insert_columns['read__type']!=5967 /* Email Sent causes endless loop */ && !is_dev_environment()){

            //Try to fetch subscribers:
            $sources__5967 = $this->config->item('sources__5967'); //Include subscription details
            $sub_emails = array();
            $sub_source__ids = array();
            foreach(explode(',', $sources__5967[$insert_columns['read__type']]['m_desc']) as $subscriber_source__id){

                //Do not inform the user who just took the action:
                if($subscriber_source__id==$insert_columns['read__source']){
                    continue;
                }

                //Try fetching subscribers email:
                foreach($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                    'read__up' => 3288, //Mench Email
                    'read__down' => $subscriber_source__id,
                ), array('source_portfolio')) as $en_email){
                    if(filter_var($en_email['read__message'], FILTER_VALIDATE_EMAIL)){
                        //All good, add to list:
                        array_push($sub_source__ids , $en_email['source__id']);
                        array_push($sub_emails , $en_email['read__message']);
                    }
                }
            }


            //Did we find any subscribers?
            if(count($sub_source__ids) > 0){

                //yes, start drafting email to be sent to them...

                if($insert_columns['read__source'] > 0){

                    //Fetch player details:
                    $player_ens = $this->SOURCE_model->fetch(array(
                        'source__id' => $insert_columns['read__source'],
                    ));

                    $player_name = $player_ens[0]['source__title'];

                } else {

                    //No player:
                    $player_name = 'MENCH';

                }


                //Email Subject:
                $subject = 'Notification: '  . $player_name . ' ' . $sources__5967[$insert_columns['read__type']]['m_name'];

                //Compose email body, start with link content:
                $html_message = '<div>' . ( strlen($insert_columns['read__message']) > 0 ? $insert_columns['read__message'] : '<i>No link content</i>') . '</div><br />';

                $sources__6232 = $this->config->item('sources__6232'); //PLATFORM VARIABLES

                //Append link object links:
                foreach($this->config->item('sources__11081') as $source__id => $m) {

                    if (!intval($insert_columns[$sources__6232[$source__id]['m_desc']])) {
                        continue;
                    }

                    if (in_array(6202 , $m['m_parents'])) {

                        //IDEA
                        $ins = $this->IDEA_model->fetch(array( 'idea__id' => $insert_columns[$sources__6232[$source__id]['m_desc']] ));
                        $html_message .= '<div>' . $m['m_name'] . ': <a href="'.$this->config->item('base_url').'idea/go/' . $ins[0]['idea__id'] . '" target="_parent">#'.$ins[0]['idea__id'].' '.$ins[0]['idea__title'].'</a></div>';

                    } elseif (in_array(6160 , $m['m_parents'])) {

                        //SOURCE
                        $ens = $this->SOURCE_model->fetch(array( 'source__id' => $insert_columns[$sources__6232[$source__id]['m_desc']] ));
                        $html_message .= '<div>' . $m['m_name'] . ': <a href="'.$this->config->item('base_url').'source/' . $ens[0]['source__id'] . '" target="_parent">@'.$ens[0]['source__id'].' '.$ens[0]['source__title'].'</a></div>';

                    } elseif (in_array(4367 , $m['m_parents'])) {

                        //READ
                        $html_message .= '<div>' . $m['m_name'] . ' ID: <a href="'.$this->config->item('base_url').'source/plugin/12722?read__id=' . $insert_columns[$sources__6232[$source__id]['m_desc']] . '" target="_parent">'.$insert_columns[$sources__6232[$source__id]['m_desc']].'</a></div>';

                    }

                }

                //Finally append READ ID:
                $html_message .= '<div>READ ID: <a href="'.$this->config->item('base_url').'source/plugin/12722?read__id=' . $insert_columns['read__id'] . '">' . $insert_columns['read__id'] . '</a></div>';

                //Inform how to change settings:
                $html_message .= '<div style="color: #DDDDDD; font-size:0.9em; margin-top:20px;">Manage your email notifications via <a href="'.$this->config->item('base_url').'source/5967" target="_blank">@5967</a></div>';

                //Send email:
                $dispatched_email = $this->READ_model->send_email($sub_emails, $subject, $html_message);

                //Log emails sent:
                foreach($sub_source__ids as $to_source__id){
                    $this->READ_model->create(array(
                        'read__type' => 5967, //Link Carbon Copy Email
                        'read__source' => $to_source__id, //Sent to this user
                        'read__metadata' => $dispatched_email, //Save a copy of email
                        'read__reference' => $insert_columns['read__id'], //Save link

                        //Import potential Idea/source connections from link:
                        'read__right' => $insert_columns['read__right'],
                        'read__left' => $insert_columns['read__left'],
                        'read__down' => $insert_columns['read__down'],
                        'read__up' => $insert_columns['read__up'],
                    ));
                }
            }
        }

        //Return:
        return $insert_columns;

    }

    function fetch($match_columns = array(), $join_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('read__id' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('mench_read');

        //Any Idea joins?
        if (in_array('idea_previous', $join_objects)) {
            $this->db->join('mench_idea', 'read__left=idea__id','left');
        } elseif (in_array('idea_next', $join_objects)) {
            $this->db->join('mench_idea', 'read__right=idea__id','left');
        }

        //Any source joins?
        if (in_array('source_profile', $join_objects)) {
            $this->db->join('mench_source', 'read__up=source__id','left');
        } elseif (in_array('source_portfolio', $join_objects)) {
            $this->db->join('mench_source', 'read__down=source__id','left');
        } elseif (in_array('en_type', $join_objects)) {
            $this->db->join('mench_source', 'read__type=source__id','left');
        } elseif (in_array('en_creator', $join_objects)) {
            $this->db->join('mench_source', 'read__source=source__id','left');
        }

        foreach($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        foreach($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }

        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }
        $q = $this->db->get();
        return $q->result_array();
    }

    function update($id, $update_columns, $read__source = 0, $read__type = 0, $read__message = '')
    {

        if (count($update_columns) == 0) {
            return false;
        } elseif ($read__type>0 && !in_array($read__type, $this->config->item('sources_id_4593'))) {
            return false;
        }

        if($read__source > 0){
            //Fetch link before updating:
            $before_data = $this->READ_model->fetch(array(
                'read__id' => $id,
            ));
        }

        //Update metadata if needed:
        if(isset($update_columns['read__metadata']) && is_array($update_columns['read__metadata'])){
            $update_columns['read__metadata'] = serialize($update_columns['read__metadata']);
        }

        //Set content to null if defined as empty:
        if(isset($update_columns['read__message']) && !strlen($update_columns['read__message'])){
            $update_columns['read__message'] = null;
        }

        //Update:
        $this->db->where('read__id', $id);
        $this->db->update('mench_read', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Log changes if successful:
        if ($affected_rows > 0 && $read__source > 0 && $read__type > 0) {

            if(strlen($read__message) == 0){
                if(in_array($read__type, $this->config->item('sources_id_10593') /* Statement */)){

                    //Since it's a statement we want to determine the change in content:
                    if($before_data[0]['read__message']!=$update_columns['read__message']){
                        $read__message .= update_description($before_data[0]['read__message'], $update_columns['read__message']);
                    }

                } else {

                    //Log modification link for every field changed:
                    foreach($update_columns as $key => $value) {
                        if($before_data[0][$key]==$value){
                            continue;
                        }

                        //Now determine what type is this:
                        if($key=='read__status'){

                            $sources__6186 = $this->config->item('sources__6186'); //Read Status
                            $read__message .= view_db_field($key) . ' updated from [' . $sources__6186[$before_data[0][$key]]['m_name'] . '] to [' . $sources__6186[$value]['m_name'] . ']'."\n";

                        } elseif($key=='read__type'){

                            $sources__4593 = $this->config->item('sources__4593'); //Link Types
                            $read__message .= view_db_field($key) . ' updated from [' . $sources__4593[$before_data[0][$key]]['m_name'] . '] to [' . $sources__4593[$value]['m_name'] . ']'."\n";

                        } elseif(in_array($key, array('read__up', 'read__down'))) {

                            //Fetch new/old source names:
                            $before_ens = $this->SOURCE_model->fetch(array(
                                'source__id' => $before_data[0][$key],
                            ));
                            $after_ens = $this->SOURCE_model->fetch(array(
                                'source__id' => $value,
                            ));

                            $read__message .= view_db_field($key) . ' updated from [' . $before_ens[0]['source__title'] . '] to [' . $after_ens[0]['source__title'] . ']' . "\n";

                        } elseif(in_array($key, array('read__left', 'read__right'))) {

                            //Fetch new/old Idea outcomes:
                            $before_ins = $this->IDEA_model->fetch(array(
                                'idea__id' => $before_data[0][$key],
                            ));
                            $after_ins = $this->IDEA_model->fetch(array(
                                'idea__id' => $value,
                            ));

                            $read__message .= view_db_field($key) . ' updated from [' . $before_ins[0]['idea__title'] . '] to [' . $after_ins[0]['idea__title'] . ']' . "\n";

                        } elseif(in_array($key, array('read__message', 'read__sort'))){

                            $read__message .= view_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']'."\n";

                        } else {

                            //Should not log updates since not specifically programmed:
                            continue;

                        }
                    }
                }
            }

            //Determine fields that have changed:
            $fields_changed = array();
            foreach($update_columns as $key => $value) {
                if($before_data[0][$key]!=$value){
                    array_push($fields_changed, array(
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ));
                }
            }

            if(strlen($read__message) > 0 && count($fields_changed) > 0){
                //Value has changed, log link:
                $this->READ_model->create(array(
                    'read__reference' => $id, //Link Reference
                    'read__source' => $read__source,
                    'read__type' => $read__type,
                    'read__message' => $read__message,
                    'read__metadata' => array(
                        'read__id' => $id,
                        'fields_changed' => $fields_changed,
                    ),
                    //Copy old values for parent/child idea/SOURCE LINKS:
                    'read__up' => $before_data[0]['read__up'],
                    'read__down'  => $before_data[0]['read__down'],
                    'read__left' => $before_data[0]['read__left'],
                    'read__right'  => $before_data[0]['read__right'],
                ));
            }
        }

        return $affected_rows;
    }

    function max_order($match_columns)
    {

        //Fetches the maximum order value
        $this->db->select('MAX(read__sort) as largest_order');
        $this->db->from('mench_read');
        foreach($match_columns as $key => $value) {
            $this->db->where($key, $value);
        }
        $q = $this->db->get();
        $stats = $q->row_array();
        return ( count($stats) > 0 ? intval($stats['largest_order']) : 0 );

    }



    function send_email($to_array, $subject, $html_message)
    {

        /*
         *
         * Send an email via our Amazon server
         *
         * */

        if (is_dev_environment()) {
            return false; //We cannot send emails on Dev server
        }

        //Loadup amazon SES:
        require_once('application/libraries/aws/aws-autoloader.php');
        $this->CLIENT = new Aws\Ses\SesClient([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => $this->config->item('cred_aws'),
        ]);

        return $this->CLIENT->sendEmail(array(
            // Source is required
            'Source' => config_var(3288),
            // Destination is required
            'Destination' => array(
                'ToAddresses' => $to_array,
                'CcAddresses' => array(),
                'BccAddresses' => array(),
            ),
            // Message is required
            'Message' => array(
                // Subject is required
                'Subject' => array(
                    // Data is required
                    'Data' => $subject,
                    'Charset' => 'UTF-8',
                ),
                // Body is required
                'Body' => array(
                    'Text' => array(
                        // Data is required
                        'Data' => strip_tags($html_message),
                        'Charset' => 'UTF-8',
                    ),
                    'Html' => array(
                        // Data is required
                        'Data' => $html_message,
                        'Charset' => 'UTF-8',
                    ),
                ),
            ),
            'ReplyToAddresses' => array(config_var(3288)),
            'ReturnPath' => config_var(3288),
        ));
    }


    function send_message($input_message, $recipient_en = array(), $message_idea__id = 0)
    {

        /*
         *
         * The primary function that constructs messages based on the following inputs:
         *
         *
         * - $input_message:        The message text which may include source
         *                          references like "@123". This may NOT include
         *                          URLs as they must be first turned into an
         *                          source and then referenced within a message.
         *
         *
         * - $recipient_en:         The source object that this message is supposed
         *                          to be delivered to. May be an empty array for
         *                          when we want to show these messages to guests,
         *                          and it may contain the full source object or it
         *                          may only contain the source ID, which enables this
         *                          function to fetch further information from that
         *                          source as required based on its other parameters.
         *
         * */

        //This could happen with random messages
        if(strlen($input_message) < 1){
            return false;
        }

        //Validate message:
        $msg_validation = $this->READ_model->send_message_build($input_message, $recipient_en, 0, $message_idea__id, false);


        //Did we have ane error in message validation?
        if (!$msg_validation['status'] || !isset($msg_validation['output_messages'])) {

            //Log Error Link:
            $this->READ_model->create(array(
                'read__type' => 4246, //Platform Bug Reports
                'read__source' => (isset($recipient_en['source__id']) ? $recipient_en['source__id'] : 0),
                'read__message' => 'send_message_build() returned error [' . $msg_validation['message'] . '] for input message [' . $input_message . ']',
                'read__metadata' => array(
                    'input_message' => $input_message,
                    'recipient_en' => $recipient_en,
                    'message_idea__id' => $message_idea__id
                ),
            ));

            return false;
        }

        //Message validation passed...
        $html_message_body = '';
        foreach($msg_validation['output_messages'] as $output_message) {
            $html_message_body .= $output_message['message_body'];
        }
        return $html_message_body;

    }


    function send_message_build($input_message, $recipient_en = array(), $message_type_source__id = 0, $message_idea__id = 0, $strict_validation = true)
    {

        /*
         *
         * This function is used to validate IDEA NOTES.
         *
         * See send_message() for more information on input variables.
         *
         * */


        //Try to fetch session if recipient not provided:
        if(!isset($recipient_en['source__id'])){
            $recipient_en = superpower_assigned();
        }

        $is_being_modified = ( $message_type_source__id > 0 ); //IF $message_type_source__id > 0 means we're adding/editing and need to do extra checks

        //Cleanup:
        $input_message = trim($input_message);
        $input_message = str_replace('’','\'',$input_message);

        //Start with basic input validation:
        if (strlen($input_message) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Message Content',
            );
        } elseif ($strict_validation && strlen($input_message) > config_var(4485)) {
            return array(
                'status' => 0,
                'message' => 'Message is '.strlen($input_message).' characters long which is more than the allowed ' . config_var(4485) . ' characters',
            );
        } elseif (!preg_match('//u', $input_message)) {
            return array(
                'status' => 0,
                'message' => 'Message must be UTF8',
            );
        } elseif ($message_type_source__id > 0 && !in_array($message_type_source__id, $this->config->item('sources_id_4485'))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Message type ID',
            );
        }


        /*
         *
         * Let's do a generic message reference validation
         * that does not consider $message_type_source__id if passed
         *
         * */
        $string_references = extract_source_references($input_message);

        if($strict_validation){
            //Check only in strict mode:
            if (count($string_references['ref_urls']) > 1) {

                return array(
                    'status' => 0,
                    'message' => 'You can reference a maximum of 1 URL per message',
                );

            } elseif (count($string_references['ref_sources']) > 1) {

                return array(
                    'status' => 0,
                    'message' => 'Message can include a maximum of 1 source reference',
                );

            } elseif (count($string_references['ref_sources']) > 0 && count($string_references['ref_urls']) > 0) {

                return array(
                    'status' => 0,
                    'message' => 'You can either reference an source OR a URL, as URLs are transformed to sources',
                );

            }
        }



        /*
         *
         * $message_type_source__id Validation
         * only in strict mode!
         *
         * */
        if($strict_validation && $message_type_source__id > 0){

            //See if this message type has specific input requirements:
            $sources__4485 = $this->config->item('sources__4485');

            //Now check for source referencing settings:
            if(!in_array(4986 , $sources__4485[$message_type_source__id]['m_parents']) && !in_array(7551 , $sources__4485[$message_type_source__id]['m_parents']) && count($string_references['ref_sources']) > 0){

                return array(
                    'status' => 0,
                    'message' => $sources__4485[$message_type_source__id]['m_name'].' do not support source referencing.',
                );

            } elseif(in_array(7551 , $sources__4485[$message_type_source__id]['m_parents']) && count($string_references['ref_sources']) != 1 && count($string_references['ref_urls']) != 1){

                return array(
                    'status' => 0,
                    'message' => $sources__4485[$message_type_source__id]['m_name'].' require an source reference.',
                );

            }

        }





        /*
         *
         * Transform URLs into Player + Links
         *
         * */
        if ($strict_validation && count($string_references['ref_urls']) > 0) {

            //No source linked, but we have a URL that we should turn into an source if not previously:
            $url_source = $this->SOURCE_model->url($string_references['ref_urls'][0], ( isset($recipient_en['source__id']) ? $recipient_en['source__id'] : 0 ));

            //Did we have an error?
            if (!$url_source['status'] || !isset($url_source['en_url']['source__id']) || intval($url_source['en_url']['source__id']) < 1) {
                return $url_source;
            }

            //Transform this URL into an source IF it was found/created:
            if(intval($url_source['en_url']['source__id']) > 0){

                $string_references['ref_sources'][0] = intval($url_source['en_url']['source__id']);

                //Replace the URL with this new @source in message.
                //This is the only valid modification we can do to $input_message before storing it in the DB:
                $input_message = str_replace($string_references['ref_urls'][0], '@' . $string_references['ref_sources'][0], $input_message);

                //Delete URL:
                unset($string_references['ref_urls'][0]);

            }

        }


        /*
         *
         * Process Commands
         *
         * */

        //Start building the Output message body based on format:
        $output_body_message = htmlentities($input_message);



        /*
         *
         * Referenced Player
         *
         * */

        //Will contain media from referenced source:
        $fb_media_attachments = array();

        //We assume this message has text, unless its only content is an source reference like "@123"
        $has_text = true;

        if (count($string_references['ref_sources']) > 0) {

            //We have a reference within this message, let's fetch it to better understand it:
            $ens = $this->SOURCE_model->fetch(array(
                'source__id' => $string_references['ref_sources'][0], //Alert: We will only have a single reference per message
            ));

            if (count($ens) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'The referenced source @' . $string_references['ref_sources'][0] . ' not found',
                );
            }

            //Direct Media URLs supported:
            $sources__6177 = $this->config->item('sources__6177');


            //See if this source has any parent links to be shown in this appendix
            $valid_url = array();
            $message_visual_media = 0;
            $message_any = 0;
            $source_appendix = null;
            $current_mench = current_mench();
            $has_text = substr_count($input_message, ' ');

            //SOURCE IDENTIFIER
            $string_references = extract_source_references($input_message, true);
            $is_current_source = $current_mench['x_name']=='source' && $this->uri->segment(2)==$string_references['ref_sources'][0];


            //Determine what type of Media this reference has:
            //Source Profile
            if(!$is_current_source || $string_references['ref_time_found']){

                foreach($this->READ_model->fetch(array(
                    'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12822')) . ')' => null, //SOURCE LINK MESSAGE DISPLAY
                    'read__down' => $string_references['ref_sources'][0],
                ), array('source_profile'), 0, 0, array('source__id' => 'ASC' /* Hack to get Text first */)) as $parent_en) {

                    $message_any++;

                    if (in_array($parent_en['read__type'], $this->config->item('sources_id_12524'))) {

                        //SOURCE LINK VISUAL
                        $message_visual_media++;

                    } elseif($parent_en['read__type'] == 4256 /* URL */){

                        array_push($valid_url, $parent_en['read__message']);

                    } elseif($parent_en['read__type'] == 4255 /* TEXT */){

                        $source_appendix .= '<div class="source-appendix paddingup">*' . $parent_en['read__message'] . '</div>';
                        continue;

                    } else {

                        //Not supported for now:
                        continue;

                    }

                    $source_appendix .= '<div class="source-appendix paddingup">' . view_read__message($parent_en['read__message'], $parent_en['read__type'], $input_message) . '</div>';

                }
            }



            //Append any appendix generated:
            $single_word_class = ( !substr_count($ens[0]['source__title'], ' ') ? ' inline-block ' : '' );
            $output_body_message .= $source_appendix;
            if($string_references['ref_time_found']){
                $identifier_string = '@' . $string_references['ref_sources'][0].':'.$string_references['ref_time_start'].':'.$string_references['ref_time_end'];
            } else {
                $identifier_string = '@' . $string_references['ref_sources'][0];
            }

            //PLAYER REFERENCE
            if(($current_mench['x_name']=='read' && !superpower_active(10967, true)) || $is_current_source){

                //NO LINK so we can maintain focus...

                if(!$has_text || ($message_any==1 && $message_visual_media==1)){

                    //HIDE
                    $output_body_message = str_replace($identifier_string, '', $output_body_message);

                } else {

                    //TEXT ONLY
                    $output_body_message = str_replace($identifier_string, '<span class="'.$single_word_class.'"><span class="img-block">'.view_source__icon($ens[0]['source__icon']).'</span>&nbsp;<span class="text__6197_' . $ens[0]['source__id']  . '">' . $ens[0]['source__title']  . '</span></span>', $output_body_message);

                }

            } else {

                //FULL SOURCE LINK
                $output_body_message = str_replace($identifier_string, '<a class="montserrat '.$single_word_class.extract_icon_color($ens[0]['source__icon']).'" href="/source/' . $ens[0]['source__id'] . '">'.( !in_array($ens[0]['source__status'], $this->config->item('sources_id_7357')) ? '<span class="img-block icon-block-xs">'.$sources__6177[$ens[0]['source__status']]['m_icon'].'</span> ' : '' ).'<span class="img-block icon-block-xs">'.view_source__icon($ens[0]['source__icon']).'</span><span class="text__6197_' . $ens[0]['source__id']  . '">' . $ens[0]['source__title']  . '</span></a>', $output_body_message);

            }
        }


        //Return results:
        return array(
            'status' => 1,
            'input_message' => trim($input_message),
            'output_messages' => array(
                array(
                    'message_type_source__id' => 4570, //User Received Email Message
                    'message_body' => ( strlen($output_body_message) ? '<div class="i_content padded"><div class="msg">' . nl2br($output_body_message) . '</div></div>' : null ),
                ),
            ),
            'read__up' => (count($string_references['ref_sources']) > 0 ? $string_references['ref_sources'][0] : 0),
        );
    }


    function find_previous($source__id, $idea__id, $public_only = true)
    {

        if($source__id){
            $player_read_ids = $this->READ_model->ids($source__id);
            if(!count($player_read_ids)){
                return 0;
            }
        } else {
            $grand_parents = array();
        }

        //Fetch parents:
        foreach($this->READ_model->fetch(array(
            'idea__status IN (' . join(',', $this->config->item(($public_only ? 'sources_id_7355' : 'sources_id_7356'))) . ')' => null,
            'read__status IN (' . join(',', $this->config->item(($public_only ? 'sources_id_7359' : 'sources_id_7360'))) . ')' => null,
            'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'read__right' => $idea__id,
        ), array('idea_previous'), 0, 0, array(), 'idea__id') as $in_parent) {

            $recursive_parents = $this->READ_model->find_previous(0, $in_parent['idea__id']);

            if($source__id){
                $top_read_ids = array_intersect($player_read_ids, array_flatten($recursive_parents));
                if(count($top_read_ids)){

                    $ins = $this->IDEA_model->fetch(array(
                        'idea__id' => end($top_read_ids),
                    ));

                    //Find the next idea from the top read:
                    return $this->READ_model->find_next($source__id, $ins[0], false);

                }
            } else {
                if(count($recursive_parents)){
                    array_push($grand_parents, array_merge(array(intval($in_parent['idea__id'])), $recursive_parents));
                } else {
                    array_push($grand_parents, array(intval($in_parent['idea__id'])));
                }
            }

        }

        return ( $source__id ? 0 /* We could not find it */ : $grand_parents );
    }

    function find_next($source__id, $in, $first_step = true){

        /*
         *
         * Searches within a user Reads to find
         * first incomplete step.
         *
         * */

        $idea__metadata = unserialize($in['idea__metadata']);

        //Make sure of no terminations first:
        $check_termination_answers = array();

        if(count($idea__metadata['in__metadata_expansion_steps']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($idea__metadata['in__metadata_expansion_steps']));
        }
        if(count($idea__metadata['in__metadata_expansion_some']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($idea__metadata['in__metadata_expansion_some']));
        }
        if(count($idea__metadata['in__metadata_expansion_conditional']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($idea__metadata['in__metadata_expansion_conditional']));
        }
        if(count($check_termination_answers) > 0 && count($this->READ_model->fetch(array(
                'read__type' => 7492, //TERMINATE
                'read__source' => $source__id, //Belongs to this User
                'read__left IN (' . join(',' , $check_termination_answers) . ')' => null, //All possible answers that might terminate...
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            ))) > 0){
            return -1;
        }



        foreach(array_flatten($idea__metadata['in__metadata_common_steps']) as $common_step_idea__id){

            //Is this an expansion step?
            $is_expansion = isset($idea__metadata['in__metadata_expansion_steps'][$common_step_idea__id]) || isset($idea__metadata['in__metadata_expansion_some'][$common_step_idea__id]);
            $is_condition = isset($idea__metadata['in__metadata_expansion_conditional'][$common_step_idea__id]);

            //Have they completed this?
            if($is_expansion){

                //First fetch all possible answers based on correct order:
                $found_expansion = 0;
                foreach($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'read__left' => $common_step_idea__id,
                ), array('idea_next'), 0, 0, array('read__sort' => 'ASC')) as $ln){

                    //See if this answer was selected:
                    if(count($this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINK
                        'read__left' => $common_step_idea__id,
                        'read__right' => $ln['idea__id'],
                        'read__source' => $source__id, //Belongs to this User
                    )))){

                        $found_expansion++;

                        //Yes was answered, see if it's completed:
                        if(!count($this->READ_model->fetch(array(
                            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                            'read__type IN (' . join(',' , $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
                            'read__source' => $source__id, //Belongs to this User
                            'read__left' => $ln['idea__id'],
                        )))){

                            //Answer is not completed, go there:
                            return $ln['idea__id'];

                        } else {

                            //Answer previously completed, see if there is anyting else:
                            $found_idea__id = $this->READ_model->find_next($source__id, $ln, false);
                            if($found_idea__id != 0){
                                return $found_idea__id;
                            }

                        }
                    }
                }

                if(!$found_expansion){
                    return $common_step_idea__id;
                }

            } elseif($is_condition){

                //See which path they got unlocked, if any:
                foreach($this->READ_model->fetch(array(
                    'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINKS
                    'read__source' => $source__id, //Belongs to this User
                    'read__left' => $common_step_idea__id,
                    'read__right IN (' . join(',', $idea__metadata['in__metadata_expansion_conditional'][$common_step_idea__id]) . ')' => null,
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                ), array('idea_next')) as $unlocked_condition){

                    //Completed step that has OR expansions, check recursively to see if next step within here:
                    $found_idea__id = $this->READ_model->find_next($source__id, $unlocked_condition, false);

                    if($found_idea__id != 0){
                        return $found_idea__id;
                    }

                }

            } elseif(!count($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',' , $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
                    'read__source' => $source__id, //Belongs to this User
                    'read__left' => $common_step_idea__id,
                )))){

                //Not completed yet, this is the next step:
                return $common_step_idea__id;

            }

        }


        //If not part of the Reads, go to reads idea
        if($first_step){
            return $this->READ_model->find_previous($source__id, $in['idea__id']);
        }


        //Really not found:
        return 0;

    }

    function find_next_go($source__id)
    {

        /*
         *
         * Searches for the next Reads step
         *
         * */

        $player_reads = $this->READ_model->fetch(array(
            'read__source' => $source__id,
            'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ), array('idea_previous'), 0, 0, array('read__sort' => 'ASC'));

        if(!count($player_reads)){
            return 0;
        }

        //Loop through Reads Ideas and see what's next:
        foreach($player_reads as $user_in){

            //Find first incomplete step for this Reads Idea:
            $next_idea__id = $this->READ_model->find_next($source__id, $user_in);

            if($next_idea__id < 0){

                //We need to terminate this:
                $this->READ_model->delete($source__id, $user_in['idea__id'], 7757); //MENCH REMOVED BOOKMARK
                break;

            } elseif($next_idea__id > 0){

                //We found the next incomplete step, return:
                break;

            }
        }

        //Return next step Idea or false:
        return intval($next_idea__id);

    }


    function focus($source__id){

        /*
         *
         * A function that goes through the Reads
         * and finds the top-priority that the user
         * is currently working on.
         *
         * */

        $top_priority_in = false;
        foreach($this->READ_model->fetch(array(
            'read__source' => $source__id,
            'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ), array('idea_previous'), 0, 0, array('read__sort' => 'ASC')) as $home_in){

            //See progress rate so far:
            $completion_rate = $this->READ_model->completion_progress($source__id, $home_in);

            if($completion_rate['completion_percentage'] < 100){
                //This is the top priority now:
                $top_priority_in = $home_in;
                break;
            }

        }

        if(!$top_priority_in){
            return false;
        }

        //Return what's found:
        return array(
            'in' => $top_priority_in,
            'completion_rate' => $completion_rate,
        );

    }

    function delete($source__id, $idea__id, $stop_method_id, $stop_feedback = null){


        if(!in_array($stop_method_id, $this->config->item('sources_id_6150') /* Reads Idea Completed */)){
            return array(
                'status' => 0,
                'message' => 'Invalid stop method',
            );
        }

        //Validate idea to be deleted:
        $ins = $this->IDEA_model->fetch(array(
            'idea__id' => $idea__id,
        ));
        if (count($ins) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea',
            );
        }

        //Go ahead and delete from Reads:
        $player_reads = $this->READ_model->fetch(array(
            'read__source' => $source__id,
            'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__left' => $idea__id,
        ));
        if(count($player_reads) < 1){
            return array(
                'status' => 0,
                'message' => 'Could not locate Reads',
            );
        }

        //Delete Bookmark:
        foreach($player_reads as $ln){
            $this->READ_model->update($ln['read__id'], array(
                'read__message' => $stop_feedback,
                'read__status' => 6173, //DELETED
            ), $source__id, $stop_method_id);
        }

        return array(
            'status' => 1,
            'message' => 'Success',
        );

    }

    function start($source__id, $idea__id, $recommender_idea__id = 0){

        //Validate Idea ID:
        $ins = $this->IDEA_model->fetch(array(
            'idea__id' => $idea__id,
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ));
        if (count($ins) != 1) {
            return false;
        }


        //Make sure not previously added to this User's Reads:
        if(!count($this->READ_model->fetch(array(
                'read__source' => $source__id,
                'read__left' => $idea__id,
                'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            )))){

            //Not added to their Reads so far, let's go ahead and add it:
            $in_rank = 1;
            $home = $this->READ_model->create(array(
                'read__type' => ( $recommender_idea__id > 0 ? 7495 /* User Idea Recommended */ : 4235 /* User Idea Set */ ),
                'read__source' => $source__id, //Belongs to this User
                'read__left' => $ins[0]['idea__id'], //The Idea they are adding
                'read__right' => $recommender_idea__id, //Store the recommended idea
                'read__sort' => $in_rank, //Always place at the top of their Reads
            ));

            //Mark as readed if possible:
            if($ins[0]['idea__type']==6677){
                $this->READ_model->is_complete($ins[0], array(
                    'read__type' => 4559, //READ MESSAGES
                    'read__source' => $source__id,
                    'read__left' => $ins[0]['idea__id'],
                ));
            }

            //Move other ideas down in the Reads:
            foreach($this->READ_model->fetch(array(
                'read__id !=' => $home['read__id'], //Not the newly added idea
                'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__source' => $source__id, //Belongs to this User
            ), array(''), 0, 0, array('read__sort' => 'ASC')) as $current_ins){

                //Increase rank:
                $in_rank++;

                //Update order:
                $this->READ_model->update($current_ins['read__id'], array(
                    'read__sort' => $in_rank,
                ), $source__id, 10681 /* Ideas Ordered Automatically  */);
            }

        }

        return true;

    }




    function completion_recursive_up($source__id, $in, $is_bottom_level = true){

        /*
         *
         * Let's see how many steps get unlocked @6410
         *
         * */


        //First let's make sure this entire Idea completed by the user:
        $completion_rate = $this->READ_model->completion_progress($source__id, $in);


        if($completion_rate['completion_percentage'] < 100){
            //Not completed, so can't go further up:
            return array();
        }


        //Look at Conditional Idea Links ONLY at this level:
        $idea__metadata = unserialize($in['idea__metadata']);
        if(isset($idea__metadata['in__metadata_expansion_conditional'][$in['idea__id']]) && count($idea__metadata['in__metadata_expansion_conditional'][$in['idea__id']]) > 0){

            //Make sure previous link unlocks have NOT happened before:
            $existing_expansions = $this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type' => 6140, //READ UNLOCK LINK
                'read__source' => $source__id,
                'read__left' => $in['idea__id'],
                'read__right IN (' . join(',', $idea__metadata['in__metadata_expansion_conditional'][$in['idea__id']]) . ')' => null, //Limit to cached answers
            ));
            if(count($existing_expansions) > 0){

                //Oh we do have an expansion that previously happened! So skip this:
                /*
                 * This was being triggered but I am not sure if its normal or not!
                 * For now will comment out so no errors are logged
                 * TODO: See if you can make sense of this section. The question is
                 * if we would ever try to process a conditional step twice? If it
                 * happens, is it an error or not, and should simply be ignored?
                 *
                $this->READ_model->create(array(
                    'read__left' => $in['idea__id'],
                    'read__right' => $existing_expansions[0]['read__right'],
                    'read__message' => 'completion_recursive_up() detected duplicate Label Expansion entries',
                    'read__type' => 4246, //Platform Bug Reports
                    'read__source' => $source__id,
                ));
                */

                return array();

            }


            //Yes, Let's calculate user's score for this idea:
            $user_marks = $this->READ_model->completion_marks($source__id, $in);





            //Detect potential conditional steps to be Unlocked:
            $found_match = 0;
            $locked_links = $this->READ_model->fetch(array(
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',', $this->config->item('sources_id_12842')) . ')' => null, //IDEA LINKS ONE-WAY
                'read__left' => $in['idea__id'],
                'read__right IN (' . join(',', $idea__metadata['in__metadata_expansion_conditional'][$in['idea__id']]) . ')' => null, //Limit to cached answers
            ), array('idea_next'), 0, 0);


            foreach($locked_links as $locked_link) {

                //See if it unlocks any of these ranges defined in the metadata:
                $read__metadata = unserialize($locked_link['read__metadata']);

                //Defines ranges:
                if(!isset($read__metadata['tr__conditional_score_min'])){
                    $read__metadata['tr__conditional_score_min'] = 0;
                }
                if(!isset($read__metadata['tr__conditional_score_max'])){
                    $read__metadata['tr__conditional_score_max'] = 0;
                }


                if($user_marks['steps_answered_score']>=$read__metadata['tr__conditional_score_min'] && $user_marks['steps_answered_score']<=$read__metadata['tr__conditional_score_max']){

                    //Found a match:
                    $found_match++;

                    //Unlock Reads:
                    $this->READ_model->create(array(
                        'read__type' => 6140, //READ UNLOCK LINK
                        'read__source' => $source__id,
                        'read__left' => $in['idea__id'],
                        'read__right' => $locked_link['idea__id'],
                        'read__metadata' => array(
                            'completion_rate' => $completion_rate,
                            'user_marks' => $user_marks,
                            'condition_ranges' => $locked_links,
                        ),
                    ));

                }
            }

            //We must have exactly 1 match by now:
            if($found_match != 1){
                $this->READ_model->create(array(
                    'read__message' => 'completion_recursive_up() found ['.$found_match.'] routing logic matches!',
                    'read__type' => 4246, //Platform Bug Reports
                    'read__source' => $source__id,
                    'read__left' => $in['idea__id'],
                    'read__metadata' => array(
                        'completion_rate' => $completion_rate,
                        'user_marks' => $user_marks,
                        'conditional_ranges' => $locked_links,
                    ),
                ));
            }

        }


        //Now go up since we know there are more levels...
        if($is_bottom_level){

            //Fetch user ideas:
            $player_read_ids = $this->READ_model->ids($source__id);

            //Prevent duplicate processes even if on multiple parent ideas:
            $parents_checked = array();

            //Go through parents ideas and detect intersects with user ideas. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
            foreach($this->IDEA_model->recursive_parents($in['idea__id']) as $grand_parent_ids) {

                //Does this parent and its grandparents have an intersection with the user ideas?
                if(!array_intersect($grand_parent_ids, $player_read_ids)){
                    //Parent idea is NOT part of their Reads:
                    continue;
                }

                //Let's go through until we hit their intersection
                foreach($grand_parent_ids as $p_id){

                    //Make sure not duplicated:
                    if(in_array($p_id , $parents_checked)){
                        continue;
                    }

                    array_push($parents_checked, $p_id);

                    //Fetch parent idea:
                    $parent_ins = $this->IDEA_model->fetch(array(
                        'idea__id' => $p_id,
                        'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                    ));

                    //Now see if this child completion resulted in a full parent completion:
                    if(count($parent_ins) > 0){

                        //Fetch parent completion:
                        $this->READ_model->completion_recursive_up($source__id, $parent_ins[0], false);

                    }

                    //Terminate if we reached the Reads idea level:
                    if(in_array($p_id , $player_read_ids)){
                        break;
                    }
                }
            }
        }


        return true;
    }


    function unlock_locked_step($source__id, $in){

        /*
         * A function that starts from a locked idea and checks:
         *
         * 1. List users who have completed ALL/ANY (Depending on AND/OR Lock) of its children
         * 2. If > 0, then goes up recursively to see if these completions unlock other completions
         *
         * */

        if(!in_is_unlockable($in)){
            return array(
                'status' => 0,
                'message' => 'Not a valid locked idea type and status',
            );
        }


        $ideas_next = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'read__left' => $in['idea__id'],
        ), array('idea_next'), 0, 0, array('read__sort' => 'ASC'));
        if(count($ideas_next) < 1){
            return array(
                'status' => 0,
                'message' => 'Idea has no child ideas',
            );
        }



        /*
         *
         * Now we need to determine idea completion method.
         *
         * It's one of these two cases:
         *
         * AND Ideas are completed when all their children are completed
         *
         * OR Ideas are completed when a single child is completed
         *
         * */
        $requires_all_children = ( $in['idea__type'] == 6914 /* AND Lock, meaning all children are needed */ );

        //Generate list of users who have completed it:
        $qualified_completed_users = array();

        //Go through children and see how many completed:
        foreach($ideas_next as $count => $child_in){

            //Fetch users who completed this:
            if($count==0){

                //Always add all the first users to the full list:
                $qualified_completed_users = $this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null, //READ COIN
                    'read__left' => $child_in['idea__id'],
                ), array(), 0, 0, array(), 'COUNT(read__id) as totals');

                if($requires_all_children && count($qualified_completed_users)==0){
                    //No users found that would meet all children requirements:
                    break;
                }

            } else {

                //2nd Update onwards, by now we must have a base:
                if($requires_all_children){

                    //Update list of qualified users:
                    $qualified_completed_users = $this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null, //READ COIN
                        'read__left' => $child_in['idea__id'],
                    ), array(), 0, 0, array(), 'COUNT(read__id) as totals');

                }

            }
        }

        if(count($qualified_completed_users) > 0){
            return array(
                'status' => 0,
                'message' => 'No users found to have completed',
            );
        }

    }


    function in_home($idea__id, $recipient_en){

        $read_in_home = false;

        if($recipient_en['source__id'] > 0){

            //Fetch entire Reads:
            $player_read_ids = $this->READ_model->ids($recipient_en['source__id']);
            $read_in_home = in_array($idea__id, $player_read_ids);

            if(!$read_in_home){
                //Go through parents ideas and detect intersects with user ideas. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
                foreach($this->IDEA_model->recursive_parents($idea__id) as $grand_parent_ids) {
                    //Does this parent and its grandparents have an intersection with the user ideas?
                    if (array_intersect($grand_parent_ids, $player_read_ids)) {
                        //Idea is part of their Reads:
                        $read_in_home = true;
                        break;
                    }
                }
            }
        }

        return $read_in_home;

    }


    function is_complete($in, $insert_columns){

        //Log completion link:
        $new_link = $this->READ_model->create($insert_columns);

        //Process completion automations:
        $this->READ_model->completion_recursive_up($insert_columns['read__source'], $in);

        return $new_link;

    }

    function completion_marks($source__id, $in, $top_level = true)
    {

        //Fetch/validate Reads Common Ideas:
        $idea__metadata = unserialize($in['idea__metadata']);
        if(!isset($idea__metadata['in__metadata_common_steps'])){

            //Should not happen, log error:
            $this->READ_model->create(array(
                'read__message' => 'completion_marks() Detected user Reads without in__metadata_common_steps value!',
                'read__type' => 4246, //Platform Bug Reports
                'read__source' => $source__id,
                'read__left' => $in['idea__id'],
            ));

            return 0;
        }

        //Generate flat steps:
        $flat_common_steps = array_flatten($idea__metadata['in__metadata_common_steps']);

        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            //Generic assessment marks stats:
            'steps_question_count' => 0, //The parent idea
            'steps_marks_min' => 0,
            'steps_marks_max' => 0,

            //User answer stats:
            'steps_answered_count' => 0, //How many they have answered so far
            'steps_answered_marks' => 0, //Indicates completion score

            //Calculated at the end:
            'steps_answered_score' => 0, //Used to determine which label to be unlocked...
        );


        //Process Answer ONE:
        if(isset($idea__metadata['in__metadata_expansion_steps']) && count($idea__metadata['in__metadata_expansion_steps']) > 0){

            //We need expansion steps (OR Ideas) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_idea__ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($idea__metadata['in__metadata_expansion_steps'] as $question_idea__id => $answers_idea__ids ){

                //Calculate local min/max marks:
                array_push($question_idea__ids, $question_idea__id);
                $metadata_this['steps_question_count'] += 1;
                $local_min = null;
                $local_max = null;

                //Calculate min/max points for this based on answers:
                foreach($this->READ_model->fetch(array(
                    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'read__left' => $question_idea__id,
                    'read__right IN (' . join(',', $answers_idea__ids) . ')' => null, //Limit to cached answers
                ), array('idea_next')) as $in_answer){

                    //Extract Link Metadata:
                    $possible_answer_metadata = unserialize($in_answer['read__metadata']);

                    //Assign to this question:
                    $answer_marks_index[$in_answer['idea__id']] = ( isset($possible_answer_metadata['tr__assessment_points']) ? intval($possible_answer_metadata['tr__assessment_points']) : 0 );

                    //Addup local min/max marks:
                    if(is_null($local_min) || $answer_marks_index[$in_answer['idea__id']] < $local_min){
                        $local_min = $answer_marks_index[$in_answer['idea__id']];
                    }
                    if(is_null($local_max) || $answer_marks_index[$in_answer['idea__id']] > $local_max){
                        $local_max = $answer_marks_index[$in_answer['idea__id']];
                    }
                }

                //Did we have any marks for this question?
                if(!is_null($local_min)){
                    $metadata_this['steps_marks_min'] += $local_min;
                }
                if(!is_null($local_max)){
                    $metadata_this['steps_marks_max'] += $local_max;
                }
            }



            //Now let's check user answers to see what they have done:
            $total_completion = $this->READ_model->fetch(array(
                'read__source' => $source__id, //Belongs to this User
                'read__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
                'read__left IN (' . join(',', $question_idea__ids ) . ')' => null,
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            ), array(), 0, 0, array(), 'COUNT(read__id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->READ_model->fetch(array(
                'read__source' => $source__id, //Belongs to this User
                'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINKS
                'read__left IN (' . join(',', $question_idea__ids ) . ')' => null,
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            ), array('idea_next'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->READ_model->completion_marks($source__id, $answer_in, false);

                $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];
                $metadata_this['steps_answered_marks'] += $answer_marks_index[$answer_in['idea__id']] + $recursive_stats['steps_answered_marks'];

            }
        }


        //Process Answer SOME:
        if(isset($idea__metadata['in__metadata_expansion_some']) && count($idea__metadata['in__metadata_expansion_some']) > 0){

            //We need expansion steps (OR Ideas) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_idea__ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($idea__metadata['in__metadata_expansion_some'] as $question_idea__id => $answers_idea__ids ){

                //Calculate local min/max marks:
                array_push($question_idea__ids, $question_idea__id);
                $metadata_this['steps_question_count'] += 1;
                $local_min = null;
                $local_max = null;

                //Calculate min/max points for this based on answers:
                foreach($this->READ_model->fetch(array(
                    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'read__left' => $question_idea__id,
                    'read__right IN (' . join(',', $answers_idea__ids) . ')' => null, //Limit to cached answers
                ), array('idea_next')) as $in_answer){

                    //Extract Link Metadata:
                    $possible_answer_metadata = unserialize($in_answer['read__metadata']);

                    //Assign to this question:
                    $answer_marks_index[$in_answer['idea__id']] = ( isset($possible_answer_metadata['tr__assessment_points']) ? intval($possible_answer_metadata['tr__assessment_points']) : 0 );

                    //Addup local min/max marks:
                    if(is_null($local_min) || $answer_marks_index[$in_answer['idea__id']] < $local_min){
                        $local_min = $answer_marks_index[$in_answer['idea__id']];
                    }
                }

                //Did we have any marks for this question?
                if(!is_null($local_min)){
                    $metadata_this['steps_marks_min'] += $local_min;
                }

                //Always Add local max:
                $metadata_this['steps_marks_max'] += $answer_marks_index[$in_answer['idea__id']];

            }



            //Now let's check user answers to see what they have done:
            $total_completion = $this->READ_model->fetch(array(
                'read__source' => $source__id, //Belongs to this User
                'read__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
                'read__left IN (' . join(',', $question_idea__ids ) . ')' => null,
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            ), array(), 0, 0, array(), 'COUNT(read__id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->READ_model->fetch(array(
                'read__source' => $source__id, //Belongs to this User
                'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINKS
                'read__left IN (' . join(',', $question_idea__ids ) . ')' => null,
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            ), array('idea_next'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->READ_model->completion_marks($source__id, $answer_in, false);

                $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];
                $metadata_this['steps_answered_marks'] += $answer_marks_index[$answer_in['idea__id']] + $recursive_stats['steps_answered_marks'];

            }
        }



        if($top_level && $metadata_this['steps_answered_count'] > 0){

            $divider = ( $metadata_this['steps_marks_max'] - $metadata_this['steps_marks_min'] ) * 100;

            if($divider > 0){
                //See assessment summary:
                $metadata_this['steps_answered_score'] = floor( ($metadata_this['steps_answered_marks'] - $metadata_this['steps_marks_min']) / $divider );
            } else {
                //See assessment summary:
                $metadata_this['steps_answered_score'] = 0;
            }

        }


        //Return results:
        return $metadata_this;

    }



    function completion_progress($source__id, $in, $top_level = true)
    {

        if(!isset($in['idea__metadata'])){
            return false;
        }

        //Fetch/validate Reads Common Ideas:
        $idea__metadata = unserialize($in['idea__metadata']);
        if(!isset($idea__metadata['in__metadata_common_steps'])){
            //Since it's not there yet we assume the idea it self only!
            $idea__metadata['in__metadata_common_steps'] = array($in['idea__id']);
        }


        //Generate flat steps:
        $flat_common_steps = array_flatten($idea__metadata['in__metadata_common_steps']);


        //Count totals:
        $common_totals = $this->IDEA_model->fetch(array(
            'idea__id IN ('.join(',',$flat_common_steps).')' => null,
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ), 0, 0, array(), 'COUNT(idea__id) as total_steps, SUM(idea__duration) as total_seconds');


        //Count completed for user:
        $common_completed = $this->READ_model->fetch(array(
            'read__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
            'read__source' => $source__id, //Belongs to this User
            'read__left IN (' . join(',', $flat_common_steps ) . ')' => null,
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ), array('idea_previous'), 0, 0, array(), 'COUNT(idea__id) as completed_steps, SUM(idea__duration) as completed_seconds');


        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            'steps_total' => intval($common_totals[0]['total_steps']),
            'steps_completed' => intval($common_completed[0]['completed_steps']),
            'seconds_total' => intval($common_totals[0]['total_seconds']),
            'seconds_completed' => intval($common_completed[0]['completed_seconds']),
        );


        //Expansion Answer ONE
        $answer_array = array();
        if(isset($idea__metadata['in__metadata_expansion_steps']) && count($idea__metadata['in__metadata_expansion_steps']) > 0) {
            $answer_array = array_merge($answer_array , array_flatten($idea__metadata['in__metadata_expansion_steps']));
        }
        if(isset($idea__metadata['in__metadata_expansion_some']) && count($idea__metadata['in__metadata_expansion_some']) > 0) {
            $answer_array = array_merge($answer_array , array_flatten($idea__metadata['in__metadata_expansion_some']));
        }

        if(count($answer_array)){

            //Now let's check user answers to see what they have done:
            foreach($this->READ_model->fetch(array(
                'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINKS
                'read__source' => $source__id, //Belongs to this User
                'read__left IN (' . join(',', $flat_common_steps ) . ')' => null,
                'read__right IN (' . join(',', $answer_array) . ')' => null,
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            ), array('idea_next')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->READ_model->completion_progress($source__id, $expansion_in, false);

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];
                $metadata_this['seconds_total'] += $recursive_stats['seconds_total'];
                $metadata_this['seconds_completed'] += $recursive_stats['seconds_completed'];
            }
        }


        //Expansion steps Recursive
        if(isset($idea__metadata['in__metadata_expansion_conditional']) && count($idea__metadata['in__metadata_expansion_conditional']) > 0){

            //Now let's check if user has unlocked any Miletones:
            foreach($this->READ_model->fetch(array(
                'read__type' => 6140, //READ UNLOCK LINK
                'read__source' => $source__id, //Belongs to this User
                'read__left IN (' . join(',', $flat_common_steps ) . ')' => null,
                'read__right IN (' . join(',', array_flatten($idea__metadata['in__metadata_expansion_conditional'])) . ')' => null,
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            ), array('idea_next')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->READ_model->completion_progress($source__id, $expansion_in, false);

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];
                $metadata_this['seconds_total'] += $recursive_stats['seconds_total'];
                $metadata_this['seconds_completed'] += $recursive_stats['seconds_completed'];

            }
        }


        if($top_level){

            /*
             *
             * Completing an Reads depends on two factors:
             *
             * 1) number of steps (some may have 0 time estimate)
             * 2) estimated seconds (usual ly accurate)
             *
             * To increase the accurate of our completion % function,
             * We would also assign a default time to the average step
             * so we can calculate more accurately even if none of the
             * steps have an estimated time.
             *
             * */

            //Set default seconds per step:
            $metadata_this['completion_percentage'] = 0;
            $step_default_seconds = config_var(12176);


            //Calculate completion rate based on estimated time cost:
            if($metadata_this['steps_total'] > 0 || $metadata_this['seconds_total'] > 0){
                $metadata_this['completion_percentage'] = intval(ceil( ($metadata_this['seconds_completed']+($step_default_seconds*$metadata_this['steps_completed'])) / ($metadata_this['seconds_total']+($step_default_seconds*$metadata_this['steps_total'])) * 100 ));
            }


            //Hack for now, investigate later:
            if($metadata_this['completion_percentage'] > 100){
                $metadata_this['completion_percentage'] = 100;
            }

        }

        //Return results:
        return $metadata_this;

    }


    function ids($source__id){
        //Simply returns all the idea IDs for a user's Reads:
        $player_read_ids = array();
        foreach($this->READ_model->fetch(array(
            'read__source' => $source__id,
            'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ), array('idea_previous'), 0) as $user_in){
            array_push($player_read_ids, intval($user_in['idea__id']));
        }
        return $player_read_ids;
    }




    function answer($source__id, $question_idea__id, $answer_idea__ids){

        $ins = $this->IDEA_model->fetch(array(
            'idea__id' => $question_idea__id,
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ));
        $ens = $this->SOURCE_model->fetch(array(
            'source__id' => $source__id,
            'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
        ));
        if (!count($ins)) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea ID',
            );
        } elseif (!count($ens)) {
            return array(
                'status' => 0,
                'message' => 'Invalid source ID',
            );
        } elseif (!in_array($ins[0]['idea__type'], $this->config->item('sources_id_7712'))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Idea type [Must be Answer]',
            );
        } elseif (!count($answer_idea__ids)) {
            return array(
                'status' => 0,
                'message' => 'Missing Answer',
            );
        }


        //Define completion links for each answer:
        if($ins[0]['idea__type'] == 6684){

            //ONE ANSWER
            $read__type = 6157; //Award Coin
            $in_link_type_id = 12336; //Save Answer

        } elseif($ins[0]['idea__type'] == 7231){

            //SOME ANSWERS
            $read__type = 7489; //Award Coin
            $in_link_type_id = 12334; //Save Answer

        }

        //Delete ALL previous answers:
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_7704')) . ')' => null, //READ ANSWERED
            'read__source' => $source__id,
            'read__left' => $ins[0]['idea__id'],
        )) as $read_progress){
            $this->READ_model->update($read_progress['read__id'], array(
                'read__status' => 6173, //Link Deleted
            ), $source__id, 12129 /* READ ANSWER DELETED */);
        }

        //Add New Answers
        $answers_newly_added = 0;
        foreach($answer_idea__ids as $answer_idea__id){
            $answers_newly_added++;
            $this->READ_model->create(array(
                'read__type' => $in_link_type_id,
                'read__source' => $source__id,
                'read__left' => $ins[0]['idea__id'],
                'read__right' => $answer_idea__id,
            ));
        }


        //Ensure we logged an answer:
        if(!$answers_newly_added){
            return array(
                'status' => 0,
                'message' => 'No answers saved.',
            );
        }

        //Issue READ/IDEA COIN:
        $this->READ_model->is_complete($ins[0], array(
            'read__type' => $read__type,
            'read__source' => $source__id,
            'read__left' => $ins[0]['idea__id'],
        ));

        //All good, something happened:
        return array(
            'status' => 1,
            'message' => $answers_newly_added.' Selected. Going Next...',
        );

    }



}