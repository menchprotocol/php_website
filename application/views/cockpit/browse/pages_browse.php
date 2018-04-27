<?php
/**
 * Created by PhpStorm.
 * User: shervinenayati
 * Date: 2018-04-13
 * Time: 9:39 AM
 */

$pages = $this->Db_model->fp_fetch(array(
    'fp_status >=' => 0, //Activated
),array('u'), array(
    'fp_status'=>'DESC',
    'fs_timestamp'=>'DESC'
));

?>
    <table class="table table-condensed table-striped left-table" style="font-size:0.8em; width:100%;">
        <thead>
        <tr>
            <th style="width:40px;">#</th>
            <th> </th>
            <th>Facebook Page</th>
            <th>FP ID</th>
            <th> </th>
            <th>Instructor</th>
            <th>Page Updated</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
<?php

foreach($pages as $key=>$fp){

    echo '<tr>';
    echo '<td>'.($key+1).'</td>';
    echo '<td>'.status_bible('fp',$fp['fp_status'],1,'right').'</td>';
    echo '<td>'.$fp['fp_name'].'</td>';
    echo '<td>'.$fp['fp_id'].'</td>';
    echo '<td><a href="https://www.facebook.com/'.$fp['fp_fb_id'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="Open Facebook Page in a new window"><i class="fa fa-external-link-square" aria-hidden="true"></i></a></td>';
    echo '<td>'.$fp['u_fname'].'</td>';

    echo '<td>'.time_format($fp['fs_timestamp'],0).'</td>';



    //Test Connection:
    echo '<td>';
    if($fp['fp_status']==1){

        //Test this connection to make sure we're all good:
        $graph_fetch = $this->Comm_model->fb_graph($fp['fp_id'], 'GET', '/me/messenger_profile', array('fields'=>'persistent_menu,get_started,greeting,whitelisted_domains'), $fp);

        //Passon results to a JS variable so admin can echo in console.log
        echo '<script> e_json_'.$fp['fs_id'].' = '.json_encode($graph_fetch['e_json']['result']).'; </script>';

        //Show results:
        echo '<a href="javascript:console.log(e_json_'.$fp['fs_id'].');">'.( isset($graph_fetch['e_json']['result']['error']) ? '<i class="fa fa-exclamation-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Connection seems Broken. Click once to load error message in console."></i>' : '<i class="fa fa-check-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Connection to Facebook Page is healthy. Click once to load Page Settings in console."></i>').'</a>';

    }
    echo '</td>';


    echo '</tr>';
}
