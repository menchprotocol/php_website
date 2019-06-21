<script src="/js/custom/landing-page-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

<?php
//Prepare some handy variables:
$metadata = unserialize($in['in_metadata']);
$expand_mode = (isset($_GET['expand_mode']) && intval($_GET['expand_mode']));
$hide_subscribe = (isset($_GET['hide_subscribe']) && intval($_GET['hide_subscribe']));


echo '<div class="landing-page-intro" id="in_landing_page">';


//Intent Title:
echo '<h1 style="margin-bottom:30px;" id="title-parent">' . echo_in_outcome($in['in_outcome']) . '</h1>';


//Fetch & Display Intent Note Messages for this intent:
foreach ($this->Links_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_entity_id' => 4231, //Intent Note Messages
    'ln_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
    echo $this->Communication_model->dispatch_message($ln['ln_content']);
}


//Overview:
if (!$hide_subscribe) {

    $source_info = echo_tree_experts($in, false);
    $step_info = echo_tree_steps($in, false);
    $time_info = echo_tree_completion_time($in, false);
    $user_info = echo_tree_users($in, false);

    if($step_info || $source_info || $time_info){
        echo '<h3 style="margin-bottom:5px; margin-top:15px !important;">Overview:</h3>';
        echo '<div style="margin:5px 0 25px 0;" class="maxout">';
        echo $source_info;
        echo $step_info;
        echo $time_info;
        echo $user_info;
        echo '</div>';
    } else {
        //Just give some space:
        echo '<br />';
    }

    //Check to see if added to Action Plan for logged-in users:
    if(isset($session_en['en_id'])){

        $en_all_4488 = $this->config->item('en_all_4488');

        if(count($this->Links_model->ln_fetch(array(
                'ln_miner_entity_id' => $session_en['en_id'],
                'ln_type_entity_id' => 4235, //Action Plan Set Intention
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
                'ln_parent_intent_id' => $in['in_id'],
            ))) > 0){

            //Show when was added:
            echo '<p>Intention is already added to your <a href="/actionplan">'.$en_all_4488[6138]['m_icon'].' '.$en_all_4488[6138]['m_name'].'</a>.</p>';

        } else {

            //Give option to add:
            echo '<div id="added_to_actionplan"><a class="btn btn-primary" href="javascript:void(0);" onclick="add_to_actionplan('.$in['in_id'].')" style="display: inline-block; padding:12px 36px;">Add to '.$en_all_4488[6138]['m_icon'].' '.$en_all_4488[6138]['m_name'].'</a></div>';

        }

    } else {

        //Give option to add:
        echo '<a class="btn btn-primary" href="https://m.me/askmench?ref='.$in['in_id'].'" style="display: inline-block; padding:12px 36px;">Get Started &nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>';

    }

    //Build trust:
    echo '<p style="font-size:1em !important;">Mench is an open-source project. <a href="/'.$this->config->item('in_learn_mench_id').'">Learn more</a>.</p>';

} else {

    //Just show the Action Plan:
    echo '<br />'.echo_public_actionplan($in, $expand_mode);

}

echo '</div>';




//Exclude certain intents form being displayed on this section:
$exclude_array = $this->config->item('in_system_lock');

//Also exclude this intent:
array_push($exclude_array, $in['in_id']);

//Fetch other intentions:
$other_intentions = $this->Links_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
    'ln_type_entity_id' => 4228, //Fixed intent links only
    'ln_child_intent_id' => $in['in_id'],
    'in_id NOT IN (' . join(',', $exclude_array) . ')' => null,
), array('in_parent'));


//Parent intentions:
$body = '';
foreach ($other_intentions as $parent_intention) {
    if(!in_is_clean_outcome($parent_intention)){
        continue;
    }
    //Add parent intention to UI:
    $body .= echo_in_recommend($parent_intention);
    //Make sure to not load this again:
    array_push($exclude_array, $parent_intention['in_id']);
}

$recommend_intention = $this->Links_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'in_status_entity_id' => 7351, //Intent Featured
    'ln_type_entity_id' => 4228, //Fixed intent links only
    'ln_parent_intent_id' => 8469, //Recommend Mench Intentions
    'in_id NOT IN (' . join(',', $exclude_array) . ')' => null,
), array('in_child'), 0, 0, array('ln_order' => 'ASC'));


//Display if any:
if(count($other_intentions) > 0 || count($recommend_intention) > 0){

    echo '<h3 style="margin-bottom:5px; margin-top:55px;">Other Intentions:</h3>';
    echo '<div class="list-group grey_list actionplan_list maxout">';

        echo $body;

        //Now fetch Recommended Intents:
        foreach ($recommend_intention as $recommend_intention) {
            if(!in_is_clean_outcome($recommend_intention)){
                continue;
            }
            echo echo_in_recommend($recommend_intention);
        }

    echo '</div>';

}

?>