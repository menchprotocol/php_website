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
$is_primary_in = ($in['in_id'] == $this->config->item('in_home_page'));
$hide_subscribe = (isset($_GET['hide_subscribe']) && intval($_GET['hide_subscribe']));


echo '<div class="landing-page-intro" id="in_landing_page">';


//Intent Title:
echo '<h1 style="margin-bottom:30px;" id="title-parent">' . $in['in_outcome'] . '</h1>';


//Fetch & Display Intent Note Messages for this intent:
foreach ($this->Database_model->fn___tr_fetch(array(
    'tr_status >=' => 2, //Published+
    'tr_type_entity_id' => 4231, //Intent Note Messages
    'tr_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
    echo $this->Chat_model->fn___dispatch_message($tr['tr_content']);
}


//Overview:
if (!$hide_subscribe) {

    echo '<h3 style="margin-top:20px !important;">Overview:</h3>';
    echo '<div style="margin:5px 0 0 5px;" class="maxout">';
    echo fn___echo_tree_steps($in, false);
    echo fn___echo_tree_sources($in, false);
    echo fn___echo_tree_cost($in, false);
    echo '</div>';

    //Call to action button:
    echo '<a class="btn btn-primary" href="https://m.me/askmench?ref='.$in['in_id'].'" style="display: inline-block; padding: 12px 36px;">Get Started &nbsp;&nbsp;&nbsp; <i class="fas fa-angle-double-right"></i></a>';
}



//Action Plan:

$children_ins = $this->Database_model->fn___tr_fetch(array(
    'tr_status >=' => 2, //Published+
    'in_status >=' => 2, //Published+
    'tr_type_entity_id' => 4228, //Fixed intent links only
    'tr_parent_intent_id' => $in['in_id'],
), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

if(count($children_ins) > 0){

    echo '<h3>'.( !$hide_subscribe ? 'Action Plan:' : '&nbsp;').'</h3>';
    echo '<div class="list-group grey_list actionplan_list maxout" style="margin:5px 0 0 5px;">';

    foreach ($children_ins as $in_level2_counter => $in_level2) {


        //Level 2 title:
        echo '<div class="panel-group" id="open' . $in_level2_counter . '" role="tablist" aria-multiselectable="true">';
        echo '<div class="panel panel-primary">';
        echo '<div class="panel-heading" role="tab" id="heading' . $in_level2_counter . '">';


        echo '<h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#open' . $in_level2_counter . '" href="#collapse' . $in_level2_counter . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $in_level2_counter . '">' . '<i class="fal fa-plus-circle" style="font-size: 1em !important; margin-left: 0; width: 21px;"></i>'. ($in['in_type'] ? 'Option ' : 'Step ') . ($in_level2_counter + 1) . ': <span id="title-' . $in_level2['in_id'] . '">' . $in_level2['in_outcome'] . '</span>';

        $in_level2_metadata = unserialize($in_level2['in_metadata']);
        if (isset($in_level2_metadata['in__tree_max_seconds']) && $in_level2_metadata['in__tree_max_seconds'] > 0) {
            echo ' <span style="font-size: 0.9em; font-weight: 300;"><i class="fal fa-clock" style="width:16px; text-transform: none !important;"></i>' . fn___echo_time_range($in_level2, true) . '</span>';
        }

        echo '</a></h4>';
        echo '</div>';


        //Level 2 body:
        echo '<div id="collapse' . $in_level2_counter . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $in_level2_counter . '">';
        echo '<div class="panel-body" style="padding:5px 0 0 25px;">';

        //Messages:
        foreach ($this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 2, //Published+
            'tr_type_entity_id' => 4231, //Intent Note Messages
            'tr_child_intent_id' => $in_level2['in_id'],
        ), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
            echo $this->Chat_model->fn___dispatch_message($tr['tr_content']);
        }


        //Level 3 intents:
        $grandchildren_ins = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 2, //Published+
            'in_status >=' => 2, //Published+
            'tr_type_entity_id' => 4228, //Fixed intent links only
            'tr_parent_intent_id' => $in_level2['in_id'],
        ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

        if (count($grandchildren_ins) > 0) {

            //List level 3:
            echo '<ul style="list-style:none; margin:10px 0 10px -40px; font-size:1em;">';
            foreach ($grandchildren_ins as $in_level3_counter => $in_level3) {

                echo '<li>' . ($in_level2['in_type'] ? 'Option ' : 'Step ') . ($in_level2_counter + 1) . '.' . ($in_level3_counter + 1) . ': ' . $in_level3['in_outcome'];
                $in_level3_metadata = unserialize($in_level3['in_metadata']);
                if (isset($in_level3_metadata['in__tree_max_seconds']) && $in_level3_metadata['in__tree_max_seconds'] > 0) {
                    echo ' <span style="font-size: 0.9em; font-weight: 300;"><i class="fal fa-clock"></i> ' . fn___echo_time_range($in_level3, true) . '</span>';
                }
                echo '</li>';

            }
            echo '</ul>';

            //Show call to action to go here only:
            if (!$expand_mode) {
                //Since it has children, lets also give the option to navigate downwards:
                echo '<p>You can view <a href="/' . $in_level2['in_id'] . '" ' . ($is_primary_in ? 'onclick="confirm_child_go(' . $in_level2['in_id'] . ')"' : '') . ' class="this-step alink-' . $in_level2['in_id'] . '">this step</a> only.</p>';
            }

        }

        echo '</div></div></div></div>';

    }
    echo '</div>';
}

echo '</div>';





//More Intents:
$parent_ui = null;

//Exclude this intent and the featured home page intent to start:
$exclude_array = array($in['in_id'], $this->config->item('in_home_page'));

//Add current intent's parents:
foreach ($this->Database_model->fn___tr_fetch(array(
    'tr_status >=' => 2, //Published+
    'in_status >=' => 2, //Published+
    'in_id !=' => $this->config->item('in_home_page'),
    'tr_type_entity_id' => 4228, //Fixed intent links only
    'tr_child_intent_id' => $in['in_id'],
), array('in_parent')) as $in_parent) {
    $parent_ui .= fn___echo_in_featured($in_parent);
    array_push($exclude_array, $in_parent['in_id']);
}

//Now fetch featured intents:
$featured_ins = $this->Database_model->fn___in_fetch(array(
    'in_status' => 3, //Featured Intents
    'in_id NOT IN (' . join(',', $exclude_array) . ')' => null,
));
if ((count($featured_ins) > 0 || $parent_ui)) {
    echo '<h3 style="margin-bottom:5px;">More Intentions:</h3>';
    echo '<div class="list-group grey_list actionplan_list maxout">';
    echo $parent_ui;
    foreach ($featured_ins as $featured_c) {
        echo fn___echo_in_featured($featured_c);
    }
    echo '</div>';
}

?>