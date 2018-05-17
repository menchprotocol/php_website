<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Urls extends CI_Controller
{

    //This controller is for functions that do mass adjustments on the DB

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }

    function add_url() {

        //Auth user and check required variables:
        $udata = auth(array(1308,1280));

        if(!$udata){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif(!isset($_POST['x_outbound_u_id']) || intval($_POST['x_outbound_u_id'])<=0){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Outbound Entity ID',
            ));
        } elseif(!isset($_POST['x_url']) || strlen($_POST['x_url'])<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing URL',
            ));
        } elseif(!filter_var($_POST['x_url'], FILTER_VALIDATE_URL)){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));
        }

        sleep(10);

        $_POST['x_url'] = trim($_POST['x_url']);

        //Validate entity
        $outbound_us = $this->Db_model->u_fetch(array(
            'u_id' => $_POST['x_outbound_u_id'],
        ));

        //Call URL to validate it further:
        $curl = curl_html($_POST['x_url'], true);

        //Make sure this URL does not exist:
        $dup_urls = $this->Db_model->x_fetch(array(
            'x_url' => $_POST['x_url'],
            'x_clean_url' => $_POST['x_url'],
        ), array('u'));

        if(!$curl) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));
        } elseif (count($dup_urls) > 0) {

            if($dup_urls[0]['u_id']==$_POST['x_outbound_u_id']){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'This URL has already been added to [' . $dup_urls[0]['u_full_name'] . ']',
                ));
            } else {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'URL is already being used by [' . $dup_urls[0]['u_full_name'] . ']. URLs cannot belong to multiple entities.',
                ));
            }

        } elseif(count($outbound_us)<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Outbound Entity ID ['.$_POST['x_outbound_u_id'].']',
            ));
        } elseif ($curl['url_is_broken']) {
            return echo_json(array(
                'status' => 0,
                'message' => 'URL seems broken with http code [' . $curl['httpcode'] . ']',
            ));
        }

        //All good, Save URL:
        $new_x = $this->Db_model->x_create(array(
            'x_inbound_u_id' => $udata['u_id'],
            'x_outbound_u_id' => $_POST['x_outbound_u_id'],
            'x_url' => $_POST['x_url'],
            'x_http_code' => $curl['httpcode'],
            'x_clean_url' => ($curl['clean_url'] ? $curl['clean_url'] : $_POST['x_url']),
            'x_type' => $curl['x_type'],
            'x_status' => ( $curl['url_is_broken'] ? 1 : 2 ),
        ));


        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
            'new_x' => echo_x($outbound_us[0], $new_x),
        ));

    }

}