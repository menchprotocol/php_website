<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class X_model extends CI_Model
{

    /*
     *
     * Member related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function create($add_fields, $external_sync = false)
    {

        //Set some defaults:
        if (!isset($add_fields['x__creator']) || intval($add_fields['x__creator']) < 1) {
            $add_fields['x__creator'] = 14068; //GUEST MEMBER
        }

        //Only require transaction type:
        if (detect_missing_columns($add_fields, array('x__type'), $add_fields['x__creator'])) {
            return false;
        }

        if(!in_array($add_fields['x__type'], $this->config->item('n___4593'))){
            $this->X_model->create(array(
                'x__message' => 'x->create() failed to create because of invalid transaction type @'.$add_fields['x__type'],
                'x__type' => 4246, //Platform Bug Reports
                'x__creator' => $add_fields['x__creator'],
                'x__metadata' => $add_fields,
            ));
            return false;
        }

        //Clean metadata is provided:
        if (isset($add_fields['x__metadata']) && is_array($add_fields['x__metadata'])) {
            $add_fields['x__metadata'] = serialize($add_fields['x__metadata']);
        } else {
            $add_fields['x__metadata'] = null;
        }

        //Set some defaults:
        if (!isset($add_fields['x__message'])) {
            $add_fields['x__message'] = null;
        }

        //Set some defaults:
        if (!isset($add_fields['x__website']) || $add_fields['x__website']<1) {
            $add_fields['x__website'] = website_setting(0, $add_fields['x__creator']);
        }


        if (!isset($add_fields['x__time']) || is_null($add_fields['x__time'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $add_fields['x__time'] = $d->format("Y-m-d H:i:s.u");
        }

        if (!isset($add_fields['x__access'])|| is_null($add_fields['x__access'])) {
            $add_fields['x__access'] = 6176; //Transaction Published
        }

        //Set some zero defaults if not set:
        foreach(array('x__right', 'x__left', 'x__down', 'x__up', 'x__reference', 'x__weight') as $dz) {
            if (!isset($add_fields[$dz])) {
                $add_fields[$dz] = 0;
            }
        }

        //Lets log:
        $this->db->insert('table__x', $add_fields);


        //Fetch inserted id:
        $add_fields['x__id'] = $this->db->insert_id();


        //All good huh?
        if ($add_fields['x__id'] < 1) {

            //This should not happen:
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__creator' => $add_fields['x__creator'],
                'x__message' => 'create() Failed to create',
                'x__metadata' => array(
                    'input' => $add_fields,
                ),
            ));

            return false;
        }

        //Sync algolia?
        if ($external_sync) {
            if ($add_fields['x__up'] > 0) {
                update_algolia(12274, $add_fields['x__up']);
            }

            if ($add_fields['x__down'] > 0) {
                update_algolia(12274, $add_fields['x__down']);
            }

            if ($add_fields['x__left'] > 0) {
                update_algolia(12273, $add_fields['x__left']);
            }

            if ($add_fields['x__right'] > 0) {
                update_algolia(12273, $add_fields['x__right']);
            }
        }


        //See if this transaction type has any followers that are essentially subscribed to it:
        $tr_watchers = $this->E_model->fetch_recursive(12274, $add_fields['x__type'], $this->config->item('n___30820'), array(), 1);
        if(count($tr_watchers)){

            //yes, start drafting email to be sent to them...
            $u_name = 'Unknown';
            if($add_fields['x__creator'] > 0){
                //Fetch member details:
                $add_e = $this->E_model->fetch(array(
                    'e__id' => $add_fields['x__creator'],
                ));
                if(count($add_e)){
                    $u_name = $add_e[0]['e__title'];
                }
            }


            //Email Subject:
            $e___4593 = $this->config->item('e___4593'); //Transaction Types
            $subject = 'Notification: '  . $u_name . ' ' . $e___4593[$add_fields['x__type']]['m__title'];

            //Compose email body, start with transaction content:
            $plain_message = ( strlen($add_fields['x__message']) > 0 ? $add_fields['x__message'] : '') . "\n";

            $e___32088 = $this->config->item('e___32088'); //Platform Variables

            //Append transaction object transactions:
            foreach($this->config->item('e___4341') as $e__id => $m) {

                if (in_array(6202 , $m['m__following'])) {

                    //IDEA
                    foreach($this->I_model->fetch(array( 'i__id' => $add_fields[$e___32088[$e__id]['m__message']] )) as $this_i){
                        $plain_message .= $m['m__title'] . ': '.view_i_title($this_i, true).':'."\n".$this->config->item('base_url').'/~' . $this_i['i__id']."\n\n";
                    }

                } elseif (in_array(6160 , $m['m__following'])) {

                    //SOURCE
                    foreach($this->E_model->fetch(array( 'e__id' => $add_fields[$e___32088[$e__id]['m__message']] )) as $this_e){
                        $plain_message .= $m['m__title'] . ': '.$this_e['e__title']."\n".$this->config->item('base_url').'/@' . $this_e['e__id'] . "\n\n";
                    }

                } elseif (in_array(4367 , $m['m__following'])) {

                    //DISCOVERY
                    $plain_message .= $m['m__title'] . ':'."\n".$this->config->item('base_url').'/-12722?x__id=' . $add_fields[$e___32088[$e__id]['m__message']]."\n\n";

                }

            }

            //Finally append DISCOVERY ID:
            $plain_message .= 'TRANSACTION: #'.$add_fields['x__id']."\n".$this->config->item('base_url').'/-12722?x__id=' . $add_fields['x__id']."\n\n";

            //Inform how to change settings:
            $plain_message .= 'You received this notification because you follow: '."\n".$this->config->item('base_url').'/@'.$add_fields['x__type']."\n\n";

            //Send to all Watchers:
            foreach($tr_watchers as $tr_watcher) {
                //Do not inform the member who just took the action:
                if($tr_watcher['e__id']!=$add_fields['x__creator']){
                    $this->X_model->send_dm($tr_watcher['e__id'], $subject, $plain_message, array(
                        'x__reference' => $add_fields['x__id'], //Save transaction
                        'x__right' => $add_fields['x__right'],
                        'x__left' => $add_fields['x__left'],
                        'x__down' => $add_fields['x__down'],
                        'x__up' => $add_fields['x__up'],
                    ));
                }
            }
        }

        //Return:
        return $add_fields;

    }

    function fetch($query_filters = array(), $join_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('x__id' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('table__x');

        //IDA JOIN?
        if (in_array('x__left', $join_objects)) {
            $this->db->join('table__i', 'x__left=i__id','left');
        } elseif (in_array('x__right', $join_objects)) {
            $this->db->join('table__i', 'x__right=i__id','left');
        }

        //SOURCE JOIN?
        if (in_array('x__up', $join_objects)) {
            $this->db->join('table__e', 'x__up=e__id','left');
        } elseif (in_array('x__down', $join_objects)) {
            $this->db->join('table__e', 'x__down=e__id','left');
        } elseif (in_array('x__type', $join_objects)) {
            $this->db->join('table__e', 'x__type=e__id','left');
        } elseif (in_array('x__creator', $join_objects)) {
            $this->db->join('table__e', 'x__creator=e__id','left');
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

    function update($id, $update_columns, $x__creator = 0, $x__type = 0, $x__message = '')
    {

        $id = intval($id);
        if (count($update_columns)==0) {
            return false;
        } elseif ($x__type>0 && !in_array($x__type, $this->config->item('n___4593'))) {
            $this->X_model->create(array(
                'x__message' => 'x->update() failed to update because of invalid transaction type @'.$x__type,
                'x__type' => 4246, //Platform Bug Reports
                'x__creator' => $x__creator,
                'x__metadata' => $update_columns,
            ));
            return false;
        }

        //Fetch transaction before updating:
        $before_data = $this->X_model->fetch(array(
            'x__id' => $id,
        ));

        //Update metadata if needed:
        if(isset($update_columns['x__metadata']) && is_array($update_columns['x__metadata'])){
            //Merge this update into existing metadata:
            if(strlen($before_data[0]['x__metadata'])){

                //We have something, merge:
                $x__metadata = unserialize($before_data[0]['x__metadata']);
                $merged_array = array_merge($x__metadata, $update_columns['x__metadata']);
                $update_columns['x__metadata'] = serialize($merged_array);

            } else {
                //We have nothing, insert entire thing:
                $update_columns['x__metadata'] = serialize($update_columns['x__metadata']);
            }
        }

        //Set content to null if defined as empty:
        if(isset($update_columns['x__message']) && !strlen($update_columns['x__message'])){
            $update_columns['x__message'] = null;
        }

        //Update:
        $this->db->where('x__id', $id);
        $this->db->update('table__x', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Log changes if successful:
        if ($affected_rows > 0 && $x__creator > 0 && $x__type > 0) {

            if(strlen($x__message)==0){
                //Log modification transaction for every field changed:
                foreach($update_columns as $key => $value) {

                    if($before_data[0][$key]==$value){
                        continue;
                    }

                    //Now determine what type is this:
                    if($key=='x__access'){

                        $e___6186 = $this->config->item('e___6186'); //Transaction Status
                        $x__message .= view_db_field($key) . ' updated from [' . $e___6186[$before_data[0][$key]]['m__title'] . '] to [' . $e___6186[$value]['m__title'] . ']'."\n";

                    } elseif($key=='x__type'){

                        $e___4593 = $this->config->item('e___4593'); //Transaction Types
                        $x__message .= view_db_field($key) . ' updated from [' . $e___4593[$before_data[0][$key]]['m__title'] . '] to [' . $e___4593[$value]['m__title'] . ']'."\n";

                    } elseif(in_array($key, array('x__up', 'x__down'))) {

                        //Fetch new/old source names:
                        $befores = $this->E_model->fetch(array(
                            'e__id' => $before_data[0][$key],
                        ));
                        $after_e = $this->E_model->fetch(array(
                            'e__id' => $value,
                        ));

                        $x__message .= view_db_field($key) . ' updated from [' . $befores[0]['e__title'] . '] to [' . $after_e[0]['e__title'] . ']' . "\n";

                    } elseif(in_array($key, array('x__left', 'x__right'))) {

                        //Fetch new/old Idea outcomes:
                        $before_i = $this->I_model->fetch(array(
                            'i__id' => $before_data[0][$key],
                        ));
                        $after_i = $this->I_model->fetch(array(
                            'i__id' => $value,
                        ));

                        $x__message .= view_db_field($key) . ' updated from [' . $before_i[0]['i__message'] . '] to [' . $after_i[0]['i__message'] . ']' . "\n";

                    } elseif(in_array($key, array('x__message', 'x__weight'))){

                        $x__message .= view_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']'."\n";

                    } else {

                        //Should not log updates since not specifically programmed:
                        continue;

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

            if(strlen($x__message) > 0 && count($fields_changed) > 0){
                //Value has changed, log transaction:
                $this->X_model->create(array(
                    'x__reference' => $id, //Transaction Reference
                    'x__creator' => $x__creator,
                    'x__type' => $x__type,
                    'x__message' => $x__message,
                    'x__metadata' => array(
                        'x__id' => $id,
                        'fields_changed' => $fields_changed,
                    ),
                    //Copy old values:
                    'x__up' => $before_data[0]['x__up'],
                    'x__down'  => $before_data[0]['x__down'],
                    'x__left' => $before_data[0]['x__left'],
                    'x__right'  => $before_data[0]['x__right'],
                ));
            }
        }

        return $affected_rows;
    }


    function update_dropdown($focus_id, $o__id, $element_id, $new_e__id, $migrate_s__id, $x__id = 0) {


        //Authenticate Member:
        $member_e = superpower_unlocked();
        if (!$member_e) {
            return array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            );
        } elseif (intval($o__id) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Target ID',
            );
        } elseif (intval($element_id) < 1 || !count($this->config->item('n___'.$element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Variable ID ['.$element_id.']',
            );
        } elseif (intval($new_e__id) < 1 || !in_array($new_e__id, $this->config->item('n___'.$element_id))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Value ID',
            );
        }


        //See if anything is being deleted:
        $deletion_redirect = null;
        $delete_element = null;
        $links_removed = -1;
        $status = 0;

        if($element_id==4486 && $x__id > 0){

            //IDEA LINK TYPE
            $status = $this->X_model->update($x__id, array(
                'x__type' => $new_e__id,
            ), $member_e['e__id'], 13962);

        } elseif($element_id==13550 && $x__id > 0){

            //SOURCE LINK TYPE
            $status = $this->X_model->update($x__id, array(
                'x__type' => $new_e__id,
            ), $member_e['e__id'], 28799);

        } elseif($element_id==32292 && $x__id > 0){

            //SOURCE/SOURCE LINK
            $status = $this->X_model->update($x__id, array(
                'x__type' => $new_e__id,
            ), $member_e['e__id'], 28799);

        } elseif($element_id==6177){

            //SOURCE ACCESS

            //Delete?
            if(!in_array($new_e__id, $this->config->item('n___7358'))){

                //Determine what to do after deleted:
                if($o__id==$focus_id){

                    //Find Published Followings:
                    foreach($this->X_model->fetch(array(
                        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                        'x__down' => $o__id,
                    ), array('x__up'), 1, 0, array('e__title' => 'DESC')) as $up_e) {
                        $deletion_redirect = '/@'.$up_e['e__id'];
                    }

                    //If still not found, go to main page if no followings found:
                    if(!$deletion_redirect){
                        $deletion_redirect = '/@'.$o__id;
                    }

                } else {

                    //Just delete from UI using JS:
                    $delete_element = '.card___12274_' . $o__id;

                }

                //Delete all transactions:
                $links_removed = $this->E_model->remove($o__id, $member_e['e__id'], $migrate_s__id);

            }

            //Update:
            if(!intval($migrate_s__id) || count($this->E_model->fetch(array(
                    'e__id' => $migrate_s__id,
                    'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                )))){
                $status = $this->E_model->update($o__id, array(
                    'e__access' => $new_e__id,
                ), true, $member_e['e__id']);
            }

            //Update Algolia:
            update_algolia(12274,  $o__id);

        } elseif($element_id==31004){

            //IDEA ACCESS

            //Delete?
            if(!in_array($new_e__id, $this->config->item('n___31871'))){

                //Determine what to do after deleted:
                if($o__id==$focus_id){

                    //Find Published Followings:
                    foreach($this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                        'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                        'x__right' => $o__id,
                    ), array('x__left'), 1) as $previous_i) {
                        $deletion_redirect = '/~'.$previous_i['i__id'];
                    }

                    //If not found, find active followings:
                    if(!$deletion_redirect){
                        foreach($this->X_model->fetch(array(
                            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                            'x__right' => $o__id,
                        ), array('x__left'), 1) as $previous_i) {
                            $deletion_redirect = '/~'.$previous_i['i__id'];
                        }
                    }

                    //If still not found, go to main page if no followings found:
                    if(!$deletion_redirect){
                        $deletion_redirect = '/~'.$o__id;
                    }

                } else {

                    //Just delete from UI using JS:
                    $delete_element = '.card___12273_' . $o__id;

                }

                //Delete all transactions:
                $links_removed = $this->I_model->remove($o__id , $member_e['e__id'], $migrate_s__id);

            }

            //Update Idea:
            $status = $this->I_model->update($o__id, array(
                'i__access' => $new_e__id,
            ), true, $member_e['e__id']);

            //Update Algolia:
            update_algolia(12273,  $o__id);

        } elseif($element_id==4737){

            //IDEA TYPE
            $status = $this->I_model->update($o__id, array(
                'i__type' => $new_e__id,
            ), true, $member_e['e__id']);

        }

        return array(
            'status' => intval($status) && ($links_removed<0 || $links_removed>0),
            'message' => 'Delete status ['.$status.'] with '.$links_removed.' Links removed',
            'deletion_redirect' => $deletion_redirect,
            'delete_element' => $delete_element,
        );

    }
    function send_dm($e__id, $subject, $plain_message, $x_data = array(), $template_id = 0, $x__website = 0, $log_tr = true)
    {

        $sms_subscriber = false;
        $bypass_notifications = in_array($template_id, $this->config->item('n___31779'));

        if(!$bypass_notifications){
            $notification_levels = $this->X_model->fetch(array(
                'x__up IN (' . join(',', $this->config->item('n___30820')) . ')' => null, //Active Subscriber
                'x__down' => $e__id,
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ));
            if (!count($notification_levels)) {
                return array(
                    'status' => 0,
                    'message' => 'User is not an active subscriber',
                );
            }
            $sms_subscriber = in_array($notification_levels[0]['x__up'], $this->config->item('n___28915'));
        }

        $stats = array(
            'email_addresses' => array(),
            'phone_count' => 0,
        );


        //Send Emails:
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
            'x__down' => $e__id,
        )) as $e_data){

            if(!filter_var($e_data['x__message'], FILTER_VALIDATE_EMAIL)){
                $this->X_model->update($e_data['x__id'], array(
                    'x__access' => 6173, //Transaction Deleted
                ), $e__id, 27890 /* Website Archive */);
                continue;
            }

            array_push($stats['email_addresses'], $e_data['x__message']);

        }

        if(count($stats['email_addresses']) > 0){
            //Send email:
            send_email($stats['email_addresses'], $subject, $plain_message, $e__id, $x_data, $template_id, $x__website, $log_tr);
        }



        //Should we send SMS?
        $twilio_account_sid = website_setting(30859);
        $twilio_auth_token = website_setting(30860);
        $twilio_from_number = website_setting(27673);
        if($sms_subscriber && $twilio_account_sid && $twilio_auth_token && $twilio_from_number){

            //Yes, generate message
            $sms_message = $subject.( preg_match("/[a-z]/i", substr(strtolower($subject), -1)) ? ': ' : ' ' ).$plain_message;
            if(count($stats['email_addresses']) && strlen($sms_message)>view_memory(6404,27891)){
                $sms_message  = 'We emailed ['.$subject.'] to '.join(' & ',$stats['email_addresses']).' (it may end-up in Spam)';
            }

            //Breakup into smaller SMS friendly messages
            $sms_message = str_replace("\n"," ",$sms_message);

            //Send SMS
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__up' => 4783, //Phone
                'x__down' => $e__id,
            )) as $e_data){

                foreach(explode('|||',wordwrap($sms_message, view_memory(6404,27891), "|||")) as $single_message){

                    $sms_sent = send_sms($e_data['x__message'], $single_message, $e__id, $x_data, $template_id, $x__website, $log_tr);

                    if(!$sms_sent){
                        //bad number, remove it:
                        $this->X_model->update($e_data['x__id'], array(
                            'x__access' => 6173, //Transaction Deleted
                        ), $e__id, 27890 /* Website Archive */);
                    }

                }

                $stats['phone_count']++;

            }

        }

        return array(
            'status' => ( $stats['phone_count']>0 || count($stats['email_addresses'])>0 ? 1 : 0 ),
            'email_count' => count($stats['email_addresses']),
            'phone_count' => $stats['phone_count'],
            'message' => 'Message sent',
        );

    }

    function message_view($message_input, $is_discovery_mode = true, $member_e = array(), $message_i__id = 0, $plain_no_html = false)
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
         * - $member_e:         The source object that this message is supposed
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
        $msg_validation = $this->X_model->message_compile($message_input, $is_discovery_mode, $member_e, $message_i__id, false, $plain_no_html);



        //Did we have ane error in message validation?
        if(!isset($msg_validation['output_messages'])){

            return false;

        } elseif (!$msg_validation['status']) {

            //Log Error Transaction:
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__creator' => (isset($member_e['e__id']) ? $member_e['e__id'] : 0),
                'x__message' => 'message_compile() returned error [' . (isset($msg_validation['message']) ? $msg_validation['message'] : '') . '] for input message [' . $message_input . ']',
                'x__metadata' => array(
                    'clean_message' => $message_input,
                    'member_e' => $member_e,
                    'message_i__id' => $message_i__id
                ),
            ));

            return false;

        }


        //Message validation passed...
        return $msg_validation['output_messages'];

    }


    function message_compile($message_input, $is_discovery_mode, $member_e = array(), $message_i__id = 0, $strict_validation = true, $plain_no_html = false)
    {

        /*
         *
         * This function is used to validate IDEA NOTES.
         *
         * See message_view() for more information on input variables.
         *
         * */

        //Try to fetch session if recipient not provided:
        if(!isset($member_e['e__id'])){
            $member_e = superpower_unlocked();
        }

        //Cleanup:
        $message_input = trim($message_input);
        $message_input = str_replace('’','\'',$message_input);

        //Start with basic input validation:
        if (strlen($message_input) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Message',
            );
        } elseif (!preg_match('//u', $message_input)) {
            return array(
                'status' => 0,
                'message' => 'Message must be UTF8',
            );
        }





        /*
         *
         * Referenced Sources
         *
         * */

        //Start building the Output message body based on format:

        $message_input .= ' ';//Helps with accurate source reference replacement
        $output_body_message = htmlentities($message_input).' ';
        $string_references = extract_e_references($message_input); //Do it again since it may be updated
        $note_references = array();

        foreach($string_references['ref_e'] as $referenced_e){

            //We have a reference within this message, let's fetch it to better understand it:
            $es = $this->E_model->fetch(array(
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                'e__id' => $referenced_e,
            ));
            if (count($es) < 1) {
                //Remove Source:
                continue;
            }


            //Set as source reference:
            array_push($note_references, intval($referenced_e));


            //See if this source has any followings transactions to be shown in this appendix
            $e_media_count = 0;
            $e_count = 0;
            $e_appendix = null;
            $e_links = array();
            $first_segment = $this->uri->segment(1);
            $is_current_e = ( $first_segment=='@'.$referenced_e );

            //Determine what type of Media this reference has:
            if(!$is_current_e || $string_references['ref_time_found']){

                foreach($this->X_model->fetch(array(
                    'e__access IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //Public/Owner Access
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__down' => $referenced_e,
                    'LENGTH(x__message)>0' => null,
                ), array('x__up'), 0, 0, array(
                    'x__type' => 'ASC', /* Text first */
                    'e__weight' => 'DESC',
                )) as $e_up) {

                    //if(!strlen($e_up['x__message']) || (in_array($e_up['e__access'], $this->config->item('n___30956')) && !write_access_e($e_up['e__id']))){ continue; }

                    $detect_data_type = detect_data_type($e_up['x__message']);

                    $e_count++;

                    if($detect_data_type['x__type']==4256 /* URL */) {

                        array_push($e_links, $e_up);

                    } elseif (in_array($detect_data_type['x__type'], $this->config->item('n___12524'))) {

                        //SOURCE LINK VISUAL
                        $e_media_count++;
                        $e_appendix .= '<div class="e-appendix paddingup">' . preview_x__message($e_up['x__message'], $detect_data_type['x__type'], $message_input, $is_discovery_mode) . '</div>';

                    }
                }
            }



            //Append any appendix generated:
            $identifier_string = '@' . $referenced_e.($string_references['ref_time_found'] ? one_two_explode('@' . $referenced_e,' ',$message_input) : '' );

            $edit_btn = false;
            if(strlen($es[0]['e__cover'])){
                $edit_btn = '<span class="icon-block-xxs mini_6197_'.$es[0]['e__id'].'">'.view_cover($es[0]['e__cover'], true).'</span> ';
            }

            $on_its_own_line = false;
            $new_lines = 0;
            if($e_media_count > 0){
                foreach(explode("\n", $message_input) as $line){
                    if(strlen($line) > 0){
                        $new_lines++;
                    }
                    if(!$on_its_own_line && trim($line)==trim($identifier_string)){
                        $on_its_own_line = true;
                    }
                }
            }

            //Add Dropdown frame IF any:
            $e_dropdown = '';

            if(!$e_media_count && !count($e_links)){

                //Links not supported
                if($plain_no_html){
                    $e_dropdown .= $edit_btn.$es[0]['e__title'];
                } else {
                    $e_dropdown .= '<a href="/@'.$es[0]['e__id'].'" class="ignore-click"><span class="icon-block-xxs">' . view_cover($es[0]['e__cover'], true).'</span><u>'.$es[0]['e__title'].'</u></a>';
                }

            } elseif(!count($e_links) && $is_discovery_mode && !($on_its_own_line && $e_media_count)){

                //Just reference the source:
                $e_dropdown .= '<a href="/@'.$es[0]['e__id'].'" target="_blank" class="ignore-click"><span class="icon-block-xxs">' . view_cover($es[0]['e__cover'], true).'</span><u>'.$es[0]['e__title'].'</u></a>';

            } elseif(count($e_links)==1 && $is_discovery_mode){

                //Just show one:
                $e_dropdown .= '<a href="'.$e_links[0]['x__message'].'" target="_blank" class="ignore-click" title="'.$e_links[0]['e__title'].'">'.( in_array($es[0]['e__id'] , $this->config->item('n___34866')) ? '<u>'.$e_links[0]['x__message'].'</u>' : '<span class="icon-block-xxs">' . view_cover($es[0]['e__cover'], true).'</span><u>'.$es[0]['e__title'].'</u>' ).'</a>';

            } elseif(count($e_links) > 0) {

                //List all links:
                $e_dropdown .= '<div class="dropdown inline-block inline-dropdown"><button type="button" class="btn-transparent no-left-padding no-right-padding ignore-click" id="externalRef'.$es[0]['e__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.( $is_discovery_mode ? '<span class="icon-block-xs">' . view_cover($es[0]['e__cover'], true).'</span><u>'.$es[0]['e__title'].'</u>' : '' ).'</button><div class="dropdown-menu" aria-labelledby="externalRef'.$es[0]['e__id'].'">'; //<span class="icon-block-xs" style="font-size:0.89em;"><i class="far fa-angle-down"></i></span>

                //Link references:
                foreach($e_links as $e_link){
                    $e_dropdown .= '<a href="'.$e_link['x__message'].'" target="_blank" class="dropdown-item main__title ignore-click"><span class="icon-block">'.view_cover($e_link['e__cover'], true).'</span>'.$e_link['e__title'].' <i class="far fa-external-link"></i></a>';
                }

                //Source reference:
                $e_dropdown .= '<a href="/@'.$es[0]['e__id'].'" class="dropdown-item main__title ignore-click"><span class="icon-block">'.view_cover($es[0]['e__cover'], true).'</span>'.$es[0]['e__title'].'</a>';

                $e_dropdown .= '</div></div>';

            }


            //Displays:
            if($on_its_own_line){

                if($new_lines <= 1){
                    $output_body_message = $e_appendix.str_replace($identifier_string, $e_dropdown, $output_body_message);
                } else {
                    $output_body_message = str_replace($identifier_string, $e_dropdown, $output_body_message).$e_appendix;
                }

            } else {
                $output_body_message = str_replace($identifier_string, $e_dropdown, $output_body_message).$e_appendix;
            }

        }

        //Return results:
        return array(
            'status' => 1,
            'clean_message' => trim($message_input),
            'output_messages' => ( strlen(trim($message_input)) ? '<div class="msg"><span>' . nl2br($output_body_message) . '</span></div>' : null ),
            'note_references' => $note_references,
        );
    }



    function find_previous($e__id, $top_i__id, $i__id, $loop_breaker_ids = array())
    {

        if(count($loop_breaker_ids)>0 && in_array($i__id, $loop_breaker_ids)){
            return array();
        }
        array_push($loop_breaker_ids, intval($i__id));

        //Fetch followings:
        foreach($this->X_model->fetch(array(
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
            'x__right' => $i__id,
        ), array('x__left')) as $i_previous) {

            //Validate Selection:
            $is_or_i = in_array($i_previous['i__type'], $this->config->item('n___7712'));
            $is_fixed_x = in_array($i_previous['x__type'], $this->config->item('n___12840'));
            $is_selected = count($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'x__left' => $i_previous['i__id'],
                'x__right' => $i__id,
                'x__creator' => $e__id,
            )));

            if($e__id>0 && !$is_selected && ($is_or_i || !$is_fixed_x)){
                continue;
            }

            //Did we find it?
            if($i_previous['i__id']==$top_i__id){
                return array($i_previous);
            }

            //Keep looking:
            $top_search = $this->X_model->find_previous($e__id, $top_i__id, $i_previous['i__id'], $loop_breaker_ids);
            if(count($top_search)){
                array_push($top_search, $i_previous);
                return $top_search;
            }
        }

        //Did not find any followings:
        return array();

    }




    function find_next($e__id, $top_i__id, $i, $find_after_i__id = 0, $search_up = true, $top_completed = false, $loop_breaker_ids = array())
    {

        if(count($loop_breaker_ids)>0 && in_array($i['i__id'], $loop_breaker_ids)){
            return 0;
        }
        array_push($loop_breaker_ids, intval($i['i__id']));

        $is_or_i = in_array($i['i__type'], $this->config->item('n___7712'));
        $found_trigger = false;

        foreach ($this->X_model->fetch(array(
            'x__left' => $i['i__id'],
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $next_i) {

            //Validate Find After:
            if ($find_after_i__id && !$found_trigger) {
                if ($next_i['i__id']==$find_after_i__id) {
                    $found_trigger = true;
                }
                continue;
            }

            //Validate Selection:
            $is_fixed_x = in_array($next_i['x__type'], $this->config->item('n___12840'));
            $is_selected = count($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'x__left' => $i['i__id'],
                'x__right' => $next_i['i__id'],
                'x__creator' => $e__id,
            )));
            if(($is_or_i || !$is_fixed_x) && !$is_selected){
                continue;
            }


            //Return this if everything is completed, or if this is incomplete:
            if($top_completed || !count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__creator' => $e__id,
                    'x__left' => $next_i['i__id'],
                )))){
                return intval($next_i['i__id']);
            }

            //Keep looking deeper:
            $found_next = $this->X_model->find_next($e__id, $top_i__id, $next_i, 0, false, $top_completed, $loop_breaker_ids);
            if ($found_next) {
                return $found_next;
            }

        }

        if ($search_up && $top_i__id!=$i['i__id']) {
            //Check Previous/Up
            $current_previous = $i['i__id'];
            foreach (array_reverse($this->X_model->find_previous($e__id, $top_i__id, $i['i__id'])) as $p_i) {
                //Find the next siblings:
                $found_next = $this->X_model->find_next($e__id, $top_i__id, $p_i, $current_previous, false, $top_completed);
                if ($found_next) {
                    return $found_next;
                }
                $current_previous = $p_i['i__id'];
            }
        }


        //Nothing found:
        return 0;

    }






    function read_only_complete($top_i__id, $i, $x_data = array()){

        //Try to auto complete:
        $x__type = 0;

        if (in_array($i['i__type'], $this->config->item('n___34826'))) {
            if ($i['i__type'] == 6677) {
                $x__type = 4559;
            } elseif ($i['i__type'] == 30874) {
                $x__type = 31810;
            } elseif ($i['i__type'] == 31807) {
                $x__type = 31808;
            }
        }

        if($x__type > 0){
            return $this->X_model->mark_complete($top_i__id, $i, array_merge($x_data, array(
                'x__type' => $x__type,
            )));
        } else {
            return false;
        }

    }


    function mark_complete($top_i__id, $i, $add_fields) {

        if(!isset($add_fields['x__type']) || !in_array($add_fields['x__type'], $this->config->item('n___31777'))){
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__message' => 'mark_complete() Invalid x__type @'.$add_fields['x__type'].' missing in @31777',
                'x__metadata' => array(
                    '$top_i__id' => $top_i__id,
                    '$i' => $i,
                    '$add_fields' => $add_fields,
                ),
            ));
        }

        //Always add Idea to x__left
        if($top_i__id>0 && (!isset($add_fields['x__right']) || intval($add_fields['x__right'])==0)){
            $add_fields['x__right'] = $top_i__id;
        }
        $add_fields['x__left'] = $i['i__id'];

        if (!isset($add_fields['x__message'])) {
            $add_fields['x__message'] = null;
        }

        $member_e = superpower_unlocked();
        if (!isset($add_fields['x__creator']) && $member_e) {
            $add_fields['x__creator'] = $member_e['e__id'];
        }
        $es_creator = $this->E_model->fetch(array(
            'e__id' => $add_fields['x__creator'],
        ));

        $x__creator = ( isset($add_fields['x__creator']) ? $add_fields['x__creator'] : 0);

        //Make sure not duplicate:
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__type NOT IN (' . join(',', $this->config->item('n___30469')) . ')' => null, //TICKETS
            'x__left' => ( isset($add_fields['x__left']) ? $add_fields['x__left'] : 0 ),
            'x__right' => ( isset($add_fields['x__right']) ? $add_fields['x__right'] : 0 ),
            'x__creator' => $x__creator,
            'x__message' => @$add_fields['x__message'],
        )) as $already_discovered){
            //Already discovered! Return this:
            return $already_discovered;
        }

        //Add new transaction:
        $domain_url = get_domain('m__message', $x__creator);
        $new_x = $this->X_model->create($add_fields);


        //Auto Complete OR Answers:
        if(in_array($i['i__type'], $this->config->item('n___7712'))){
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
                'x__creator' => $add_fields['x__creator'],
                'x__left' => $i['i__id'],
            ), array('x__right'), 0) as $next_i){
                //Mark as complete:
                $this->X_model->read_only_complete($top_i__id, $next_i, $add_fields);
            }
        }

        //Ticket Email?
        if(isset($new_x['x__id']) && isset($new_x['x__creator']) && in_array($new_x['x__type'], $this->config->item('n___32014'))){
            send_qr($new_x['x__id'],$new_x['x__creator']);
        }


        if ($add_fields['x__creator'] && in_array($add_fields['x__type'], $this->config->item('n___40986'))) {

            //Discovery Triggers?
            $clone_urls = '';
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___32275')) . ')' => null, //DISCOVERY TRIGGERS
                'x__left' => $i['i__id'],
            ), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $clone_i){

                if($clone_i['x__type']==32247){

                    //Discovery Clone
                    $new_title = $es_creator[0]['e__title'].' '.$clone_i['i__message'];
                    $result = $this->I_model->recursive_clone($clone_i['i__id'], 0, $add_fields['x__creator'], null, $new_title);
                    if($result['status']){

                        //Add as watcher:
                        $this->X_model->create(array(
                            'x__type' => 10573, //WATCHERS
                            'x__creator' => $add_fields['x__creator'],
                            'x__up' => $add_fields['x__creator'],
                            'x__right' => $result['new_i__id'],
                        ));

                        //New link:
                        $clone_urls .= $new_title.':'."\n".'https://'.get_domain('m__message', $add_fields['x__creator']).'/'.$result['new_i__id']."\n\n";
                    }

                } elseif($clone_i['x__type']==32304){

                    //Discovery Forget: Remove all Discoveries made by this user:
                    foreach($this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__left' => $i['i__id'],
                        'x__creator' => $add_fields['x__creator'],
                    )) as $remove_x){
                        $this->X_model->update($remove_x['x__id'], array(
                            'x__access' => 6173, //Remove this discovery
                        ), $add_fields['x__creator'], 29431 /* Play Auto Removed */);
                    }

                } elseif($clone_i['x__type']==32426){

                    //TODO Set the focus source to the source that was created here, if any:

                }

            }

            if(strlen($clone_urls)){
                //Send DM with all the new clone idea URLs:
                $clone_urls = $clone_urls.'You have been added as a subscriber so you will be notified when anyone start using your link.';
                $i_title = view_i_title($i, true);
                $this->X_model->send_dm($add_fields['x__creator'], $i_title , $clone_urls);
                //Also DM all watchers of the idea:
                foreach($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type' => 10573, //WATCHERS
                    'x__right' => $i['i__id'],
                ), array(), 0) as $watcher){
                    $this->X_model->send_dm($watcher['x__up'], $i_title, $clone_urls);
                }
            }



            //ADD PROFILE?
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7545, //Following Add
                'x__right' => $i['i__id'],
            ), array('x__up')) as $x_tag){

                //Check if special profile add?

                if($x_tag['x__up']==6197 && strlen(trim($add_fields['x__message']))>=2){

                    //Update Source Title:
                    $this->E_model->update($add_fields['x__creator'], array(
                        'e__title' => $add_fields['x__message'],
                    ), true, $add_fields['x__creator']);

                    //Update live session as well:
                    $es_creator[0]['e__title'] = $add_fields['x__message'];
                    $this->E_model->activate_session($es_creator[0], true);

                } elseif($x_tag['x__up']==6198 && intval(str_replace('@','',$add_fields['x__message']))){

                    //Update Source Cover:
                    foreach($this->X_model->fetch(array(
                        'x__type' => 4260, //IMAGES
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__down' => intval(str_replace('@','',$add_fields['x__message'])),
                    ), array('x__up'), 1, 0, array('e__weight' => 'DESC')) as $following){

                        //Update profile picture for current user:
                        $this->E_model->update($add_fields['x__creator'], array(
                            'e__cover' => $following['x__message'],
                        ), true, $add_fields['x__creator']);

                        //Update live session as well:
                        $es_creator[0]['e__cover'] = $following['x__message'];
                        $this->E_model->activate_session($es_creator[0], true);

                    }

                } else {

                    //Generate stats:
                    $x_added = 0;
                    $x_edited = 0;
                    $x_deleted = 0;

                    //Assign tag if following/follower transaction NOT previously assigned:
                    $existing_x = $this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__up' => $x_tag['x__up'],
                        'x__down' => $add_fields['x__creator'],
                    ));

                    if(count($existing_x)){

                        //Transaction previously exists, see if content value is the same:
                        if(strtolower($existing_x[0]['x__message'])==strtolower($add_fields['x__message'])){
                            //Everything is the same, nothing to do here:
                            continue;
                        }

                        $x_edited++;

                        //Content value has changed, update the transaction:
                        $this->X_model->update($existing_x[0]['x__id'], array(
                            'x__message' => $add_fields['x__message'],
                        ), $add_fields['x__creator'], 10657 /* SOURCE LINK CONTENT UPDATE  */);

                        $this->X_model->create(array(
                            'x__type' => 12197, //Following Added
                            'x__creator' => $add_fields['x__creator'],
                            'x__up' => $x_tag['x__up'],
                            'x__down' => $add_fields['x__creator'],
                            'x__left' => $i['i__id'],
                            'x__message' => $x_added.' added, '.$x_edited.' edited & '.$x_deleted.' deleted with new content ['.$add_fields['x__message'].']',
                        ));

                    } else {

                        //Create transaction:
                        $x_added++;
                        $this->X_model->create(array(
                            'x__type' => 4230, //Follow Source
                            'x__message' => $add_fields['x__message'],
                            'x__creator' => $add_fields['x__creator'],
                            'x__up' => $x_tag['x__up'],
                            'x__down' => $add_fields['x__creator'],
                        ));

                        $this->X_model->create(array(
                            'x__type' => 12197, //Following Added
                            'x__creator' => $add_fields['x__creator'],
                            'x__up' => $x_tag['x__up'],
                            'x__down' => $add_fields['x__creator'],
                            'x__left' => $i['i__id'],
                            'x__message' => $x_added.' added, '.$x_edited.' edited & '.$x_deleted.' deleted with new content ['.$add_fields['x__message'].']',
                        ));

                    }

                    //See if Session needs to be updated:
                    if($member_e && $member_e['e__id']==$add_fields['x__creator'] && ($x_added>0 || $x_edited>0 || $x_deleted>0)){
                        $this->E_model->activate_session($es_creator[0], true);
                    }
                }
            }


            //REMOVE PROFILE?
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 26599, //Following Remove
                'x__right' => $i['i__id'],
            )) as $x_tag){

                //Remove Following IF previously assigned:
                foreach($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__up' => $x_tag['x__up'], //CERTIFICATES saved here
                    'x__down' => $add_fields['x__creator'],
                )) as $existing_x){

                    $this->X_model->update($existing_x['x__id'], array(
                        'x__access' => 6173,
                    ), $add_fields['x__creator'], 12197 /* Following Removed */);

                    //See if Session needs to be updated:
                    if($member_e && $member_e['e__id']==$add_fields['x__creator']){
                        //Yes, update session:
                        $this->E_model->activate_session($es_creator[0], true);
                    }
                }
            }


            //Notify watchers IF any:
            $watchers = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 10573, //WATCHERS
                'x__right' => $i['i__id'],
            ), array(), 0);
            if(count($watchers)){

                $es_discoverer = $this->E_model->fetch(array(
                    'e__id' => $add_fields['x__creator'],
                ));
                if(count($es_discoverer)){

                    //Fetch Discoverer contact:
                    $discoverer_contact = '';
                    foreach($this->config->item('e___34541') as $x__type => $m) {
                        foreach($this->X_model->fetch(array(
                            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__down' => $add_fields['x__creator'],
                            'x__up' => $x__type,
                            'LENGTH(x__message)>0' => null,
                        )) as $x_progress){
                            $discoverer_contact .= $m['m__title'].':'."\n".$x_progress['x__message']."\n\n";
                        }
                    }

                    //Notify Idea Watchers
                    $sent_watchers = array();
                    foreach($watchers as $watcher){
                        if(!in_array(intval($watcher['x__up']), $sent_watchers)){
                            array_push($sent_watchers, intval($watcher['x__up']));

                            $this->X_model->send_dm($watcher['x__up'], $es_discoverer[0]['e__title'].' Discovered: '.view_i_title($i, true),
                                //Message Body:
                                view_i_title($i, true).':'."\n".'https://'.$domain_url.'/~'.$i['i__id']."\n\n".
                                ( strlen($add_fields['x__message']) ? $add_fields['x__message']."\n\n" : '' ).
                                $es_discoverer[0]['e__title'].':'."\n".'https://'.$domain_url.'/@'.$es_discoverer[0]['e__id']."\n\n".
                                $discoverer_contact
                            );
                        }
                    }
                }
            }
        }
        


        return $new_x;

    }



    function tree_progress($e__id, $i, $current_level = 0, $loop_breaker_ids = array())
    {

        if(count($loop_breaker_ids)>0 && in_array($i['i__id'], $loop_breaker_ids)){
            return false;
        }

        $recursive_down_ids = $this->I_model->recursive_down_ids($i, 'AND');
        if(!count($recursive_down_ids)){
            return false;
        }

        $current_level++;
        array_push($loop_breaker_ids, intval($i['i__id']));

        //Count completed:
        $list_discovered = array();
        foreach($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__creator' => $e__id, //Belongs to this Member
            'x__left IN (' . join(',', $recursive_down_ids ) . ')' => null,
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('x__left'), 0) as $completed){
            if(!in_array(intval($completed['i__id']), $list_discovered)){
                array_push($list_discovered, intval($completed['i__id']));
            }
        }


        //Calculate common steps and expansion steps recursively for this u:
        $metadata_this = array(
            'fixed_total' => count($recursive_down_ids),
            'list_total' => $recursive_down_ids,
            'fixed_discovered' => count($list_discovered),
            'list_discovered' => $list_discovered,
        );

        //Now let's check possible expansions:
        foreach($this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //Discovery Expansion
            'x__creator' => $e__id, //Belongs to this Member
            'x__left IN (' . join(',', $recursive_down_ids ) . ')' => null,
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('x__right')) as $expansion_in) {

            //Fetch recursive:
            $tree_progress = $this->X_model->tree_progress($e__id, $expansion_in, $current_level, $loop_breaker_ids);

            //Addup completion stats for this:
            $metadata_this['fixed_total'] += $tree_progress['fixed_total'];
            $metadata_this['fixed_discovered'] += $tree_progress['fixed_discovered'];

            if($tree_progress['list_total'] && count($tree_progress['list_total'])){
                foreach($tree_progress['list_total'] as $tree_id){
                    if(!in_array($tree_id, $metadata_this['list_total'])){
                        array_push($metadata_this['list_total'], $tree_id);
                    }
                }
            }

            if($tree_progress['list_discovered'] && count($tree_progress['list_discovered'])){
                foreach($tree_progress['list_discovered'] as $tree_id){
                    if(!in_array($tree_id, $metadata_this['list_discovered'])){
                        array_push($metadata_this['list_discovered'], $tree_id);
                    }
                }
            }

        }


        if($current_level==1){

            /*
             *
             * Completing an discoveries depends on two factors:
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
            $metadata_this['fixed_completed_percentage'] = 0;

            //Calculate completion rate based on estimated time cost:
            if($metadata_this['fixed_total'] > 0){
                $metadata_this['fixed_completed_percentage'] = intval(ceil( $metadata_this['fixed_discovered'] / $metadata_this['fixed_total'] * 100 ));
            }


        }

        //Return results:
        return $metadata_this;

    }


    function started_ids($e__id, $i__id = 0){

        //Simply returns all the idea IDs for a users starting points
        if($i__id > 0){

            if(!$e__id){
                return false;
            }

            return count($this->X_model->fetch(array(
                'x__left' => $i__id,
                'x__right' => $i__id,
                'x__creator' => $e__id,
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )));

        } else {

            $u_x_ids = array();
            if($e__id > 0){
                foreach($this->X_model->fetch(array(
                    'x__left=x__right' => null,
                    'x__creator' => $e__id,
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )) as $u_in){
                    array_push($u_x_ids, intval($u_in['x__left']));
                }
            }
            return $u_x_ids;

        }
    }




    function x_select($top_i__id, $focus_i__id, $answer_i__ids){

        $member_e = superpower_unlocked();
        if (!$member_e) {
            return array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            );
        }

        $is = $this->I_model->fetch(array(
            'i__id' => $focus_i__id,
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        $es = $this->E_model->fetch(array(
            'e__id' => $member_e['e__id'],
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if (!count($is)) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea ID',
            );
        } elseif (!count($es)) {
            return array(
                'status' => 0,
                'message' => 'Invalid Source ID #4',
            );
        } elseif (!in_array($is[0]['i__type'], $this->config->item('n___7712'))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Idea type [Must be Answer]',
            );
        }


        //Can they skip without selecting anything?
        $can_skip = count($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__right' => $focus_i__id,
            'x__up' => 28239, //Can Skip
        )));
        if(!$can_skip && !count($answer_i__ids)){
            return array(
                'status' => 0,
                'message' => 'You must select an item before going next.',
            );
        }
        $did_skip = ( $can_skip && !count($answer_i__ids) );



        //Can they avoid min/max selection limits? Only if an answer is linked to @39658
        $avoid_selection_limits = count($answer_i__ids) && count($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__right IN (' . join(',', $answer_i__ids) . ')' => null,
            'x__up' => 39658, //Avoid Selection Limits
        ), array(), 1));

        if(!$avoid_selection_limits){

            //You can't avoid it! Let's check...

            //How about the min selection?
            if(!$can_skip){
                foreach($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $focus_i__id,
                    'x__up' => 40834, //Min Selection
                ), array(), 1) as $limit){
                    if(intval($limit['x__message']) > 0 && count($answer_i__ids) < intval($limit['x__message'])){
                        return array(
                            'status' => 0,
                            'message' => 'You must select '.$limit['x__message'].' items or more.',
                        );
                    }
                }
            }

            //How about  max selection?
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $focus_i__id,
                'x__up' => 40833, //Max Selection
            ), array(), 1) as $limit){
                if(intval($limit['x__message']) > 0 && count($answer_i__ids) > intval($limit['x__message'])){
                    return array(
                        'status' => 0,
                        'message' => 'You cannot select more than '.$limit['x__message'].' items.',
                    );
                }
            }

        }



        //Define completion transactions for each answer:
        if(in_array($is[0]['i__type'], $this->config->item('n___7712'))){
            $x__type = ( count($answer_i__ids) ? ( $is[0]['i__type']==6684 ? 6157 : 41940 ) : 31022 ); //Choose & Next / Skip
            $i_x__type = 12336; //Discovery Choose Link
        }

        //Delete ALL previous answers:
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___32234')) . ')' => null, //DISCOVERY ANSWERED
            'x__creator' => $member_e['e__id'],
            'x__left' => $is[0]['i__id'],
        )) as $x_progress){
            $this->X_model->update($x_progress['x__id'], array(
                'x__access' => 6173, //Transaction Deleted
            ), $member_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);
            //TODO Also remove the discovery of the selected if not a payment type:
        }

        //Add New Answers
        $answers_newly_added = 0;
        if(count($answer_i__ids)){
            foreach($answer_i__ids as $answer_i__id){
                $answers_newly_added++;
                $this->X_model->create(array(
                    'x__type' => $i_x__type,
                    'x__creator' => $member_e['e__id'],
                    'x__left' => $is[0]['i__id'],
                    'x__right' => $answer_i__id,
                ));
            }
        }

        //Issue DISCOVERY/IDEA COIN:
        $this->X_model->mark_complete($top_i__id, $is[0], array(
            'x__type' => $x__type,
            'x__creator' => $member_e['e__id'],
        ));

        //All good, something happened:
        return array(
            'status' => 1,
            'message' => $answers_newly_added.' Selected. Going Next...',
        );

    }



}