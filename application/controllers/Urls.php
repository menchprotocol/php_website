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


    function text_url(){
        //To see how our URL testing functions work...
        echo '<div><form action=""><input type="url" name="url" value="'.@$_GET['url'].'" style="width:400px;"> <input type="submit" value="Go"></form></div>';
        $curl = curl_html($_GET['url'],true);
        foreach($curl as $key=>$value){
            echo '<div style="color:'.( $key=='url_is_broken' && intval($value) ? '#FF0000' : '#000000' ).';">'.$key.': <b>'.$value.'</b></div>';
        }
    }

    function set_cover(){
        //Auth user and check required variables:
        $udata = auth(array(1308));

        if(!$udata){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif(!isset($_POST['x_id']) || strlen($_POST['x_id'])<1){
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

        //Set Cover photo:
        $this->Db_model->u_update( $urls[0]['u_id'] , array(
            'u_cover_x_id' => $_POST['x_id'],
        ));

        //Log Engagements:
        $this->Db_model->e_create(array(
            'e_parent_c_id' => 69123, //Cover photo added
            'e_parent_u_id' => $udata['u_id'],
            'e_child_u_id' => $urls[0]['u_id'],
            'e_x_id' => $_POST['x_id'],
        ));

        return echo_json(array(
            'status' => 1,
            'message' => '<i class="fas fa-file-check" data-toggle="tooltip" data-placement="left" title="File Set as Cover Photo"></i>',
        ));
    }

    function add_url() {
        return echo_json($this->Db_model->x_sync($_POST['x_url'],$_POST['x_u_id'],$_POST['can_edit']));
    }


    function delete_url(){

        //Auth user and check required variables:
        $udata = auth(array(1308));

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
            'e_parent_c_id' => 6912, //URL Deleted
            'e_parent_u_id' => $udata['u_id'],
            'e_child_u_id' => $urls[0]['x_u_id'],
            'e_x_id' => $_POST['x_id'],
        ));

        //Is this URL set as the Cover photo?
        if($urls[0]['u_cover_x_id']==$_POST['x_id']){
            //This is set as the Cover photo, let's remove it:
            $this->Db_model->u_update( $urls[0]['u_id'] , array(
                'u_cover_x_id' => 0,
            ));

            //Log Engagement:
            $this->Db_model->e_create(array(
                'e_json' => $urls[0],
                'e_parent_c_id' => 6924, //Cover Photo Removed
                'e_parent_u_id' => $udata['u_id'],
                'e_child_u_id' => $urls[0]['x_u_id'],
                'e_x_id' => $_POST['x_id'],
            ));
        }

        return echo_json(array(
            'status' => 1,
            'message' => '<i class="fas fa-trash-alt"></i> Deleted',
        ));
    }

}