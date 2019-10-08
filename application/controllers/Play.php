<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Play extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    function overview(){
        $this->load->view('view_mench/shared_header', array(
            'title' => 'PLAY',
        ));
        $this->load->view('view_play/play_overview');
        $this->load->view('view_mench/shared_footer');
    }



    function leaderboard($choose_10591){

        //Fetch top users per each direction
        $show_max = 10;

        $filters = array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_creator_entity_id >' => 0,
        );

        //Now see what type of report they want:
        $en_all_10591 = $this->config->item('en_all_10591'); //PLAYER PLAYS

        //Append custom filter:
        if($choose_10591==10589){
            $filters['ln_words>'] = 0;
        } elseif($choose_10591==10590){
            $filters['ln_words<'] = 0;
        }


        //Do we have a date filter?
        $start_date = null;
        /*
        if($timeframe_en_id==7801){ //Weekly

            //Week always starts on Monday:
            if(date('D') === 'Mon'){
                //Today is Monday:
                $start_date = date("Y-m-d");
            } else {
                $start_date = date("Y-m-d", strtotime('previous monday'));
            }
            $filters['ln_timestamp >='] = $start_date.' 00:00:00'; //From beginning of the day
        }
        */

        //Fetch leaderboard:
        $leaderboard_ens = $this->Links_model->ln_fetch($filters, array('ln_creator'), $show_max, 0, array('total_words' => 'DESC'), 'SUM(ABS(ln_words)) as total_words, en_name, en_icon, en_id', 'en_id, en_name, en_icon');

        //Did we find anyone?
        if(count($leaderboard_ens) > 0){
            foreach ($leaderboard_ens as $count=>$ln) {
                if($ln['total_words'] >= 1){
                    echo '<tr>';
                    echo '<td style="text-align: left;"><span class="parent-icon icon-block-lg">'.echo_en_icon($ln).'</span><a href="/entities/'.$ln['en_id'].'">'.one_two_explode('',' ',$ln['en_name']).'</a> '.echo_rank($count+1).'</td>';
                    echo '<td style="text-align: right;"><a href="/links?ln_status_entity_id='.join(',', $this->config->item('en_ids_7359')) /* Link Statuses Public */.'&ln_type_entity_id='.join(',', $this->config->item('en_ids_'.$choose_10591)).'&ln_creator_entity_id='.$ln['en_id'].( $start_date ? '&start_range='.$start_date : $start_date ).'" data-toggle="tooltip" title="WORDS '.$en_all_10591[$choose_10591]['m_name'].'" data-placement="top" class="mono">'.number_format($ln['total_words'], 0).'</a></td>';
                    echo '</tr>';

                }
            }
        } else {
            echo '<tr><td colspan="2"><div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> No Players Yet...</div></td></tr>';
        }
    }

    function sign($in_id = 0){

        //Check to see if they are already logged in?
        $session_en = $this->session->userdata('user');
        if (isset($session_en['en__parents'][0])) {
            //Lead trainer and above, go to console:
            if(filter_array($session_en['en__parents'], 'en_id', $this->config->item('en_ids_10691') /* Mench Trainers */)){
                return redirect_message('/intents');
            } else {
                return redirect_message('/actionplan' . ( $in_id > 0 ? '/'.$in_id : '' ));
            }
        }


        $en_all_7369 = $this->config->item('en_all_7369');
        $this->load->view('view_mench/shared_header', array(
            'hide_header' => 1,
            'title' => $en_all_7369[4269]['m_name'],
        ));
        $this->load->view('view_play/play_signing', array(
            'referrer_in_id' => intval($in_id),
        ));
        $this->load->view('view_mench/shared_footer');

    }

}