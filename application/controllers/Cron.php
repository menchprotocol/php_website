<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*

# ACTIVE:
* * * * *       /usr/bin/php /var/www/platform/index.php cron cron__7275  # Common Base
10 * * * *      /usr/bin/php /var/www/platform/index.php cron cron__7276  # Extra Insights
20 * * * *      /usr/bin/php /var/www/platform/index.php cron cron__12523 # Icon Sync
30 * * * *      /usr/bin/php /var/www/platform/index.php cron cron__12569 # Weight Sync
01 7 * * 1      /usr/bin/php /var/www/platform/index.php cron cron__12114 # Growth Report Email
40 3 * * *      /usr/bin/php /var/www/platform/index.php cron cron__7278  # Gephi Sync
50 6 * * *      /usr/bin/php /var/www/platform/index.php cron cron__7277  # Metadata Cleanup

# INACTIVE:
# 45 1 19 * *   /usr/bin/php /var/www/platform/index.php cron cron__7279  # Algolia Search SYNC
# * * * * *     /usr/bin/php /var/www/platform/index.php cron cron__7281  # Messenger Chat Media
# 0 * * * *     /usr/bin/php /var/www/platform/index.php cron cron__7282  # Messenger Sync Attachments

*/

class Cron extends CI_Controller
{

    var $is_player_request;
    var $session_en;

