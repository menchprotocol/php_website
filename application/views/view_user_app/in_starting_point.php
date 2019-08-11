
<script>
    var in_focus_id = <?= $in['in_id'] ?>;
    var session_en_id = <?= ( isset($session_en['en_id']) ? intval($session_en['en_id']) : 0 ) ?>;
</script>
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
if(isset($session_en['en_id']) && $referrer_en_id == 0){

    $en_all_7369 = $this->config->item('en_all_7369');

    if(count($this->Links_model->ln_fetch(array(
            'ln_creator_entity_id' => $session_en['en_id'],
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'ln_parent_intent_id' => $in['in_id'],
        ))) > 0){

        //Show when was added:
        echo '<p>Intention is already added to your '.$en_all_7369[6138]['m_icon'].' '.$en_all_7369[6138]['m_name'].'.</p>';

        echo '<a class="btn btn-primary tag-manager-get-started" href="/actionplan/'.$in['in_id'].'" style="display: inline-block; padding:12px 36px; font-size: 1.3em;">Resume&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>';

    } else {

        //Give option to add:
        echo '<div id="added_to_actionplan"><a class="btn btn-primary tag-manager-get-started" href="javascript:void(0);" onclick="add_to_actionplan()" style="display: inline-block; padding:12px 36px; font-size: 1.3em;">Get Started&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a></div>';


    }

} else {

    //Give option to add:
    echo '<a class="btn btn-primary tag-manager-get-started" href="'.( $referrer_en_id > 0 ? $referrer_en_id.'_' : '' ).$in['in_id'].'/signin" style="display: inline-block; padding:12px 36px; font-size: 1.3em;">Get Started&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>';

}






//Start generating relevant intentions we can recommend as other intentions:

//Child intentions:
$in__children = $this->Links_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'in_start_mode_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //Intent Action Plan Addable
    'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
    'ln_type_entity_id' => 4228, //Intent Link Regular Step
    'ln_parent_intent_id' => $in['in_id'],
), array('in_child'));

//Parent intentions:
$in__parents = $this->Links_model->ln_fetch(array(
    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'in_start_mode_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //Intent Action Plan Addable
    'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
    'ln_type_entity_id' => 4228, //Intent Link Regular Step
    'ln_child_intent_id' => $in['in_id'],
), array('in_parent'));

if($referrer_en_id > 0){
    //Only show children as other intents:
    $in__other = array_merge($in__children, $in__parents);
} else {


    //Recommended intentions:
    $in__recommended = $this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_parent_intent_id' => 8469, //Recommend Mench Intentions
        'in_start_mode_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //Intent Action Plan Addable
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'in_id !=' => $in['in_id'], //Not the current intent
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

    //Sibling intentions:
    $in__siblings = array();
    foreach ($this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_child_intent_id' => $in['in_id'],
    ), array('in_parent')) as $parent_in) {
        $in__siblings = array_merge($in__siblings, $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_start_mode_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //Intent Action Plan Addable
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $parent_in['in_id'],
            'in_id !=' => $in['in_id'], //Not the current intent
        ), array('in_child')));
    }

    //Granchildren intentions:
    $in__granchildren = array();
    foreach ($this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child')) as $child_in) {
        $in__granchildren = array_merge($in__granchildren, $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_start_mode_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //Intent Action Plan Addable
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $child_in['in_id'],
            'in_id !=' => $in['in_id'], //Not the current intent
        ), array('in_child')));
    }

    //Merge all intents:
    $in__other = array_merge($in__recommended, $in__parents, $in__siblings, $in__children, $in__granchildren);

}



//Display if any:
if(count($in__other) > 0){

    //echo '<h3 style="margin-bottom:5px; margin-top:55px;">Other Intentions:</h3>';
    echo '<p style="margin:40px 0 15px;">Other intentions I can help you with:</p>';
    echo '<div class="list-group grey_list actionplan_list maxout">';
    $max_visible = 7;

    //Now fetch Recommended Intents:
    $already_printed = array(); //Make sure we don't show anything twice
    foreach ($in__other as $other_in) {
        if(!in_is_clean_outcome($other_in)){
            continue;
        }
        if(in_array($other_in['in_id'], $already_printed)){
            continue; //Already printed!
        }

        echo echo_in_recommend($other_in, null, ( count($already_printed) >= $max_visible ? 'extra-recommendations hidden' : null ), $referrer_en_id);
        array_push($already_printed, $other_in['in_id']); //Keep track to make sure its printed only once
    }

    if(count($already_printed) > $max_visible){
        //Show show more button:
        echo '<a href="javascript:void(0);" onclick="$(\'.extra-recommendations\').toggleClass(\'hidden\');" class="list-group-item extra-recommendations"><i class="fas fa-plus-circle"></i> <b style="font-weight: 500;">'.(count($already_printed)-$max_visible).' More Recommendations</b></a>';
    }

    echo '</div>';

}


echo '</div>';
?>