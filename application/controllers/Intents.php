<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intents extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }

    function ping()
    {
        echo_json(array('status' => 'success'));
    }


    //For trainers to see and manage an intent:
    function intent_manage( $inbound_c_id ){

        //Authenticate level 2 or higher, redirect if not:
        $udata = auth(array(1308,1280),1);

        //Fetch intent tree:
        $c__tree = $this->Db_model->c_recursive_fetch($inbound_c_id,0,null);

        if(!$c__tree['c__count']){
            die('Intent ID '.$inbound_c_id.' not found');
        }

        if(isset($_GET['raw'])){
            echo_json($c__tree);
            exit;
        }

        //Load view
        $intent = end($c__tree['tree_top']);
        $data = array(
            'title' => $intent['c_outcome'],
            'c__tree' => $c__tree,
            'intent' => $intent,
            'breadcrumb' => array(), //Even if empty show it
        );

        //Search for all parent intents:
        $parent_cs = $this->Db_model->cr_inbound_fetch(array(
            'cr.cr_outbound_c_id' => $inbound_c_id,
            'cr.cr_status >=' => 1,
        ));

        //Did we find anything?
        if(count($parent_cs)>0){
            $data['breadcrumb_css'] = 'bintent';
            foreach ($parent_cs as $parent_c){
                array_push($data['breadcrumb'], array(
                    'link' => '/intents/'.$parent_c['c_id'],
                    'anchor' => $parent_c['c_outcome'],
                ));
            }
        }

        $this->load->view('console/console_header', $data);
        $this->load->view('intents/intent_manage' , $data);
        $this->load->view('console/console_footer');
    }


    function intent_public($c_id){

        //TODO Optimize this to become the front facing intent browser

        //Fetch data:
        $udata = $this->session->userdata('user');
        $bs = $this->Db_model->remix_bs(array(
            'LOWER(b.b_url_key)' => strtolower($b_url_key),
        ));

        //Validate Bootcamp:
        if(!isset($bs[0])){
            //Invalid key, redirect back:
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Bootcamp URL.</div>');
        } elseif($bs[0]['b_status']<2){
            redirect_message('/','<div class="alert alert-danger" role="alert">Bootcamp is archived.</div>');
        } elseif($bs[0]['b_fp_id']<=0){
            redirect_message('/','<div class="alert alert-danger" role="alert">Bootcamp not connected to a Facebook Page.</div>');
        } elseif(!$bs[0]['b_offers_diy'] && !$bs[0]['b_offers_coaching']){
            redirect_message('/','<div class="alert alert-danger" role="alert">Bootcamp not yet open for enrollment.</div>');
        } elseif(!(strcmp($bs[0]['b_url_key'], $b_url_key)==0)){
            //URL Case sensitivity redirect:
            redirect_message('/'.$bs[0]['b_url_key']);
        }

        //Fetch future classes:
        $next_classes = $this->Db_model->r_fetch(array(
            'r_b_id' => $bs[0]['b_id'],
            'r_status IN ('. ( $bs[0]['b_offers_diy'] ? '0,1' : '1' /* Require coaching */ ).')' => null,
            'r_start_date >' => date("Y-m-d"),
        ),null,'ASC',1);

        if(count($next_classes)<1){
            redirect_message('/','<div class="alert alert-danger" role="alert">Bootcamp does not have any active classes.</div>');
        }

        //Load home page:
        $this->load->view('front/shared/f_header' , array(
            'title' => $bs[0]['c_outcome'],
            'b_id' => $bs[0]['b_id'],
            'b_fb_pixel_id' => $bs[0]['b_fb_pixel_id'], //Will insert pixel code in header
            'canonical' => 'https://mench.com/'.$bs[0]['b_url_key'], //Would set this in the <head> for SEO purposes
        ));
        $this->load->view('front/b/landing_page' , array(
            'b' => $bs[0],
            'next_classes' => $next_classes,
        ));
        $this->load->view('front/shared/f_footer');
    }


}