    function __construct()
    {

        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));

        boost_power();

        //Running from browser? If so, authenticate:
        $this->is_player_request = isset($_ENV['SSH_CLIENT']);
        if($this->is_player_request){
            $this->session_en = superpower_assigned(12728, true);
        }

    }

    function index()
    {
        //List Crons:
        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
        $this->load->view('header', array(
            'title' => $en_all_11035[7274]['m_name'],
        ));
        $this->load->view('source/source_crons');
        $this->load->view('footer');

    }

    function cron__12569($obj = null /* Can be in or en */){

        //Update object weight

        $stats = array(
            'start_time' => time(),
            'in_scanned' => 0,
            'in_updated' => 0,
            'in_total_weights' => 0,
            'en_scanned' => 0,
            'en_updated' => 0,
        );

        if(!$obj || $obj=='in'){

            //Update the weights for ideas and sources
            foreach($this->IDEA_model->in_fetch(array(
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
            )) as $in) {
                $stats['in_scanned']++;
                $stats['in_updated'] += in_weight_updater($in);
            }

            //Now addup weights starting from primary Idea:
            $stats['in_total_weights'] = $this->IDEA_model->in_weight(config_var(12156));

        }


        if(!$obj || $obj=='en'){
            //Update the weights for ideas and sources
            foreach($this->SOURCE_model->en_fetch(array(
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
            )) as $en) {
                $stats['en_scanned']++;
                $stats['en_updated'] += en_weight_updater($en);
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




    function cron__12114(){

        //Calculates the weekly coins issued:
        $last_week_start_timestamp = mktime(0, 0, 0, date("n"), date("j")-7, date("Y"));
        $last_week_end_timestamp = mktime(23, 59, 59, date("n"), date("j")-1, date("Y"));

        $last_week_start = date("Y-m-d H:i:s", $last_week_start_timestamp);
        $last_week_end = date("Y-m-d H:i:s", $last_week_end_timestamp);

        //IDEA
        $idea_coins_new_last_week = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $idea_coins_last_week = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $idea_coins_growth_rate = format_percentage(($idea_coins_last_week[0]['totals'] / ( $idea_coins_last_week[0]['totals'] - $idea_coins_new_last_week[0]['totals'] ) * 100) - 100);


        //DISCOVER
        $discover_coins_new_last_week = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //DISCOVER COIN
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $discover_coins_last_week = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //DISCOVER COIN
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $discover_coins_growth_rate = format_percentage(( $discover_coins_last_week[0]['totals'] / ( $discover_coins_last_week[0]['totals'] - $discover_coins_new_last_week[0]['totals'] ) * 100)-100);



        //SOURCE
        $source_coins_new_last_week = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //SOURCE COIN
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $source_coins_last_week = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12274')) . ')' => null, //SOURCE COIN
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $source_coins_growth_rate = format_percentage( ($source_coins_last_week[0]['totals'] / ( $source_coins_last_week[0]['totals'] - $source_coins_new_last_week[0]['totals'] ) * 100)-100);


        //ledger
        $ledger_transactions_new_last_week = $this->LEDGER_model->ln_fetch(array(
            'ln_timestamp >=' => $last_week_start,
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $ledger_transactions_last_week = $this->LEDGER_model->ln_fetch(array(
            'ln_timestamp <=' => $last_week_end,
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        $ledger_transactions_growth_rate = format_percentage(($ledger_transactions_last_week[0]['totals'] / ( $ledger_transactions_last_week[0]['totals'] - $ledger_transactions_new_last_week[0]['totals'] ) * 100)-100);



        //Email Subject
        $subject = 'MENCH 🟡 IDEAS '.( $idea_coins_growth_rate > 0 ? '+' : ( $idea_coins_growth_rate < 0 ? '-' : '' ) ).$idea_coins_growth_rate.'% for the week of '.date("M jS", $last_week_start_timestamp);

        //Email Body
        $html_message = '<br />';
        $html_message .= '<div>Growth report from '.date("l F jS G:i:s", $last_week_start_timestamp).' to '.date("l F jS G:i:s", $last_week_end_timestamp).' '.config_var(11079).':</div>';
        $html_message .= '<br />';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🟡</b><b style="min-width:55px; display: inline-block;">'.( $idea_coins_growth_rate >= 0 ? '+' : '-' ).$idea_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($idea_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.echo_number($idea_coins_last_week[0]['totals']).'</span>)</span><a href="https://mench.com/idea" target="_blank" style="color: #ffc500; font-weight:bold; text-decoration:none;">IDEA &raquo;</a></div>';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔴</b><b style="min-width:55px; display: inline-block;">'.( $discover_coins_growth_rate >= 0 ? '+' : '-' ).$discover_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($discover_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.echo_number($discover_coins_last_week[0]['totals']).'</span>)</span><a href="https://mench.com" target="_blank" style="color: #FC1B44; font-weight:bold; text-decoration:none;">DISCOVER &raquo;</a></div>';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">🔵</b><b style="min-width:55px; display: inline-block;">'.( $source_coins_growth_rate >= 0 ? '+' : '-' ).$source_coins_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($source_coins_last_week[0]['totals'], 0).' Coins" style="border-bottom:1px dotted #999999;">'.echo_number($source_coins_last_week[0]['totals']).'</span>)</span><a href="https://mench.com/source" target="_blank" style="color: #007AFD; font-weight:bold; text-decoration:none;">SOURCE &raquo;</a></div>';

        $html_message .= '<div style="padding-bottom:10px;"><b style="min-width:30px; text-align: center; display: inline-block;">📖</b><b style="min-width:55px; display: inline-block;">'.( $ledger_transactions_growth_rate >= 0 ? '+' : '-' ).$ledger_transactions_growth_rate.'%</b><span style="min-width:55px; display: inline-block;">(<span title="'.number_format($ledger_transactions_last_week[0]['totals'], 0).' Transactions" style="border-bottom:1px dotted #999999;">'.echo_number($ledger_transactions_last_week[0]['totals']).'</span>)</span><a href="https://mench.com/ledger" target="_blank" style="color: #000000; font-weight:bold; text-decoration:none;">LEDGER &raquo;</a></div>';


        $html_message .= '<br />';
        $html_message .= '<div>'.echo_platform_message(12691).'</div>';
        $html_message .= '<div>MENCH</div>';

        $subscriber_filters = array(
            'ln_profile_source_id' => 12114,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Transactions
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
        );

        //Should we limit the scope?
        if($this->is_player_request){
            $subscriber_filters['ln_portfolio_source_id'] = $this->session_en['en_id'];
        }


        $email_recipients = 0;
        //Send email to all subscribers:
        foreach($this->LEDGER_model->ln_fetch($subscriber_filters, array('en_portfolio')) as $subscribed_player){
            //Try fetching subscribers email:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id' => 4255, //Text
                'ln_profile_source_id' => 3288, //Mench Email
                'ln_portfolio_source_id' => $subscribed_player['en_id'],
            )) as $en_email){
                if(filter_var($en_email['ln_content'], FILTER_VALIDATE_EMAIL)){
                    //Send Email
                    $this->COMMUNICATION_model->comm_email_send(array($en_email['ln_content']), $subject, '<div>Hi '.one_two_explode('',' ',$subscribed_player['en_name']).' 👋</div>'.$html_message);
                    $email_recipients++;
                }
            }
        }

        echo 'Sent '.$email_recipients.' Emails';

    }





    function cron__7275($in_id = 0)
    {

        /*
         *
         * Updates common base metadata for published ideas
         *
         * */

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
            $this->session->set_flashdata('flash_message', $success_message);
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


    function cron__7276($in_id = 0)
    {

        /*
         *
         * Updates idea insights (like min/max ideas, time & cost)
         * based on its common and expansion idea.
         *
         * */



        $in_id = ( $in_id>0 ? $in_id : config_var(12156) );

        //Increment count by 1:

        //Start with common base:
        foreach($this->IDEA_model->in_fetch(array('in_id' => $in_id)) as $published_in){
            $this->IDEA_model->in_metadata_common_base($published_in);
        }

        //Update extra insights:
        $idea = $this->IDEA_model->in_metadata_source_insights($in_id);

        $success_message = 'Extra Insights Metadata updated.';

        //Show json:
        echo_json(array(
            'message' => $success_message,
        ));

    }






    function cron__7279($input_obj_type = null, $input_obj_id = null){

        if(!intval(config_var(12678))){
            die('Algolia is currently disabled');
        }

        //Call the update function and passon possible values:
        echo_json(update_algolia($input_obj_type, $input_obj_id));
    }


    function cron__7278(){

        /*
         *
         * Populates the nodes and edges table for
         * Gephi https://gephi.org network visualizer
         *
         * */

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
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
                'ln_previous_idea_id' => $in['in_id'],
            ), array('in_next'), 0, 0) as $child_in){

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
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_profile_source_id' => $en['en_id'],
            ), array('en_portfolio'), 0, 0) as $en_child){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$en_child['ln_profile_source_id'],
                    'target' => $id_prefix['en'].$en_child['ln_portfolio_source_id'],
                    'label' => $en_all_4593[$en_child['ln_type_source_id']]['m_name'].': '.$en_child['ln_content'],
                    'weight' => 1,
                    'edge_type_en_id' => $en_child['ln_type_source_id'],
                    'edge_status' => $en_child['ln_status_source_id'],
                ));

            }
        }

        //Add messages:
        $messages = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Idea Notes
        ), array('in_next'), 0, 0);
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
            if ($message['ln_profile_source_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$message['ln_profile_source_id'],
                    'target' => $message['ln_id'],
                    'label' => 'Parent Source',
                    'weight' => 1,
                ));
            }

        }

        echo count($ins).' ideas & '.count($ens).' sources & '.count($messages).' messages synced.';
    }




    function cron__7277(){

        /*
         *
         * A function that would run through all
         * object metadata variables and delete
         * all variables that are not indexed
         * as part of Variables Names source @6232
         *
         * https://mench.com/source/6232
         *
         *
         * */


        //Fetch all valid variable names:
        $valid_variables = array();
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_profile_source_id' => 6232, //Variables Names
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
            'LENGTH(ln_content) > 0' => null,
        ), array('en_portfolio'), 0) as $var_name){
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
                    //Delete this:
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
                    //Delete this:
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
            //Did we have anything to delete? Report with system bug:
            $this->LEDGER_model->ln_create(array(
                'ln_content' => 'cron__7277() deleted '.count($invalid_variables).' unknown variables from idea/source metadatas. To prevent this from happening, register the variables via Variables Names @6232',
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_profile_source_id' => 6232, //Variables Names
                'ln_metadata' => $ln_metadata,
            ));
        }

        echo_json($ln_metadata);

    }





    function cron__7281()
    {

        /*
         *
         * Stores these media in Mench CDN:
         *
         * 1) Media received from users
         * 2) Media sent from Mench Players via Facebook Chat Inbox
         *
         * Alert: It would not store media that is sent from idea
         * ideas since those are previously stored.
         *
         * */

        $ln_pending = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id' => 6175, //Transaction Drafting
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
            $this->LEDGER_model->ln_update($ln['ln_id'], array(
                'ln_content' => $cdn_status['cdn_url'], //CDN URL
                'ln_portfolio_source_id' => $cdn_status['cdn_en']['en_id'], //New URL Player
                'ln_status_source_id' => 6176, //Transaction Published
            ), $ln['ln_creator_source_id'], 10690 /* User Media Uploaded */);

            //Increase counter:
            $counter++;
        }

        //Echo message for cron job:
        echo $counter . ' message media files saved to Mench CDN';

    }



    function cron__7282()
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
        $ln_pending = $this->LEDGER_model->ln_fetch(array(
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
            $result = $this->COMMUNICATION_model->comm_facebook_graph('POST', '/me/message_attachments', $payload);

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
                $this->LEDGER_model->ln_create(array(
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




    function cron__12523()
    {

        /*
         *
         * Cronjob to sync icons where granchildren of source 12523 will inherit their parent icon (child of 12523)
         *
         * */

        $updated = 0;
        foreach($this->config->item('en_all_12523') as $en_id => $m) {

            //Update All Child Icons that are not the same:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_profile_source_id' => $en_id,
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
                '(LENGTH(en_icon) < 1 OR en_icon IS NULL)' => null, //Missing Icon
            ), array('en_portfolio'), 0) as $en) {
                $updated++;
                $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_icon' => $m['m_icon'],
                ), true);
            }

        }

        echo $updated.' Icons updated across '.count($this->config->item('en_all_12523')).' sources.';

    }


}

