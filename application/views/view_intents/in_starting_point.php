<script src="/js/custom/intent-featured.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

<?php

echo '<div class="landing-page-intro" id="in_public_ui">';

//Intent Title:
echo '<h1 style="margin-bottom:30px;" id="title-parent">' . echo_in_outcome($in['in_outcome']) . '</h1>';

//Fetch & Display Intent Note Messages:
foreach ($this->Links_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_entity_id' => 4231, //Intent Note Messages
    'ln_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
    echo $this->Communication_model->dispatch_message($ln['ln_content']);
}


//Action Plan Overview:
$source_info = echo_tree_experts($in, false);
$step_info = echo_tree_steps($in, false);
$user_info = echo_tree_users($in, false);

if($step_info || $source_info || $user_info){
    echo '<h3 style="margin-bottom:5px; margin-top:15px !important;">Overview:</h3>';
    echo '<div style="margin:5px 0 25px 0;" class="maxout">';
    echo $source_info;
    echo $step_info;
    echo $user_info;
    echo '</div>';
} else {
    //Just give some space:
    echo '<br />';
}

//Check to see if added to Action Plan for logged-in users:
if(isset($session_en['en_id'])){

    $en_all_7369 = $this->config->item('en_all_7369');

    if(count($this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $session_en['en_id'],
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'ln_parent_intent_id' => $in['in_id'],
        ))) > 0){

        //Show when was added:
        echo '<p>Intention is already added to your <a href="/actionplan">'.$en_all_7369[6138]['m_icon'].' '.$en_all_7369[6138]['m_name'].'</a>.</p>';

    } else {

        //Give option to add:
        echo '<div id="added_to_actionplan"><a class="btn btn-primary" href="javascript:void(0);" onclick="add_to_actionplan('.$in['in_id'].')" style="display: inline-block; padding:12px 36px;">Add to '.$en_all_7369[6138]['m_icon'].' '.$en_all_7369[6138]['m_name'].'</a></div>';

    }

} else {

    //Give option to add:
    echo '<a class="btn btn-primary tag-manager-get-started" href="https://m.me/askmench?ref='.$in['in_id'].'" style="display: inline-block; padding:12px 36px;">Get Started &nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>';

}

//Build trust:
echo '<p style="font-size:1em !important;">Mench is an open-source project.</p>';


echo '</div>';



//Exclude certain intents form being displayed on this section:
$exclude_array = $this->config->item('in_system_lock');

//Also exclude this intent:
array_push($exclude_array, $in['in_id']);

//Fetch other intentions:
$parent_intentions = $this->Links_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'in_status_entity_id' => 7351, //Intent Starting Point
    'ln_type_entity_id' => 4228, //Fixed intent links only
    'ln_child_intent_id' => $in['in_id'],
    'in_id NOT IN (' . join(',', $exclude_array) . ')' => null,
), array('in_parent'));


//Parent intentions:
foreach ($parent_intentions as $parent_intention) {
    if(in_is_clean_outcome($parent_intention)){
        //Make sure to not load this again:
        array_push($exclude_array, $parent_intention['in_id']);
    }
}

$recommended_intention = $this->Links_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_entity_id' => 4228, //Fixed intent links only
    'ln_parent_intent_id' => 8469, //Recommend Mench Intentions
    'in_id NOT IN (' . join(',', $exclude_array) . ')' => null,
    'in_status_entity_id' => 7351, //Intent Starting Point
), array('in_child'), 0, 0, array('ln_order' => 'ASC'));


//Display if any:
if(count($parent_intentions) > 0 || count($recommended_intention) > 0){

    echo '<h3 style="margin-bottom:5px; margin-top:55px;">Other Intentions:</h3>';
    echo '<div class="list-group grey_list actionplan_list maxout">';

    //Now fetch Recommended Intents:
    $in__other = array_merge($recommended_intention, $parent_intentions);
    $common_prefix = common_prefix($in__other);
    foreach ($in__other as $other_in) {
        if(!in_is_clean_outcome($other_in)){
            continue;
        }
        echo echo_in_recommend($other_in, false, $common_prefix);
    }

    echo '</div>';

}
?>