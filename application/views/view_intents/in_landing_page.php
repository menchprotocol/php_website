<script src="/js/custom/landing-page-js.js?v=v<?= $this->config->item('app_version') ?>"
        type="text/javascript"></script>

<?php
//Prepare some handy variables:
$metadata = unserialize($in['in_metadata']);
$expand_mode = (isset($_GET['expand_mode']) && intval($_GET['expand_mode']));
$hide_subscribe = (isset($_GET['hide_subscribe']) && intval($_GET['hide_subscribe']));


echo '<div class="landing-page-intro" id="in_landing_page">';


//Intent Title:
echo '<h1 style="margin-bottom:30px;" id="title-parent">' . echo_in_outcome($in['in_outcome'], true) . '</h1>';


//Fetch & Display Intent Note Messages for this intent:
foreach ($this->Database_model->ln_fetch(array(
    'ln_status' => 2, //Published
    'ln_type_entity_id' => 4231, //Intent Note Messages
    'ln_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
    echo $this->Communication_model->dispatch_message($ln['ln_content']);
}


//Overview:
if (!$hide_subscribe) {

    $source_info = echo_tree_references($in, false);
    $step_info = echo_tree_steps($in, false);
    $time_info = echo_tree_time_estimate($in, false);

    if($step_info || $source_info || $time_info){
        echo '<h3 style="margin-bottom:5px; margin-top:15px !important;">Overview:</h3>';
        echo '<div style="margin:5px 0 25px 0;" class="maxout">';
        echo $source_info;
        echo $step_info;
        echo $time_info;
        echo '</div>';
    }

    //Check to see if added to Action Plan for logged-in students:
    if(isset($session_en['en_id'])){

        $en_all_6196 = $this->config->item('en_all_6196');

        if(count($this->Database_model->ln_fetch(array(
                'ln_miner_entity_id' => $session_en['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6147')) . ')' => null, //Action Plan Intentions
                'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                'ln_parent_intent_id' => $in['in_id'],
            ))) > 0){

            //Show when was added:
            echo '<p>Intention is already added to your <a href="/messenger/actionplan">'.$en_all_6196[6138]['m_icon'].' '.$en_all_6196[6138]['m_name'].'</a>.</p>';

        } else {

            //Give option to add:
            echo '<div id="added_to_actionplan"><a class="btn btn-primary" href="javascript:void(0);" onclick="add_to_actionplan('.$in['in_id'].')" style="display: inline-block; padding:12px 36px;">Add to '.$en_all_6196[6138]['m_icon'].' '.$en_all_6196[6138]['m_name'].'</a></div>';

        }

    } else {

        //Give option to add:
        echo '<a class="btn btn-primary" href="https://m.me/askmench?ref='.$in['in_id'].'" style="display: inline-block; padding:12px 36px;">Get Started &nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>';

        //Build trust:
        echo '<p style="font-size:1em !important;">Mench is an open-source & non-profit project.</p>';

    }

} else {

    //Just show the Action Plan:
    echo '<br />'.echo_public_actionplan($in, $expand_mode);

}

echo '</div>';



//Exclude certain intents form being displayed on this section:
$exclude_array = $this->config->item('in_status_locked');

//Also exclude this intent:
array_push($exclude_array, $in['in_id']);

//Fetch other intentions:
$other_intentions = $this->Database_model->ln_fetch(array(
    'ln_status' => 2, //Published
    'in_status' => 2, //Published
    'ln_type_entity_id' => 4228, //Fixed intent links only
    'ln_child_intent_id' => $in['in_id'],
    'in_id NOT IN (' . join(',', $exclude_array) . ')' => null,
), array('in_parent'));

//Display if any:
if(count($other_intentions) > 0){

    echo '<h3 style="margin-bottom:5px; margin-top:22px;">Other Intentions:</h3>';
    echo '<div class="list-group grey_list actionplan_list maxout">';

    //Parent intentions:
    foreach ( as $parent_intention) {
        //Add parent intention to UI:
        echo echo_in_featured($parent_intention);
        //Make sure to not load this again:
        array_push($exclude_array, $parent_intention['in_id']);
    }

    //Now fetch featured intents:
    foreach ($this->Database_model->ln_fetch(array(
        'ln_status' => 2, //Published
        'in_status' => 2, //Published
        'ln_type_entity_id' => 4228, //Fixed intent links only
        'ln_parent_intent_id' => $this->config->item('in_featured'), //Feature Mench Intentions
        'in_id NOT IN (' . join(',', $exclude_array) . ')' => null,
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $featured_intention) {
        echo echo_in_featured($featured_intention);
    }

    echo '</div>';

}

?>