<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 *
 *

# ESSENTIALS:
* * * * *       /usr/bin/php /var/www/platform/index.php cron common_base
10 * * * *      /usr/bin/php /var/www/platform/index.php cron source_insights
20 * * * *      /usr/bin/php /var/www/platform/index.php cron icons
30 * * * *      /usr/bin/php /var/www/platform/index.php cron weights
01 7 * * 1      /usr/bin/php /var/www/platform/index.php cron report

# NICE-TO-HAVES:
40 3 * * *      /usr/bin/php /var/www/platform/index.php cron gephi
50 6 * * *      /usr/bin/php /var/www/platform/index.php cron metadatas

# INACTIVE:
# 45 1 19 * *     /usr/bin/php /var/www/platform/index.php cron algolia

 * */

class Cron extends CI_Controller
{


    function __construct()
    {

        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));

        boost_power();

    }




    function weights($obj = null){

        $stats = array(
            'start_time' => time(),
            'in_scanned' => 0,
            'in_updated' => 0,
            'in_weight' => 0,
            'en_scanned' => 0,
            'en_updated' => 0,
        );

        if(!$obj || $obj=='in'){

            //Update the weights for ideas and sources
            foreach($this->IDEA_model->in_fetch(array(
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
            )) as $in) {

                $stats['in_scanned']++;

                //Calculate the weight for this:
                $weight = in_weight_calculator($in);

                //Should we update?
                if($weight != $in['in_weight']){
                    $stats['in_updated']++;
                    $this->IDEA_model->in_update($in['in_id'], array(
                        'in_weight' => $weight,
                    ));
                }
            }

            //Now Update Main Idea:
            $stats['in_weight'] = $this->IDEA_model->in_weight(config_var(12156));

        }


        if(!$obj || $obj=='en'){
            //Update the weights for ideas and sources
            foreach($this->SOURCE_model->en_fetch(array(
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
            )) as $en) {

                $stats['en_scanned']++;

                //Calculate the weight for this:
                $weight = en_weight_calculator($en);

                //Should we update?
                if($weight != $en['en_weight']){
                    $stats['en_updated']++;
                    $this->SOURCE_model->en_update($en['en_id'], array(
                        'en_weight' => $weight,
                    ));
                }
            }
        }

        $stats['end_time'] = time();
        $stats['total_seconds'] = $stats['end_time'] - $stats['start_time'];
        $stats['total_items'] = $stats['en_scanned'] + $stats['in_scanned'];
        if($stats['total_seconds'] > 0){
            $stats['millisecond_speed'] = round(($stats['total_seconds'] / $stats['total_items'] * 1000), 3);
        }

        //Return results:
        echo_json($stats);

    }




    function report(){

        //Calculates the weekly coins issued:
        $last_week_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-7, date("Y"));
        $last_week_start = date("D M j G:i:s T Y", $last_week_start_timestamp);
        $last_week_end = date("D M j G:i:s T Y", mktime(23, 59, 59, date("n"), date("j")-1, date("Y")));

        //IDEA
        $idea_coins_new_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id' => 4250, //UNIQUE IDEAS
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $idea_coins_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id' => 4250, //UNIQUE IDEAS
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $idea_coins_growth_rate = format_percentage(($idea_coins_last_week[0]['totals'] / ( $idea_coins_last_week[0]['totals'] - $idea_coins_new_last_week[0]['totals'] ) * 100) - 100);


        //READ
        $read_coins_new_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $read_coins_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $read_coins_growth_rate = format_percentage(( $read_coins_last_week[0]['totals'] / ( $read_coins_last_week[0]['totals'] - $read_coins_new_last_week[0]['totals'] ) * 100)-100);



        //SOURCE
        $source_coins_new_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //SOURCE COIN
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $source_coins_last_week = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //SOURCE COIN
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $source_coins_growth_rate = format_percentage( ($source_coins_last_week[0]['totals'] / ( $source_coins_last_week[0]['totals'] - $source_coins_new_last_week[0]['totals'] ) * 100)-100);


        //ledger
        $ledger_transactions_new_last_week = $this->READ_model->ln_fetch(array(
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $ledger_transactions_last_week = $this->READ_model->ln_fetch(array(
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $ledger_transactions_growth_rate = format_percentage(($ledger_transactions_last_week[0]['totals'] / ( $ledger_transactions_last_week[0]['totals'] - $ledger_transactions_new_last_week[0]['totals'] ) * 100)-100);



        //Email Subject
        $subject = 'MENCH 🟡'.( $idea_coins_growth_rate > 0 ? '+' : ( $idea_coins_growth_rate < 0 ? '-' : '' ) ).$idea_coins_growth_rate.'% for the week of '.date("M jS", $last_week_start_timestamp);

        //Email Body
        $html_message = '<br />';
        $html_message .= '<div>Growth rates from '.$last_week_start.' to '.$last_week_end.' '.config_var(11079).':</div>';
        $html_message .= '<br />';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">📖</b><b style="min-width:55px; display: inline-block;">'.( $ledger_transactions_growth_rate >= 0 ? '+' : '-' ).$ledger_transactions_growth_rate.'%</b>to <span style="min-width:47px; display: inline-block;"><span title="'.number_format($ledger_transactions_last_week[0]['totals'], 0).' Transactions" style="border-bottom:1px dotted #999999;">'.echo_number($ledger_transactions_last_week[0]['totals']).'</span></span><a href="https://mench.com/ledger" target="_blank" style="color: #000000; font-weight:bold; text-decoration:none;">TRANSACTIONS &raquo;</a></div>';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔵</b><b style="min-width:55px; display: inline-block;">'.( $source_coins_growth_rate >= 0 ? '+' : '-' ).$source_coins_growth_rate.'%</b>to <span style="min-width:47px; display: inline-block;"><span title="'.number_format($source_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.echo_number($source_coins_last_week[0]['totals']).'</span></span><a href="https://mench.com/source" target="_blank" style="color: #007AFD; font-weight:bold; text-decoration:none;">SOURCES &raquo;</a></div>';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔴</b><b style="min-width:55px; display: inline-block;">'.( $read_coins_growth_rate >= 0 ? '+' : '-' ).$read_coins_growth_rate.'%</b>to <span style="min-width:47px; display: inline-block;"><span title="'.number_format($read_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.echo_number($read_coins_last_week[0]['totals']).'</span></span><a href="https://mench.com" target="_blank" style="color: #FC1B44; font-weight:bold; text-decoration:none;">READS &raquo;</a></div>';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🟡</b><b style="min-width:55px; display: inline-block;">'.( $idea_coins_growth_rate >= 0 ? '+' : '-' ).$idea_coins_growth_rate.'%</b>to <span style="min-width:47px; display: inline-block;"><span title="'.number_format($idea_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.echo_number($idea_coins_last_week[0]['totals']).'</span></span><a href="https://mench.com/idea" target="_blank" style="color: #ffc500; font-weight:bold; text-decoration:none;">IDEAS &raquo;</a></div>';

        $html_message .= '<br /><br />';
        $html_message .= '<div>'.echo_random_message('email_yours_truly_line').'</div>';
        $html_message .= '<div>MENCH</div>';

        $subscriber_filters = array(
            'ln_parent_source_id' => 12114,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
        );

        //Should we limit the scope?
        if(isset($_GET['notify_source_id']) && intval($_GET['notify_source_id']) > 0){
            $subscriber_filters['ln_child_source_id'] = $_GET['notify_source_id'];
        } else {
            $session_en = superpower_assigned();
            if($session_en){
                $subscriber_filters['ln_child_source_id'] = $session_en['en_id'];
            }
        }


        $email_recipients = 0;
        //Send email to all subscribers:
        foreach($this->READ_model->ln_fetch($subscriber_filters, array('en_child')) as $subscribed_player){
            //Try fetching subscribers email:
            foreach($this->READ_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id' => 4255, //Linked Players Text (Email is text)
                'ln_parent_source_id' => 3288, //Mench Email
                'ln_child_source_id' => $subscribed_player['en_id'],
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





    function common_base($in_id = 0)
    {

        /*
         *
         * Updates common base metadata for published ideas
         *
         * */

        if($in_id < 0){
            //Gateway URL to give option to run...
            die('<a href="/cron/common_base">Click here</a> to start running this function.');
        }

        $start_time = time();
        $filters = array(
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        );
        if($in_id > 0){
            $filters['in_id'] = $in_id;
        }

        $published_ins = $this->IDEA_model->in_fetch($filters);
        foreach($published_ins as $published_in){
            $idea = $this->IDEA_model->in_metadata_common_base($published_in);
        }

        $total_time = time() - $start_time;
        $success_message = 'Common Base Metadata updated for '.count($published_ins).' published idea'.echo__s(count($published_ins)).'.';
        if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
            //Now redirect;
            $this->session->set_flashdata('flash_message', '<div class="alert alert-success" role="alert">' . $success_message . '</div>');
            header('Location: ' . $_GET['redirect']);
        } else {
            //Show json:
            echo_json(array(
                'message' => $success_message,
                'total_time' => echo_time_minutes($total_time),
                'item_time' => round(($total_time/count($published_ins)),1).' Seconds',
                'last_item' => $idea,
            ));
        }
    }


    function source_insights($in_id = 0)
    {

        /*
         *
         * Updates idea insights (like min/max reads, time & cost)
         * based on its common and expansion idea.
         *
         * */


        if($in_id < 0){
            //Gateway URL to give option to run...
            die('<a href="/cron/source_insights">Click here</a> to start running this function.');
        }

        $start_time = time();
        $update_count = 0;

        if($in_id > 0){

            //Increment count by 1:
            $update_count++;

            //Start with common base:
            foreach($this->IDEA_model->in_fetch(array('in_id' => $in_id)) as $published_in){
                $this->IDEA_model->in_metadata_common_base($published_in);
            }

            //Update extra insights:
            $idea = $this->IDEA_model->in_metadata_source_insights($in_id);

        } else {

            //Update all Recommended Ideas and their idea:
            foreach ($this->IDEA_model->in_fetch(array(
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
            )) as $published_in) {
                $idea = $this->IDEA_model->in_metadata_source_insights($published_in['in_id']);
                if($idea){
                    $update_count++;
                }
            }

        }



        $end_time = time() - $start_time;
        $success_message = 'Extra Insights Metadata updated for '.$update_count.' idea'.echo__s($update_count).'.';

        //Show json:
        echo_json(array(
            'message' => $success_message,
            'total_time' => echo_time_minutes($end_time),
            'item_time' => round(($end_time/$update_count),1).' Seconds',
            'last_item' => $idea,
        ));
    }






    function algolia($input_obj_type = null, $input_obj_id = null){

        if(!intval(config_var(12678))){
            die('Algolia is currently disabled');
        }

        if($input_obj_type < 0){
            //Gateway URL to give option to run...
            die('<a href="/cron/algolia">Click here</a> to start running this function.');
        }

        //Call the update function and passon possible values:
        echo_json(update_algolia($input_obj_type, $input_obj_id));
    }


    function gephi($affirmation = null){

        /*
         *
         * Populates the nodes and edges table for
         * Gephi https://gephi.org network visualizer
         *
         * */

        if($affirmation < 0){
            //Gateway URL to give option to run...
            die('<a href="/cron/gephi">Click here</a> to start running this function.');
        }


        //Empty both tables:
        $this->db->query("TRUNCATE TABLE public.gephi_edges CONTINUE IDENTITY RESTRICT;");
        $this->db->query("TRUNCATE TABLE public.gephi_nodes CONTINUE IDENTITY RESTRICT;");

        //Load Idea-to-Idea Links:
        $en_all_4593 = $this->config->item('en_all_4593');

        //To make sure Idea/source IDs are unique:
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

        //Add Ideas:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
        ));
        foreach($ins as $in){

            //Prep metadata:
            $in_metadata = ( strlen($in['in_metadata']) > 0 ? unserialize($in['in_metadata']) : array());

            //Add Idea node:
            $this->db->insert('gephi_nodes', array(
                'id' => $id_prefix['in'].$in['in_id'],
                'label' => $in['in_title'],
                //'size' => ( isset($in_metadata['in__metadata_max_seconds']) ? round(($in_metadata['in__metadata_max_seconds']/3600),0) : 0 ), //Max time
                'size' => $node_size['in'],
                'node_type' => 1, //Idea
                'node_status' => $in['in_status_source_id'],
            ));

            //Fetch children:
            foreach($this->READ_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
                'ln_previous_idea_id' => $in['in_id'],
            ), array('in_child'), 0, 0) as $child_in){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['in'].$child_in['ln_previous_idea_id'],
                    'target' => $id_prefix['in'].$child_in['ln_next_idea_id'],
                    'label' => $en_all_4593[$child_in['ln_type_source_id']]['m_name'], //TODO maybe give visibility to condition here?
                    'weight' => 1,
                    'edge_type_en_id' => $child_in['ln_type_source_id'],
                    'edge_status' => $child_in['ln_status_source_id'],
                ));

            }
        }


        //Add sources:
        $ens = $this->SOURCE_model->en_fetch(array(
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
        ));
        foreach($ens as $en){

            //Add source node:
            $this->db->insert('gephi_nodes', array(
                'id' => $id_prefix['en'].$en['en_id'],
                'label' => $en['en_name'],
                'size' => $node_size['en'] ,
                'node_type' => 2, //Player
                'node_status' => $en['en_status_source_id'],
            ));

            //Fetch children:
            foreach($this->READ_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_parent_source_id' => $en['en_id'],
            ), array('en_child'), 0, 0) as $en_child){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$en_child['ln_parent_source_id'],
                    'target' => $id_prefix['en'].$en_child['ln_child_source_id'],
                    'label' => $en_all_4593[$en_child['ln_type_source_id']]['m_name'].': '.$en_child['ln_content'],
                    'weight' => 1,
                    'edge_type_en_id' => $en_child['ln_type_source_id'],
                    'edge_status' => $en_child['ln_status_source_id'],
                ));

            }
        }

        //Add messages:
        $messages = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Idea Pads
        ), array('in_child'), 0, 0);
        foreach($messages as $message) {

            //Add message node:
            $this->db->insert('gephi_nodes', array(
                'id' => $message['ln_id'],
                'label' => $en_all_4593[$message['ln_type_source_id']]['m_name'] . ': ' . $message['ln_content'],
                'size' => $node_size['msg'],
                'node_type' => $message['ln_type_source_id'], //Message type
                'node_status' => $message['ln_status_source_id'],
            ));

            //Add child idea link:
            $this->db->insert('gephi_edges', array(
                'source' => $message['ln_id'],
                'target' => $id_prefix['in'].$message['ln_next_idea_id'],
                'label' => 'Child Idea',
                'weight' => 1,
            ));

            //Add parent idea link?
            if ($message['ln_previous_idea_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['in'].$message['ln_previous_idea_id'],
                    'target' => $message['ln_id'],
                    'label' => 'Parent Idea',
                    'weight' => 1,
                ));
            }

            //Add parent source link?
            if ($message['ln_parent_source_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$message['ln_parent_source_id'],
                    'target' => $message['ln_id'],
                    'label' => 'Parent Source',
                    'weight' => 1,
                ));
            }

        }

        echo count($ins).' ideas & '.count($ens).' sources & '.count($messages).' messages synced.';
    }




    function metadatas($affirmation = null){

        /*
         *
         * A function that would run through all
         * object metadata variables and remove
         * all variables that are not indexed
         * as part of Variables Names source @6232
         *
         * https://mench.com/source/6232
         *
         *
         * */

        if($affirmation < 0){
            //Gateway URL to give option to run...
            die('<a href="/cron/metadatas">Click here</a> to start running this function.');
        }


        //Fetch all valid variable names:
        $valid_variables = array();
        foreach($this->READ_model->ln_fetch(array(
            'ln_parent_source_id' => 6232, //Variables Names
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
            'LENGTH(ln_content) > 0' => null,
        ), array('en_child'), 0) as $var_name){
            array_push($valid_variables, $var_name['ln_content']);
        }

        //Now let's start the cleanup process...
        $invalid_variables = array();

        //Idea Metadata
        foreach($this->IDEA_model->in_fetch(array()) as $in){

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
        foreach($this->SOURCE_model->en_fetch(array()) as $en){

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
                'ln_content' => 'metadatas() removed '.count($invalid_variables).' unknown variables from idea/source metadatas. To prevent this from happening, register the variables via Variables Names @6232',
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_parent_source_id' => 6232, //Variables Names
                'ln_metadata' => $ln_metadata,
            ));
        }

        echo_json($ln_metadata);

    }





    function save_chat_media()
    {

        /*
         *
         * Stores these media in Mench CDN:
         *
         * 1) Media received from users
         * 2) Media sent from Mench Trainers via Facebook Chat Inbox
         *
         * Alert: It would not store media that is sent from idea
         * ideas since those are already stored.
         *
         * */

        $ln_pending = $this->READ_model->ln_fetch(array(
            'ln_status_source_id' => 6175, //Link Drafting
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6102')) . ')' => null, //User Sent/Received Media Links
        ), array(), 10);

        $counter = 0;
        foreach ($ln_pending as $ln) {

            //Store to CDN:
            $cdn_status = upload_to_cdn($ln['ln_content'], $ln['ln_creator_source_id'], $ln);
            if(!$cdn_status['status']){
                continue;
            }

            //Update link:
            $this->READ_model->ln_update($ln['ln_id'], array(
                'ln_content' => $cdn_status['cdn_url'], //CDN URL
                'ln_child_source_id' => $cdn_status['cdn_en']['en_id'], //New URL Player
                'ln_status_source_id' => 6176, //Link Published
            ), $ln['ln_creator_source_id'], 10690 /* User Media Uploaded */);

            //Increase counter:
            $counter++;
        }

        //Echo message for cron job:
        echo $counter . ' message media files saved to Mench CDN';

    }



    function attachments()
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
            'ln_type_source_id IN (' . join(',', array_keys($en_all_11059)) . ')' => null,
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_metadata' => null, //Missing Facebook Attachment ID [Alert: Must make sure ln_metadata is not used for anything else for these link types]
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
                        'type' => $en_all_11059[$ln['ln_type_source_id']]['m_desc'],
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
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                    'ln_parent_transaction_id' => $ln['ln_id'],
                    'ln_content' => 'attachments() Failed to sync attachment to Facebook API: ' . (isset($result['ln_metadata']['result']['error']['message']) ? $result['ln_metadata']['result']['error']['message'] : 'Unknown Error'),
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




    function icons()
    {

        /*
         *
         * Cronjob to sync icons where granchildren of source 12523 will inherit their parent icon (child of 12523)
         *
         * */

        $updated = 0;
        foreach($this->config->item('en_all_12523') as $en_id => $m) {

            //Update All Child Icons that are not the same:
            foreach($this->READ_model->ln_fetch(array(
                'ln_parent_source_id' => $en_id,
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                '(LENGTH(en_icon) < 1 OR en_icon IS NULL)' => null, //Missing Icon
            ), array('en_child'), 0) as $en) {
                $updated++;
                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_icon' => $m['m_icon'],
                ), true);
            }

        }

        echo $updated.' Icons updated across '.count($this->config->item('en_all_12523')).' sources.';

    }





    function add_11158(){

        //A function that goes through Medium topics @11097 and fetches all the top Publishers @11158 within that topic
        $ln_creator_source_id = 1; //Shervin as Developer for logging all READS

        //Fetch URL:
        $medium_urls = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_parent_source_id' => 1326, //Domain Names
            'ln_child_source_id' => 3311, //Medium URL
        ));


        $topic_count = 0;
        foreach ($this->READ_model->ln_fetch(array(
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_parent_source_id' => 11097, //Medium Topic
        ), array('en_child')) as $medium_topic){

            $topic_count++;

            //Fetch this page:
            foreach ($this->READ_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id' => 4256, //URL
                'ln_parent_source_id' => 3311, //Medium Link
                'ln_child_source_id' => $medium_topic['en_id'],
            )) as $medium_topic_link){

                //Fetch Publishers within this topic:
                $url_content = @file_get_contents($medium_topic_link['ln_content']);

                if(!$url_content){
                    echo '<div>FAILED to fetch ['.$medium_topic_link['ln_content'].']</div>';
                    continue;
                }

                //Fetch UNIQUE source URLs:
                $unique_sources = array();
                foreach(explode('"/@', $url_content) as $index => $source_string){

                    if(!$index){
                        continue; //Do not check the first one
                    }

                    $source_url_path = one_two_explode('', '"', $source_string);

                    if(substr_count($source_url_path, '/')){
                        $source_handler = one_two_explode('', '/', $source_url_path);
                    } elseif(substr_count($source_url_path, '?')){
                        $source_handler = one_two_explode('', '?', $source_url_path);
                    } else {
                        $source_handler = $source_url_path;
                    }

                    if(!in_array($source_handler, $unique_sources)){
                        array_push($unique_sources, $source_handler);
                    }

                }

                //Now sync Sources in Database:
                $newly_added = 0;

                foreach($unique_sources as $source_handler){

                    $full_url = rtrim($medium_urls[0]['ln_content'], '/') . '/@' . $source_handler;

                    $already_added = $this->READ_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                        'ln_type_source_id' => 4256, //Generic URL
                        'ln_parent_source_id' => 3311, //Medium URL
                        'ln_content' => $full_url,
                    ));

                    //Add to DB IF not already there:
                    if(!count($already_added)){

                        $newly_added++;

                        //Create new Player:
                        $added_en = $this->SOURCE_model->en_verify_create($source_handler, $ln_creator_source_id, 6181, random_source_avatar());

                        //Create relevant READS:

                        $this->READ_model->ln_create(array(
                            'ln_type_source_id' => 4256, //Generic URL
                            'ln_creator_source_id' => $ln_creator_source_id,
                            'ln_parent_source_id' => 3311, //Medium URL
                            'ln_child_source_id' => $added_en['en']['en_id'],
                            'ln_content' => $full_url,
                        ));

                        $this->READ_model->ln_create(array(
                            'ln_type_source_id' => 4230, //Raw link
                            'ln_creator_source_id' => $ln_creator_source_id,
                            'ln_parent_source_id' => 1278, //People
                            'ln_child_source_id' => $added_en['en']['en_id'],
                        ));

                        $this->READ_model->ln_create(array(
                            'ln_type_source_id' => 4230, //Raw link
                            'ln_creator_source_id' => $ln_creator_source_id,
                            'ln_parent_source_id' => 11158, //Medium Publisher
                            'ln_child_source_id' => $added_en['en']['en_id'],
                        ));

                        //Medium Topic
                        $this->READ_model->ln_create(array(
                            'ln_type_source_id' => 4230, //Raw link
                            'ln_creator_source_id' => $ln_creator_source_id,
                            'ln_parent_source_id' => $medium_topic['en_id'],
                            'ln_child_source_id' => $added_en['en']['en_id'],
                        ));

                    }
                }

                //Count total Sources:
                echo '<div>'.$topic_count.') Added '.$newly_added.' Sources in ['.$medium_topic_link['ln_content'].'] from the full list ['.join(', ',$unique_sources).']</div>';

            }
        }
    }

    function bot(){

        $url = 'https://medium.com/_/graphql';
        $topic = 'books';
        $custom_header = array(
            'medium-frontend-app: lite/master-20191021-212205-4df9cf54be',
            'medium-frontend-route: topic',
            'sec-fetch-mode: cors',
            'sec-fetch-site: same-origin',
            'apollographql-client-name: lite',
            'apollographql-client-version: master-20191021-212205-4df9cf54be',

            'accept: */*',
            'accept-encoding: gzip, deflate, br',
            'accept-language: en-GB,en-US;q=0.9,en;q=0.8',
            'content-length: 6731',
            'graphql-operation: TopicHandler',
            'content-type: application/json',
            'origin: https://medium.com',
            'referer: https://medium.com/topic/'.$topic,

            'cookie: __cfduid=db6eef3c324dc50d96ef21938b6f00edc1559329362; _ga=GA1.2.2055208362.1565325511; lightstep_session_id=7e05aed248707e2e; lightstep_guid/medium-web=93bf19db9151b98a; tz=420; pr=2; lightstep_guid/lite-web=58f426d9268501ac; _gid=GA1.2.1980711924.1571614565; optimizelyEndUserId=lo_e8082354b03e; uid=lo_e8082354b03e; sid=1:/xbfZQ7E3E7EPoIIifaIKj/DmNhQCAKcI9h6hfo+EFoV1Vicr75acNYhuyD26dd9; __cfruid=119da54070c31f79db03af372b90931f3f9aa260-1571694671; _parsely_session={%22sid%22:38%2C%22surl%22:%22https://medium.com/%22%2C%22sref%22:%22%22%2C%22sts%22:1571694672029%2C%22slts%22:1571688510366}; _parsely_visitor={%22id%22:%22pid=acaaa24a25423adbd91231b52e769418%22%2C%22session_count%22:38%2C%22last_session_ts%22:1571694672029}; sz=1652',
        );

        if(0){
            if(!isset($_POST['custom_head'])){
                $_POST['custom_head'] = join("\n", $custom_header);
            }


            echo count(explode("\n", $_POST['custom_head']));

            ?>
            <form action="" method="post">
                <input type="submit" name="GO">
                <textarea style="width: 800px; height: 500px;" name="custom_head"><?= $_POST['custom_head'] ?></textarea>
            </form>
            <?php
        }




        $data = array(
            'operationName' => 'TopicHandler',
            'variables' => array(
                'feedPagingOptions' => array(
                    'limit' => 25,
                    'to' => '1571614526950',
                ),
                'sidebarPagingOptions' => array(
                    'limit' => 5,
                ),
                'topicSlug' => $topic,
            ),
            'query' => 'query TopicHandler($topicSlug: ID!, $feedPagingOptions: PagingOptions, $sidebarPagingOptions: PagingOptions) {
  topic(slug: $topicSlug) {
    canonicalSlug
    ...TopicScreen_topic
    __typename
  }
}

fragment PostListingItemFeed_postPreview on PostPreview {
  post {
    ...PostListingItemPreview_post
    ...PostListingItemByline_post
    ...PostListingItemImage_post
    ...PostPresentationTracker_post
    __typename
  }
  __typename
}

fragment PostListingItemPreview_post on Post {
  id
  mediumUrl
  title
  previewContent {
    subtitle
    isFullContent
    __typename
  }
  isPublished
  creator {
    id
    __typename
  }
  __typename
}

fragment PostListingItemByline_post on Post {
  id
  creator {
    id
    username
    name
    __typename
  }
  isLocked
  readingTime
  ...BookmarkButton_post
  firstPublishedAt
  statusForCollection
  collection {
    id
    name
    ...collectionUrl_collection
    __typename
  }
  __typename
}

fragment BookmarkButton_post on Post {
  ...SusiClickable_post
  ...WithSetReadingList_post
  __typename
}

fragment SusiClickable_post on Post {
  id
  mediumUrl
  ...SusiContainer_post
  __typename
}

fragment SusiContainer_post on Post {
  id
  __typename
}

fragment WithSetReadingList_post on Post {
  ...ReadingList_post
  __typename
}

fragment ReadingList_post on Post {
  id
  readingList
  __typename
}

fragment collectionUrl_collection on Collection {
  id
  domain
  slug
  __typename
}

fragment PostListingItemImage_post on Post {
  id
  mediumUrl
  previewImage {
    id
    focusPercentX
    focusPercentY
    __typename
  }
  __typename
}

fragment PostPresentationTracker_post on Post {
  id
  visibility
  previewContent {
    isFullContent
    __typename
  }
  collection {
    id
    __typename
  }
  __typename
}

fragment TopicScreen_topic on Topic {
  id
  ...TopicMetadata_topic
  ...TopicLandingHeader_topic
  ...TopicFeaturedAndLatest_topic
  ...TopicLandingRelatedTopics_topic
  ...TopicLandingPopular_posts
  __typename
}

fragment TopicMetadata_topic on Topic {
  name
  description
  image {
    id
    __typename
  }
  __typename
}

fragment TopicLandingHeader_topic on Topic {
  name
  description
  visibility
  ...TopicFollowButtonSignedIn_topic
  ...TopicFollowButtonSignedOut_topic
  __typename
}

fragment TopicFollowButtonSignedIn_topic on Topic {
  slug
  isFollowing
  __typename
}

fragment TopicFollowButtonSignedOut_topic on Topic {
  id
  slug
  ...SusiClickable_topic
  __typename
}

fragment SusiClickable_topic on Topic {
  ...SusiContainer_topic
  __typename
}

fragment SusiContainer_topic on Topic {
  ...SignInContainer_topic
  ...SignUpOptions_topic
  __typename
}

fragment SignInContainer_topic on Topic {
  ...SignInOptions_topic
  __typename
}

fragment SignInOptions_topic on Topic {
  id
  name
  __typename
}

fragment SignUpOptions_topic on Topic {
  id
  name
  __typename
}

fragment TopicFeaturedAndLatest_topic on Topic {
  featuredPosts {
    postPreviews {
      post {
        id
        ...TopicLandingFeaturedStory_post
        __typename
      }
      __typename
    }
    __typename
  }
  featuredTopicWriters(limit: 1) {
    ...FeaturedWriter_featuredTopicWriter
    __typename
  }
  latestPosts(paging: $feedPagingOptions) {
    postPreviews {
      post {
        id
        __typename
      }
      ...PostListingItemFeed_postPreview
      __typename
    }
    pagingInfo {
      next {
        limit
        to
        __typename
      }
      __typename
    }
    __typename
  }
  __typename
}

fragment TopicLandingFeaturedStory_post on Post {
  ...FeaturedPostPreview_post
  ...PostListingItemPreview_post
  ...PostListingItemBylineWithAvatar_post
  ...PostListingItemImage_post
  ...PostPresentationTracker_post
  __typename
}

fragment FeaturedPostPreview_post on Post {
  id
  title
  mediumUrl
  previewContent {
    subtitle
    isFullContent
    __typename
  }
  __typename
}

fragment PostListingItemBylineWithAvatar_post on Post {
  creator {
    username
    name
    id
    imageId
    mediumMemberAt
    __typename
  }
  isLocked
  readingTime
  updatedAt
  statusForCollection
  collection {
    id
    name
    ...collectionUrl_collection
    __typename
  }
  __typename
}

fragment FeaturedWriter_featuredTopicWriter on FeaturedTopicWriter {
  user {
    id
    username
    name
    bio
    ...UserAvatar_user
    ...UserFollowButton_user
    __typename
  }
  posts {
    postPreviews {
      ...PostListingItemFeaturedWriter_postPreview
      __typename
    }
    __typename
  }
  __typename
}

fragment UserAvatar_user on User {
  username
  id
  name
  imageId
  mediumMemberAt
  __typename
}

fragment UserFollowButton_user on User {
  ...UserFollowButtonSignedIn_user
  ...UserFollowButtonSignedOut_user
  __typename
}

fragment UserFollowButtonSignedIn_user on User {
  id
  isFollowing
  __typename
}

fragment UserFollowButtonSignedOut_user on User {
  id
  ...SusiClickable_user
  __typename
}

fragment SusiClickable_user on User {
  ...SusiContainer_user
  __typename
}

fragment SusiContainer_user on User {
  ...SignInContainer_user
  ...SignUpOptions_user
  __typename
}

fragment SignInContainer_user on User {
  ...SignInOptions_user
  __typename
}

fragment SignInOptions_user on User {
  id
  name
  __typename
}

fragment SignUpOptions_user on User {
  id
  name
  __typename
}

fragment PostListingItemFeaturedWriter_postPreview on PostPreview {
  postId
  post {
    readingTime
    id
    mediumUrl
    title
    ...PostListingItemImage_post
    ...PostPresentationTracker_post
    __typename
  }
  __typename
}

fragment TopicLandingRelatedTopics_topic on Topic {
  relatedTopics {
    topic {
      name
      slug
      __typename
    }
    __typename
  }
  __typename
}

fragment TopicLandingPopular_posts on Topic {
  name
  popularPosts(paging: $sidebarPagingOptions) {
    postPreviews {
      post {
        ...PostListingItemSidebar_post
        __typename
      }
      __typename
    }
    __typename
  }
  __typename
}

fragment PostListingItemSidebar_post on Post {
  id
  mediumUrl
  title
  readingTime
  ...PostListingItemImage_post
  ...PostPresentationTracker_post
  __typename
}',
        );


        for($i=0;$i<count($custom_header);$i++){

            echo $custom_header[$i].'<br />';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array($custom_header[$i]));
            $server_output = curl_exec ($ch);
            curl_close ($ch);

            echo '<div style="font-weight: bold; color:#FF0000;">'.$server_output.'</div>';
            echo '<hr />';

        }

    }




}
?>