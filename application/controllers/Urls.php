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
        $_POST['x_url'] = trim($_POST['x_url']);

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

        //Validate entity
        $outbound_us = $this->Db_model->u_fetch(array(
            'u_id' => $_POST['x_outbound_u_id'],
        ));

        //Call URL to validate it further:
        $curl = curl_html($_POST['x_url'], true);

        //Make sure this URL does not exist:
        $dup_urls = $this->Db_model->x_fetch(array(
            'x_status >' => -2,
            '(x_url LIKE \''.$_POST['x_url'].'\' OR x_clean_url LIKE \''.$_POST['x_url'].'\')' => null,
        ), array('u'));

        if(!$curl){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));
        } elseif(count($dup_urls) > 0){

            if($dup_urls[0]['u_id']==$_POST['x_outbound_u_id']){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'This URL has already been added!',
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
        } elseif($curl['url_is_broken']) {
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

        //Log Engagements:
        $this->Db_model->e_create(array(
            'e_json' => $curl,
            'e_inbound_c_id' => 6911, //URL Detected Live
            'e_inbound_u_id' => $udata['u_id'],
            'e_outbound_u_id' => $_POST['x_outbound_u_id'],
            'e_x_id' => $new_x['x_id'],
        ));
        $this->Db_model->e_create(array(
            'e_json' => $new_x,
            'e_inbound_c_id' => 6910, //URL Added
            'e_inbound_u_id' => $udata['u_id'],
            'e_outbound_u_id' => $_POST['x_outbound_u_id'],
            'e_x_id' => $new_x['x_id'],
        ));

        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
            'new_x' => echo_x($outbound_us[0], $new_x),
        ));

    }

    function delete_url(){

        //Auth user and check required variables:
        $udata = auth(array(1308,1280));

        if(!$udata){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif(!isset($_POST['x_id']) || intval($_POST['x_id'])<=0){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing URL ID',
            ));
        }

        //Validate URL:
        $urls = $this->Db_model->x_fetch(array(
            'x_id' => $_POST['x_id'],
        ), array('u'));

        if(count($urls)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid URL ID',
            ));
        }

        //Make the update (Assume it's all good):
        $rows_updated = $this->Db_model->x_update( $_POST['x_id'] , array(
            'x_status' => -2, //Delete by user
        ));

        if(!$rows_updated){
            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown error while trying to delete this URL',
            ));
        }

        //Log Engagement:
        $this->Db_model->e_create(array(
            'e_json' => $urls[0],
            'e_inbound_c_id' => 6912, //URL Deleted
            'e_inbound_u_id' => $udata['u_id'],
            'e_outbound_u_id' => $urls[0]['x_outbound_u_id'],
            'e_x_id' => $_POST['x_id'],
        ));

        //Is this URL set as the Cover photo?
        if($urls[0]['u_cover_x_id']==$_POST['x_id']){
            //This is set as the Cover photo, let's remove it:
            $this->Db_model->u_update( $urls[0]['u_id'] , array(
                'u_cover_x_id' => 0, //Remove Cover photo
            ));

            //Log Engagement:
            $this->Db_model->e_create(array(
                'e_json' => $urls[0],
                'e_inbound_c_id' => 6924, //Cover Photo Removed
                'e_inbound_u_id' => $udata['u_id'],
                'e_outbound_u_id' => $urls[0]['x_outbound_u_id'],
                'e_x_id' => $_POST['x_id'],
            ));
        }

        return echo_json(array(
            'status' => 1,
            'message' => '<i class="fas fa-trash-alt"></i> Deleted',
        ));
    }


    function cover_photo_set(){

        //Auth user and check required variables:
        $udata = auth(array(1308,1280));

        if(!$udata){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif(!isset($_POST['x_id']) || intval($_POST['x_id'])<=0){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing URL ID',
            ));
        }

        //Validate URL:
        $urls = $this->Db_model->x_fetch(array(
            'x_id' => $_POST['x_id'],
        ), array('u'));

        if(count($urls)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid URL ID',
            ));
        }

        //This is set as the Cover photo, let's remove it:
        $this->Db_model->u_update( $urls[0]['u_id'] , array(
            'u_cover_x_id' => $_POST['x_id'], //Add Cover photo
        ));

        //Log Engagement:
        $this->Db_model->e_create(array(
            'e_json' => $urls[0],
            'e_inbound_c_id' => 6924, //Cover Photo Removed
            'e_inbound_u_id' => $udata['u_id'],
            'e_outbound_u_id' => $urls[0]['x_outbound_u_id'],
            'e_x_id' => $_POST['x_id'],
        ));

        return echo_json(array(
            'status' => 1,
        ));
    }


}