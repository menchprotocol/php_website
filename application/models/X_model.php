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
        if (!isset($add_fields['x__source']) || intval($add_fields['x__source']) < 1) {
            $add_fields['x__source'] = 14068; //GUEST MEMBER
        }

        //Only require transaction type:
        if (detect_missing_columns($add_fields, array('x__type'), $add_fields['x__source'])) {
            return false;
        }

        if(!in_array($add_fields['x__type'], $this->config->item('n___4593'))){
            $this->X_model->create(array(
                'x__message' => 'x->create() failed to create because of invalid transaction type @'.$add_fields['x__type'],
                'x__type' => 4246, //Platform Bug Reports
                'x__source' => $add_fields['x__source'],
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
        if (!isset($add_fields['x__domain'])) {
            $add_fields['x__domain'] = get_domain_setting(0, $add_fields['x__source']);
        }


        if (!isset($add_fields['x__time']) || is_null($add_fields['x__time'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $add_fields['x__time'] = $d->format("Y-m-d H:i:s.u");
        }

        if (!isset($add_fields['x__status'])|| is_null($add_fields['x__status'])) {
            $add_fields['x__status'] = 6176; //Transaction Published
        }

        //Set some zero defaults if not set:
        foreach(array('x__right', 'x__left', 'x__down', 'x__up', 'x__reference', 'x__spectrum') as $dz) {
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
                'x__source' => $add_fields['x__source'],
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


        //SOURCE SYNC Status
        if(in_array($add_fields['x__type'] , $this->config->item('n___12401'))){
            if($add_fields['x__down'] > 0){
                $e__id = $add_fields['x__down'];
            } elseif($add_fields['x__up'] > 0){
                $e__id = $add_fields['x__up'];
            }
            $this->E_model->match_x_status($add_fields['x__source'], array(
                'e__id' => $e__id,
            ));
        }

        //IDEA SYNC Status
        if(in_array($add_fields['x__type'] , $this->config->item('n___12400'))){
            if($add_fields['x__right'] > 0){
                $i__id = $add_fields['x__right'];
            } elseif($add_fields['x__left'] > 0){
                $i__id = $add_fields['x__left'];
            }
            $this->I_model->match_x_status($add_fields['x__source'], array(
                'i__id' => $i__id,
            ));
        }


        //See if this transaction type has any subscribers:
        if(in_array($add_fields['x__type'] , $this->config->item('n___5967')) && $add_fields['x__type']!=5967 /* Email Sent causes endless loop */){

            $e___5967 = $this->config->item('e___5967'); //Include subscription details
            $sub_e__ids = explode(',', $e___5967[$add_fields['x__type']]['m__message']);

            //Did we find any subscribers?
            if(count($sub_e__ids) > 0){

                //yes, start drafting email to be sent to them...
                $u_name = get_domain('m__title', $add_fields['x__source']);
                if($add_fields['x__source'] > 0){
                    //Fetch member details:
                    $add_e = $this->E_model->fetch(array(
                        'e__id' => $add_fields['x__source'],
                    ));
                    if(count($add_e)){
                        $u_name = $add_e[0]['e__title'];
                    }
                }


                //Email Subject:
                $subject = 'Notification: '  . $u_name . ' ' . $e___5967[$add_fields['x__type']]['m__title'];

                //Compose email body, start with transaction content:
                $plain_message = ( strlen($add_fields['x__message']) > 0 ? $add_fields['x__message'] : '') . "\n";

                $var_index = var_index();

                //Append transaction object transactions:
                foreach($this->config->item('e___11081') as $e__id => $m) {

                    if(!array_key_exists($e__id, $var_index) || !intval($add_fields[$var_index[$e__id]])){
                        continue;
                    }

                    if (in_array(6202 , $m['m__profile'])) {

                        //IDEA
                        $is = $this->I_model->fetch(array( 'i__id' => $add_fields[$var_index[$e__id]] ));
                        $plain_message .= $m['m__title'] . ': '.$is[0]['i__title'].':'."\n".$this->config->item('base_url').'/i/i_go/' . $is[0]['i__id']."\n\n";

                    } elseif (in_array(6160 , $m['m__profile'])) {

                        //SOURCE
                        $es = $this->E_model->fetch(array( 'e__id' => $add_fields[$var_index[$e__id]] ));
                        if(count($es)){
                            $plain_message .= $m['m__title'] . ': '.$es[0]['e__title']."\n".$this->config->item('base_url').'/@' . $es[0]['e__id'] . "\n\n";
                        }

                    } elseif (in_array(4367 , $m['m__profile'])) {

                        //DISCOVERY
                        $plain_message .= $m['m__title'] . ':'."\n".$this->config->item('base_url').'/-12722?x__id=' . $add_fields[$var_index[$e__id]]."\n\n";

                    }

                }

                //Finally append DISCOVERY ID:
                $plain_message .= 'TRANSACTION: #'.$add_fields['x__id']."\n".$this->config->item('base_url').'/-12722?x__id=' . $add_fields['x__id']."\n\n";

                //Inform how to change settings:
                $plain_message .= 'Manage your transaction notifications:'."\n".$this->config->item('base_url').'/@5967'."\n\n";

                //Try to fetch subscribers:

                foreach($sub_e__ids as $subscriber_e__id){
                    //Do not inform the member who just took the action:
                    if($subscriber_e__id!=$add_fields['x__source']){
                        $this->X_model->send_dm($subscriber_e__id, $subject, $plain_message, array(
                            'x__reference' => $add_fields['x__id'], //Save transaction
                            'x__right' => $add_fields['x__right'],
                            'x__left' => $add_fields['x__left'],
                            'x__down' => $add_fields['x__down'],
                            'x__up' => $add_fields['x__up'],
                        ));
                    }
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
        } elseif (in_array('x__source', $join_objects)) {
            $this->db->join('table__e', 'x__source=e__id','left');
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

    function update($id, $update_columns, $x__source = 0, $x__type = 0, $x__message = '')
    {

        $id = intval($id);
        if (count($update_columns) == 0) {
            return false;
        } elseif ($x__type>0 && !in_array($x__type, $this->config->item('n___4593'))) {
            $this->X_model->create(array(
                'x__message' => 'x->update() failed to update because of invalid transaction type @'.$x__type,
                'x__type' => 4246, //Platform Bug Reports
                'x__source' => $x__source,
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
        if ($affected_rows > 0 && $x__source > 0 && $x__type > 0) {

            if(strlen($x__message) == 0){
                if(in_array($x__type, $this->config->item('n___10593') /* Statement */)){

                    //Since it's a statement we want to determine the change in content:
                    if($before_data[0]['x__message']!=$update_columns['x__message']){
                        $x__message .= update_description($before_data[0]['x__message'], $update_columns['x__message']);
                    }

                } else {

                    //Log modification transaction for every field changed:
                    foreach($update_columns as $key => $value) {

                        if($before_data[0][$key]==$value){
                            continue;
                        }

                        //Now determine what type is this:
                        if($key=='x__status'){

                            $e___6186 = $this->config->item('e___6186'); //Transaction Status
                            $x__message .= view_db_field($key) . ' updated from [' . $e___6186[$before_data[0][$key]]['m__title'] . '] to [' . $e___6186[$value]['m__title'] . ']'."\n";

                            //Is this a Paypal transaction being removed?
                            if(count($before_data)){
                                $x__metadata = @unserialize($before_data[0]['x__metadata']);
                                $paypal_client_id = get_domain_setting(30857);
                                $paypal_secret_key = get_domain_setting(30858);

                                if($paypal_client_id && $paypal_secret_key && isset($x__metadata['txn_id']) && strlen($x__metadata['txn_id']) && $before_data[0]['x__type']==26595 && $before_data[0]['x__status']!=6173 && $value==6173){

                                    $ch=curl_init();
                                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                        'Content-Type: application/json',
                                        'Authorization: Basic '.base64_encode($paypal_client_id.":".$paypal_secret_key),
                                    ));
                                    curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/payments/sale/".$x__metadata['txn_id']."/refund");
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_HEADER, false);
                                    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                    curl_setopt($ch, CURLOPT_POST, true);
                                    curl_setopt($ch, CURLOPT_POSTFIELDS, "{}");
                                    $result = curl_exec($ch);
                                    $y=json_decode($result,true);

                                    //Log this refund:
                                    $this->X_model->create(array(
                                        'x__source' => $before_data[0]['x__source'],
                                        'x__type' => 29432, //Paypal Full Refund
                                        'x__right' => $before_data[0]['x__right'],
                                        'x__left' => $before_data[0]['x__left'],
                                        'x__up' => $before_data[0]['x__up'],
                                        'x__down' => $before_data[0]['x__down'],
                                        'x__reference' => $before_data[0]['x__id'],
                                        'x__message' => $x__metadata['mc_currency'].' '.$x__metadata['mc_gross'].' Refunded in Full',
                                        'x__metadata' => $y,
                                    ));

                                }
                            }

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

                            $x__message .= view_db_field($key) . ' updated from [' . $before_i[0]['i__title'] . '] to [' . $after_i[0]['i__title'] . ']' . "\n";

                        } elseif(in_array($key, array('x__message', 'x__spectrum'))){

                            $x__message .= view_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']'."\n";

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

            if(strlen($x__message) > 0 && count($fields_changed) > 0){
                //Value has changed, log transaction:
                $this->X_model->create(array(
                    'x__reference' => $id, //Transaction Reference
                    'x__source' => $x__source,
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

    function max_spectrum($query_filters)
    {

        //Fetches the maximum order value
        $this->db->select('MAX(x__spectrum) as largest_order');
        $this->db->from('table__x');
        foreach($query_filters as $key => $value) {
            $this->db->where($key, $value);
        }
        $q = $this->db->get();
        $stats = $q->row_array();
        return ( count($stats) > 0 ? intval($stats['largest_order']) : 0 );

    }



    function send_dm($e__id, $subject, $plain_message, $x_data = array(), $template_id = 0, $x__domain = 0)
    {

        $notification_levels = $this->X_model->fetch(array(
            'x__up IN (' . join(',', $this->config->item('n___28904')) . ')' => null, //Manage Notifications
            'x__down' => $e__id,
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ));

        if (!count($notification_levels)) {
            $this->X_model->create(array(
                'x__message' => 'send_dm() user is not in any of @28904',
                'x__type' => 4246, //Platform Bug Reports
                'x__up' => 28904,
                'x__down' => $e__id,
            ));
            return array(
                'status' => 0,
                'message' => 'Unknown user status',
            );
        }

        if(in_array($notification_levels[0]['x__up'], $this->config->item('n___28914'))){
            return array(
                'status' => 0,
                'message' => 'User is unsubscribed',
            );
        }

        $stats = array(
            'email_addresses' => array(),
            'phone_count' => 0,
        );


        //Send Emails:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
            'x__down' => $e__id,
        )) as $e_data){

            if(!filter_var($e_data['x__message'], FILTER_VALIDATE_EMAIL)){
                $this->X_model->update($e_data['x__id'], array(
                    'x__status' => 6173, //Transaction Deleted
                ), $e__id, 27890 /* Invalid Input Removed */);
                continue;
            }

            array_push($stats['email_addresses'], $e_data['x__message']);

        }

        if(count($stats['email_addresses']) > 0){
            //Send email:
            email_send($stats['email_addresses'], $subject, $plain_message, $e__id, $x_data, $template_id, $x__domain);
        }



        //Should we send SMS?
        $twilio_account_sid = get_domain_setting(30859);
        $twilio_auth_token = get_domain_setting(30860);
        $twilio_from_number = get_domain_setting(27673);
        if(in_array($notification_levels[0]['x__up'], $this->config->item('n___28915')) && $twilio_account_sid && $twilio_auth_token && $twilio_from_number){

            //Yes, generate message
            $sms_message = $subject.( preg_match("/[a-z]/i", substr(strtolower($subject), -1)) ? ': ' : ' ' ).$plain_message;
            if(count($stats['email_addresses']) && strlen($sms_message)>view_memory(6404,27891)){
                $sms_message  = 'We emailed ['.$subject.'] to '.join(' & ',$stats['email_addresses']).' (Might go to Spam)';
            }

            //Breakup into smaller SMS friendly messages
            $sms_message = str_replace("\n"," ",$sms_message);

            //Send SMS
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => 4783, //Phone
                'x__down' => $e__id,
            )) as $e_data){

                foreach(explode('|||',wordwrap($sms_message, view_memory(6404,27891), "|||")) as $single_message){

                    $post = array(
                        'From' => $twilio_from_number,
                        'Body' => $single_message,
                        'To' => $e_data['x__message'],
                    );

                    $x = curl_init("https://api.twilio.com/2010-04-01/Accounts/".$twilio_account_sid."/SMS/Messages");
                    curl_setopt($x, CURLOPT_POST, true);
                    curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($x, CURLOPT_USERPWD, $twilio_account_sid.":".$twilio_auth_token);
                    curl_setopt($x, CURLOPT_POSTFIELDS, http_build_query($post));
                    $y = curl_exec($x);
                    curl_close($x);


                    if(substr_count($y, '<Code>21211</Code>')){
                        //Remove Phone Number:
                        $sms_success = false;
                        $this->X_model->update($e_data['x__id'], array(
                            'x__status' => 6173, //Transaction Deleted
                        ), $e__id, 27890 /* Invalid Input Removed */);

                        break;

                    } else {
                        $sms_success = substr_count($y, '<SMSMessage><Sid>');
                    }

                    //Log transaction:
                    $this->X_model->create(array_merge($x_data, array(
                        'x__type' => ( $sms_success ? 27676 : 27678 ), //SMS Success/Fail
                        'x__source' => $e__id,
                        'x__message' => $single_message,
                        'x__down' => $template_id,
                        'x__metadata' => array(
                            'post' => $post,
                            'response' => $y,
                        ),
                    )));

                }

                $stats['phone_count']++;

            }

        }

        return array(
            'status' => ( $stats['phone_count']>0 || count($stats['email_addresses'])>0 ? 1 : 0 ),
            'email_count' => count($stats['email_addresses']),
            'phone_count' => $stats['phone_count'],
            'message' => 'Message sent',
        );;

    }

    function message_view($message_input, $is_discovery_mode, $member_e = array(), $message_i__id = 0, $simple_version = false)
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
        $msg_validation = $this->X_model->message_compile($message_input, $is_discovery_mode, $member_e, 0, $message_i__id, false, $simple_version);


        //Did we have ane error in message validation?
        if(!isset($msg_validation['output_messages'])){

            return false;

        } elseif (!$msg_validation['status']) {

            //Log Error Transaction:
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__source' => (isset($member_e['e__id']) ? $member_e['e__id'] : 0),
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


    function save_note_extra_sources($x__id, $x__source, $note_references, $i__id, $delete_previous){

        if(!$x__id){
            return false;
        }

        //Remove first one as already saved:
        array_shift($note_references);

        //Also remove all current extra sources:
        if($delete_previous){
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type' => 14947, //Note Extra Sources
                'x__reference' => $x__id,
            ), array(), 0) as $delete_x) {
                $this->X_model->update($delete_x['x__id'], array(
                    'x__status' => 6173,
                ));
            }
        }

        //Now see if any more left to save?
        if(count($note_references)){
            //Yes we have more than one which means we need to store them separately:
            foreach($note_references as $extra_references){
                $this->X_model->create(array(
                    'x__source' => $x__source,
                    'x__type' => 14947, //Note Extra Source
                    'x__right' => $i__id,
                    'x__reference' => $x__id,
                    'x__up' => $extra_references,
                ));
            }
        }

    }

    function message_compile($message_input, $is_discovery_mode, $member_e = array(), $message_type_e__id = 0, $message_i__id = 0, $strict_validation = true, $simple_version = false)
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

        $e___6177 = $this->config->item('e___6177');

        //Cleanup:
        $message_input = trim($message_input);
        $message_input = str_replace('’','\'',$message_input);

        //Start with basic input validation:
        if (strlen($message_input) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Message',
            );
        } elseif ($strict_validation && strlen($message_input) > view_memory(6404,4485)) {
            return array(
                'status' => 0,
                'message' => 'Message is '.strlen($message_input).' characters long which is more than the allowed ' . view_memory(6404,4485) . ' characters',
            );
        } elseif (!preg_match('//u', $message_input)) {
            return array(
                'status' => 0,
                'message' => 'Message must be UTF8',
            );
        } elseif ($message_type_e__id > 0 && !in_array($message_type_e__id, $this->config->item('n___4485'))) {
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
        if($strict_validation && substr_count($message_input, '||')>0 && substr_count($message_input, '@')>=substr_count($message_input, '~')){
            //We Seem to have a creation mode:
            $e__title = one_two_explode('@','||',$message_input);
            $added_e = $this->E_model->verify_create($e__title, $member_e['e__id']);
            if(!$added_e['status']){
                return $added_e;
            } else {
                //New source added, replace text:
                $message_input = str_replace($e__title.'||', $added_e['new_e']['e__id'], $message_input);
            }
        }
        //Do we have a second source creation?
        if($strict_validation && substr_count($message_input, '@')==2 && substr_count($message_input, '||')==1){
            //We Seem to have a creation mode:
            $e__title = one_two_explode('@','||',$message_input);
            $added_e = $this->E_model->verify_create($e__title, $member_e['e__id']);
            if(!$added_e['status']){
                return $added_e;
            } else {
                //New source added, replace text:
                $message_input = str_replace($e__title.'||', $added_e['new_e']['e__id'], $message_input);
            }
        }



        /*
         *
         * Let's do a generic message reference validation
         * that does not consider $message_type_e__id if passed
         *
         * */
        $string_references = extract_e_references($message_input);

        if($strict_validation && $message_type_e__id > 0){

            if(in_array($message_type_e__id, $this->config->item('n___14311'))){
                //POWER EDITOR UNLIMITED SOURCES
                $min_e = 0;
                $max_e = 99;
            } elseif(in_array($message_type_e__id, $this->config->item('n___13550'))){
                //IDEA NOTES 1X SOURCE REFERENCE REQUIRED
                $min_e = 1;
                $max_e = 1;
            } else {
                $min_e = 0;
                $max_e = 0;
            }

            /*
             *
             * $message_type_e__id Validation
             * only in strict mode!
             *
             * */

            //URLs are the same as a source:
            $total_references = count($string_references['ref_e']) + count($string_references['ref_urls']);

            if($total_references<$min_e || $total_references>$max_e){
                return array(
                    'status' => 0,
                    'message' => 'You referenced '.$total_references.' source'.view__s($total_references).' but you must have '.$min_e.( $max_e!=$min_e ? '-'.$max_e : '' ).' source references.',
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

                //No source, but we have a URL that we should turn into an source if not previously:
                $url_e = $this->E_model->url($input_url, ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 ));

                //Did we have an error?
                if (!$url_e['status'] || !isset($url_e['e_url']['e__id']) || intval($url_e['e_url']['e__id']) < 1) {
                    return $url_e;
                }

                //Transform URL into a source:
                if(intval($url_e['e_url']['e__id']) > 0){

                    array_push($string_references['ref_e'], intval($url_e['e_url']['e__id']));

                    //Replace the URL with this new @source in message.
                    //This is the only valid modification we can do to $message_input before storing it in the DB:
                    $message_input = str_replace($input_url, '@' . $url_e['e_url']['e__id'], $message_input.' ');

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

        $message_input .= ' ';//Helps with accurate source reference replacement
        $output_body_message = htmlentities($message_input).' ';
        $string_references = extract_e_references($message_input); //Do it again since it may be updated
        $note_references = array();

        foreach($string_references['ref_e'] as $referenced_e){

            //We have a reference within this message, let's fetch it to better understand it:
            $es = $this->E_model->fetch(array(
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                'e__id' => $referenced_e,
            ));
            if (count($es) < 1) {
                //Remove Source:

                continue;
            }

            //Set as source reference:
            array_push($note_references, intval($referenced_e));


            //See if this source has any parent transactions to be shown in this appendix
            $e_media_count = 0;
            $e_count = 0;
            $e_appendix = null;
            $e_links = array();
            $first_segment = $this->uri->segment(1);
            $is_current_e = ( $first_segment == '@'.$referenced_e );
            $tooltip_info = null;


            //Determine what type of Media this reference has:
            if(!$is_current_e || $string_references['ref_time_found']){

                foreach($this->X_model->fetch(array(
                    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12822')) . ')' => null, //SOURCE LINK MESSAGE DISPLAY
                    'x__down' => $referenced_e,
                ), array('x__up'), 0, 0, array(
                    'x__type' => 'ASC', /* Text first */
                    'e__spectrum' => 'DESC',
                )) as $e_profile) {

                    if(in_array($e_profile['e__id'], $this->config->item('n___4755'))){
                        //ACTIVE Transactions Not Allowed:
                        continue;
                    }

                    $e_count++;

                    if (in_array($e_profile['x__type'], $this->config->item('n___13899'))) {

                        //TOOLTIP INFO
                        $tooltip_info .= ( strlen($tooltip_info) ? ' | ' : '' ).$e_profile['e__title'].': ' . str_replace("\n",' ',$e_profile['x__message']);

                    } elseif (in_array($e_profile['x__type'], $this->config->item('n___12524'))) {

                        //SOURCE LINK VISUAL
                        $e_media_count++;
                        $e_appendix .= '<div class="e-appendix paddingup">' . view_x__message($e_profile['x__message'], $e_profile['x__type'], $message_input, $is_discovery_mode) . '</div>';

                    } elseif($e_profile['x__type'] == 4256 /* URL */) {

                        array_push($e_links, $e_profile);

                    } else {

                        //Text and Percentage, etc...
                        $e_appendix .= '<div class="e-appendix paddingup"><span class="icon-block-xs">' . view_cover(12274,$e_profile['e__cover'], true).'</span>' . $e_profile['e__title'].': ' . $e_profile['x__message'] . '</div>';

                    }
                }
            }



            //Append any appendix generated:
            $identifier_string = '@' . $referenced_e.($string_references['ref_time_found'] ? one_two_explode('@' . $referenced_e,' ',$message_input) : '' );
            $tooltip_class = ( $tooltip_info ? ' title="'.$tooltip_info.'" data-toggle="tooltip" data-placement="bottom" ' : null );
            $tooltip_underdot = ( $tooltip_info ? ' underdot ' : null );


            if(!$is_discovery_mode && source_of_e($es[0]['e__id'])){
                $tooltip_class .= ' class="inline-block ignore-click" coin__type="12274" coin__id="' . $es[0]['e__id'] . '" ';
                $edit_btn = '<span class="icon-block-img mini_6197_'.$es[0]['e__id'].' ignore-click">'.view_cover(12274,$es[0]['e__cover'], true).'</span> ';
            } else {
                $tooltip_class .= ' class="inline-block" ';
                $edit_btn = '<span class="icon-block-img mini_6197_'.$es[0]['e__id'].'">'.view_cover(12274,$es[0]['e__cover'], true).'</span> ';
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
            if(count($e_links)){

                if($simple_version){

                    //Links not supported
                    $e_dropdown .= $es[0]['e__title'];

                } elseif(count($e_links)==1){

                    //Just show one:
                    $e_dropdown .= '<a href="'.$e_links[0]['x__message'].'" target="_blank" class="ignore-click" title="'.$e_links[0]['e__title'].'"><span class="icon-block-xxs">' . view_cover(12274,$es[0]['e__cover'], true).'</span><u>'.$es[0]['e__title'].'</u></a>';

                } else {

                    //List all links:
                    $e_dropdown .= '<div class="dropdown inline-block inline-dropdown"><button type="button" class="btn-transparent no-left-padding no-right-padding ignore-click" id="externalRef'.$es[0]['e__id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.( $is_discovery_mode ? '<span class="icon-block-xxs">' . view_cover(12274,$es[0]['e__cover'], true).'</span><u>'.$es[0]['e__title'].'</u>' : '' ).'<span class="icon-block-xs" style="font-size:0.89em;"><i class="far fa-angle-down"></i></span></button><div class="dropdown-menu" aria-labelledby="externalRef'.$es[0]['e__id'].'">';
                    foreach($e_links as $e_link){
                        $e_dropdown .= '<a href="'.$e_link['x__message'].'" target="_blank" class="dropdown-item move_away css__title ignore-click"><span class="icon-block">'.view_cover(12274,$e_link['e__cover'], true).'</span>'.$e_link['e__title'].'</a>';
                    }
                    $e_dropdown .= '</div></div>';

                }
            }


            //Displays:
            if($on_its_own_line){
                $the_title = '<span class="subtle-line mini-grey text__6197_'.$es[0]['e__id'].$tooltip_underdot.'">' . $es[0]['e__title'] . '</span>';
                if($new_lines <= 1){
                    $output_body_message = $e_appendix.str_replace($identifier_string, ( !count($e_links) || !$is_discovery_mode ? '<span '.$tooltip_class.'>'.$the_title.'</span>' : '' ).$e_dropdown, $output_body_message); //'.$edit_btn.'
                } else {
                    $output_body_message = str_replace($identifier_string, ( !count($e_links) || !$is_discovery_mode ? '<span '.$tooltip_class.'>'.$edit_btn.$the_title.'</span>' : '' ).$e_dropdown, $output_body_message).$e_appendix;
                }
            } else {
                $output_body_message = str_replace($identifier_string, ( !count($e_links) || !$is_discovery_mode ? '<span '.$tooltip_class.'>'.$edit_btn.'<span class="text__6197_'.$es[0]['e__id'].$tooltip_underdot.'">' . $es[0]['e__title'] . '</span></span>' : '' ).$e_dropdown, $output_body_message).$e_appendix;
            }

        }

        //Return results:
        return array(
            'status' => 1,
            'clean_message' => trim($message_input),
            'output_messages' => ( strlen(trim($output_body_message)) ? '<div '.( is_new() ? '' : 'class="msg"' ).'><span>' . nl2br($output_body_message) . '</span></div>' : null ),
            'note_references' => $note_references,
        );
    }



    function find_previous($e__id, $top_i__id, $i__id, $stopper_i_id = 0)
    {

        if($stopper_i_id>0 && $stopper_i_id==$i__id){
            return 0;
        }

        //Fetch parents:
        foreach($this->X_model->fetch(array(
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__right' => $i__id,
        ), array('x__left')) as $i_previous) {

            //Validate Selection:
            $is_or_i = in_array($i_previous['i__type'], $this->config->item('n___6193'));
            $is_fixed_x = in_array($i_previous['x__type'], $this->config->item('n___12840'));
            if($e__id>0 && ($is_or_i || !$is_fixed_x) && !count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY EXPANSIONS
                    'x__left' => $i_previous['i__id'],
                    'x__right' => $i__id,
                    'x__source' => $e__id,
                )))){
                continue;
            }

            //Did we find it?
            if($i_previous['i__id']==$top_i__id){
                return array($i_previous);
            }

            //Keep looking:
            $top_search = $this->X_model->find_previous($e__id, $top_i__id, $i_previous['i__id'], ( $stopper_i_id>0 ? $stopper_i_id : $i__id ));
            if(count($top_search)){
                array_push($top_search, $i_previous);
                return $top_search;
            }
        }

        //Did not find any parents:
        return array();

    }




    function find_next($e__id, $top_i__id, $i, $find_after_i__id = 0, $search_up = true, $top_completed = false, $stopper_i_id = 0)
    {

        if($stopper_i_id>0 && $stopper_i_id==$i['i__id']){
            return 0;
        }

        $is_or_i = in_array($i['i__type'], $this->config->item('n___6193'));
        $found_trigger = false;
        foreach ($this->X_model->fetch(array(
            'x__left' => $i['i__id'],
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $next_i) {

            //Validate Find After:
            if ($find_after_i__id && !$found_trigger) {
                if ($next_i['i__id'] == $find_after_i__id) {
                    $found_trigger = true;
                }
                continue;
            }

            //Validate Selection:
            $is_fixed_x = in_array($next_i['x__type'], $this->config->item('n___12840'));
            if(($is_or_i || !$is_fixed_x) && !count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY EXPANSIONS
                    'x__left' => $i['i__id'],
                    'x__right' => $next_i['i__id'],
                    'x__source' => $e__id,
                )))){
                continue;
            }


            //Return this if everything is completed, or if this is incomplete:
            if($top_completed || !count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE
                    'x__source' => $e__id,
                    'x__left' => $next_i['i__id'],
                )))){
                return intval($next_i['i__id']);
            }


            //Keep looking deeper:
            $found_next = $this->X_model->find_next($e__id, $top_i__id, $next_i, 0, false, $top_completed, ( $stopper_i_id>0 ? $stopper_i_id : $i['i__id'] ));
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


    function delete($x__id){

        $member_e = superpower_unlocked();
        if (!$member_e) {
            return array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            );
        }

        $this->X_model->update($x__id, array(
            'x__status' => 6173, //DELETED
        ), $member_e['e__id'], 6155);

        return array(
            'status' => 1,
            'message' => 'Success',
        );

    }

    function start($e__id, $i__id, $recommender_i__id = 0){

        //Validate Idea ID:
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));
        if (count($is) != 1 || !in_array($is[0]['i__type'], $this->config->item('n___26124'))) {
            return 0;
        }

        $next_i__id = $i__id;


        //Make sure not previously added to this Member's discoveries:
        if(!count($this->X_model->fetch(array(
                'x__source' => $e__id,
                'x__left' => $i__id,
                'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //STARTED
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){

            //Not added to their discoveries so far, let's go ahead and add it:
            $this->X_model->create(array(
                'x__type' => 4235,
                'x__source' => $e__id, //Belongs to this Member
                'x__left' => $is[0]['i__id'], //The Idea they are adding
                'x__right' => $is[0]['i__id'], //Store the recommended idea
                'x__spectrum' => 1, //Always place at the top of their discoveries
            ));

            //Now find next idea:
            $next_i__id = $this->X_model->find_next($e__id, $is[0]['i__id'], $is[0]);

        }

        return $next_i__id;

    }




    function completion_recursive_up($e__id, $top_i__id, $i, $is_bottom_level = true){

        /*
         *
         * Let's see how many steps get unlocked @6410
         *
         * */

        //First let's make sure this entire Idea completed by member:
        $completion_rate = $this->X_model->completion_progress($e__id, $i);


        if($completion_rate['completion_percentage'] < 100){
            //Not completed, so can't go further up:
            return array();
        }


        //Look at Conditional Idea Transactions ONLY at this level:
        $i__metadata = unserialize($i['i__metadata']);
        if(isset($i__metadata['i___6283'][$i['i__id']]) && count($i__metadata['i___6283'][$i['i__id']]) > 0){

            //Make sure previous transaction unlocks have NOT happened before:
            $existing_expansions = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 6140, //DISCOVERY UNLOCK LINK
                'x__source' => $e__id,
                'x__left' => $i['i__id'],
                'x__right IN (' . join(',', $i__metadata['i___6283'][$i['i__id']]) . ')' => null, //Limit to cached answers
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
                $this->X_model->create(array(
                    'x__left' => $i['i__id'],
                    'x__right' => $existing_expansions[0]['x__right'],
                    'x__message' => 'completion_recursive_up() detected duplicate Label Expansion entries',
                    'x__type' => 4246, //Platform Bug Reports
                    'x__source' => $e__id,
                ));
                */

                return array();

            }


            //Yes, Let's calculate u's score for this idea:
            $u_marks = $this->X_model->completion_marks($e__id, $i);




            //Detect potential conditional steps to be Unlocked:
            $found_match = 0;
            $locked_x = $this->X_model->fetch(array(
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12842')) . ')' => null, //IDEA LINKS ONE-WAY
                'x__left' => $i['i__id'],
                'x__right IN (' . join(',', $i__metadata['i___6283'][$i['i__id']]) . ')' => null, //Limit to cached answers
            ), array('x__right'), 0, 0);


            foreach($locked_x as $locked_x) {

                if($u_marks['steps_answered_score']>=fetch_i_source(4735, $locked_x['i__id']) && $u_marks['steps_answered_score']<=fetch_i_source(4739, $locked_x['i__id'])){

                    //Found a match:
                    $found_match++;

                    //Unlock read:
                    $this->X_model->create(array(
                        'x__type' => 6140, //DISCOVERY UNLOCK LINK
                        'x__source' => $e__id,
                        'x__left' => $i['i__id'],
                        'x__right' => $locked_x['i__id'],
                        'x__metadata' => array(
                            'completion_rate' => $completion_rate,
                            'u_marks' => $u_marks,
                            'condition_ranges' => $locked_x,
                        ),
                    ));

                }
            }

            //We must have exactly 1 match by now:
            if($found_match != 1){
                $this->X_model->create(array(
                    'x__message' => 'completion_recursive_up() found ['.$found_match.'] routing logic matches!',
                    'x__type' => 4246, //Platform Bug Reports
                    'x__source' => $e__id,
                    'x__left' => $i['i__id'],
                    'x__metadata' => array(
                        'completion_rate' => $completion_rate,
                        'u_marks' => $u_marks,
                        'conditional_ranges' => $locked_x,
                    ),
                ));
            }
        }


        //Now go up since we know there are more levels...
        if($is_bottom_level){
            //Go through parents ideas and detect intersects with member ideas
            foreach(array_reverse($this->X_model->find_previous($e__id, $top_i__id, $i['i__id'])) as $p_i) {
                $this->X_model->completion_recursive_up($e__id, $top_i__id, $p_i, false);
            }
        }


        return true;
    }


    function unlock_locked_step($e__id, $i){

        /*
         * A function that starts from a locked idea and checks:
         *
         * 1. List members who have completed ALL/ANY (Depending on AND/OR Lock) of its children
         * 2. If > 0, then goes up recursively to see if these completions unlock other completions
         *
         * */

        if(!i_unlockable($i)){
            return array(
                'status' => 0,
                'message' => 'Not a valid locked idea type and status',
            );
        }


        $is_next = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $i['i__id'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));
        if(count($is_next) < 1){
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
        $requires_all_children = in_array($i['i__type'], $this->config->item('n___13987') /* REQUIRE ALL CHILDREN */ );

        //Generate list of members who have completed it:
        $qualified_completed = array();

        //Go through children and see how many completed:
        foreach($is_next as $count => $next_i){

            //Fetch members who completed this:
            if($count==0){

                //Always add all the first members to the full list:
                $qualified_completed = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
                    'x__left' => $next_i['i__id'],
                ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

                if($requires_all_children && count($qualified_completed)==0){
                    //No members found that would meet all children requirements:
                    break;
                }

            } else {

                //2nd Update onwards, by now we must have a base:
                if($requires_all_children){

                    //Update list of qualified members:
                    $qualified_completed = $this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
                        'x__left' => $next_i['i__id'],
                    ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

                }

            }
        }

        if(count($qualified_completed) > 0){
            return array(
                'status' => 0,
                'message' => 'No users found to have completed',
            );
        }

    }



    function mark_complete($top_i__id, $i, $add_fields) {

        //Always add Idea to x__left
        if($top_i__id>0 && (!isset($add_fields['x__right']) || intval($add_fields['x__right'])==0)){
            $add_fields['x__right'] = $top_i__id;
        }
        $add_fields['x__left'] = $i['i__id'];

        if (!isset($add_fields['x__message'])) {
            $add_fields['x__message'] = null;
        }

        $x__source = ( isset($add_fields['x__source']) ? $add_fields['x__source'] : 0);
        $domain_url = get_domain('m__message', $x__source);

        $search_fields = $add_fields;


        if(isset($search_fields['x__metadata'])){
            unset($search_fields['x__metadata']);
        }
        if(!isset($search_fields['x__status'])){
            //Only search active:
            $search_fields['x__status IN (' . join(',', $this->config->item('n___7360')) . ')'] = null;
        }

        //Log completion transaction if not duplicate:
        $check_duplicate = $this->X_model->fetch($search_fields);
        if(in_array($add_fields['x__type'], $this->config->item('n___30469')) && isset($check_duplicate[0]['x__id'])){

            $new_x = $check_duplicate[0];

        } else {

            $new_x = $this->X_model->create($add_fields);

        }

        if(!isset($check_duplicate[0]['x__id'])){
            //Fetch Source ID:
            $watchers = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 10573, //WATCHERS
                'x__right' => $i['i__id'],
                'x__up > 0' => null,
            ), array(), 0);
            if(count($watchers)){

                $es_discoverer = $this->E_model->fetch(array(
                    'e__id' => $add_fields['x__source'],
                ));
                if(count($es_discoverer)){

                    //Fetch Discoverer contact:
                    $u_list_phone = '';
                    $u_clean_phone = '';
                    foreach($this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                        'x__down' => $add_fields['x__source'],
                        'x__up' => 4783, //Phone
                    )) as $x_progress){
                        $u_clean_phone = clean_phone($u_clean_phone);
                        $u_list_phone .= 'Phone:'."\n".$u_clean_phone."\n\n";
                    }

                    //Fetch Full Legal Name:
                    $u_list_name = '';
                    foreach($this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
                        'x__left' => 15736, //What's your Full Legal Name that Matches your ID
                        'x__source' => $add_fields['x__source'],
                    )) as $x_progress){
                        $u_list_name .= 'Full Name:'."\n".$x_progress['x__message']."\n\n";
                    }



                    //Notify Idea Watchers
                    $sent_watchers = array();
                    foreach($watchers as $watcher){
                        if(!in_array(intval($watcher['x__up']), $sent_watchers)){
                            array_push($sent_watchers, intval($watcher['x__up']));
                            $this->X_model->send_dm($watcher['x__up'], $es_discoverer[0]['e__title'].' '.( $u_clean_phone ? $u_clean_phone.' ' : '' ).'Played: '.$i['i__title'],
                                //Message Body:
                                $i['i__title'].':'."\n".'https://'.$domain_url.'/~'.$i['i__id']."\n\n".
                                ( strlen($add_fields['x__message']) ? $add_fields['x__message']."\n\n" : '' ).
                                $es_discoverer[0]['e__title'].':'."\n".'https://'.$domain_url.'/@'.$es_discoverer[0]['e__id']."\n\n".
                                $u_list_name.
                                $u_list_phone
                            );
                        }
                    }
                }

            }
        }



        //Check Auto Completes:
        $is_next_autoscan = array();
        if(in_array($i['i__type'], $this->config->item('n___7712'))){

            //IDEA TYPE SELECT NEXT
            $is_next_autoscan = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //DISCOVERY ANSWERED
                'x__source' => $add_fields['x__source'],
                'x__left' => $i['i__id'],
                'x__right >' => 0, //With an answer
            ), array('x__right'), 0);

        } elseif(in_array($i['i__type'], $this->config->item('n___13022'))){

            //IDEA TYPE ALL NEXT
            $is_next_autoscan = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                'x__left' => $i['i__id'],
            ), array('x__right'), 0);

        }

        foreach($is_next_autoscan as $next_i){

            //IS IT EMPTY?
            if(

                //Auto completable type?
                in_array($next_i['i__type'], $this->config->item('n___12330')) &&

                //No Messages
                !count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type' => 4231, //IDEA NOTES Messages
                    'x__right' => $next_i['i__id'],
                ))) &&

                //One or less next
                /*
                count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'x__left' => $next_i['i__id'],
                ))) <= 1 &&
                */

                //Not Already Completed:
                !count($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE
                    'x__source' => $add_fields['x__source'],
                    'x__left' => $next_i['i__id'],
                )))){

                //Mark as complete:
                $this->X_model->mark_complete($top_i__id, $next_i, array(
                    'x__type' => 4559, //DISCOVERY MESSAGES
                    'x__source' => $add_fields['x__source'],
                ));

            }
        }

        $member_e = superpower_unlocked();
        $detected_x_type = x_detect_type($add_fields['x__message']);
        if ($detected_x_type['status']) {

            //Forget PLAYS?
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 29430, //Remove Plays
                'x__right' => $i['i__id'],
            )) as $e_play_removal){

                //Go through all Notes associated with this source:
                foreach($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                    'x__up' => $e_play_removal['x__up'],
                    'x__right !=' => $i['i__id'],
                )) as $remove_i){

                    //Remove all Discoveries made by this user:
                    foreach($this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
                        'x__left' => $remove_i['x__right'], //IDEA LINKS
                        'x__source' => $member_e['e__id'],
                    )) as $remove_x){

                        //Remove this discovery:
                        $this->X_model->update($remove_x['x__id'], array(
                            'x__status' => 6173,
                        ), $member_e['e__id'], 29431 /* Play Auto Removed */);

                    }
                }

            }

            //ADD PROFILE?
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7545, //Profile Add
                'x__right' => $i['i__id'],
            ), array('x__up')) as $x_tag){

                //Check if special profile add?

                if($member_e && $x_tag['x__up']==13025){

                    if(strlen(trim($add_fields['x__message']))>=2){

                        //Update full name for current user:
                        $this->E_model->update($member_e['e__id'], array(
                            'e__title' => $add_fields['x__message'],
                        ), true, $member_e['e__id']);

                        //Update live session as well:
                        $member_e['e__title'] = $add_fields['x__message'];
                        $this->E_model->activate_session($member_e, true);

                    }

                } elseif($member_e && $x_tag['x__up']==26139){

                    //Make sure submission is image source reference:
                    foreach($this->X_model->fetch(array(
                        'x__type' => 4260, //IMAGES
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__down' => intval(str_replace('@','',$add_fields['x__message'])),
                    ), array('x__up'), 1, 0, array('e__spectrum' => 'DESC')) as $profile){

                        //Update profile picture for current user:
                        $this->E_model->update($member_e['e__id'], array(
                            'e__cover' => $profile['x__message'],
                        ), true, $member_e['e__id']);

                        //Update live session as well:
                        $member_e['e__cover'] = $profile['x__message'];
                        $this->E_model->activate_session($member_e, true);

                    }

                } else {

                    //Generate stats:
                    $x_added = 0;
                    $x_edited = 0;
                    $x_deleted = 0;

                    //Assign tag if parent/child transaction NOT previously assigned:
                    $existing_x = $this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                        'x__up' => $x_tag['x__up'], //CERTIFICATES saved here
                        'x__down' => $add_fields['x__source'],
                    ));

                    if(count($existing_x)){

                        //Transaction previously exists, see if content value is the same:
                        if($existing_x[0]['x__message'] == $add_fields['x__message'] && $existing_x[0]['x__type'] == $detected_x_type['x__type']){

                            //Everything is the same, nothing to do here:
                            continue;

                        } else {

                            $x_edited++;

                            //Content value has changed, update the transaction:
                            $this->X_model->update($existing_x[0]['x__id'], array(
                                'x__message' => $add_fields['x__message'],
                                'x__type' => $detected_x_type['x__type'],
                            ), $add_fields['x__source'], 10657 /* SOURCE LINK CONTENT UPDATE  */);

                            $this->X_model->create(array(
                                'x__type' => 12197, //Profile Added
                                'x__source' => $add_fields['x__source'],
                                'x__up' => $x_tag['x__up'],
                                'x__down' => $add_fields['x__source'],
                                'x__left' => $i['i__id'],
                                'x__message' => $x_added.' added, '.$x_edited.' edited & '.$x_deleted.' deleted with new content ['.$add_fields['x__message'].']',
                            ));

                        }

                    } else {

                        //See if we need to delete single selectable transactions:
                        foreach($this->config->item('n___6204') as $single_select_e__id){
                            $single_selectable = $this->config->item('n___'.$single_select_e__id);
                            if(is_array($single_selectable) && count($single_selectable) && in_array($x_tag['x__up'], $single_selectable)){
                                //Delete other siblings, if any:
                                foreach($this->X_model->fetch(array(
                                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                                    'x__up IN (' . join(',', $single_selectable) . ')' => null,
                                    'x__up !=' => $x_tag['x__up'],
                                    'x__down' => $add_fields['x__source'],
                                )) as $single_selectable_siblings_preset){
                                    $x_deleted += $this->X_model->update($single_selectable_siblings_preset['x__id'], array(
                                        'x__status' => 6173, //Transaction Deleted
                                    ), $add_fields['x__source'], 10673 /* Member Transaction Unpublished */);
                                }
                            }
                        }

                        //Create transaction:
                        $x_added++;
                        $this->X_model->create(array(
                            'x__type' => $detected_x_type['x__type'],
                            'x__message' => $add_fields['x__message'],
                            'x__source' => $add_fields['x__source'],
                            'x__up' => $x_tag['x__up'],
                            'x__down' => $add_fields['x__source'],
                        ));

                        $this->X_model->create(array(
                            'x__type' => 12197, //Profile Added
                            'x__source' => $add_fields['x__source'],
                            'x__up' => $x_tag['x__up'],
                            'x__down' => $add_fields['x__source'],
                            'x__left' => $i['i__id'],
                            'x__message' => $x_added.' added, '.$x_edited.' edited & '.$x_deleted.' deleted with new content ['.$add_fields['x__message'].']',
                        ));

                        //Notify the user of this new profile addition?
                        if(in_array($x_tag['e__id'], $this->config->item('n___28702'))){
                            $this->X_model->send_dm($add_fields['x__source'], get_domain('m__title', $add_fields['x__source']).' Profile Update', '['.$x_tag['e__title'].'] was added to your profile'.( strlen($add_fields['x__message'])>0 ? ' with value ['.$add_fields['x__message'].']' : '' ));
                        }

                    }

                    if($x_added>0 || $x_edited>0 || $x_deleted>0){
                        //See if Session needs to be updated:
                        if($member_e && $member_e['e__id']==$add_fields['x__source']){
                            //Yes, update session:
                            $this->E_model->activate_session($member_e, true);
                        }
                    }
                }
            }



            //REMOVE PROFILE?
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 26599, //Profile Remove
                'x__right' => $i['i__id'],
            )) as $x_tag){

                //Remove Profile IF previously assigned:
                $existing_x = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__up' => $x_tag['x__up'], //CERTIFICATES saved here
                    'x__down' => $add_fields['x__source'],
                ));

                if(count($existing_x)){

                    //Exists, so must be removed:
                    $member_e = superpower_unlocked();

                    $this->X_model->update($existing_x[0]['x__id'], array(
                        'x__status' => 6173,
                    ), $member_e['e__id'], 12197 /* Profile Removed */);

                    //See if Session needs to be updated:
                    if($member_e && $member_e['e__id']==$add_fields['x__source']){
                        //Yes, update session:
                        $this->E_model->activate_session($member_e, true);
                    }
                }
            }


        }


        //Process completion automations:
        $this->X_model->completion_recursive_up($add_fields['x__source'], $top_i__id, $i);

        return $new_x;

    }

    function completion_marks($e__id, $i, $top_level = true, $stopper_i_id = 0)
    {

        if($stopper_i_id>0 && $stopper_i_id==$i['i__id']){
            return 0;
        }

        //Fetch/validate read Common Ideas:
        $i__metadata = unserialize($i['i__metadata']);
        if(!isset($i__metadata['i___6168'])){

            //Should not happen, log error:
            $this->X_model->create(array(
                'x__message' => 'completion_marks() Detected member discoveries without i___6168 value!',
                'x__type' => 4246, //Platform Bug Reports
                'x__source' => $e__id,
                'x__left' => $i['i__id'],
            ));

            return 0;
        }

        //Generate flat steps:
        $flat_common_x = array_flatten($i__metadata['i___6168']);

        //Calculate common steps and expansion steps recursively for this u:
        $metadata_this = array(
            //Generic assessment marks stats:
            'steps_question_count' => 0, //The parent idea
            'steps_marks_min' => 0,
            'steps_marks_max' => 0,

            //Member answer stats:
            'steps_answered_count' => 0, //How many they have answered so far
            'steps_answered_marks' => 0, //Indicates completion score

            //Calculated at the end:
            'steps_answered_score' => 0, //Used to determine which label to be unlocked...
        );


        //Process Answer ONE:
        if(isset($i__metadata['i___6228']) && count($i__metadata['i___6228']) > 0){

            //We need expansion steps (OR Ideas) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_i__ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($i__metadata['i___6228'] as $question_i__id => $answers_i__ids ){

                //Calculate local min/max marks:
                array_push($question_i__ids, $question_i__id);
                $metadata_this['steps_question_count'] += 1;
                $local_min = null;
                $local_max = null;

                //Calculate min/max points for this based on answers:
                foreach($this->X_model->fetch(array(
                    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'x__left' => $question_i__id,
                    'x__right IN (' . join(',', $answers_i__ids) . ')' => null, //Limit to cached answers
                ), array('x__right')) as $i_answer){


                    //Assign to this question:
                    $answer_marks_index[$i_answer['i__id']] = fetch_i_source(4358, $i_answer['i__id']);

                    //Addup local min/max marks:
                    if(is_null($local_min) || $answer_marks_index[$i_answer['i__id']] < $local_min){
                        $local_min = $answer_marks_index[$i_answer['i__id']];
                    }
                    if(is_null($local_max) || $answer_marks_index[$i_answer['i__id']] > $local_max){
                        $local_max = $answer_marks_index[$i_answer['i__id']];
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



            //Now let's check member answers to see what they have done:
            $total_completion = $this->X_model->fetch(array(
                'x__source' => $e__id, //Belongs to this Member
                'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE
                'x__left IN (' . join(',', $question_i__ids ) . ')' => null,
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array(), 0, 0, array(), 'COUNT(x__id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->X_model->fetch(array(
                'x__source' => $e__id, //Belongs to this Member
                'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY EXPANSIONS
                'x__left IN (' . join(',', $question_i__ids ) . ')' => null,
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            ), array('x__right'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->X_model->completion_marks($e__id, $answer_in, false, ( $stopper_i_id>0 ? $stopper_i_id : $i['i__id'] ));

                $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];
                $metadata_this['steps_answered_marks'] += $answer_marks_index[$answer_in['i__id']] + $recursive_stats['steps_answered_marks'];

            }
        }


        //Process Answer SOME:
        if(isset($i__metadata['i___12885']) && count($i__metadata['i___12885']) > 0){

            //We need expansion steps (OR Ideas) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_i__ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($i__metadata['i___12885'] as $question_i__id => $answers_i__ids ){

                //Calculate local min/max marks:
                array_push($question_i__ids, $question_i__id);
                $metadata_this['steps_question_count'] += 1;
                $local_min = null;
                $local_max = null;

                //Calculate min/max points for this based on answers:
                foreach($this->X_model->fetch(array(
                    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'x__left' => $question_i__id,
                    'x__right IN (' . join(',', $answers_i__ids) . ')' => null, //Limit to cached answers
                ), array('x__right')) as $i_answer){

                    //Extract Transaction Metadata:
                    $possible_answer_metadata = unserialize($i_answer['x__metadata']);

                    //Assign to this question:
                    $answer_marks_index[$i_answer['i__id']] = fetch_i_source(4358, $i_answer['i__id']);

                    //Addup local min/max marks:
                    if(is_null($local_min) || $answer_marks_index[$i_answer['i__id']] < $local_min){
                        $local_min = $answer_marks_index[$i_answer['i__id']];
                    }
                }

                //Did we have any marks for this question?
                if(!is_null($local_min)){
                    $metadata_this['steps_marks_min'] += $local_min;
                }

                //Always Add local max:
                $metadata_this['steps_marks_max'] += $answer_marks_index[$i_answer['i__id']];

            }



            //Now let's check member answers to see what they have done:
            $total_completion = $this->X_model->fetch(array(
                'x__source' => $e__id, //Belongs to this Member
                'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE
                'x__left IN (' . join(',', $question_i__ids ) . ')' => null,
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array(), 0, 0, array(), 'COUNT(x__id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->X_model->fetch(array(
                'x__source' => $e__id, //Belongs to this Member
                'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY EXPANSIONS
                'x__left IN (' . join(',', $question_i__ids ) . ')' => null,
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            ), array('x__right'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->X_model->completion_marks($e__id, $answer_in, false, ( $stopper_i_id>0 ? $stopper_i_id : $i['i__id'] ));

                $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];
                $metadata_this['steps_answered_marks'] += $answer_marks_index[$answer_in['i__id']] + $recursive_stats['steps_answered_marks'];

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



    function completion_progress($e__id, $i, $top_level = true, $stopper_i_id = 0)
    {

        if($stopper_i_id>0 && $stopper_i_id==$i['i__id']){
            return false;
        }
        if(!isset($i['i__metadata'])){
            return false;
        }

        //Fetch/validate discoveries Common Ideas:
        $i__metadata = unserialize($i['i__metadata']);
        if(!isset($i__metadata['i___6168'])){
            //Since it's not there yet we assume the idea it self only!
            $i__metadata['i___6168'] = array($i['i__id']);
        }


        //Generate flat steps:
        $flat_common_x = array_flatten($i__metadata['i___6168']);


        //Count totals:
        $common_totals = $this->I_model->fetch(array(
            'i__id IN ('.join(',',$flat_common_x).')' => null,
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ), 0, 0, array(), 'COUNT(DISTINCT i__id) as total_x');


        //Count completed so far:
        $common_completed = $this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE
            'x__source' => $e__id, //Belongs to this Member
            'x__left IN (' . join(',', $flat_common_x ) . ')' => null,
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ), array('x__left'), 0, 0, array(), 'COUNT(DISTINCT i__id) as completed_x');


        //Calculate common steps and expansion steps recursively for this u:
        $metadata_this = array(
            'steps_total' => intval($common_totals[0]['total_x']),
            'steps_completed' => intval($common_completed[0]['completed_x']),
            'steps_incomplete' => intval($common_completed[0]['completed_x']),
        );


        //Expansion Answer ONE
        $answer_array = array();
        if(isset($i__metadata['i___6228']) && count($i__metadata['i___6228']) > 0) {
            $answer_array = array_merge($answer_array , array_flatten($i__metadata['i___6228']));
        }
        if(isset($i__metadata['i___12885']) && count($i__metadata['i___12885']) > 0) {
            $answer_array = array_merge($answer_array , array_flatten($i__metadata['i___12885']));
        }

        if(count($answer_array)){

            //Now let's check member answers to see what they have done:
            foreach($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___12326')) . ')' => null, //DISCOVERY EXPANSIONS
                'x__source' => $e__id, //Belongs to this Member
                'x__left IN (' . join(',', $flat_common_x ) . ')' => null,
                'x__right IN (' . join(',', $answer_array) . ')' => null,
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            ), array('x__right')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->X_model->completion_progress($e__id, $expansion_in, false, ( $stopper_i_id>0 ? $stopper_i_id : $i['i__id'] ));

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];
            }
        }


        //Expansion steps Recursive
        if(isset($i__metadata['i___6283']) && count($i__metadata['i___6283']) > 0){

            //Now let's check if member has unlocked any Miletones:
            foreach($this->X_model->fetch(array(
                'x__type' => 6140, //DISCOVERY UNLOCK LINK
                'x__source' => $e__id, //Belongs to this Member
                'x__left IN (' . join(',', $flat_common_x ) . ')' => null,
                'x__right IN (' . join(',', array_flatten($i__metadata['i___6283'])) . ')' => null,
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            ), array('x__right')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->X_model->completion_progress($e__id, $expansion_in, false, ( $stopper_i_id>0 ? $stopper_i_id : $i['i__id'] ));

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];

            }
        }


        if($top_level){

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
            $metadata_this['completion_percentage'] = 0;

            //Calculate completion rate based on estimated time cost:
            if($metadata_this['steps_total'] > 0){
                $metadata_this['completion_percentage'] = intval(ceil( $metadata_this['steps_completed'] / $metadata_this['steps_total'] * 100 ));
            }


        }

        //Return results:
        return $metadata_this;

    }


    function ids($e__id, $i__id = 0){

        //Simply returns all the idea IDs for a u's discoveries:
        if($i__id > 0){

            if(!$e__id){
                return false;
            }

            return count($this->X_model->fetch(array(
                'x__left' => $i__id,
                'x__source' => $e__id,
                'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //STARTED
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )));

        } else {

            $u_x_ids = array();
            if($e__id > 0){
                foreach($this->X_model->fetch(array(
                    'x__source' => $e__id,
                    'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //STARTED
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )) as $u_in){
                    array_push($u_x_ids, intval($u_in['x__left']));
                }
            }
            return $u_x_ids;

        }
    }




    function x_save_select($e__id, $top_i__id, $question_i__id, $answer_i__ids){

        $is = $this->I_model->fetch(array(
            'i__id' => $question_i__id,
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));
        $es = $this->E_model->fetch(array(
            'e__id' => $e__id,
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
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


        //Define completion transactions for each answer:
        if($is[0]['i__type'] == 6684){

            //ONE ANSWER
            $x__type = 6157; //Award Coin
            $i_x__type = 12336; //Save Answer

        } elseif($is[0]['i__type'] == 7231 || $is[0]['i__type'] == 14861){

            //SOME ANSWERS
            $x__type = 7489; //Award Coin
            $i_x__type = 12334; //Save Answer

        }

        //Delete ALL previous answers:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___7704')) . ')' => null, //DISCOVERY ANSWERED
            'x__source' => $e__id,
            'x__left' => $is[0]['i__id'],
        )) as $x_progress){

            $this->X_model->update($x_progress['x__id'], array(
                'x__status' => 6173, //Transaction Deleted
            ), $e__id, 12129 /* DISCOVERY ANSWER DELETED */);

            //TODO Also remove the discovery of the selected if not a payment type:


        }

        //Add New Answers
        $answers_newly_added = 0;
        if(count($answer_i__ids)){
            foreach($answer_i__ids as $answer_i__id){
                $answers_newly_added++;
                $this->X_model->create(array(
                    'x__type' => $i_x__type,
                    'x__source' => $e__id,
                    'x__left' => $is[0]['i__id'],
                    'x__right' => $answer_i__id,
                ));
            }
        }

        //Issue DISCOVERY/IDEA COIN:
        $this->X_model->mark_complete($top_i__id, $is[0], array(
            'x__type' => $x__type,
            'x__source' => $e__id,
        ));

        //All good, something happened:
        return array(
            'status' => 1,
            'message' => $answers_newly_added.' Selected. Going Next...',
        );

    }



}