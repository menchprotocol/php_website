<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Read extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }


    function read_add($in_id){

        $session_en = superpower_assigned();

        //Check to see if added to READING LIST for logged-in users:
        if(!isset($session_en['en_id'])){
            return redirect_message('/sign/'.$in_id);
        }

        //Add this Blog to their READING LIST:
        if(!$this->READ_model->read_add($session_en['en_id'], $in_id)){
            //Failed to add to reading list:
            return redirect_message('/read', '<div class="alert alert-danger" role="alert">Failed to add blog to your reading list.</div>');
        }

        //Find Next & go:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
        ));
        $next_in_id = $this->READ_model->read_next_find($session_en['en_id'], $ins[0]);
        return redirect_message('/'.($next_in_id > 0 ? $next_in_id : $in_id ), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-check-circle read"></i></span>Added to your reading list. Continue below...</div>');

    }

    function read_next($in_id = 0){

        $session_en = superpower_assigned();
        if(!isset($session_en['en_id']) || !$session_en['en_id']){
            return redirect_message('/sign');
        }

        if($in_id > 0){

            //Fetch Blog:
            $ins = $this->BLOG_model->in_fetch(array(
                'in_id' => $in_id,
            ));


            //Should we check for auto next redirect if empty? Only if this is a selection:
            $append_url = null;
            if(in_array($ins[0]['in_type_play_id'], $this->config->item('en_ids_7712'))){
                $append_url = '?next_if_empty=1';
            }


            //Find next Blog based on player's reading list:
            $next_in_id = $this->READ_model->read_next_find($session_en['en_id'], $ins[0]);
            if($next_in_id > 0){
                return redirect_message('/' . $next_in_id.$append_url);
            } else {
                $next_in_id = $this->READ_model->read_next_go($session_en['en_id'], false);
                if($next_in_id > 0){
                    return redirect_message('/' . $next_in_id.$append_url);
                } else {
                    return redirect_message('/', '<div class="alert alert-danger" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully read your entire reading list.</div></div>');
                }
            }

        } else {

            //Find the next blog in the READING LIST to skip:
            $next_in_id = $this->READ_model->read_next_go($session_en['en_id'], false);
            if($next_in_id > 0){
                return redirect_message('/' . $next_in_id);
            } else {
                return redirect_message('/', '<div class="alert alert-danger" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully read your entire reading list.</div></div>');
            }

        }
    }

    function read_in_history($tab_group_id, $note_in_id = 0, $owner_en_id = 0, $last_loaded_ln_id = 0){

        if (!$note_in_id && !$owner_en_id) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Require either Blog or Play ID',
            ));

        } elseif (!in_array($tab_group_id, $this->config->item('en_ids_12410') /* BLOG & READ COIN */) || !count($this->config->item('en_ids_'.$tab_group_id))) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Read Type Group ID',
            ));

        }

        $match_columns = array(
            'ln_id >' => $last_loaded_ln_id,
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_'.$tab_group_id)) . ')' => null,
        );

        if($note_in_id > 0){

            $match_columns['ln_parent_blog_id'] = $note_in_id;
            $list_url = '/blog/'.$note_in_id;
            $list_class = 'itemblog';
            $join_objects = array('en_owner');

        } elseif($owner_en_id > 0){

            if($tab_group_id == 12273 /* BLOG COIN */){

                $list_class = 'itemread';
                $join_objects = array('in_child');
                $match_columns['ln_parent_play_id'] = $owner_en_id;
                $match_columns['in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')'] = null;

            } elseif($tab_group_id == 6255 /* READ COIN */){

                $list_class = 'itemplay';
                $list_url = '/play/'.$owner_en_id;
                $join_objects = array('in_parent');
                $match_columns['ln_owner_play_id'] = $owner_en_id;

            }

        }


        //List Read History:
        $ui = '<div class="list-group dynamic-reads">';
        foreach($this->READ_model->ln_fetch($match_columns, $join_objects, config_var(11064), 0, array('ln_id' => 'DESC')) as $in_read){
            if($note_in_id > 0){

                $ui .= echo_en($in_read);

            } elseif($owner_en_id > 0){

                $footnotes = null;
                if(strlen($in_read['ln_content'])){
                    $footnotes .= '<div class="message_content">';
                    $footnotes .= $this->READ_model->dispatch_message($in_read['ln_content']);
                    $footnotes .= '</div>';
                }

                $ui .= echo_in($in_read, 0, false, false);

            }
        }
        $ui .= '</div>';



        //Return success:
        return echo_json(array(
            'status' => 1,
            'message' => $ui,
        ));

    }


    function cron__weekly_coins(){

        //Calculates the weekly coins issued:
        $last_week_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-7, date("Y"));
        $last_week_start = date(config_var(12355), $last_week_start_timestamp);
        $last_week_end = date(config_var(12355), mktime(23, 59, 59, date("n"), date("j")-1, date("Y")));

        //BLOG
        $blog_coins_new_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 4250, //UNIQUE BLOGS
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
        $blog_coins_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 4250, //UNIQUE BLOGS
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
        $blog_coins_growth_rate = format_percentage( $blog_coins_last_week[0]['total_coins'] / ( $blog_coins_last_week[0]['total_coins'] - $blog_coins_new_last_week[0]['total_coins'] ) * 100 ) - 100;


        //READ
        $read_coins_new_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
        $read_coins_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
        $read_coins_growth_rate = format_percentage( $read_coins_last_week[0]['total_coins'] / ( $read_coins_last_week[0]['total_coins'] - $read_coins_new_last_week[0]['total_coins'] ) * 100 ) - 100;



        //PLAY
        $play_coins_new_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //PLAY COIN
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
        $play_coins_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //PLAY COIN
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
        $play_coins_growth_rate = format_percentage( $play_coins_last_week[0]['total_coins'] / ( $play_coins_last_week[0]['total_coins'] - $play_coins_new_last_week[0]['total_coins'] ) * 100 ) - 100;


        //ledger
        $ledger_transactions_new_last_week = $this->READ_model->ln_fetch(array(
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
        $ledger_transactions_last_week = $this->READ_model->ln_fetch(array(
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_coins');
        $ledger_transactions_growth_rate = format_percentage( $ledger_transactions_last_week[0]['total_coins'] / ( $ledger_transactions_last_week[0]['total_coins'] - $ledger_transactions_new_last_week[0]['total_coins'] ) * 100 ) - 100;



        //Email Subject
        $subject = 'MENCH Weekly Growth Report';

        //Email Body
        $html_message = '<br />';
        $html_message .= '<div>MENCH growth for the <span title="'.$last_week_start.' to '.$last_week_end.' '.config_var(11079).' Timezone" style="border-bottom:1px dotted #AAAAAA;">week of '.date("M jS", $last_week_start_timestamp).'</span>:</div>';
        $html_message .= '<br />';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">📖</b><b style="min-width:55px; display: inline-block;">'.( $ledger_transactions_growth_rate >= 0 ? '+' : '-' ).$ledger_transactions_growth_rate.'%</b>to <span style="min-width:47px; display: inline-block;"><span title="'.number_format($ledger_transactions_last_week[0]['total_coins'], 0).' Transactions" style="border-bottom:1px dotted #AAAAAA;">'.echo_number($ledger_transactions_last_week[0]['total_coins']).'</span></span><a href="https://mench.com/ledger" target="_blank" style="color: #000000; font-weight:bold; text-decoration:none;">TRANSACTIONS &raquo;</a></div>';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔵</b><b style="min-width:55px; display: inline-block;">'.( $play_coins_growth_rate >= 0 ? '+' : '-' ).$play_coins_growth_rate.'%</b>to <span style="min-width:47px; display: inline-block;"><span title="'.number_format($play_coins_last_week[0]['total_coins'], 0).' Coins" style="border-bottom:1px dotted #AAAAAA;">'.echo_number($play_coins_last_week[0]['total_coins']).'</span></span><a href="https://mench.com/play" target="_blank" style="color: #008DF2; font-weight:bold; text-decoration:none;">PLAY &raquo;</a></div>';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔴</b><b style="min-width:55px; display: inline-block;">'.( $read_coins_growth_rate >= 0 ? '+' : '-' ).$read_coins_growth_rate.'%</b>to <span style="min-width:47px; display: inline-block;"><span title="'.number_format($read_coins_last_week[0]['total_coins'], 0).' Coins" style="border-bottom:1px dotted #AAAAAA;">'.echo_number($read_coins_last_week[0]['total_coins']).'</span></span><a href="https://mench.com" target="_blank" style="color: #FC1B44; font-weight:bold; text-decoration:none;">READ &raquo;</a></div>';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🟡</b><b style="min-width:55px; display: inline-block;">'.( $blog_coins_growth_rate >= 0 ? '+' : '-' ).$blog_coins_growth_rate.'%</b>to <span style="min-width:47px; display: inline-block;"><span title="'.number_format($blog_coins_last_week[0]['total_coins'], 0).' Coins" style="border-bottom:1px dotted #AAAAAA;">'.echo_number($blog_coins_last_week[0]['total_coins']).'</span></span><a href="https://mench.com/blog" target="_blank" style="color: #ffd600; font-weight:bold; text-decoration:none;">BLOG &raquo;</a></div>';

        $html_message .= '<br /><br />';
        $html_message .= '<div>Cheers,</div>';
        $html_message .= '<div>MENCH</div>';

        $subscriber_filters = array(
            'ln_parent_play_id' => 12114,
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
        );

        //Should we limit the scope?
        if(isset($_GET['notify_play_id']) && intval($_GET['notify_play_id']) > 0){
            $subscriber_filters['ln_child_play_id'] = $_GET['notify_play_id'];
        } else {
            $session_en = superpower_assigned();
            if($session_en){
                $subscriber_filters['ln_child_play_id'] = $session_en['en_id'];
            }
        }


        $email_recipients = 0;
        //Send email to all subscribers:
        foreach($this->READ_model->ln_fetch($subscriber_filters, array('en_child')) as $subscribed_player){
            //Try fetching subscribers email:
            foreach($this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id' => 4255, //Linked Players Text (Email is text)
                'ln_parent_play_id' => 3288, //Mench Email
                'ln_child_play_id' => $subscribed_player['en_id'],
            )) as $en_email){
                if(filter_var($en_email['ln_content'], FILTER_VALIDATE_EMAIL)){
                    //Send Email
                    $this->READ_model->dispatch_emails(array($en_email['ln_content']), $subject, '<div>Hi '.one_two_explode('',' ',$subscribed_player['en_name']).' 👋</div>'.$html_message);
                    $email_recipients++;
                }
            }
        }

        echo 'Sent '.$email_recipients.' Emails';
    }

    function read_home(){

        $session_en = superpower_assigned(null, true);
        $player_reads = array();


        if($session_en){
            //Fetch reading list:
            $player_reads = $this->READ_model->ln_fetch(array(
                'ln_owner_play_id' => $session_en['en_id'],
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));
            if(!count($player_reads)){
                //Nothing in their reading list:
                return redirect_message('/');
            }

            //Log 🔴 READING LIST View:
            $this->READ_model->ln_create(array(
                'ln_type_play_id' => 4283, //Opened 🔴 READING LIST
                'ln_owner_play_id' => $session_en['en_id'],
            ));
        }


        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH
        $this->load->view('header', array(
            'title' => $en_all_2738[6205]['m_name'],
        ));

        $this->load->view('read/read_home', array(
            'session_en' => $session_en,
            'player_reads' => $player_reads,
        ));

        $this->load->view('footer');

    }


    function read_coin($in_id = 0)
    {

        /*
         *
         * Enables a PLAYer to READ a BLOG
         * on the public web
         *
         * */

        //Fetch user session:
        $session_en = superpower_assigned();

        if(!$in_id){
            //Load the Starting Blog:
            $in_id = config_var(12156);
        }

        //Fetch data:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
        ));

        //Make sure we found it:
        if ( count($ins) < 1) {
            return redirect_message('/', '<div class="alert alert-danger" role="alert">Blog #' . $in_id . ' not found</div>');
        } elseif(!in_array($ins[0]['in_status_play_id'], $this->config->item('en_ids_7355') /* Blog Statuses Public */)){

            if(superpower_assigned(10939)){
                //Give them blog access:
                return redirect_message('/blog/' . $in_id);
            } else {
                //Inform them not published:
                return redirect_message('/', '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>Cannot read this blog because it\'s not published yet.</div>');
            }

        }

        $this->load->view('header', array(
            'title' => echo_in_title($ins[0]['in_title'], true).' | READ',
            'in' => $ins[0],
        ));

        //Load specific view based on Blog Level:
        $this->load->view('read/read_coin', array(
            'in' => $ins[0],
            'session_en' => $session_en,
        ));

        $this->load->view('footer');

    }



    function read_file_upload()
    {

        //TODO: MERGE WITH FUNCTION in_note_create_upload()

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));

        } elseif (!isset($_POST['in_id'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing BLOG',
            ));

        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));

        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown error while trying to save file.',
            ));

        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_var(11063) * 1024 * 1024)) {

            return echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . config_var(11063) . ' MB.',
            ));

        }

        //Validate Blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Blog ID',
            ));
        }


        //Attempt to save file locally:
        $file_parts = explode('.', $_FILES[$_POST['upload_type']]["name"]);
        $temp_local = "application/cache/temp_files/" . md5($file_parts[0] . $_FILES[$_POST['upload_type']]["type"] . $_FILES[$_POST['upload_type']]["size"]) . '.' . $file_parts[(count($file_parts) - 1)];
        move_uploaded_file($_FILES[$_POST['upload_type']]['tmp_name'], $temp_local);


        //Attempt to store in Mench Cloud on Amazon S3:
        if (isset($_FILES[$_POST['upload_type']]['type']) && strlen($_FILES[$_POST['upload_type']]['type']) > 0) {
            $mime = $_FILES[$_POST['upload_type']]['type'];
        } else {
            $mime = mime_content_type($temp_local);
        }

        $cdn_status = upload_to_cdn($temp_local, $session_en['en_id'], $_FILES[$_POST['upload_type']], true);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return echo_json($cdn_status);
        }


        //Delete previous answer(s):
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_parent_blog_id' => $ins[0]['in_id'],
            'ln_owner_play_id' => $session_en['en_id'],
        )) as $read_progress){
            $this->READ_model->ln_update($read_progress['ln_id'], array(
                'ln_status_play_id' => 6173, //Link Removed
            ), $session_en['en_id'], 12129 /* READ ANSWER ARCHIVED */);
        }

        //Save new answer:
        $new_message = '@'.$cdn_status['cdn_en']['en_id'];
        $this->READ_model->read_is_complete($ins[0], array(
            'ln_type_play_id' => 12117,
            'ln_parent_blog_id' => $ins[0]['in_id'],
            'ln_owner_play_id' => $session_en['en_id'],
            'ln_content' => $new_message,
            'ln_parent_play_id' => $cdn_status['cdn_en']['en_id'],
        ));

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => '<div class="read-topic"><span class="icon-block-sm"><i class="fas fa-history"></i></span>YOUR UPLOAD:</div><div class="previous_answer">'.$this->READ_model->dispatch_message($new_message).'</div>',
        ));

    }




    function read_text_answer(){

        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session, login and try again',
            ));
        } elseif (!isset($_POST['in_id']) || !intval($_POST['in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing blog ID.',
            ));
        } elseif (!isset($_POST['read_text_answer']) || !strlen($_POST['read_text_answer'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing text answer.',
            ));
        }

        //Validate/Fetch blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ));
        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Blog not published.',
            ));
        }

        //Delete previous answer(s):
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_parent_blog_id' => $ins[0]['in_id'],
            'ln_owner_play_id' => $session_en['en_id'],
        )) as $read_progress){
            $this->READ_model->ln_update($read_progress['ln_id'], array(
                'ln_status_play_id' => 6173, //Link Removed
            ), $session_en['en_id'], 12129 /* READ ANSWER ARCHIVED */);
        }

        //Save new answer:
        $this->READ_model->read_is_complete($ins[0], array(
            'ln_type_play_id' => 6144,
            'ln_parent_blog_id' => $ins[0]['in_id'],
            'ln_owner_play_id' => $session_en['en_id'],
            'ln_content' => $_POST['read_text_answer'],
        ));

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => 'Answer Saved',
        ));

    }


    function read_answer(){

        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session, login and try again',
            ));
        } elseif (!isset($_POST['in_loaded_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing blog id.',
            ));
        } elseif (!isset($_POST['answered_ins']) || !is_array($_POST['answered_ins']) || !count($_POST['answered_ins'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Select at-least one answer',
            ));
        }

        //Save answer:
        return echo_json($this->READ_model->read_answer($session_en['en_id'], $_POST['in_loaded_id'], $_POST['answered_ins']));

    }



    function read_ledger()
    {
        /*
         *
         * List all Links on reverse chronological order
         * and Display statuses for blogs, players and
         * links.
         *
         * */

        //Load header:
        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION

        $this->load->view('header', array(
            'title' => $en_all_11035[11999]['m_name'],
        ));
        $this->load->view('read/read_ledger');
        $this->load->view('footer');
    }



    function read_stats(){


        //Blogs

        $en_all_7302 = $this->config->item('en_all_7302'); //Blog Stats


        //Blog Statuses:
        echo '<table class="table table-sm table-striped stats-table mini-stats-table blog_statuses">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.$en_all_7302[4737]['m_name'].echo__s(count($this->config->item('en_all_4737')), true).'</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_4737') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->BLOG_model->in_fetch(array(
                'in_status_play_id' => $en_id
            ), 0, 0, array(), 'COUNT(in_id) as totals');

            //Display this status count:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/play/'.$en_id.'">' . $m['m_name'] . '</a></td>';

            echo '<td style="text-align: right;">' . '<a href="/ledger?in_status_play_id=' . $en_id . '&ln_type_play_id=4250">' . number_format($objects_count[0]['totals'],0) .'</a></td>';

            echo '</tr>';

        }
        echo '</table>';





        //Count all Blog Subtypes:
        $blog_types_counts = $this->BLOG_model->in_fetch(array(
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ), 0, 0, array(), 'COUNT(in_type_play_id) as total_count, en_name, en_icon, en_id', 'en_id, en_name, en_icon');

        //Count totals:
        $addup_count = addup_array($blog_types_counts, 'total_count');

        //Link Stages
        echo_2level_stats($en_all_7302[10602]['m_name'], 10602, 7585, $blog_types_counts, $addup_count, 'in_type_play_id', 'total_count');





        //Players
        $en_all_7303 = $this->config->item('en_all_7303'); //Platform Dashboard
        $en_all_6177 = $this->config->item('en_all_6177'); //Player Statuses





        //Player Statuses
        echo '<table class="table table-sm table-striped stats-table mini-stats-table player_statuses '.superpower_active(10967).'">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.$en_all_7303[6177]['m_name'].echo__s(count($this->config->item('en_all_6177')), true).'</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_6177') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->PLAY_model->en_fetch(array(
                'en_status_play_id' => $en_id
            ), 0, 0, array(), 'COUNT(en_id) as totals');

            //Display this status count:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/play/'.$en_id.'">' . $m['m_name'] . '</a></td>';
            echo '<td style="text-align: right;">' . '<a href="/ledger?en_status_play_id=' . $en_id . '&ln_type_play_id=4251">' . number_format($objects_count[0]['totals'], 0) . '</a>' . '</td>';
            echo '</tr>';

        }
        echo '</table>';





        //Mench Community
        echo echo_en_stats_overview($this->config->item('en_all_6827'), $en_all_7303[6827]['m_name']);




        //Expert Sources
        $expert_sources_unpublished = ''; //Saved the UI for later view...
        $expert_sources_published = ''; //Saved the UI for later view...
        $total_counts = array();
        foreach ($this->config->item('en_all_3000') as $en_id => $m) {

            $expert_source_statuses = '';
            unset($total_counts);
            $total_counts = array();

            //Count totals for each active status:
            foreach($this->config->item('en_all_7358') /* Player Active Statuses */ as $en_status_play_id => $m_status){

                //Count this type:
                $source_count = $this->PLAY_model->en_child_count($en_id, array($en_status_play_id)); //Count completed

                //Addup count:
                if(isset($total_counts[$en_status_play_id])){
                    $total_counts[$en_status_play_id] += $source_count;
                } else {
                    $total_counts[$en_status_play_id] = $source_count;
                }


                if(isset($total_counts[$en_status_play_id])){
                    $total_counts[$en_status_play_id] += $source_count;
                } else {
                    $total_counts[$en_status_play_id] = $source_count;
                }

                //Display row:
                $expert_source_statuses .= '<td style="text-align: right;"'.( $en_status_play_id != 6181 /* Player Featured */ ? ' class="' . superpower_active(10967) . '"' : '' ).'><a href="/play/' . $en_id .'#status-'.$en_status_play_id.'">'.number_format($source_count,0).'</a></td>';

            }

            //Echo stats:
            $expert_sources = '<tr class="' .( !$total_counts[6181] ? superpower_active(10967) : '' ) . '">';
            $expert_sources .= '<td style="text-align: left;"><span class="icon-block">'.$m['m_icon'].'</span><a href="/play/'.$en_id.'">'.$m['m_name'].'</a></td>';
            $expert_sources .= $expert_source_statuses;
            $expert_sources .= '</tr>';

            if($total_counts[6181]){
                $expert_sources_published .= $expert_sources;
            } else {
                $expert_sources_unpublished .= $expert_sources;
            }

        }

        echo '<table class="table table-sm table-striped stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7303[3000]['m_name'].' ['.number_format($total_counts[6181], 0).']</td>';
        foreach($this->config->item('en_all_7358') /* Player Active Statuses */ as $en_status_play_id => $m_status){
            if($en_status_play_id == 6181 /* Player Published */){
                echo '<td style="text-align:right;"><div class="' . superpower_active(10967) . '">' . $en_all_6177[$en_status_play_id]['m_name'] . '</div></td>';
            } else {
                echo '<td style="text-align:right;" class="' . superpower_active(10967) . '">' . $en_all_6177[$en_status_play_id]['m_name'] . '</td>';
            }
        }
        echo '</tr>';


        echo $expert_sources_published;
        echo $expert_sources_unpublished;


        echo '<tr style="font-weight: bold;" class="'.superpower_active(10967).'">';
        echo '<td style="text-align: left;"><span class="icon-block"><i class="fas fa-asterisk"></i></span>Totals</td>';
        foreach($this->config->item('en_all_7358') /* Player Active Statuses */ as $en_status_play_id => $m_status){
            echo '<td style="text-align: right;" '.( $en_status_play_id != 6181 /* Player Featured */ ? ' class="' . superpower_active(10967) . '"' : '' ).'>' . number_format($total_counts[$en_status_play_id], 0) . '</td>';
        }
        echo '</tr>';


        echo '</table>';








        //READ


        $en_all_4593 = $this->config->item('en_all_4593'); //Load all link types
        $en_all_7304 = $this->config->item('en_all_7304'); //Link Stats


        //READ Status:
        echo '<table class="table table-sm table-striped stats-table mini-stats-table link_statuses">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.$en_all_7304[6186]['m_name'].echo__s(count($this->config->item('en_all_6186')), true).'</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_6186') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->READ_model->ln_fetch(array(
                'ln_status_play_id' => $en_id
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

            //Display this status count:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/play/'.$en_id.'">' . $m['m_name'] . '</a></td>';
            echo '<td style="text-align: right;">';
            echo '<a href="/ledger?ln_status_play_id=' . $en_id . '">' . number_format($objects_count[0]['totals'],0) . '</a>';
            echo '</td>';
            echo '</tr>';

        }

        echo '</table>';






        //Count all rows:
        $link_types_counts = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        ), array('en_type'), 0, 0, array(), 'COUNT(ln_id) as total_count, en_name, en_icon, en_id', 'en_id, en_name, en_icon');

        //Count totals:
        $addup_count = addup_array($link_types_counts, 'total_count');

        //Link Direction
        echo_2level_stats('Types', 2738, 4593, $link_types_counts, $addup_count, 'ln_type_play_id', 'total_count');


    }



    function js_ln_create(){

        //Log link from JS source:
        if(isset($_POST['ln_order']) && strlen($_POST['ln_order'])>0 && !is_numeric($_POST['ln_order'])){
            //We have an order set, but its not an integer, which means it's a cookie name that needs to be analyzed:
            $_POST['ln_order'] = fetch_cookie_order($_POST['ln_order']);
        }

        //Log engagement:
        echo_json($this->READ_model->ln_create($_POST));
    }


    function load_ledger(){

        /*
         * Loads the list of links based on the
         * filters passed on.
         *
         * */

        $filters = unserialize($_POST['link_filters']);
        $join_by = unserialize($_POST['link_join_by']);
        $page_num = ( isset($_POST['page_num']) && intval($_POST['page_num'])>=2 ? intval($_POST['page_num']) : 1 );
        $next_page = ($page_num+1);
        $item_per_page = (is_dev_environment() ? 20 : config_var(11064));
        $query_offset = (($page_num-1)*$item_per_page);
        $session_en = superpower_assigned();

        $message = '';

        //Fetch links and total link counts:
        $lns = $this->READ_model->ln_fetch($filters, $join_by, $item_per_page, $query_offset);
        $lns_count = $this->READ_model->ln_fetch($filters, $join_by, 0, 0, array(), 'COUNT(ln_id) as total_count');
        $total_items_loaded = ($query_offset+count($lns));
        $has_more_links = ($lns_count[0]['total_count'] > 0 && $total_items_loaded < $lns_count[0]['total_count']);


        //Display filter notes:
        if($total_items_loaded > 0){
            $message .= '<div class="montserrat" style="margin:0 0 15px 0;"><span class="icon-block"><i class="fas fa-file-search"></i></span>'.( $has_more_links && $query_offset==0  ? 'FIRST ' : ($query_offset+1).' - ' ) . ( $total_items_loaded >= ($query_offset+1) ?  $total_items_loaded . ' OF ' : '' ) . number_format($lns_count[0]['total_count'] , 0) .' TRANSACTIONS:</div>';
        }


        if(count($lns)>0){

            $message .= '<div class="list-group list-grey">';
            foreach ($lns as $ln) {

                $message .= echo_ln($ln);

                if($session_en && strlen($ln['ln_content'])>0 && strlen($_POST['ln_content_search'])>0 && strlen($_POST['ln_content_replace'])>0 && substr_count($ln['ln_content'], $_POST['ln_content_search'])>0){

                    $new_content = str_replace($_POST['ln_content_search'],trim($_POST['ln_content_replace']),$ln['ln_content']);

                    $this->READ_model->ln_update($ln['ln_id'], array(
                        'ln_content' => $new_content,
                    ), $session_en['en_id'], 12360, update_description($ln['ln_content'], $new_content));

                    $message .= '<div class="alert alert-danger" role="alert"><i class="fas fa-check-circle"></i> Replaced ['.$_POST['ln_content_search'].'] with ['.trim($_POST['ln_content_replace']).']</div>';

                }

            }
            $message .= '</div>';

            //Do we have more to show?
            if($has_more_links){
                $message .= '<div id="link_page_'.$next_page.'"><a href="javascript:void(0);" style="margin:10px 0 72px 0;" class="btn btn-read" onclick="load_ledger(link_filters, link_join_by, '.$next_page.');"><span class="icon-block"><i class="fas fa-plus-circle"></i></span>Page '.$next_page.'</a></div>';
                $message .= '';
            } else {
                $message .= '<div style="margin:10px 0 72px 0;"><span class="icon-block"><i class="far fa-check-circle"></i></span>All '.$lns_count[0]['total_count'].' link'.echo__s($lns_count[0]['total_count']).' have been loaded</div>';

            }

        } else {

            //Show no link warning:
            $message .= '<div class="alert alert-warning" role="alert" style="margin-top:20px;"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>No Links found with the selected filters. Modify filters and try again.</div>';

        }


        return echo_json(array(
            'status' => 1,
            'message' => $message,
        ));


    }


    function view_json($ln_id)
    {

        //Fetch link metadata and display it:
        $lns = $this->READ_model->ln_fetch(array(
            'ln_id' => $ln_id,
        ));

        if (count($lns) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid READ ID',
            ));
        } elseif(!superpower_assigned(10985)) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing superpower or session expired',
            ));

        } else {

            //unserialize metadata if needed:
            if(strlen($lns[0]['ln_metadata']) > 0){
                $lns[0]['ln_metadata'] = unserialize($lns[0]['ln_metadata']);
            }

            //Print on scree:
            echo_json($lns[0]);

        }

    }




    function cron__sync_algolia($input_obj_type = null, $input_obj_id = null){

        if($input_obj_type < 0){
            //Gateway URL to give option to run...
            die('<a href="/read/cron__sync_algolia">Click here</a> to start running this function.');
        }

        //Call the update function and passon possible values:
        echo_json(update_algolia($input_obj_type, $input_obj_id));
    }

    function load_link_connections(){


        //Authenticate Trainer:
        if (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing READ ID',
            ));
        } elseif (!isset($_POST['load_main'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing loading preference',
            ));
        }

        //Fetch and validate link:
        $lns = $this->READ_model->ln_fetch(array(
            'ln_id' => $_POST['ln_id'],
        ));
        if (count($lns) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid READ ID',
            ));
        }

        //Show Links:
        $ln_connections_ui = ( intval($_POST['load_main']) ? '' : echo_ln_connections($lns[0]) );

        //Now show all links for this link:
        foreach ($this->READ_model->ln_fetch(array(
            'ln_parent_read_id' => $_POST['ln_id'],
        ), array(), 0, 0, array('ln_id' => 'DESC')) as $ln_child) {
            $ln_connections_ui .= '<div class="read-hisotry-child">' . echo_ln($ln_child, true) . '</div>';
        }

        //Return UI:
        return echo_json(array(
            'status' => 1,
            'ln_connections_ui' => $ln_connections_ui,
        ));

    }

    function cron__sync_gephi($affirmation = null){

        /*
         *
         * Populates the nodes and edges table for
         * Gephi https://gephi.org network visualizer
         *
         * */

        if($affirmation < 0){
            //Gateway URL to give option to run...
            die('<a href="/read/cron__sync_gephi">Click here</a> to start running this function.');
        }

        //Boost processing power:
        boost_power();

        //Empty both tables:
        $this->db->query("TRUNCATE TABLE public.gephi_edges CONTINUE IDENTITY RESTRICT;");
        $this->db->query("TRUNCATE TABLE public.gephi_nodes CONTINUE IDENTITY RESTRICT;");

        //Load Blog-to-Blog Links:
        $en_all_4593 = $this->config->item('en_all_4593');

        //To make sure Blog/player IDs are unique:
        $id_prefix = array(
            'in' => 100,
            'en' => 200,
        );

        //Size of nodes:
        $node_size = array(
            'in' => 3,
            'en' => 2,
            'msg' => 1,
        );

        //Add Blogs:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
        ));
        foreach($ins as $in){

            //Prep metadata:
            $in_metadata = ( strlen($in['in_metadata']) > 0 ? unserialize($in['in_metadata']) : array());

            //Add Blog node:
            $this->db->insert('gephi_nodes', array(
                'id' => $id_prefix['in'].$in['in_id'],
                'label' => $in['in_title'],
                //'size' => ( isset($in_metadata['in__metadata_max_seconds']) ? round(($in_metadata['in__metadata_max_seconds']/3600),0) : 0 ), //Max time
                'size' => $node_size['in'],
                'node_type' => 1, //Blog
                'node_status' => $in['in_status_play_id'],
            ));

            //Fetch children:
            foreach($this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
                'ln_parent_blog_id' => $in['in_id'],
            ), array('in_child'), 0, 0) as $child_in){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['in'].$child_in['ln_parent_blog_id'],
                    'target' => $id_prefix['in'].$child_in['ln_child_blog_id'],
                    'label' => $en_all_4593[$child_in['ln_type_play_id']]['m_name'], //TODO maybe give visibility to condition here?
                    'weight' => 1,
                    'edge_type_en_id' => $child_in['ln_type_play_id'],
                    'edge_status' => $child_in['ln_status_play_id'],
                ));

            }
        }


        //Add players:
        $ens = $this->PLAY_model->en_fetch(array(
            'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
        ));
        foreach($ens as $en){

            //Add player node:
            $this->db->insert('gephi_nodes', array(
                'id' => $id_prefix['en'].$en['en_id'],
                'label' => $en['en_name'],
                'size' => $node_size['en'] ,
                'node_type' => 2, //Player
                'node_status' => $en['en_status_play_id'],
            ));

            //Fetch children:
            foreach($this->READ_model->ln_fetch(array(
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                'ln_parent_play_id' => $en['en_id'],
            ), array('en_child'), 0, 0) as $en_child){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$en_child['ln_parent_play_id'],
                    'target' => $id_prefix['en'].$en_child['ln_child_play_id'],
                    'label' => $en_all_4593[$en_child['ln_type_play_id']]['m_name'].': '.$en_child['ln_content'],
                    'weight' => 1,
                    'edge_type_en_id' => $en_child['ln_type_play_id'],
                    'edge_status' => $en_child['ln_status_play_id'],
                ));

            }
        }

        //Add messages:
        $messages = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Blog Notes
        ), array('in_child'), 0, 0);
        foreach($messages as $message) {

            //Add message node:
            $this->db->insert('gephi_nodes', array(
                'id' => $message['ln_id'],
                'label' => $en_all_4593[$message['ln_type_play_id']]['m_name'] . ': ' . $message['ln_content'],
                'size' => $node_size['msg'],
                'node_type' => $message['ln_type_play_id'], //Message type
                'node_status' => $message['ln_status_play_id'],
            ));

            //Add child blog link:
            $this->db->insert('gephi_edges', array(
                'source' => $message['ln_id'],
                'target' => $id_prefix['in'].$message['ln_child_blog_id'],
                'label' => 'Child Blog',
                'weight' => 1,
            ));

            //Add parent blog link?
            if ($message['ln_parent_blog_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['in'].$message['ln_parent_blog_id'],
                    'target' => $message['ln_id'],
                    'label' => 'Parent Blog',
                    'weight' => 1,
                ));
            }

            //Add parent player link?
            if ($message['ln_parent_play_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$message['ln_parent_play_id'],
                    'target' => $message['ln_id'],
                    'label' => 'Parent Player',
                    'weight' => 1,
                ));
            }

        }

        echo count($ins).' blogs & '.count($ens).' players & '.count($messages).' messages synced.';
    }




    function cron__clean_metadatas($affirmation = null){

        /*
         *
         * A function that would run through all
         * object metadata variables and remove
         * all variables that are not indexed
         * as part of Variables Names player @6232
         *
         * https://mench.com/play/6232
         *
         *
         * */

        if($affirmation < 0){
            //Gateway URL to give option to run...
            die('<a href="/read/cron__clean_metadatas">Click here</a> to start running this function.');
        }

        boost_power();

        //Fetch all valid variable names:
        $valid_variables = array();
        foreach($this->READ_model->ln_fetch(array(
            'ln_parent_play_id' => 6232, //Variables Names
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
            'LENGTH(ln_content) > 0' => null,
        ), array('en_child'), 0) as $var_name){
            array_push($valid_variables, $var_name['ln_content']);
        }

        //Now let's start the cleanup process...
        $invalid_variables = array();

        //Blog Metadata
        foreach($this->BLOG_model->in_fetch(array()) as $in){

            if(strlen($in['in_metadata']) < 1){
                continue;
            }

            foreach(unserialize($in['in_metadata']) as $key => $value){
                if(!in_array($key, $valid_variables)){
                    //Remove this:
                    update_metadata('in', $in['in_id'], array(
                        $key => null,
                    ));

                    //Add to index:
                    if(!in_array($key, $invalid_variables)){
                        array_push($invalid_variables, $key);
                    }
                }
            }

        }

        //Player Metadata
        foreach($this->PLAY_model->en_fetch(array()) as $en){

            if(strlen($en['en_metadata']) < 1){
                continue;
            }

            foreach(unserialize($en['en_metadata']) as $key => $value){
                if(!in_array($key, $valid_variables)){
                    //Remove this:
                    update_metadata('en', $en['en_id'], array(
                        $key => null,
                    ));

                    //Add to index:
                    if(!in_array($key, $invalid_variables)){
                        array_push($invalid_variables, $key);
                    }
                }
            }

        }

        $ln_metadata = array(
            'invalid' => $invalid_variables,
            'valid' => $valid_variables,
        );

        if(count($invalid_variables) > 0){
            //Did we have anything to remove? Report with system bug:
            $this->READ_model->ln_create(array(
                'ln_content' => 'cron__clean_metadatas() removed '.count($invalid_variables).' unknown variables from blog/player metadatas. To prevent this from happening, register the variables via Variables Names @6232',
                'ln_type_play_id' => 4246, //Platform Bug Reports
                'ln_parent_play_id' => 6232, //Variables Names
                'ln_metadata' => $ln_metadata,
            ));
        }

        echo_json($ln_metadata);

    }






    function actionplan_reset_progress($en_id, $timestamp, $secret_key){

        if($secret_key != md5($en_id . $this->config->item('cred_password_salt') . $timestamp)){
            die('Invalid Secret Key');
        }

        //Fetch their current progress links:
        $progress_links = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_12227')) . ')' => null,
            'ln_owner_play_id' => $en_id,
        ), array(), 0);

        if(count($progress_links) > 0){

            //Yes they did have some:
            $message = 'Removed '.count($progress_links).' read'.echo__s(count($progress_links)).' from your list.';

            //Log link:
            $clear_all_link = $this->READ_model->ln_create(array(
                'ln_content' => $message,
                'ln_type_play_id' => 6415, //🔴 READING LIST Reset Reads
                'ln_owner_play_id' => $en_id,
            ));

            //Remove all progressions:
            foreach($progress_links as $progress_link){
                $this->READ_model->ln_update($progress_link['ln_id'], array(
                    'ln_status_play_id' => 6173, //Link Removed
                    'ln_parent_read_id' => $clear_all_link['ln_id'], //To indicate when it was removed
                ), $en_id, 6415 /* User Cleared 🔴 READING LIST */);
            }

        } else {

            //Nothing to do:
            $message = 'Your 🔴 READING LIST was already empty as there was nothing to delete';

        }

        //Show basic UI for now:
        return redirect_message('/read', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>'.$message.'</div>');

    }


    function actionplan_stop_save(){

        /*
         *
         * When users indicate they want to stop
         * a BLOG this function saves the changes
         * necessary and remove the blog from their
         * 🔴 READING LIST.
         *
         * */


        if (!isset($_POST['js_pl_id']) || intval($_POST['js_pl_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid trainer ID',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing blog ID',
            ));
        }

        //Call function to remove form 🔴 READING LIST:
        $delete_result = $this->READ_model->read_delete($_POST['js_pl_id'], $_POST['in_id'], 6155); //READER REMOVED BOOKMARK

        if(!$delete_result['status']){
            return echo_json($delete_result);
        } else {
            return echo_json(array(
                'status' => 1,
            ));
        }
    }


    function actionplan_skip_preview($en_id, $in_id)
    {

        //Just give them an overview of what they are about to skip:
        return echo_json(array(
            'skip_step_preview' => 'WARNING: '.$this->READ_model->read_skip_initiate($en_id, $in_id, false).' Are you sure you want to skip?',
        ));

    }

    function actionplan_skip_apply($en_id, $in_id)
    {

        //Actually go ahead and skip
        $this->READ_model->read_skip_apply($en_id, $in_id, false);
        //Assume its all good!

        //We actually skipped, draft message:
        $message = '<div class="alert alert-danger" role="alert">I successfully skipped selected steps.</div>';

        //Find the next item to navigate them to:
        $next_in_id = $this->READ_model->read_next_go($en_id, false);
        if ($next_in_id > 0) {
            return redirect_message('/' . $next_in_id, $message);
        } else {
            return redirect_message('/read', $message);
        }

    }

    function actionplan_sort_save()
    {
        /*
         *
         * Saves the order of 🔴 READING LIST blogs based on
         * user preferences.
         *
         * */

        if (!isset($_POST['js_pl_id']) || intval($_POST['js_pl_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid trainer ID',
            ));
        } elseif (!isset($_POST['new_actionplan_order']) || !is_array($_POST['new_actionplan_order']) || count($_POST['new_actionplan_order']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing sorting blogs',
            ));
        }

        //Update the order of their 🔴 READING LIST:
        $results = array();
        foreach($_POST['new_actionplan_order'] as $ln_order => $ln_id){
            if(intval($ln_id) > 0 && intval($ln_order) > 0){
                //Update order of this link:
                $results[$ln_order] = $this->READ_model->ln_update(intval($ln_id), array(
                    'ln_order' => $ln_order,
                ), $_POST['js_pl_id'], 6132 /* Blogs Ordered by User */);
            }
        }

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => count($_POST['new_actionplan_order']).' Blogs Sorted',
        ));
    }


    function debug($in_id){

        $session_en = superpower_assigned();
        if(!isset($session_en['en_id'])){
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        }


        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ));

        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Public Blog not found',
            ));
        }

        //List the blog:
        return echo_json(array(
            'in_user' => array(
                'next_in_id' => $this->READ_model->read_next_find($session_en['en_id'], $ins[0]),
                'progress' => $this->READ_model->read__completion_progress($session_en['en_id'], $ins[0]),
                'marks' => $this->READ_model->read__completion_marks($session_en['en_id'], $ins[0]),
            ),
            'in_general' => array(
                'recursive_parents' => $this->BLOG_model->in_fetch_recursive_parents($ins[0]['in_id']),
                'common_base' => $this->BLOG_model->in_metadata_common_base($ins[0]),
            ),
        ));

    }



    function messenger_fetch_profile($psid)
    {

        if (!superpower_assigned(10986)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing superpower',
            ));
        }

        //Validate messenger ID:
        $user_messenger = $this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
            'ln_parent_play_id' => 6196, //Mench Messenger
            'ln_external_id' => $psid,
        ));
        if (count($user_messenger) == 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'User not connected to Mench Messenger',
            ));
        }

        //Fetch results and show:
        return echo_json($this->READ_model->facebook_graph('GET', '/' . $user_messenger[0]['ln_external_id'], array()));

    }


    function messenger_sync_menu()
    {

        /*
         * A function that will sync the fixed
         * menu of Mench's Facebook Messenger.
         *
         * */

        //Let's first give permission to our pages to do so:
        $res = array();
        array_push($res, $this->READ_model->facebook_graph('POST', '/me/messenger_profile', array(
            'get_started' => array(
                'payload' => 'GET_STARTED',
            ),
            'whitelisted_domains' => array(
                'http://local.mench.co',
                'https://mench.co',
                'https://mench.com',
            ),
        )));

        //Wait until Facebook pro-pagates changes of our whitelisted_domains setting:
        sleep(2);

        //Now let's update the menu:
        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH

        array_push($res, $this->READ_model->facebook_graph('POST', '/me/messenger_profile', array(
            'persistent_menu' => array(
                array(
                    'locale' => 'default',
                    'composer_input_disabled' => false,
                    'disabled_surfaces' => array('CUSTOMER_CHAT_PLUGIN'),
                    'call_to_actions' => array(
                        array(
                            'title' => '🔵 '.$en_all_2738[4536]['m_name'],
                            'type' => 'web_url',
                            'url' => 'https://mench.com/play',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                        array(
                            'title' => '🔴 '.$en_all_2738[6205]['m_name'],
                            'type' => 'web_url',
                            'url' => 'https://mench.com/read',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                        array(
                            'title' => '🟡 '.$en_all_2738[4535]['m_name'],
                            'type' => 'web_url',
                            'url' => 'https://mench.com/blog',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                    ),
                ),
            ),
        )));

        //Show results:
        echo_json($res);
    }




    function messenger_webhook($test = 0)
    {

        /*
         *
         * The master function for all Facebook webhook calls
         * This URL is set as our end to receive Facebook calls:
         *
         * https://developers.facebook.com/apps/1782431902047009/webhooks/
         *
         * */


        //We need this only for the first time to authenticate that we own the server:
        if (isset($_GET['hub_challenge']) && isset($_GET['hub_verify_token']) && $_GET['hub_verify_token'] == '722bb4e2bac428aa697cc97a605b2c5a') {
            return print_r($_GET['hub_challenge']);
        }

        if($test){
            $ln_metadata = objectToArray(json_decode('{"object":"page","entry":[{"id":"381488558920384","time":1557167164354,"messaging":[{"sender":{"id":"1234880879950857"},"recipient":{"id":"381488558920384"},"timestamp":1557128383000,"message":{"quick_reply":{"payload":"ANSWERQUESTION_9295_9298"},"mid":"UcT9GZXJAm9tR1pjIvXUQv2t4AOQjIajAPJbGvHuA9nVaUUam3pCO3YSEoY8Eyh2-L1XIsMtC__mrpSXIUGn2A","seq":82388,"text":"3"}}]}]}'));
        } else {
            //Real webhook data:
            $ln_metadata = json_decode(file_get_contents('php://input'), true);
        }


        //Do some basic checks:
        if (!isset($ln_metadata['object']) || !isset($ln_metadata['entry'])) {
            //Likely loaded the URL in browser:
            return print_r('missing');
        } elseif ($ln_metadata['object'] != 'page') {
            $this->READ_model->ln_create(array(
                'ln_content' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
                'ln_metadata' => $ln_metadata,
                'ln_type_play_id' => 4246, //Platform Bug Reports
            ));
            return print_r('unknown page');
        }

        //Loop through entries:
        foreach ($ln_metadata['entry'] as $entry) {

            //check the page ID:
            if (!isset($entry['id']) || !($entry['id'] == config_var(11075))) {
                //This can happen for the older webhook that we offered to other FB pages:
                continue;
            } elseif (!isset($entry['messaging'])) {
                $this->READ_model->ln_create(array(
                    'ln_content' => 'facebook_webhook() call missing messaging Array().',
                    'ln_metadata' => $ln_metadata,
                    'ln_type_play_id' => 4246, //Platform Bug Reports
                ));
                continue;
            }

            //loop though the messages:
            foreach ($entry['messaging'] as $im) {

                if (isset($im['read']) || isset($im['delivery'])) {

                    //Message read OR delivered
                    $ln_type_play_id = (isset($im['delivery']) ? 4279 /* Message Delivered */ : 4278 /* Message Read */);

                    //Authenticate User:
                    $en = $this->PLAY_model->en_messenger_auth($im['sender']['id']);

                    //Log Link Only IF last delivery link was 3+ minutes ago (Since Facebook sends many of these):
                    $last_links_logged = $this->READ_model->ln_fetch(array(
                        'ln_type_play_id' => $ln_type_play_id,
                        'ln_owner_play_id' => $en['en_id'],
                        'ln_timestamp >=' => date("Y-m-d H:i:s", (time() - (60))), //READ logged less than 1 minutes ago
                    ), array(), 1);

                    if (count($last_links_logged) == 0) {
                        //We had no recent links of this kind, so go ahead and log:
                        $this->READ_model->ln_create(array(
                            'ln_metadata' => $ln_metadata,
                            'ln_type_play_id' => $ln_type_play_id,
                            'ln_owner_play_id' => $en['en_id'],
                        ));
                    }

                } elseif (isset($im['message'])) {

                    /*
                     *
                     * Triggered for all incoming messages and also for
                     * outgoing messages sent using the Facebook Inbox UI.
                     *
                     * */

                    //Is this a non loggable message? If so, this has already been logged by Mench:
                    if (isset($im['message']['metadata']) && $im['message']['metadata'] == 'system_logged') {

                        //This is already logged! No need to take further action!
                        return print_r('already logged');

                    }


                    //Set variables:
                    $sent_by_mench = (isset($im['message']['is_echo'])); //Indicates the message sent from the page itself
                    $en = $this->PLAY_model->en_messenger_auth(($sent_by_mench ? $im['recipient']['id'] : $im['sender']['id']));
                    $is_quick_reply = (isset($im['message']['quick_reply']['payload']));

                    //Set more variables:
                    $matching_types = array(); //Defines the supported Blog Subtypes

                    unset($ln_data); //Reset everything in case its set from the previous loop!
                    $ln_data = array(
                        'ln_owner_play_id' => $en['en_id'],
                        'ln_metadata' => $ln_metadata, //Entire JSON object received by Facebook API
                        'ln_order' => ($sent_by_mench ? 1 : 0), //A HACK to identify messages sent from us via Facebook Page Inbox
                    );

                    /*
                     *
                     * Now complete the link data based on message type.
                     * We will generally receive 3 types of Facebook Messages:
                     *
                     * - Quick Replies
                     * - Text Messages
                     * - Attachments
                     *
                     * And we will deal with each group, and their sub-group
                     * appropriately based on who sent the message (Mench/User)
                     *
                     * */

                    if ($is_quick_reply) {

                        //Quick Reply Answer Received:
                        $ln_data['ln_type_play_id'] = 4460;
                        $ln_data['ln_content'] = $im['message']['text']; //Quick reply always has a text

                        //Digest quick reply:
                        $quick_reply_results = $this->READ_model->digest_received_payload($en, $im['message']['quick_reply']['payload']);

                        if(!$quick_reply_results['status']){
                            //There was an error, inform Trainer:
                            $this->READ_model->ln_create(array(
                                'ln_content' => 'digest_received_payload() for message returned error ['.$quick_reply_results['message'].']',
                                'ln_metadata' => $ln_metadata,
                                'ln_type_play_id' => 4246, //Platform Bug Reports
                                'ln_owner_play_id' => $en['en_id'],
                            ));

                        }

                    } elseif (isset($im['message']['text'])) {

                        //Set message content:
                        $ln_data['ln_content'] = $im['message']['text'];

                        //Who sent this?
                        if ($sent_by_mench) {

                            $ln_data['ln_type_play_id'] = 4552; //User Received Text Message

                        } else {

                            //Could be either text or URL:
                            if(filter_var($im['message']['text'], FILTER_VALIDATE_URL)){
                                //The message is a URL:
                                $matching_types = array(
                                    6683 /* Send Text */ ,
                                    6682 /* Send URL */,
                                    6679 /* Send Video */,
                                    6680 /* Send Audio */,
                                    6678 /* Send Image */,
                                    6681 /* Send Document */,
                                    7637 /* ATTACHMENT */
                                );
                            } else {
                                $matching_types = array(
                                    6683 /* Send Text */
                                );
                            }
                            $ln_data['ln_type_play_id'] = 4547; //User Sent Text Message

                        }

                    } elseif (isset($im['message']['attachments'])) {

                        //We have some attachments, lets loops through them:
                        foreach ($im['message']['attachments'] as $att) {

                            //Define 4 main Attachment Message Types:
                            $att_media_types = array( //Converts video, audio, image and file messages
                                'video' => array(
                                    'sent' => 4553,     //Link type for when sent to Users via Messenger
                                    'received' => 4548, //Link type for when received from Users via Messenger
                                    'matching_types' => array(
                                        7637 /* Send Multimedia */ ,
                                        6679 /* Send Video */
                                    ),
                                ),
                                'audio' => array(
                                    'sent' => 4554,
                                    'received' => 4549,
                                    'matching_types' => array(
                                        7637 /* Send Multimedia */ ,
                                        6680 /* Send Audio */
                                    ),
                                ),
                                'image' => array(
                                    'sent' => 4555,
                                    'received' => 4550,
                                    'matching_types' => array(
                                        7637 /* Send Multimedia */ ,
                                        6678 /* Send Image */
                                    ),
                                ),
                                'file' => array(
                                    'sent' => 4556,
                                    'received' => 4551,
                                    'matching_types' => array(
                                        7637 /* Send Multimedia */ ,
                                        6681 /* Send Document */
                                    ),
                                ),
                            );

                            if (array_key_exists($att['type'], $att_media_types)) {

                                /*
                                 *
                                 * This is a media attachment.
                                 *
                                 * We cannot save this Media on-demand because it takes
                                 * a few seconds depending on the file size which would
                                 * delay our response long-enough that Facebook thinks
                                 * our server is none-responsive which would cause
                                 * Facebook to resent this Attachment!
                                 *
                                 * */

                                $ln_data['ln_type_play_id'] = $att_media_types[$att['type']][($sent_by_mench ? 'sent' : 'received')];
                                $ln_data['ln_content'] = $att['payload']['url']; //Media Attachment Temporary Facebook URL
                                $ln_data['ln_status_play_id'] = 6175; //Link Drafting, since URL needs to be uploaded to Mench CDN via cron__save_chat_media()
                                if(!$sent_by_mench){
                                    $matching_types = $att_media_types[$att['type']]['matching_types'];
                                }

                            } elseif ($att['type'] == 'location') {

                                //Location Message Received:
                                $ln_data['ln_type_play_id'] = 4557;

                                /*
                                 *
                                 * We do not have the ability to send this
                                 * type of message at this time and we will
                                 * only receive it if the User decides to
                                 * send us their location for some reason.
                                 *
                                 * Message with location attachment which
                                 * could have up to 4 main elements:
                                 *
                                 * */

                                //Generate a URL from this location data:
                                if (isset($att['url']) && strlen($att['url']) > 0) {
                                    //Sometimes Facebook Might provide a full URL:
                                    $ln_data['ln_content'] = $att['url'];
                                } else {
                                    //If not, we can generate our own URL using the Lat/Lng that will always be provided:
                                    $ln_data['ln_content'] = 'https://www.google.com/maps?q=' . $att['payload']['coordinates']['lat'] . '+' . $att['payload']['coordinates']['long'];
                                }

                            } elseif ($att['type'] == 'template') {

                                /*
                                 *
                                 * Message with template attachment, like a
                                 * button or something...
                                 *
                                 * Will have value $att['payload']['template_type'];
                                 *
                                 * TODO implement later on maybe? Not sure how this is useful...
                                 *
                                 * */

                                $this->READ_model->ln_create(array(
                                    'ln_content' => 'api_webhook() received a message type that is not yet implemented: ['.$att['type'].']',
                                    'ln_type_play_id' => 4246, //Platform Bug Reports
                                    'ln_owner_play_id' => $en['en_id'],
                                    'ln_metadata' => array(
                                        'ln_data' => $ln_data,
                                        'ln_metadata' => $ln_metadata,
                                    ),
                                ));

                            } elseif ($att['type'] == 'fallback') {

                                /*
                                 *
                                 * A fallback attachment is any attachment
                                 * not currently recognized or supported
                                 * by the Message Echo feature.
                                 *
                                 * We can ignore them for now :)
                                 * TODO implement later on maybe? Not sure how this is useful...
                                 *
                                 * */

                                $this->READ_model->ln_create(array(
                                    'ln_content' => 'api_webhook() received a message type that is not yet implemented: ['.$att['type'].']',
                                    'ln_type_play_id' => 4246, //Platform Bug Reports
                                    'ln_owner_play_id' => $en['en_id'],
                                    'ln_metadata' => array(
                                        'ln_data' => $ln_data,
                                        'ln_metadata' => $ln_metadata,
                                    ),
                                ));

                            }
                        }
                    }


                    //So did we recognized the
                    if (!isset($ln_data['ln_type_play_id']) || !isset($ln_data['ln_owner_play_id'])) {

                        //Ooooopsi, this seems to be an unknown message type:
                        $this->READ_model->ln_create(array(
                            'ln_type_play_id' => 4246, //Platform Bug Reports
                            'ln_owner_play_id' => $en['en_id'],
                            'ln_content' => 'facebook_webhook() Received unknown message type! Analyze metadata for more details',
                            'ln_metadata' => $ln_metadata,
                        ));

                        //Terminate:
                        return print_r('unknown message type');
                    }


                    //We're all good, log this message:
                    $new_message = $this->READ_model->ln_create($ln_data);


                    //Did we have a pending response?
                    if(isset($new_message['ln_id']) && count($matching_types) > 0){

                        $pending_matches = array();
                        $pending_mismatches = array();

                        //TODO Yes, see if we have a pending blog that requires answer 6144:

                        //Did we find any matching or mismatching requirement submissions?
                        if(count($pending_matches) > 0 && 0){

                            //We have some matches, focus on this:
                            $first_chioce = $pending_matches[0];

                            //Accept their answer:

                            //Validate 🔴 READING LIST read:
                            $pending_req_submission = $this->READ_model->ln_fetch(array(
                                'ln_id' => $first_chioce['ln_id'],
                                //Also validate other requirements:
                                'ln_type_play_id' => 6144, //🔴 READING LIST Submit Requirements
                                'ln_owner_play_id' => $en['en_id'], //for this user
                                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
                                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
                            ), array('in_parent'));


                            if(isset($pending_req_submission[0])){

                                //Make changes:

                                //TODO RREMOVE THIS LOGIC AND CREATE A NEW LINK WHEN ADDED

                                $this->READ_model->ln_update($pending_req_submission[0]['ln_id'], array(
                                    'ln_content' => $new_message['ln_content'],
                                    'ln_status_play_id' => 6176, //Link Published
                                    'ln_parent_read_id' => $new_message['ln_id'],
                                ));

                                //Process on-complete automations:
                                $this->READ_model->read__completion_recursive_up($en['en_id'], $pending_req_submission[0]);

                            } else {

                                //Opppsi:
                                $this->READ_model->ln_create(array(
                                    'ln_parent_read_id' => $first_chioce['ln_id'],
                                    'ln_content' => 'messenger_webhook() failed to validate user response original step',
                                    'ln_type_play_id' => 4246, //Platform Bug Reports
                                    'ln_owner_play_id' => $en['en_id'], //for this user
                                ));

                                //Confirm with user:
                                $this->READ_model->dispatch_message(
                                    'Unable to accept your response. My trainers have already been notified.',
                                    $en,
                                    true
                                );
                            }



                            //Load next option:
                            $this->READ_model->read_next_go($en['en_id'], true, true);


                        } elseif(count($pending_mismatches) > 0){

                            //Only focus on the first mismatch, ignore the rest if any!
                            $mismatch_focus = $pending_mismatches[0];

                            $en_all_7585 = $this->config->item('en_all_7585');

                            //We did not have any matches, but has some mismatches, maybe that's what they meant?
                            $this->READ_model->dispatch_message(
                                'Note: You should '.$en_all_7585[$mismatch_focus['in_type_play_id']]['m_name'].' to complete this step.',
                                $en,
                                true
                            );

                        } elseif($ln_data['ln_type_play_id']==4547){

                            //No requirement submissions for this text message... Digest text message & try to make sense of it:
                            $this->READ_model->digest_received_text($en, $im['message']['text']);

                        } else {

                            //Let them know that we did not understand them:
                            $this->READ_model->dispatch_message(
                                echo_random_message('one_way_only'),
                                $en,
                                true,
                                array(
                                    array(
                                        'content_type' => 'text',
                                        'title' => 'Next',
                                        'payload' => 'GONEXT_',
                                    )
                                )
                            );

                        }
                    }


                } elseif (isset($im['referral']) || isset($im['postback'])) {

                    /*
                     * Simple difference:
                     *
                     * Handle the messaging_postbacks event for new conversations
                     * Handle the messaging_referrals event for existing conversations
                     *
                     * */

                    //Messenger Referral OR Postback
                    $ln_type_play_id = (isset($im['delivery']) ? 4267 /* Messenger Referral */ : 4268 /* Messenger Postback */);

                    //Extract more insights:
                    if (isset($im['postback'])) {

                        //The payload field passed is defined in the above places.
                        $payload = $im['postback']['payload']; //Maybe do something with this later?

                        if (isset($im['postback']['referral']) && count($im['postback']['referral']) > 0) {

                            $array_ref = $im['postback']['referral'];

                        } elseif ($payload == 'GET_STARTED') {

                            //The very first payload, set to null:
                            $array_ref = null;

                        } else {

                            //Postback without referral, again set to null:
                            $array_ref = null;

                        }

                    } elseif (isset($im['referral'])) {

                        $array_ref = $im['referral'];

                    }

                    //Did we have a ref from Messenger?
                    $quick_reply_payload = ($array_ref && isset($array_ref['ref']) && strlen($array_ref['ref']) > 0 ? $array_ref['ref'] : null);

                    //Authenticate User:
                    $en = $this->PLAY_model->en_messenger_auth($im['sender']['id'], $quick_reply_payload);

                    //Log primary link:
                    $this->READ_model->ln_create(array(
                        'ln_type_play_id' => $ln_type_play_id,
                        'ln_metadata' => $ln_metadata,
                        'ln_content' => $quick_reply_payload,
                        'ln_owner_play_id' => $en['en_id'],
                    ));

                    //Digest quick reply Payload if any:
                    if ($quick_reply_payload) {
                        $quick_reply_results = $this->READ_model->digest_received_payload($en, $quick_reply_payload);
                        if(!$quick_reply_results['status']){
                            //There was an error, inform Trainer:
                            $this->READ_model->ln_create(array(
                                'ln_content' => 'digest_received_payload() for postback/referral returned error ['.$quick_reply_results['message'].']',
                                'ln_metadata' => $ln_metadata,
                                'ln_type_play_id' => 4246, //Platform Bug Reports
                                'ln_owner_play_id' => $en['en_id'],
                            ));

                        }
                    }

                    /*
                     *
                     * We are currently not using any of the following information...
                     *
                    if($quick_reply_payload){
                        //We have referrer data, see what this is all about!
                        //We expect an integer which is the challenge ID
                        $ref_source = $array_ref['source'];
                        $ref_type = $array_ref['type'];
                        $ad_id = ( isset($array_ref['ad_id']) ? $array_ref['ad_id'] : null ); //Only IF user comes from the Ad

                        //Optional actions that may need to be taken on SOURCE:
                        if(strtoupper($ref_source)=='ADS' && $ad_id){
                            //Ad clicks
                        } elseif(strtoupper($ref_source)=='SHORTLINK'){
                            //Came from m.me short link click
                        } elseif(strtoupper($ref_source)=='MESSENGER_CODE'){
                            //Came from m.me short link click
                        } elseif(strtoupper($ref_source)=='DISCOVER_TAB'){
                            //Came from m.me short link click
                        }
                    }
                    */

                } elseif (isset($im['optin'])) {

                    $en = $this->PLAY_model->en_messenger_auth($im['sender']['id']);

                    //Log link:
                    $this->READ_model->ln_create(array(
                        'ln_metadata' => $ln_metadata,
                        'ln_type_play_id' => 4266, //Messenger Optin
                        'ln_owner_play_id' => $en['en_id'],
                    ));

                } elseif (isset($im['message_request']) && $im['message_request'] == 'accept') {

                    //This is when we message them and they accept to chat because they had Removed Messenger or something...
                    $en = $this->PLAY_model->en_messenger_auth($im['sender']['id']);

                    //Log link:
                    $this->READ_model->ln_create(array(
                        'ln_metadata' => $ln_metadata,
                        'ln_type_play_id' => 4577, //Message Request Accepted
                        'ln_owner_play_id' => $en['en_id'],
                    ));

                } else {

                    //This should really not happen!
                    $this->READ_model->ln_create(array(
                        'ln_content' => 'facebook_webhook() received unrecognized webhook call',
                        'ln_metadata' => $ln_metadata,
                        'ln_type_play_id' => 4246, //Platform Bug Reports
                    ));

                }
            }
        }

        return print_r('success');
    }





    function cron__save_chat_media()
    {

        /*
         *
         * Stores these media in Mench CDN:
         *
         * 1) Media received from users
         * 2) Media sent from Mench Trainers via Facebook Chat Inbox
         *
         * Note: It would not store media that is sent from blog
         * notes since those are already stored.
         *
         * */

        $ln_pending = $this->READ_model->ln_fetch(array(
            'ln_status_play_id' => 6175, //Link Drafting
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6102')) . ')' => null, //User Sent/Received Media Links
        ), array(), 10);

        $counter = 0;
        foreach ($ln_pending as $ln) {

            //Store to CDN:
            $cdn_status = upload_to_cdn($ln['ln_content'], $ln['ln_owner_play_id'], $ln);
            if(!$cdn_status['status']){
                continue;
            }

            //Update link:
            $this->READ_model->ln_update($ln['ln_id'], array(
                'ln_content' => $cdn_status['cdn_url'], //CDN URL
                'ln_child_play_id' => $cdn_status['cdn_en']['en_id'], //New URL Player
                'ln_status_play_id' => 6176, //Link Published
            ), $ln['ln_owner_play_id'], 10690 /* User Media Uploaded */);

            //Increase counter:
            $counter++;
        }

        //Echo message for cron job:
        echo $counter . ' message media files saved to Mench CDN';

    }



    function cron__sync_attachments()
    {

        /*
         *
         * Messenger has a feature that allows us to cache
         * media files in their servers so we can deliver
         * them instantly without a need to re-upload them
         * every time we want to send them to a user.
         *
         */


        $en_all_11059 = $this->config->item('en_all_11059');

        $success_count = 0; //Track success
        $ln_metadata = array();


        //Let's fetch all Media files without a Facebook attachment ID:
        $ln_pending = $this->READ_model->ln_fetch(array(
            'ln_type_play_id IN (' . join(',', array_keys($en_all_11059)) . ')' => null,
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_metadata' => null, //Missing Facebook Attachment ID [NOTE: Must make sure ln_metadata is not used for anything else for these link types]
        ), array(), 10, 0, array('ln_id' => 'ASC')); //Sort by oldest added first


        //Put something in the ln_metadata so other cron jobs do not pick up on it:
        foreach ($ln_pending as $ln) {
            update_metadata('ln', $ln['ln_id'], array(
                'fb_att_id' => 0,
            ));
        }

        foreach ($ln_pending as $ln) {

            //To be set to true soon (hopefully):
            $db_result = false;

            //Payload to save attachment:
            $payload = array(
                'message' => array(
                    'attachment' => array(
                        'type' => $en_all_11059[$ln['ln_type_play_id']]['m_desc'],
                        'payload' => array(
                            'is_reusable' => true,
                            'url' => $ln['ln_content'], //The URL to the media file
                        ),
                    ),
                )
            );

            //Attempt to sync Media to Facebook:
            $result = $this->READ_model->facebook_graph('POST', '/me/message_attachments', $payload);

            if (isset($result['ln_metadata']['result']['attachment_id']) && $result['status']) {

                //Save Facebook Attachment ID to DB:
                $db_result = update_metadata('ln', $ln['ln_id'], array(
                    'fb_att_id' => intval($result['ln_metadata']['result']['attachment_id']),
                ));

            }

            //Did it go well?
            if ($db_result) {

                $success_count++;

            } else {

                //Log error:
                $this->READ_model->ln_create(array(
                    'ln_type_play_id' => 4246, //Platform Bug Reports
                    'ln_parent_read_id' => $ln['ln_id'],
                    'ln_content' => 'cron__sync_attachments() Failed to sync attachment to Facebook API: ' . (isset($result['ln_metadata']['result']['error']['message']) ? $result['ln_metadata']['result']['error']['message'] : 'Unknown Error'),
                    'ln_metadata' => array(
                        'payload' => $payload,
                        'result' => $result,
                        'ln' => $ln,
                    ),
                ));

            }

            //Save stats:
            array_push($ln_metadata, array(
                'payload' => $payload,
                'fb_result' => $result,
            ));

        }

        //Echo message:
        echo_json(array(
            'status' => ($success_count == count($ln_pending) && $success_count > 0 ? 1 : 0),
            'message' => $success_count . '/' . count($ln_pending) . ' synced using Facebook Attachment API',
            'ln_metadata' => $ln_metadata,
        ));

    }

}