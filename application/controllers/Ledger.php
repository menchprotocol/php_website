<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ledger extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }



    function index(){

        /*
         *
         * List all Links on reverse chronological order
         * and Display Status for ideas, sources and
         * links.
         *
         * */

        //Load header:
        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION

        $this->load->view('header', array(
            'title' => $en_all_11035[4341]['m_name'],
        ));
        $this->load->view('ledger/ledger_home');
        $this->load->view('footer');

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
        $query_offset = (($page_num-1)*config_var(11064));
        $session_en = superpower_assigned();

        $message = '';

        //Fetch links and total link counts:
        $lns = $this->READ_model->ln_fetch($filters, $join_by, config_var(11064), $query_offset);
        $lns_count = $this->READ_model->ln_fetch($filters, $join_by, 0, 0, array(), 'COUNT(ln_id) as total_count');
        $total_items_loaded = ($query_offset+count($lns));
        $has_more_links = ($lns_count[0]['total_count'] > 0 && $total_items_loaded < $lns_count[0]['total_count']);


        //Display filter:
        if($total_items_loaded > 0){
            $message .= '<div class="montserrat ledger-info"><span class="icon-block"><i class="fas fa-file-search"></i></span>'.( $has_more_links && $query_offset==0  ? 'FIRST ' : ($query_offset+1).' - ' ) . ( $total_items_loaded >= ($query_offset+1) ?  $total_items_loaded . ' OF ' : '' ) . number_format($lns_count[0]['total_count'] , 0) .' TRANSACTIONS:</div>';
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



    function json($ln_id)
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
        } elseif(!superpower_assigned(12701)) {

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




}
?>