<div class="container">

<?php
if(in_array($in['in_completion_method_entity_id'], $this->config->item('en_ids_7582')) /* READ LOGIN REQUIRED */){

    //READER MUST LOGIN TO CONTINUE:
    ?>

    <script>
        var in_loaded_id = <?= $in['in_id'] ?>;
        var session_en_id = <?= ( isset($session_en['en_id']) ? intval($session_en['en_id']) : 0 ) ?>;
    </script>
    <script src="/js/custom/in_landing_page.js?v=v<?= config_value(11060) ?>"
            type="text/javascript"></script>

    <?php

    echo '<h1 style="margin-bottom:30px;" id="title-parent">' . echo_in_outcome($in['in_outcome']) . '</h1>';


    //Fetch & Display Intent Note Messages:
    foreach ($this->READ_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_entity_id' => 4231, //Intent Note Messages
        'ln_child_intent_id' => $in['in_id'],
    ), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
        echo $this->READ_model->dispatch_message($ln['ln_content']);
    }



    //Action Plan Overview:
    $step_info = echo_tree_steps($in, false);
    $source_info = echo_tree_experts($in, false);
    $user_info = echo_tree_users($in, false);

    if($step_info || $source_info || $user_info){
        echo '<div style="margin:25px 0;" class="maxout">';
        echo $step_info;
        echo $source_info;
        echo $user_info;
        echo '</div>';
    } else {
        //Just give some space:
        echo '<br />';
    }


    //Check to see if added to Action Plan for logged-in users:
    if(isset($session_en['en_id'])){

        if(count($this->READ_model->ln_fetch(array(
                'ln_creator_entity_id' => $session_en['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
                'ln_parent_intent_id' => $in['in_id'],
            ))) > 0){

            //Show when was added:
            echo '<p>BLOG already added to your BOOKMARKS.</p>';

            echo '<a class="btn btn-read" href="/actionplan/'.$in['in_id'].'" style="display: inline-block; padding:12px 36px; font-size: 1.3em;">Resume&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>';

        } else {

            //Give option to add:
            echo '<div id="added_to_actionplan"><a class="btn btn-blog" href="javascript:void(0);" onclick="add_to_actionplan()" style="display: inline-block; padding:12px 36px; font-size: 1.3em;">Get Started&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a></div>';


        }

    } else {

        //Give option to add:
        echo '<a class="btn btn-blog" href="'.$in['in_id'].'/sign" style="display: inline-block; padding:12px 36px; font-size: 1.3em;">Get Started&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>';

    }






    //Start generating relevant intentions we can recommend as other intentions:

    //Child intentions:
    $in__children = $this->READ_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //READ LOGIN REQUIRED
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child'));

    //Parent intentions:
    $in__parents = $this->READ_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //READ LOGIN REQUIRED
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_child_intent_id' => $in['in_id'],
    ), array('in_parent'));



    //Sibling intentions:
    $in__siblings = array();
    foreach ($this->READ_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_child_intent_id' => $in['in_id'],
    ), array('in_parent')) as $parent_in) {
        $in__siblings = array_merge($in__siblings, $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //READ LOGIN REQUIRED
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $parent_in['in_id'],
            'in_id !=' => $in['in_id'], //Not the current intent
        ), array('in_child')));
    }

    //Granchildren intentions:
    $in__granchildren = array();
    foreach ($this->READ_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child')) as $child_in) {
        $in__granchildren = array_merge($in__granchildren, $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //READ LOGIN REQUIRED
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $child_in['in_id'],
            'in_id !=' => $in['in_id'], //Not the current intent
        ), array('in_child')));
    }

    //Merge all intents:
    $in__other = array_merge($in__parents, $in__siblings, $in__children, $in__granchildren);


    //Cleanup to create the final list:
    $already_printed = array(); //Make sure we don't show anything twice
    foreach($in__other as $key => $other_in){
        if(in_array($other_in['in_id'], $already_printed)){
            unset($in__other[$key]);
        } else {
            array_push($already_printed, $other_in['in_id']); //Keep track to make sure its printed only once
        }
    }




    //Display if any:
    if(count($in__other) > 0){


        echo '<p style="margin:25px 0 15px;" class="other_intents">Or consider <a href="javascript:void(0)" onclick="$(\'.other_intents\').toggleClass(\'hidden\')">'.count($in__other).' other intentions</a>.</p>';


        echo '<div class="other_intents hidden">';
        echo '<p style="margin:25px 0 15px;">Here are some other intentions I can help you with:</p>';
        echo '<div class="list-group grey_list actionplan_list maxout">';
        $max_visible = 30;

        //Now fetch Recommended Intents:
        foreach ($in__other as $other_in) {
            echo echo_in_recommend($other_in, null, ( count($already_printed) >= $max_visible ? 'extra-recommendations hidden' : null ));
        }

        if(count($already_printed) > $max_visible){
            //Show show more button:
            echo '<a href="javascript:void(0);" onclick="$(\'.extra-recommendations\').toggleClass(\'hidden\');" class="list-group-item extra-recommendations"><i class="fas fa-plus-circle"></i> <b style="font-weight: 500;">'.(count($already_printed)-$max_visible).' More Recommendations</b></a>';
        }

        echo '</div>';


        echo '</div>';

    }




} else {








    // GUEST READING
    ?>


    <script>
        var in_loaded_id = <?= $in['in_id'] ?>;
        var session_en_id = <?= ( isset($session_en['en_id']) ? intval($session_en['en_id']) : 0 ) ?>;
    </script>
    <script src="/js/custom/in_landing_page.js?v=v<?= config_value(11060) ?>"
            type="text/javascript"></script>

    <?php

    //Intent Title:
    echo '<h1 style="margin-bottom:30px;" id="title-parent">' . echo_in_outcome($in['in_outcome']) . '</h1>';


    //Fetch & Display Intent Note Messages:
    foreach ($this->READ_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_entity_id' => 4231, //Intent Note Messages
        'ln_child_intent_id' => $in['in_id'],
    ), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
        echo $this->READ_model->dispatch_message($ln['ln_content']);
    }


    //Intent Select Publicly? If so, allow user to choose path:
    if(in_array($in['in_completion_method_entity_id'], $this->config->item('en_ids_7588'))){

        //Give option to choose a child path:
        echo '<div class="list-group actionplan_list grey_list" style="margin-top:40px;">';
        $in__children = $this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
        $in_common_prefix = in_common_prefix($in__children);

        foreach ($in__children as $child_in) {
            echo echo_in_recommend($child_in, $in_common_prefix, null);
        }
        echo '</div>';

    } else {

        //Just show the Action Plan:
        echo '<br />'.echo_tree_actionplan($in, $autoexpand);

    }


}
?>

</div>
