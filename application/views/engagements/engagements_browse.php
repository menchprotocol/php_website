<?php

$engagement_filters = array(
    ' li_en_type_id' => 'Link Types',
    'li_id' => 'Engagement ID',
    'e_u_id' => 'Entity ID',
    'li_in_child_id' => 'Intent ID',
);

$match_columns = array();
foreach($engagement_filters as $key=>$value){
    if(isset($_GET[$key])){
        if($key=='e_u_id'){
            //We need to look for both inititors and recipients:
            if(substr_count($_GET[$key],',')>0){
                //This is multiple IDs:
                $match_columns['( li_en_child_id IN ('.$_GET[$key].') OR li_en_parent_id IN ('.$_GET[$key].'))'] = null;
            } elseif(intval($_GET[$key])>0) {
                $match_columns['( li_en_child_id = '.$_GET[$key].' OR li_en_parent_id = '.$_GET[$key].')'] = null;
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
$engagements = $this->Db_model->li_fetch($match_columns,(is_dev() ? 20 : 100));

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
    if($key==' li_en_type_id'){ //We have a list to show:

        //Fetch all engagements from intent #6653
        $all_engs = $this->Db_model->cr_children_fetch(array(
            'cr_parent_c_id IN (7720,7719,7722,7723)' => null, //The 4 branches of #Log platform engagements #6653
            'cr_status' => 1,
            'c_status >=' => 0,
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
echo '<div class="list-group list-grey maxout">';
foreach($engagements as $e){
    echo echo_e($e);
}
echo '</div>';

?>