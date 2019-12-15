<script>
    var in_loaded_id = <?= $in['in_id'] ?>;
    var session_en_id = <?= ( isset($session_en['en_id']) ? intval($session_en['en_id']) : 0 ) ?>;
</script>
<script src="/js/custom/in_landing_page.js?v=v<?= config_var(11060) ?>"
        type="text/javascript"></script>




<div class="container">
<?php

echo '<h1>' . echo_in_outcome($in['in_outcome']) . '</h1>';

//Fetch & Display Intent Note Messages:
foreach ($this->READ_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_entity_id' => 4231, //Intent Note Messages
    'ln_child_intent_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
    echo $this->READ_model->dispatch_message($ln['ln_content']);
}




if(in_array($in['in_completion_method_entity_id'], $this->config->item('en_ids_7582')) /* READ LOGIN REQUIRED */){



    //Action Plan Overview:
    /*
    $step_info = echo_tree_steps($in, false);
    $source_info = echo_tree_experts($in, false);

    if($step_info || $source_info){
        echo '<div style="margin:5px 0;">';
        echo $step_info;
        echo $source_info;
        echo '</div>';
    }
    */


    echo '<a class="btn btn-read" href="/'.$in['in_id'].'/next">NEXT <i class="fas fa-angle-right"></i></a>';


    /*


    //Start generating relevant intentions we can recommend as other intentions:

    //Child intentions:
    $in__children = $this->READ_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_6144')) . ')' => null,
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child'));

    //Parent intentions:
    $in__parents = $this->READ_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_6144')) . ')' => null,
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
            'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_6144')) . ')' => null,
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
            'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_6144')) . ')' => null,
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


        echo '<p style="margin:25px 0 15px;" class="other_intents"><a href="javascript:void(0)" onclick="$(\'.other_intents\').toggleClass(\'hidden\')">'.count($in__other).' nearby reads</a></p>';


        echo '<div class="other_intents hidden">';
        echo '<div class="list-group maxout">';
        $max_visible = 30;

        //Now fetch Recommended Intents:
        foreach ($in__other as $other_in) {
            echo echo_in_read($other_in);
        }

        if(count($already_printed) > $max_visible){
            //Show show more button:
            echo '<a href="javascript:void(0);" onclick="$(\'.extra-recommendations\').toggleClass(\'hidden\');" class="list-group-item itemread extra-recommendations"><span class="icon-block"><i class="fas fa-plus-circle"></i></span><b style="font-weight: 500;">'.(count($already_printed)-$max_visible).' More Recommendations</b></a>';
        }

        echo '</div>';


        echo '</div>';

    }

    */


} elseif($in['in_completion_method_entity_id']==6684 /* Single Answer */) {

    //Give option to choose a child path:
    echo '<div class="list-group" style="margin-top:30px;">';
    $in__children = $this->READ_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
    foreach ($in__children as $child_in) {
        echo echo_in_read($child_in, ( isset($session_en['en_id']) ? '/read/actionplan_answer_question/6157/' . $session_en['en_id'] . '/' . $in['in_id'] . '/' . md5($this->config->item('cred_password_salt') . $child_in['in_id'] . $in['in_id'] . $session_en['en_id']) : '' ), $in['in_id']);
    }
    echo '</div>';

}
?>

</div>
