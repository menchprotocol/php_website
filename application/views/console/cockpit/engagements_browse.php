<?php
/**
 * Created by PhpStorm.
 * User: shervinenayati
 * Date: 2018-04-13
 * Time: 9:38 AM
 */

//Define engagement filters:
$engagement_references = $this->config->item('engagement_references');

$engagement_filters = array(
    'e_inbound_c_id' => 'All Engagements',
    'e_id' => 'Engagement ID',
    'e_u_id' => 'Entity ID',
    'e_outbound_c_id' => 'Intent ID',
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
        echo '<div><a href="/console/360/actionplan" target="_blank">Open in Action Plan <i class="fas fa-external-link-square"></i></a></div>';

    } else {
        //show text input
        echo '<input type="text" name="'.$key.'" placeholder="'.$value.'" value="'.((isset($_GET[$key]))?$_GET[$key]:'').'" class="form-control border">';
    }
    echo '</div></td>';
}
echo '<td><input type="submit" class="btn btn-sm btn-primary" value="Apply" /></td>';
echo '</tr></table>';
echo '</form>';
?>

    <table class="table table-condensed table-striped">
        <thead>
        <tr>
            <th style="width:120px;">Action/Time</th>
            <th><div style="padding-left:10px;">Message</div></th>
            <th style="width:300px;">References</th>
            <th style="width:30px; text-align:center !important;">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php
        //Fetch objects
        foreach($engagements as $e){
            echo '<tr>';
            echo '<td>';
                echo '<div style="margin-bottom:3px; font-weight:bold;"><a href="/intents/'.$e['c_id'].'" target="_blank" data-toggle="tooltip" title="Intent #'.$e['c_id'].'" data-placement="right">'.$e['c_outcome'].'</a></div>';
                echo '<span data-toggle="tooltip" data-placement="right" title="'.date("Y-m-d H:i:s",strtotime($e['e_timestamp'])).' Engagement #'.$e['e_id'].'" class="underdot">'.echo_time($e['e_timestamp']).'</span>';
            echo '</td>';

            //Do we have a message?
            if(strlen($e['e_text_value'])>0){
                $e['e_text_value'] = format_e_text_value($e['e_text_value']);
            } elseif($e['e_i_id']>0){
                //Fetch message conent:
                $matching_messages = $this->Db_model->i_fetch(array(
                    'i_id' => $e['e_i_id'],
                ));
                if(count($matching_messages)>0){
                    $e['e_text_value'] = echo_i($matching_messages[0]);
                }
            }

            echo '<td><div style="max-width:300px; padding-left:10px;">'.$e['e_text_value'].( in_array($e['e_status'],array(0,-2)) ? '<div style="color:#008000;"><i class="fas fa-spinner fa-spin fa-3x fa-fw" style="font-size:14px;"></i> Processing...</div>' : '' ).'</div></td>';
            echo '<td>';

            //Lets go through all references to see what is there:
            foreach($engagement_references as $engagement_field=>$er){
                if(intval($e[$engagement_field])>0){
                    //Yes we have a value here:
                    echo '<div>'.$er['name'].': '.echo_object($er['object_code'], $e[$engagement_field]).'</div>';
                } elseif(intval($e[$engagement_field])>0) {
                    echo '<div>'.$er['name'].': #'.$e[$engagement_field].'</div>';
                }
            }

            echo '</td>';
            echo '<td style="text-align:center !important;">'.( $e['e_has_blob']=='t' ? '<a href="/cockpit/ej_list/'.$e['e_id'].'" target="_blank" data-toggle="tooltip" title="Analyze Engagement JSON Blob in a new window" data-placement="left"><i class="fas fa-search-plus" id="icon_'.$e['e_id'].'"></i></a>' : '' ).'</td>';
            echo '</tr>';
        }
        ?>
        </tbody>
    </table>