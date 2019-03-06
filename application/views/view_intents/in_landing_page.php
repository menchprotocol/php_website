<?php
//Prepare some handy variables:
$metadata = unserialize($in['in_metadata']);
$expand_mode = (isset($_GET['expand_mode']) && intval($_GET['expand_mode']));
$is_primary_in = ($in['in_id'] == $this->config->item('in_tactic_id'));
$hide_subscribe = (isset($_GET['hide_subscribe']) && intval($_GET['hide_subscribe']));
?>
    <style>
        .body-container .msg, .body-container li, p, .body-container a {
            font-size: 1.1em !important;
        }

        .msg {
            margin-top: 10px !important;
            font-weight: 300 !important;
            line-height: 120% !important;
        }

        .msg a {
            max-width: none;
        }

        .tooltip-inner {
            max-width: 350px !important;
        }
    </style>


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


    <div id="in_landing_page">

        <?php

        //Intent Title:
        echo '<h1 style="margin-bottom:30px;" id="title-parent">' . $in['in_outcome'] . '</h1>';


        //Fetch & Display On-Start Messages for this intent:
        foreach ($this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 2, //Published+
            'tr_type_entity' => 4231, //On-Start Messages
            'tr_child_intent' => $in['in_id'],
        ), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
            echo $this->Chat_model->fn___dispatch_message($tr['tr_content']);
        }
        ?>


        <br/>

        <?php if (!$hide_subscribe) { ?>
            <!-- Call to Actions -->
            <a class="btn btn-primary" href="https://m.me/askmench?ref=<?= $in['in_id'] ?>"
               style="display: inline-block; padding: 12px 36px;">Get Started [Free] <i class="fas fa-angle-right"></i></a>
            <br/>
            <br/>

            <h3 style="margin-top:0px !important;">Overview:</h3>
            <div style="margin:12px 0 0 5px;" class="maxout">
                <?= fn___echo_tree_tasks($in, false) ?>
                <?= fn___echo_tree_sources($in, false) ?>
                <?= fn___echo_tree_cost($in, false) ?>
            </div>
        <?php } ?>



        <?php if (count($in['in__children']) > 0) { ?>

            <?php if (!$hide_subscribe) { ?>
                <h3>Action Plan:</h3>
            <?php } ?>

            <div class="list-group actionplan_list" style="margin:12px 0 0 5px;">
                <?php
                $in_level2_counter = 0;
                foreach ($in['in__children'] as $in_level2) {

                    if ($in_level2['tr_type_entity'] == 4229) {
                        continue; //Do not show conditional post-assessment intents
                    }

                    echo '<div class="panel-group" id="open' . $in_level2_counter . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $in_level2_counter . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $in_level2_counter . '" href="#collapse' . $in_level2_counter . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $in_level2_counter . '">'.($in['in_is_any'] ? 'Option ' : 'Task '). ($in_level2_counter + 1) . ': <span id="title-' . $in_level2['in_id'] . '">' . $in_level2['in_outcome'] . '</span>';

                    //Show time if we have it:
                    $in_level2_metadata = unserialize($in_level2['in_metadata']);
                    if (isset($in_level2_metadata['in__tree_max_seconds']) && $in_level2_metadata['in__tree_max_seconds'] > 0) {
                        echo ' <span style="font-size: 0.9em; font-weight: 300;"><i class="fal fa-clock"></i> ' . fn___echo_time_range($in_level2, true) . '</span>';
                    }

                    echo '<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $in_level2_counter . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $in_level2_counter . '">
                <div class="panel-body" style="padding:5px 0 0 5px;">';


                    //Fetch & Display On-Start Messages for this intent:
                    foreach ($this->Database_model->fn___tr_fetch(array(
                        'tr_status >=' => 2, //Published+
                        'tr_type_entity' => 4231, //On-Start Messages
                        'tr_child_intent' => $in_level2['in_id'],
                    ), array(), 0, 0, array('tr_order' => 'ASC')) as $tr) {
                        echo $this->Chat_model->fn___dispatch_message($tr['tr_content']);
                    }

                    if (count($in_level2['in__grandchildren']) > 0) {

                        $in_level3_counter = 0;
                        echo '<ul style="list-style:none; margin-left:-30px; font-size:1em;">';
                        foreach ($in_level2['in__grandchildren'] as $in_level3) {

                            if ($in_level3['tr_type_entity'] == 4229) {
                                continue; //Do not show conditional post-assessment intents
                            }

                            echo '<li>'.($in_level2['in_is_any'] ? 'Option ' : 'Task ') . ($in_level2_counter + 1) . '.' . ($in_level3_counter + 1) . ': ' . $in_level3['in_outcome'];

                            //Show time if we have it:
                            $in_level3_metadata = unserialize($in_level3['in_metadata']);
                            if (isset($in_level3_metadata['in__tree_max_seconds']) && $in_level3_metadata['in__tree_max_seconds'] > 0) {
                                echo ' <span style="font-size: 0.9em; font-weight: 300;"><i class="fal fa-clock"></i> ' . fn___echo_time_range($in_level3, true) . '</span>';
                            }

                            echo '</li>';

                            //Increase counter:
                            $in_level3_counter++;
                        }
                        echo '</ul>';

                        //Since it has children, lets also give the option to navigate downwards ONLY IF...
                        if ($in_level2['in_status'] >= 2 && !$expand_mode) {
                            echo '<div>You can choose to <a href="/' . $in_level2['in_id'] . '" ' . ($is_primary_in ? 'onclick="confirm_child_go(' . $in_level2['in_id'] . ')"' : '') . ' class="alink-' . $in_level2['in_id'] . '" style="text-decoration:underline;">subscribe to this task only</a>.</div>';
                        }

                    }

                    echo '</div>
            </div>
        </div></div>';

                    //Increase counter:
                    $in_level2_counter++;

                }
                ?>
            </div>
            <br/>
        <?php } ?>


        <?php if (!$hide_subscribe) { ?>

            <p style="padding:5px 0 0 0;">Ready to <?= $in['in_outcome'] ?>?</p>

            <!-- Call to Actions -->
            <a class="btn btn-primary" href="https://m.me/askmench?ref=<?= $in['in_id'] ?>"
               style="display: inline-block; padding: 12px 36px;">Get Started [Free] <i class="fas fa-angle-right"></i></a>

            <div>
                <?php if ($in['in_id']==7436) { ?>
                    You may also
                <?php } else { ?>
                <a href="/7436" style="text-decoration:underline; display: inline-block;">Learn more</a> about Mench
                 or
                <?php } ?>
                <a href="/<?= $this->config->item('in_miner_start_id') ?>"
                   style="text-decoration:underline;  display: inline-block;">contribute</a>.</div>

        <?php } ?>


    </div>

<?php

//Display other featured intents:
$featured_ins = $ins = $this->Database_model->fn___in_fetch(array(
    'in_status' => 3, //Featured Intents
    'in_id !=' => $in['in_id'],
));
if (count($featured_ins) > 0 && !$hide_subscribe) {
    echo '<div>';
    echo '<h3>Featured Intentions:</h3>';
    echo '<div class="list-group actionplan_list maxout">';
    foreach ($in['in__parents'] as $in_parent) {
        if ($in_parent['in_status'] >= 2) {
            echo fn___echo_in_featured($in_parent);
        }
    }
    foreach ($featured_ins as $featured_c) {
        echo fn___echo_in_featured($featured_c);
    }
    echo '</div>';
    echo '</div>';
}


?>