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


    function create($add_fields, $external_sync = false)
    {

        //Set some defaults:
        if (!isset($add_fields['read__source']) || intval($add_fields['read__source']) < 1) {
            $add_fields['read__source'] = 0;
        }

        //Only require link type:
        if (detect_missing_columns($add_fields, array('read__type'), $add_fields['read__source'])) {
            return false;
        }

        //Clean metadata is provided:
        if (isset($add_fields['read__metadata']) && is_array($add_fields['read__metadata'])) {
            $add_fields['read__metadata'] = serialize($add_fields['read__metadata']);
        } else {
            $add_fields['read__metadata'] = null;
        }

        //Set some defaults:
        if (!isset($add_fields['read__message'])) {
            $add_fields['read__message'] = null;
        }


        if (!isset($add_fields['read__time']) || is_null($add_fields['read__time'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $add_fields['read__time'] = $d->format("Y-m-d H:i:s.u");
        }

        if (!isset($add_fields['read__status'])|| is_null($add_fields['read__status'])) {
            $add_fields['read__status'] = 6176; //Link Published
        }

        //Set some zero defaults if not set:
        foreach(array('read__right', 'read__left', 'read__down', 'read__up', 'read__reference', 'read__sort') as $dz) {
            if (!isset($add_fields[$dz])) {
                $add_fields[$dz] = 0;
            }
        }

        //Lets log:
        $this->db->insert('mench_interactions', $add_fields);


        //Fetch inserted id:
        $add_fields['read__id'] = $this->db->insert_id();


        //All good huh?
        if ($add_fields['read__id'] < 1) {

            //This should not happen:
            $this->READ_model->create(array(
                'read__type' => 4246, //Platform Bug Reports
                'read__source' => $add_fields['read__source'],
                'read__message' => 'create() Failed to create',
                'read__metadata' => array(
                    'input' => $add_fields,
                ),
            ));

            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($add_fields['read__up'] > 0) {
                update_algolia(4536, $add_fields['read__up']);
            }

            if ($add_fields['read__down'] > 0) {
                update_algolia(4536, $add_fields['read__down']);
            }

            if ($add_fields['read__left'] > 0) {
                update_algolia(4535, $add_fields['read__left']);
            }

            if ($add_fields['read__right'] > 0) {
                update_algolia(4535, $add_fields['read__right']);
            }
        }


        //SOURCE SYNC Status
        if(in_array($add_fields['read__type'] , $this->config->item('sources_id_12401'))){
            if($add_fields['read__down'] > 0){
                $source__id = $add_fields['read__down'];
            } elseif($add_fields['read__up'] > 0){
                $source__id = $add_fields['read__up'];
            }
            $this->SOURCE_model->match_read_status($add_fields['read__source'], array(
                'source__id' => $source__id,
            ));
        }

        //IDEA SYNC Status
        if(in_array($add_fields['read__type'] , $this->config->item('sources_id_12400'))){
            if($add_fields['read__right'] > 0){
                $idea__id = $add_fields['read__right'];
            } elseif($add_fields['read__left'] > 0){
                $idea__id = $add_fields['read__left'];
            }
            $this->IDEA_model->match_read_status($add_fields['read__source'], array(
                'idea__id' => $idea__id,
            ));
        }

        //Do we need to check for source tagging after read success?
        if(in_array($add_fields['read__type'] , $this->config->item('sources_id_6255')) && in_array($add_fields['read__status'] , $this->config->item('sources_id_7359')) && $add_fields['read__left'] > 0 && $add_fields['read__source'] > 0){


            //AUTO COMPLETES?
            $ideas_next_autoscan = array();
            $ideas = $this->IDEA_model->fetch(array(
                'idea__id' => $add_fields['read__left'],
            ));


            if(in_array($ideas[0]['idea__type'], $this->config->item('sources_id_7712'))){

                //IDEA TYPE SELECT NEXT
                $ideas_next_autoscan = $this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_7704')) . ')' => null, //READ ANSWERED
                    'read__source' => $add_fields['read__source'],
                    'read__left' => $ideas[0]['idea__id'],
                    'read__right>' => 0, //With an answer
                    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                    'idea__type IN (' . join(',', $this->config->item('sources_id_12330')) . ')' => null, //IDEA TYPE COMPLETE IF EMPTY
                ), array('read__right'), 0);

            } elseif(in_array($ideas[0]['idea__type'], $this->config->item('sources_id_13022'))){

                //IDEA TYPE ALL NEXT
                $ideas_next_autoscan = $this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'read__left' => $ideas[0]['idea__id'],
                    'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                    'idea__type IN (' . join(',', $this->config->item('sources_id_12330')) . ')' => null, //IDEA TYPE COMPLETE IF EMPTY
                ), array('read__right'), 0);

            }

            foreach($ideas_next_autoscan as $next_idea){

                //IS IT EMPTY?
                if(
                    //No Messages
                    !count($this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__type' => 4231, //IDEA NOTES Messages
                        'read__right' => $next_idea['idea__id'],
                    ))) &&

                    //No Next
                    !count($this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                        'read__left' => $next_idea['idea__id'],
                    ))) &&

                    //Not Already Completed:
                    !count($this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
                        'read__source' => $add_fields['read__source'],
                        'read__left' => $next_idea['idea__id'],
                    )))){

                    //Mark as complete:
                    $this->READ_model->mark_complete($next_idea, array(
                        'read__type' => 4559, //READ MESSAGES
                        'read__source' => $add_fields['read__source'],
                        'read__left' => $next_idea['idea__id'],
                    ));

                }
            }


            //SOURCE APPEND?
            $detected_read_type = read_detect_type($add_fields['read__message']);
            if ($detected_read_type['status']) {

                foreach($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type' => 7545, //CERTIFICATES
                    'read__right' => $ideas[0]['idea__id'],
                )) as $read_tag){

                    //Generate stats:
                    $links_added = 0;
                    $links_edited = 0;
                    $links_deleted = 0;


                    //Assign tag if parent/child link NOT previously assigned:
                    $existing_links = $this->READ_model->fetch(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                        'read__up' => $read_tag['read__up'], //CERTIFICATES saved here
                        'read__down' => $add_fields['read__source'],
                    ));

                    if(count($existing_links)){

                        //Link previously exists, see if content value is the same:
                        if($existing_links[0]['read__message'] == $add_fields['read__message'] && $existing_links[0]['read__type'] == $detected_read_type['read__type']){

                            //Everything is the same, nothing to do here:
                            continue;

                        } else {

                            $links_edited++;

                            //Content value has changed, update the link:
                            $this->READ_model->update($existing_links[0]['read__id'], array(
                                'read__message' => $add_fields['read__message'],
                            ), $add_fields['read__source'], 10657 /* Player Link Updated Content  */);

                            //Also, did the link type change based on the content change?
                            if($existing_links[0]['read__type'] != $detected_read_type['read__type']){
                                $this->READ_model->update($existing_links[0]['read__id'], array(
                                    'read__type' => $detected_read_type['read__type'],
                                ), $add_fields['read__source'], 10659 /* Player Link Updated Type */);
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
                                    'read__down' => $add_fields['read__source'],
                                )) as $single_selectable_siblings_preset){
                                    $links_deleted += $this->READ_model->update($single_selectable_siblings_preset['read__id'], array(
                                        'read__status' => 6173, //Link Deleted
                                    ), $add_fields['read__source'], 10673 /* Player Link Unpublished */);
                                }
                            }
                        }

                        //Create link:
                        $links_added++;
                        $this->READ_model->create(array(
                            'read__type' => $detected_read_type['read__type'],
                            'read__message' => $add_fields['read__message'],
                            'read__source' => $add_fields['read__source'],
                            'read__up' => $read_tag['read__up'],
                            'read__down' => $add_fields['read__source'],
                        ));

                    }

                    //Track Tag:
                    $this->READ_model->create(array(
                        'read__type' => 12197, //Tag Player
                        'read__source' => $add_fields['read__source'],
                        'read__up' => $read_tag['read__up'],
                        'read__down' => $add_fields['read__source'],
                        'read__left' => $ideas[0]['idea__id'],
                        'read__message' => $links_added.' added, '.$links_edited.' edited & '.$links_deleted.' deleted with new content ['.$add_fields['read__message'].']',
                    ));

                    if($links_added>0 || $links_edited>0 || $links_deleted>0){
                        //See if Session needs to be updated:
                        $session_source = superpower_assigned();
                        if($session_source && $session_source['source__id']==$add_fields['read__source']){
                            //Yes, update session:
                            $this->SOURCE_model->activate_session($session_source, true);
                        }
                    }
                }
            }
        }


        //See if this link type has any subscribers:
        if(in_array($add_fields['read__type'] , $this->config->item('sources_id_5967')) && $add_fields['read__type']!=5967 /* Email Sent causes endless loop */ && !is_dev_environment()){

            //Try to fetch subscribers:
            $sources__5967 = $this->config->item('sources__5967'); //Include subscription details
            $sub_emails = array();
            $sub_source__ids = array();
            foreach(explode(',', $sources__5967[$add_fields['read__type']]['m_desc']) as $subscriber_source__id){

                //Do not inform the user who just took the action:
                if($subscriber_source__id==$add_fields['read__source']){
                    continue;
                }

                //Try fetching subscribers email:
                foreach($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                    'read__up' => 3288, //Mench Email
                    'read__down' => $subscriber_source__id,
                ), array('read__down')) as $source_email){
                    if(filter_var($source_email['read__message'], FILTER_VALIDATE_EMAIL)){
                        //All good, add to list:
                        array_push($sub_source__ids , $source_email['source__id']);
                        array_push($sub_emails , $source_email['read__message']);
                    }
                }
            }


            //Did we find any subscribers?
            if(count($sub_source__ids) > 0){

                //yes, start drafting email to be sent to them...

                if($add_fields['read__source'] > 0){

                    //Fetch player details:
                    $add_sources = $this->SOURCE_model->fetch(array(
                        'source__id' => $add_fields['read__source'],
                    ));

                    $player_name = $add_sources[0]['source__title'];

                } else {

                    //No player:
                    $player_name = 'MENCH';

                }


                //Email Subject:
                $subject = 'Notification: '  . $player_name . ' ' . $sources__5967[$add_fields['read__type']]['m_name'];

                //Compose email body, start with link content:
                $html_message = '<div>' . ( strlen($add_fields['read__message']) > 0 ? $add_fields['read__message'] : '<i>No link content</i>') . '</div><br />';

                $sources__6232 = $this->config->item('sources__6232'); //PLATFORM VARIABLES

                //Append link object links:
                foreach($this->config->item('sources__11081') as $source__id => $m) {

                    if (!intval($add_fields[$sources__6232[$source__id]['m_desc']])) {
                        continue;
                    }

                    if (in_array(6202 , $m['m_parents'])) {

                        //IDEA
                        $ideas = $this->IDEA_model->fetch(array( 'idea__id' => $add_fields[$sources__6232[$source__id]['m_desc']] ));
                        $html_message .= '<div>' . $m['m_name'] . ': <a href="'.$this->config->item('base_url').'/g' . $ideas[0]['idea__id'] . '" target="_parent">#'.$ideas[0]['idea__id'].' '.$ideas[0]['idea__title'].'</a></div>';

                    } elseif (in_array(6160 , $m['m_parents'])) {

                        //SOURCE
                        $sources = $this->SOURCE_model->fetch(array( 'source__id' => $add_fields[$sources__6232[$source__id]['m_desc']] ));
                        $html_message .= '<div>' . $m['m_name'] . ': <a href="'.$this->config->item('base_url').'/@' . $sources[0]['source__id'] . '" target="_parent">@'.$sources[0]['source__id'].' '.$sources[0]['source__title'].'</a></div>';

                    } elseif (in_array(4367 , $m['m_parents'])) {

                        //READ
                        $html_message .= '<div>' . $m['m_name'] . ' ID: <a href="'.$this->config->item('base_url').'/@p12722?read__id=' . $add_fields[$sources__6232[$source__id]['m_desc']] . '" target="_parent">'.$add_fields[$sources__6232[$source__id]['m_desc']].'</a></div>';

                    }

                }

                //Finally append READ ID:
                $html_message .= '<div>READ ID: <a href="'.$this->config->item('base_url').'/@p12722?read__id=' . $add_fields['read__id'] . '">' . $add_fields['read__id'] . '</a></div>';

                //Inform how to change settings:
                $html_message .= '<div style="color: #DDDDDD; font-size:0.9em; margin-top:20px;">Manage your email notifications via <a href="'.$this->config->item('base_url').'/@5967" target="_blank">@5967</a></div>';

                //Send email:
                $dispatched_email = $this->READ_model->send_email($sub_emails, $subject, $html_message);

                //Log emails sent:
                foreach($sub_source__ids as $to_source__id){
                    $this->READ_model->create(array(
                        'read__type' => 5967, //Link Carbon Copy Email
                        'read__source' => $to_source__id, //Sent to this user
                        'read__metadata' => $dispatched_email, //Save a copy of email
                        'read__reference' => $add_fields['read__id'], //Save link

                        //Import potential Idea/source connections from link:
                        'read__right' => $add_fields['read__right'],
                        'read__left' => $add_fields['read__left'],
                        'read__down' => $add_fields['read__down'],
                        'read__up' => $add_fields['read__up'],
                    ));
                }
            }
        }

        //Return:
        return $add_fields;

    }

    function fetch($query_filters = array(), $join_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('read__id' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('mench_interactions');

        //IDA JOIN?
        if (in_array('read__left', $join_objects)) {
            $this->db->join('mench_ideas', 'read__left=idea__id','left');
        } elseif (in_array('read__right', $join_objects)) {
            $this->db->join('mench_ideas', 'read__right=idea__id','left');
        }

        //SOURCE JOIN?
        if (in_array('read__up', $join_objects)) {
            $this->db->join('mench_sources', 'read__up=source__id','left');
        } elseif (in_array('read__down', $join_objects)) {
            $this->db->join('mench_sources', 'read__down=source__id','left');
        } elseif (in_array('read__type', $join_objects)) {
            $this->db->join('mench_sources', 'read__type=source__id','left');
        } elseif (in_array('read__source', $join_objects)) {
            $this->db->join('mench_sources', 'read__source=source__id','left');
        }

        foreach($query_filters as $key => $value) {
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
        $this->db->update('mench_interactions', $update_columns);
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
                            $before_sources = $this->SOURCE_model->fetch(array(
                                'source__id' => $before_data[0][$key],
                            ));
                            $after_sources = $this->SOURCE_model->fetch(array(
                                'source__id' => $value,
                            ));

                            $read__message .= view_db_field($key) . ' updated from [' . $before_sources[0]['source__title'] . '] to [' . $after_sources[0]['source__title'] . ']' . "\n";

                        } elseif(in_array($key, array('read__left', 'read__right'))) {

                            //Fetch new/old Idea outcomes:
                            $before_ideas = $this->IDEA_model->fetch(array(
                                'idea__id' => $before_data[0][$key],
                            ));
                            $after_ideas = $this->IDEA_model->fetch(array(
                                'idea__id' => $value,
                            ));

                            $read__message .= view_db_field($key) . ' updated from [' . $before_ideas[0]['idea__title'] . '] to [' . $after_ideas[0]['idea__title'] . ']' . "\n";

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

    function max_order($query_filters)
    {

        //Fetches the maximum order value
        $this->db->select('MAX(read__sort) as largest_order');
        $this->db->from('mench_interactions');
        foreach($query_filters as $key => $value) {
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


    function message_send($message_input, $recipient_source = array(), $message_idea__id = 0)
    {

        /*
         *
         * The primary function that constructs messages based on the following inputs:
         *
         *
         * - $message_input:        The message text which may include source
         *                          references like "@123". This may NOT include
         *                          URLs as they must be first turned into an
         *                          source and then referenced within a message.
         *
         *
         * - $recipient_source:         The source object that this message is supposed
         *                          to be delivered to. May be an empty array for
         *                          when we want to show these messages to guests,
         *                          and it may contain the full source object or it
         *                          may only contain the source ID, which enables this
         *                          function to fetch further information from that
         *                          source as required based on its other parameters.
         *
         * */

        //This could happen with random messages
        if(strlen($message_input) < 1){
            return false;
        }

        //Validate message:
        $msg_validation = $this->READ_model->message_compile($message_input, $recipient_source, 0, $message_idea__id, false);


        //Did we have ane error in message validation?
        if (!$msg_validation['status'] || !isset($msg_validation['output_messages'])) {

            //Log Error Link:
            $this->READ_model->create(array(
                'read__type' => 4246, //Platform Bug Reports
                'read__source' => (isset($recipient_source['source__id']) ? $recipient_source['source__id'] : 0),
                'read__message' => 'message_compile() returned error [' . $msg_validation['message'] . '] for input message [' . $message_input . ']',
                'read__metadata' => array(
                    'input_message' => $message_input,
                    'recipient_source' => $recipient_source,
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


    function message_compile($message_input, $recipient_source = array(), $message_type_source__id = 0, $message_idea__id = 0, $strict_validation = true)
    {

        /*
         *
         * This function is used to validate IDEA NOTES.
         *
         * See message_send() for more information on input variables.
         *
         * */


        //Try to fetch session if recipient not provided:
        if(!isset($recipient_source['source__id'])){
            $recipient_source = superpower_assigned();
        }

        $sources__6177 = $this->config->item('sources__6177');
        $sources__4485 = $this->config->item('sources__4485');

        //Cleanup:
        $message_input = trim($message_input);
        $message_input = str_replace('’','\'',$message_input);

        //Start with basic input validation:
        if (strlen($message_input) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Message Content',
            );
        } elseif ($strict_validation && strlen($message_input) > config_var(4485)) {
            return array(
                'status' => 0,
                'message' => 'Message is '.strlen($message_input).' characters long which is more than the allowed ' . config_var(4485) . ' characters',
            );
        } elseif (!preg_match('//u', $message_input)) {
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
         * Source Creation within Message?
         *
         * */
        if($strict_validation && substr_count($message_input, '@')==1 && substr_count($message_input, '|')==1){
            //We Seem to have a creation mode:
            $source__title = one_two_explode('@','|',$message_input);
            $added_source = $this->SOURCE_model->verify_create($source__title, $recipient_source['source__id']);
            if(!$added_source['status']){
                return $added_source;
            } else {
                //New source added, replace text:
                $message_input = str_replace($source__title.'|', $added_source['new_source']['source__id'], $message_input);
            }
        }



        /*
         *
         * Let's do a generic message reference validation
         * that does not consider $message_type_source__id if passed
         *
         * */
        $string_references = extract_source_references($message_input);

        if($strict_validation && $message_type_source__id > 0){

            /*
             *
             * $message_type_source__id Validation
             * only in strict mode!
             *
             * */

            if(in_array($message_type_source__id, $this->config->item('sources_id_4986'))){
                //IDEA NOTES 3X SOURCE REFERENCES ALLOWED
                $min_source = 0;
                $max_source = 3;
            } elseif(in_array($message_type_source__id, $this->config->item('sources_id_7551'))){
                //IDEA NOTES 1X SOURCE REFERENCE REQUIRED
                $min_source = 1;
                $max_source = 1;
            } else {
                $min_source = 0;
                $max_source = 0;
            }

            //URLs are the same as a source:
            $total_references = count($string_references['ref_sources']) + count($string_references['ref_urls']);

            if($total_references<$min_source || $total_references>$max_source){
                return array(
                    'status' => 0,
                    'message' => 'You referenced '.$total_references.' sources where you must have '.$min_source.( $max_source!=$min_source ? '-'.$max_source : '' ).' references.',
                );
            }
        }









        /*
         *
         * Transform URLs into Source
         *
         * */
        if ($strict_validation && count($string_references['ref_urls']) > 0) {

            foreach($string_references['ref_urls'] as $url_key => $input_url) {

                //No source linked, but we have a URL that we should turn into an source if not previously:
                $url_source = $this->SOURCE_model->url($input_url, ( isset($recipient_source['source__id']) ? $recipient_source['source__id'] : 0 ));

                //Did we have an error?
                if (!$url_source['status'] || !isset($url_source['source_url']['source__id']) || intval($url_source['source_url']['source__id']) < 1) {
                    return $url_source;
                }

                //Transform URL into a source:
                if(intval($url_source['source_url']['source__id']) > 0){

                    array_push($string_references['ref_sources'], intval($url_source['source_url']['source__id']));

                    //Replace the URL with this new @source in message.
                    //This is the only valid modification we can do to $message_input before storing it in the DB:
                    $message_input = str_replace($input_url, '@' . $url_source['source_url']['source__id'], $message_input);

                    //Remove URL:
                    unset($string_references['ref_urls'][$url_key]);

                }
            }
        }


        /*
         *
         * Referenced Sources
         *
         * */

        //Start building the Output message body based on format:
        $has_text = substr_count($message_input, ' ');
        $message_input .= ' ';//Helps with accurate source reference replacement
        $output_body_message = htmlentities($message_input);
        $string_references = extract_source_references($message_input); //Do it again since it may be updated
        $current_mench = current_mench();
        $referenced_key = 0;
        $source_reference_keys = array(
            0 => 'read__up',
            1 => 'read__down',
            2 => 'read__left'
        );
        $source_reference_fields = array(
            'read__up'   => 0,
            'read__down' => 0,
            'read__left' => 0
        );

        foreach($string_references['ref_sources'] as $referenced_source){

            //We have a reference within this message, let's fetch it to better understand it:
            $sources = $this->SOURCE_model->fetch(array(
                'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
                'source__id' => $referenced_source, //Alert: We will only have a single reference per message
            ));
            if (count($sources) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'The referenced source @' . $referenced_source . ' not found',
                );
            }

            //Set as source reference:
            $source_reference_fields[$source_reference_keys[$referenced_key]] = intval($referenced_source);

            //See if this source has any parent links to be shown in this appendix
            $source_urls = array();
            $source_media_count = 0;
            $source_count = 0;
            $source_appendix = null;
            $text_tooltip = null;
            $is_current_source = $current_mench['x_name']=='source' && substr($this->uri->segment(1), 1)==$referenced_source;


            //Determine what type of Media this reference has:
            if(!$is_current_source || $string_references['ref_time_found']){

                foreach($this->READ_model->fetch(array(
                    'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12822')) . ')' => null, //SOURCE LINK MESSAGE DISPLAY
                    'read__down' => $referenced_source,
                ), array('read__up'), 0, 0, array(
                    'read__type' => 'ASC', /* Text first */
                    'source__weight' => 'DESC',
                )) as $source_profile) {

                    $source_count++;

                    if (in_array($source_profile['read__type'], $this->config->item('sources_id_12524'))) {

                        //SOURCE LINK VISUAL
                        $source_media_count++;
                        $source_appendix .= '<div class="source-appendix paddingup">' . view_read__message($source_profile['read__message'], $source_profile['read__type'], $message_input) . '</div>';

                    } elseif($source_profile['read__type'] == 4256 /* URL */) {

                        array_push($source_urls, $source_profile['read__message']);
                        $source_appendix .= '<div class="source-appendix paddingup">' . view_read__message($source_profile['read__message'], $source_profile['read__type'], $message_input) . '</div>';

                    } else {

                        //Text and Percentage, etc...
                        if(strlen($text_tooltip)){
                            $text_tooltip .= ' | ';
                        }
                        $text_tooltip .= $source_profile['source__title'].': ' . $source_profile['read__message'];

                    }
                }
            }



            //Append any appendix generated:
            $text_tooltip = ( strlen($text_tooltip) ? ' class="underdot" title="'.$text_tooltip.'" data-toggle="tooltip" data-placement="top" ' : '' );
            $short_name_class = ( strlen($sources[0]['source__title']) <= 13 ? ' inline-block ' : '' );
            $output_body_message .= $source_appendix;
            $identifier_string = '@' . $referenced_source.($string_references['ref_time_found'] ? one_two_explode('@' . $referenced_source,' ',$message_input) : '' ).' ';

            //PLAYER REFERENCE
            if(($current_mench['x_name']=='read' && !superpower_active(10967, true)) || $is_current_source){

                //NO LINK so we can maintain focus...
                if((!$has_text && $is_current_source) || ($current_mench['x_name']=='read' && $source_count==1 && ($source_media_count==1 || count($source_urls)==1))){

                    //HIDE
                    $output_body_message = str_replace($identifier_string, ' ', $output_body_message);

                } else {

                    //TEXT ONLY
                    $output_body_message = str_replace($identifier_string, '<span '.$text_tooltip.'><span class="'.$short_name_class.'"><span class="icon-block-xs img-block">'.view_source__icon($sources[0]['source__icon']).'</span><span class="text__6197_' . $sources[0]['source__id']  . '">' . $sources[0]['source__title']  . '</span></span></span>'.' ', $output_body_message);

                }

            } else {

                //FULL SOURCE LINK
                $output_body_message = str_replace($identifier_string, '<span '.$text_tooltip.'><a class="montserrat '.$short_name_class.extract_icon_color($sources[0]['source__icon']).'" href="/@' . $sources[0]['source__id'] . '">'.( !in_array($sources[0]['source__status'], $this->config->item('sources_id_7357')) ? '<span class="img-block icon-block-xs">'.$sources__6177[$sources[0]['source__status']]['m_icon'].'</span> ' : '' ).'<span class="img-block icon-block-xs">'.view_source__icon($sources[0]['source__icon']).'</span><span class="text__6197_' . $sources[0]['source__id']  . '">' . $sources[0]['source__title']  . '</span></a></span>'.' ', $output_body_message);

            }

            $referenced_key++;
        }


        //Return results:
        return array(
            'status' => 1,
            'input_message' => trim($message_input),
            'output_messages' => array(
                array(
                    'message_type_source__id' => 4570, //User Received Email Message
                    'message_body' => ( strlen($output_body_message) ? '<div class="i_content padded"><div class="msg">' . nl2br($output_body_message) . '</div></div>' : null ),
                ),
            ),
            //Source References:
            'read__up' => $source_reference_fields['read__up'],
            'read__down' => $source_reference_fields['read__down'],
            'read__left' => $source_reference_fields['read__left'],
        );
    }



    function find_next($source__id, $idea, $find_after_idea__id = 0, $search_up = true)
    {

        //CHECK DOWN/NEXT
        $first_incomplete = null;
        $found_trigger = false;
        foreach ($this->READ_model->fetch(array(
            'read__left' => $idea['idea__id'],
            'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ), array('read__right'), 0, 0, array('read__sort' => 'ASC')) as $next_idea) {

            if ($find_after_idea__id && !$found_trigger) {
                if ($next_idea['idea__id'] == $find_after_idea__id) {
                    $found_trigger = true;
                }
                continue;
            }


            $is_or_idea = in_array($idea['idea__type'], $this->config->item('sources_id_6193'));
            $is_fixed_link = in_array($next_idea['read__type'], $this->config->item('sources_id_12840'));
            $is_complete = count($this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
                'read__source' => $source__id,
                'read__left' => $next_idea['idea__id'],
            )));

            if($is_or_idea){
                //OR IDEAS - Must be Selected
                $is_selected = count($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINKS
                    'read__left' => $idea['idea__id'],
                    'read__right' => $next_idea['idea__id'],
                    'read__source' => $source__id,
                )));
            }


            if (!$is_complete && $is_fixed_link && ( !$is_or_idea || $is_selected )) {

                //FIXED LINK, or Selected OR IDEA, that is NOT COMPLETE, It's This:
                return intval($next_idea['idea__id']);

            } elseif ($is_complete) {

                //This is complete, but maybe there is a child that's not:
                $found_next = $this->READ_model->find_next($source__id, $next_idea, 0, false);
                if ($found_next) {
                    return $found_next;
                }

            }
        }



        if ($search_up) {

            //Check Previous/Up
            $current_previous = $idea['idea__id'];
            $player_read_ids = $this->READ_model->ids($source__id);
            $recursive_parents = $this->IDEA_model->recursive_parents($idea['idea__id'], true, true);
            foreach ($recursive_parents as $grand_parent_ids) {
                foreach (array_intersect($grand_parent_ids, $player_read_ids) as $intersect) {
                    foreach ($grand_parent_ids as $previous_idea__id) {

                        //Find the next siblings:
                        $ideas_this = $this->IDEA_model->fetch(array(
                            'idea__id' => $previous_idea__id,
                        ));
                        $found_next = $this->READ_model->find_next($source__id, $ideas_this[0], $current_previous, false);
                        if ($found_next) {
                            return $found_next;
                        }
                        $current_previous = $previous_idea__id;

                    }
                }
            }

            //Still Here? as a Last option go through READ LIST:
            foreach ($this->READ_model->fetch(array(
                'read__source' => $source__id,
                'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            ), array('read__left'), 0, 0, array('read__sort' => 'ASC')) as $read_list_idea) {
                $found_next = $this->READ_model->find_next($source__id, $read_list_idea, $find_after_idea__id, false);
                if ($found_next) {
                    return $found_next;
                }
            }

        }


        //Nothing found:
        return 0;

    }


    function delete($source__id, $idea__id, $stop_method_id, $stop_feedback = null){


        if(!in_array($stop_method_id, $this->config->item('sources_id_6150') /* Reads Idea Completed */)){
            return array(
                'status' => 0,
                'message' => 'Invalid stop method',
            );
        }

        //Validate idea to be deleted:
        $ideas = $this->IDEA_model->fetch(array(
            'idea__id' => $idea__id,
        ));
        if (count($ideas) < 1) {
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
        foreach($player_reads as $read){
            $this->READ_model->update($read['read__id'], array(
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
        $ideas = $this->IDEA_model->fetch(array(
            'idea__id' => $idea__id,
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ));
        if (count($ideas) != 1) {
            return 0;
        }

        //Make sure not previously added to this User's Reads:
        if(!count($this->READ_model->fetch(array(
                'read__source' => $source__id,
                'read__left' => $idea__id,
                'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            )))){

            //Not added to their Reads so far, let's go ahead and add it:
            $idea_rank = 1;
            $home = $this->READ_model->create(array(
                'read__type' => ( $recommender_idea__id > 0 ? 7495 /* User Idea Recommended */ : 4235 /* User Idea Set */ ),
                'read__source' => $source__id, //Belongs to this User
                'read__left' => $ideas[0]['idea__id'], //The Idea they are adding
                'read__right' => $recommender_idea__id, //Store the recommended idea
                'read__sort' => $idea_rank, //Always place at the top of their Reads
            ));

            //Move other ideas down in the Reads:
            foreach($this->READ_model->fetch(array(
                'read__id !=' => $home['read__id'], //Not the newly added idea
                'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__source' => $source__id, //Belongs to this User
            ), array(), 0, 0, array('read__sort' => 'ASC')) as $current_ideas){

                //Increase rank:
                $idea_rank++;

                //Update order:
                $this->READ_model->update($current_ideas['read__id'], array(
                    'read__sort' => $idea_rank,
                ), $source__id, 10681 /* Ideas Ordered Automatically  */);

            }

            //Was this their first idea?
            if(!count($this->READ_model->fetch(array(
                'read__source' => $source__id,
                'read__left !=' => $idea__id,
                'read__type IN (' . join(',', $this->config->item('sources_id_12969')) . ')' => null, //Reads Idea Set
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            )))){

                //YES! Also add the starting idea:
                $this->READ_model->start($source__id, $this->config->item('starting_idea__id'), $idea__id);

                return $this->config->item('starting_idea__id');

            } elseif($idea__id != $this->config->item('starting_idea__id')) {

                //Mark as readed if possible:
                if($ideas[0]['idea__type']==6677){
                    $this->READ_model->mark_complete($ideas[0], array(
                        'read__type' => 4559, //READ MESSAGES
                        'read__source' => $source__id,
                        'read__left' => $ideas[0]['idea__id'],
                    ));
                }

            }

        }

        return $idea__id;

    }




    function completion_recursive_up($source__id, $idea, $is_bottom_level = true){

        /*
         *
         * Let's see how many steps get unlocked @6410
         *
         * */


        //First let's make sure this entire Idea completed by the user:
        $completion_rate = $this->READ_model->completion_progress($source__id, $idea);


        if($completion_rate['completion_percentage'] < 100){
            //Not completed, so can't go further up:
            return array();
        }


        //Look at Conditional Idea Links ONLY at this level:
        $idea__metadata = unserialize($idea['idea__metadata']);
        if(isset($idea__metadata['idea___expansion_conditional'][$idea['idea__id']]) && count($idea__metadata['idea___expansion_conditional'][$idea['idea__id']]) > 0){

            //Make sure previous link unlocks have NOT happened before:
            $existing_expansions = $this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type' => 6140, //READ UNLOCK LINK
                'read__source' => $source__id,
                'read__left' => $idea['idea__id'],
                'read__right IN (' . join(',', $idea__metadata['idea___expansion_conditional'][$idea['idea__id']]) . ')' => null, //Limit to cached answers
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
                    'read__left' => $idea['idea__id'],
                    'read__right' => $existing_expansions[0]['read__right'],
                    'read__message' => 'completion_recursive_up() detected duplicate Label Expansion entries',
                    'read__type' => 4246, //Platform Bug Reports
                    'read__source' => $source__id,
                ));
                */

                return array();

            }


            //Yes, Let's calculate user's score for this idea:
            $user_marks = $this->READ_model->completion_marks($source__id, $idea);





            //Detect potential conditional steps to be Unlocked:
            $found_match = 0;
            $locked_links = $this->READ_model->fetch(array(
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',', $this->config->item('sources_id_12842')) . ')' => null, //IDEA LINKS ONE-WAY
                'read__left' => $idea['idea__id'],
                'read__right IN (' . join(',', $idea__metadata['idea___expansion_conditional'][$idea['idea__id']]) . ')' => null, //Limit to cached answers
            ), array('read__right'), 0, 0);


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
                        'read__left' => $idea['idea__id'],
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
                    'read__left' => $idea['idea__id'],
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
            foreach($this->IDEA_model->recursive_parents($idea['idea__id']) as $grand_parent_ids) {

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
                    $previous_ideas = $this->IDEA_model->fetch(array(
                        'idea__id' => $p_id,
                        'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                    ));

                    //Now see if this child completion resulted in a full parent completion:
                    if(count($previous_ideas) > 0){

                        //Fetch parent completion:
                        $this->READ_model->completion_recursive_up($source__id, $previous_ideas[0], false);

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


    function unlock_locked_step($source__id, $idea){

        /*
         * A function that starts from a locked idea and checks:
         *
         * 1. List users who have completed ALL/ANY (Depending on AND/OR Lock) of its children
         * 2. If > 0, then goes up recursively to see if these completions unlock other completions
         *
         * */

        if(!idea_is_unlockable($idea)){
            return array(
                'status' => 0,
                'message' => 'Not a valid locked idea type and status',
            );
        }


        $ideas_next = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'read__left' => $idea['idea__id'],
        ), array('read__right'), 0, 0, array('read__sort' => 'ASC'));
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
        $requires_all_children = ( $idea['idea__type'] == 6914 /* AND Lock, meaning all children are needed */ );

        //Generate list of users who have completed it:
        $qualified_completed_users = array();

        //Go through children and see how many completed:
        foreach($ideas_next as $count => $next_idea){

            //Fetch users who completed this:
            if($count==0){

                //Always add all the first users to the full list:
                $qualified_completed_users = $this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null, //READ COIN
                    'read__left' => $next_idea['idea__id'],
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
                        'read__left' => $next_idea['idea__id'],
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


    function idea_home($idea__id, $recipient_source){

        $in_my_reads = false;

        if($recipient_source['source__id'] > 0){

            //Fetch entire Reads:
            $player_read_ids = $this->READ_model->ids($recipient_source['source__id']);
            $in_my_reads = in_array($idea__id, $player_read_ids);

            if(!$in_my_reads){
                //Go through parents ideas and detect intersects with user ideas. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
                foreach($this->IDEA_model->recursive_parents($idea__id) as $grand_parent_ids) {
                    //Does this parent and its grandparents have an intersection with the user ideas?
                    if (array_intersect($grand_parent_ids, $player_read_ids)) {
                        //Idea is part of their Reads:
                        $in_my_reads = true;
                        break;
                    }
                }
            }
        }

        return $in_my_reads;

    }


    function mark_complete($idea, $add_fields){

        //Log completion link:
        $new_link = $this->READ_model->create($add_fields);

        //Process completion automations:
        $this->READ_model->completion_recursive_up($add_fields['read__source'], $idea);

        return $new_link;

    }

    function completion_marks($source__id, $idea, $top_level = true)
    {

        //Fetch/validate Reads Common Ideas:
        $idea__metadata = unserialize($idea['idea__metadata']);
        if(!isset($idea__metadata['idea___common_reads'])){

            //Should not happen, log error:
            $this->READ_model->create(array(
                'read__message' => 'completion_marks() Detected user Reads without idea___common_reads value!',
                'read__type' => 4246, //Platform Bug Reports
                'read__source' => $source__id,
                'read__left' => $idea['idea__id'],
            ));

            return 0;
        }

        //Generate flat steps:
        $flat_common_reads = array_flatten($idea__metadata['idea___common_reads']);

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
        if(isset($idea__metadata['idea___expansion_reads']) && count($idea__metadata['idea___expansion_reads']) > 0){

            //We need expansion steps (OR Ideas) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_idea__ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($idea__metadata['idea___expansion_reads'] as $question_idea__id => $answers_idea__ids ){

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
                ), array('read__right')) as $idea_answer){

                    //Extract Link Metadata:
                    $possible_answer_metadata = unserialize($idea_answer['read__metadata']);

                    //Assign to this question:
                    $answer_marks_index[$idea_answer['idea__id']] = ( isset($possible_answer_metadata['tr__assessment_points']) ? intval($possible_answer_metadata['tr__assessment_points']) : 0 );

                    //Addup local min/max marks:
                    if(is_null($local_min) || $answer_marks_index[$idea_answer['idea__id']] < $local_min){
                        $local_min = $answer_marks_index[$idea_answer['idea__id']];
                    }
                    if(is_null($local_max) || $answer_marks_index[$idea_answer['idea__id']] > $local_max){
                        $local_max = $answer_marks_index[$idea_answer['idea__id']];
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
            ), array('read__right'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->READ_model->completion_marks($source__id, $answer_in, false);

                $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];
                $metadata_this['steps_answered_marks'] += $answer_marks_index[$answer_in['idea__id']] + $recursive_stats['steps_answered_marks'];

            }
        }


        //Process Answer SOME:
        if(isset($idea__metadata['idea___expansion_some']) && count($idea__metadata['idea___expansion_some']) > 0){

            //We need expansion steps (OR Ideas) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_idea__ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($idea__metadata['idea___expansion_some'] as $question_idea__id => $answers_idea__ids ){

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
                ), array('read__right')) as $idea_answer){

                    //Extract Link Metadata:
                    $possible_answer_metadata = unserialize($idea_answer['read__metadata']);

                    //Assign to this question:
                    $answer_marks_index[$idea_answer['idea__id']] = ( isset($possible_answer_metadata['tr__assessment_points']) ? intval($possible_answer_metadata['tr__assessment_points']) : 0 );

                    //Addup local min/max marks:
                    if(is_null($local_min) || $answer_marks_index[$idea_answer['idea__id']] < $local_min){
                        $local_min = $answer_marks_index[$idea_answer['idea__id']];
                    }
                }

                //Did we have any marks for this question?
                if(!is_null($local_min)){
                    $metadata_this['steps_marks_min'] += $local_min;
                }

                //Always Add local max:
                $metadata_this['steps_marks_max'] += $answer_marks_index[$idea_answer['idea__id']];

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
            ), array('read__right'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->READ_model->completion_marks($source__id, $answer_in, false);

                $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];
                $metadata_this['steps_answered_marks'] += $answer_marks_index[$answer_in['idea__id']] + $recursive_stats['steps_answered_marks'];

            }
        }



        if($top_level && $metadata_this['steps_answered_count'] > 0){

            $divider = ( $metadata_this['steps_marks_max'] - $metadata_this['steps_marks_min'] );

            if($divider > 0){
                //See assessment summary:
                $metadata_this['steps_answered_score'] = floor(( ($metadata_this['steps_answered_marks'] - $metadata_this['steps_marks_min']) / $divider )  * 100 );
            } else {
                //See assessment summary:
                $metadata_this['steps_answered_score'] = 0;
            }

        }


        //Return results:
        return $metadata_this;

    }



    function completion_progress($source__id, $idea, $top_level = true)
    {

        if(!isset($idea['idea__metadata'])){
            return false;
        }

        //Fetch/validate Reads Common Ideas:
        $idea__metadata = unserialize($idea['idea__metadata']);
        if(!isset($idea__metadata['idea___common_reads'])){
            //Since it's not there yet we assume the idea it self only!
            $idea__metadata['idea___common_reads'] = array($idea['idea__id']);
        }


        //Generate flat steps:
        $flat_common_reads = array_flatten($idea__metadata['idea___common_reads']);


        //Count totals:
        $common_totals = $this->IDEA_model->fetch(array(
            'idea__id IN ('.join(',',$flat_common_reads).')' => null,
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ), 0, 0, array(), 'COUNT(idea__id) as total_reads, SUM(idea__duration) as total_seconds');


        //Count completed for user:
        $common_completed = $this->READ_model->fetch(array(
            'read__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
            'read__source' => $source__id, //Belongs to this User
            'read__left IN (' . join(',', $flat_common_reads ) . ')' => null,
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ), array('read__left'), 0, 0, array(), 'COUNT(idea__id) as completed_reads, SUM(idea__duration) as completed_seconds');


        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            'steps_total' => intval($common_totals[0]['total_reads']),
            'steps_completed' => intval($common_completed[0]['completed_reads']),
            'seconds_total' => intval($common_totals[0]['total_seconds']),
            'seconds_completed' => intval($common_completed[0]['completed_seconds']),
        );


        //Expansion Answer ONE
        $answer_array = array();
        if(isset($idea__metadata['idea___expansion_reads']) && count($idea__metadata['idea___expansion_reads']) > 0) {
            $answer_array = array_merge($answer_array , array_flatten($idea__metadata['idea___expansion_reads']));
        }
        if(isset($idea__metadata['idea___expansion_some']) && count($idea__metadata['idea___expansion_some']) > 0) {
            $answer_array = array_merge($answer_array , array_flatten($idea__metadata['idea___expansion_some']));
        }

        if(count($answer_array)){

            //Now let's check user answers to see what they have done:
            foreach($this->READ_model->fetch(array(
                'read__type IN (' . join(',', $this->config->item('sources_id_12326')) . ')' => null, //READ IDEA LINKS
                'read__source' => $source__id, //Belongs to this User
                'read__left IN (' . join(',', $flat_common_reads ) . ')' => null,
                'read__right IN (' . join(',', $answer_array) . ')' => null,
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            ), array('read__right')) as $expansion_in) {

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
        if(isset($idea__metadata['idea___expansion_conditional']) && count($idea__metadata['idea___expansion_conditional']) > 0){

            //Now let's check if user has unlocked any Miletones:
            foreach($this->READ_model->fetch(array(
                'read__type' => 6140, //READ UNLOCK LINK
                'read__source' => $source__id, //Belongs to this User
                'read__left IN (' . join(',', $flat_common_reads ) . ')' => null,
                'read__right IN (' . join(',', array_flatten($idea__metadata['idea___expansion_conditional'])) . ')' => null,
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            ), array('read__right')) as $expansion_in) {

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
        ), array('read__left'), 0) as $user_in){
            array_push($player_read_ids, intval($user_in['idea__id']));
        }
        return $player_read_ids;
    }




    function answer($source__id, $question_idea__id, $answer_idea__ids){

        $ideas = $this->IDEA_model->fetch(array(
            'idea__id' => $question_idea__id,
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ));
        $sources = $this->SOURCE_model->fetch(array(
            'source__id' => $source__id,
            'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
        ));
        if (!count($ideas)) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea ID',
            );
        } elseif (!count($sources)) {
            return array(
                'status' => 0,
                'message' => 'Invalid source ID',
            );
        } elseif (!in_array($ideas[0]['idea__type'], $this->config->item('sources_id_7712'))) {
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
        if($ideas[0]['idea__type'] == 6684){

            //ONE ANSWER
            $read__type = 6157; //Award Coin
            $idea_link_type_id = 12336; //Save Answer

        } elseif($ideas[0]['idea__type'] == 7231){

            //SOME ANSWERS
            $read__type = 7489; //Award Coin
            $idea_link_type_id = 12334; //Save Answer

        }

        //Delete ALL previous answers:
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_7704')) . ')' => null, //READ ANSWERED
            'read__source' => $source__id,
            'read__left' => $ideas[0]['idea__id'],
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
                'read__type' => $idea_link_type_id,
                'read__source' => $source__id,
                'read__left' => $ideas[0]['idea__id'],
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
        $this->READ_model->mark_complete($ideas[0], array(
            'read__type' => $read__type,
            'read__source' => $source__id,
            'read__left' => $ideas[0]['idea__id'],
        ));

        //All good, something happened:
        return array(
            'status' => 1,
            'message' => $answers_newly_added.' Selected. Going Next...',
        );

    }



}