<?php

$engagement_filters = array(
    'e_inbound_c_id' => 'All Engagement Types',
    'e_id' => 'Engagement ID',
    'e_u_id' => 'Entity ID',
    'e_ur_id' => 'Entity Link ID',
    'e_x_id' => 'Entity URL ID',
    'e_i_id' => 'Message ID',
    'e_outbound_c_id' => 'Intent ID',
    'e_cr_id' => 'Intent Link ID',
    'e_w_id' => 'Subscription ID',
);

$match_columns = array();
foreach($engagement_filters as $key=>$value){
    if(isset($_GET[$key])){
        if($key=='e_u_id'){
            //We need to look for both inititors and recipients:
            if(substr_count($_GET[$key],',')>0){
                //This is multiple IDs:
                $match_columns['(e_outbound_u_id IN ('.$_GET[$key].') OR e_inbound_u_id IN ('.$_GET[$key].'))'] = null;
            } elseif(intval($_GET[$key])>0) {
                $match_columns['(e_outbound_u_id = '.$_GET[$key].' OR e_inbound_u_id = '.$_GET[$key].')'] = null;
            }
        } else {
            if(substr_count($_GET[$key],',')>0){
                //This is multiple IDs:
                $match_columns[$key.' IN ('.$_GET[$key].')'] = null;
            } elseif(intval($_GET[$key])>0) {
                $match_columns[$key] = intval($_GET[$key]);
            }
        }
    }
}

//Fetch engagements with possible filters:
$engagements = $this->Db_model->e_fetch($match_columns,(is_dev() ? 20 : 100));

?>

<style>
    table, tr, td, th { text-align:left !important; font-size:14px; cursor:default !important; line-height:120% !important; }
    th { font-weight:bold !important; }
    td { padding:5px 0 !important; }
</style>

<?php
//Display filters:
echo '<h5 class="badge badge-h" style="display: inline-block;"><i class="fas fa-exchange"></i> Platform Engagements</h5>';


echo '<form action="" method="GET">';
echo '<table class="table table-condensed"><tr>';
foreach($engagement_filters as $key=>$value){
    echo '<td><div style="padding-right:5px;">';
    if($key=='e_inbound_c_id'){ //We have a list to show:

        //Fetch all engagements from intent #6653
        $all_engs = $this->Db_model->cr_outbound_fetch(array(
            'cr.cr_inbound_c_id' => 6653,
            'cr.cr_status >' => 0,
            'c.c_status >' => 0, //Use status to control menu item visibility
        ));

        echo '<select name="'.$key.'" class="border" style="width:160px;">';
        echo '<option value="0">'.$value.'</option>';
        foreach($all_engs as $c_eng){
            echo '<option value="'.$c_eng['c_id'].'" '.((isset($_GET[$key]) && $_GET[$key]==$c_eng['c_id'])?'selected="selected"':'').'>'.$c_eng['c_outcome'].'</option>';
        }
        echo '</select>';
        //echo '<div><a href="/console/360/actionplan" target="_blank">Open in Action Plan <i class="fas fa-external-link-square"></i></a></div>'; //TODO NO CLUE what this is!

    } else {
        //show text input
        echo '<input type="text" name="'.$key.'" placeholder="'.$value.'" value="'.((isset($_GET[$key]))?$_GET[$key]:'').'" class="form-control border">';
    }
    echo '</div></td>';
}
echo '<td><input type="submit" class="btn btn-sm btn-primary" value="Apply" /></td>';
echo '</tr></table>';
echo '</form>';




//Fetch objects
echo '<div class="list-group list-grey">';
foreach($engagements as $e){
    echo echo_e($e);
}
echo '</div>';

?>