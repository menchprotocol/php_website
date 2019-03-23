<script>
    function confirm_child_go(in_id) {
        $('.alink-' + in_id).attr('href', 'javascript:void(0);');
        var in_outcome_parent = $('#title-parent').text();
        var in_outcome_child = $('#title-' + in_id).text();
        var r = confirm("Press OK to ONLY " + in_outcome_child + "\nPress CANCEL to " + in_outcome_parent);
        if (r == true) {
            //Go to target intent:
            window.location = "/" + in_id;
        }
    }
</script>

<?php
//Prepare some handy variables:
$metadata = unserialize($in['in_metadata']);
$expand_mode = (isset($_GET['expand_mode']) && intval($_GET['expand_mode']));
$hide_subscribe = (isset($_GET['hide_subscribe']) && intval($_GET['hide_subscribe']));


echo '<div class="landing-page-intro" id="in_landing_page">';


//Intent Title:
echo '<h1 style="margin-bottom:30px;" id="title-parent">' . echo_in_outcome($in['in_outcome'], true) . '</h1>';


//Fetch & Display Intent Note Messages for this intent:
foreach ($this->Database_model->fn___tr_fetch(array(
    'tr_status' => 2, //Published
    'tr_type_entity_id' => 4231, //Intent Note Messages
    'tr_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
    echo $this->Chat_model->fn___dispatch_message($tr['tr_content']);
}


//Overview:
if (!$hide_subscribe) {

    $step_info = fn___echo_tree_steps($in, false);
    $source_info = fn___echo_tree_sources($in, false);
    $cost_info = fn___echo_tree_cost($in, false);

    if($step_info || $source_info || $cost_info){
        echo '<h3 style="margin-bottom:5px; margin-top:0px !important;">Overview:</h3>';
        echo '<div style="margin:5px 0 25px 5px;" class="maxout">';
        echo $step_info;
        echo $source_info;
        echo $cost_info;
        echo '</div>';
    }

    //Call to action button:
    echo '<a class="btn btn-primary" href="https://m.me/askmench?ref='.$in['in_id'].'" style="display: inline-block; padding:12px 36px;">Get Started &nbsp;&nbsp;&nbsp; <i class="fas fa-angle-double-right"></i></a>';
}

echo '</div>';



//Exclude certain intents form being displayed on this section:
$exclude_array = $this->config->item('in_status_locked');

//Also exclude this intent:
array_push($exclude_array, $in['in_id']);

echo '<h3 style="margin-bottom:5px; margin-top:22px;">Other Intentions:</h3>';
echo '<div class="list-group grey_list actionplan_list maxout">';

//Parent intentions:
foreach ($this->Database_model->fn___tr_fetch(array(
    'tr_status' => 2, //Published
    'in_status' => 2, //Published
    'tr_type_entity_id' => 4228, //Fixed intent links only
    'tr_child_intent_id' => $in['in_id'],
    'in_id NOT IN (' . join(',', $exclude_array) . ')' => null,
), array('in_parent')) as $parent_intention) {
    //Add parent intention to UI:
    echo fn___echo_in_featured($parent_intention);
    //Make sure to not load this again:
    array_push($exclude_array, $parent_intention['in_id']);
}

//Now fetch featured intents:
foreach ($this->Database_model->fn___tr_fetch(array(
    'tr_status' => 2, //Published
    'in_status' => 2, //Published
    'tr_type_entity_id' => 4228, //Fixed intent links only
    'tr_parent_intent_id' => $this->config->item('in_featured'), //Feature Mench Intentions
    'in_id NOT IN (' . join(',', $exclude_array) . ')' => null,
), array('in_child'), 0, 0, array('tr_order' => 'ASC')) as $featured_intention) {
    echo fn___echo_in_featured($featured_intention);
}

echo '</div>';


?>