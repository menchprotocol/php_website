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


//List intent children based on intent type:
if(in_is_or($in['in_type_entity_id'])){

    //Give option to choose a child path:
    echo '<div class="list-group actionplan_list grey_list" style="margin-top:40px;">';
    $in__children = $this->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
    $common_prefix = common_prefix($in__children);

    foreach ($in__children as $child_in) {
        echo echo_in_recommend($child_in, $common_prefix, null, ( count($referrer_en) > 0 ? $referrer_en['en_id'] : 0 ));
    }
    echo '</div>';

} else {

    //Just show the Action Plan:
    echo '<br />'.echo_public_actionplan($in, $autoexpand);

}



//Company signup form:
if($in['in_id']==10430){
    ?>

    <h3 style="margin-top:30px;">Sign Up</h3>
    <div class="company-signup">
        <form autocomplete="off">
            <b class="mini-header">Your Full Name</b>
            <span class="white-wrapper"><input type="text" id="user_full_name" class="form-control" autocomplete="off" data-lpignore="true"></span>

            <b class="mini-header">Your Work Email</b>
            <span class="white-wrapper"><input type="email" id="user_email" class="form-control" autocomplete="off" data-lpignore="true"></span>

            <b class="mini-header">Your Company Name</b>
            <span class="white-wrapper"><input type="text" id="company_name" class="form-control" autocomplete="off" data-lpignore="true"></span>

            <b class="mini-header">Your Password</b>
            <span class="white-wrapper"><input type="password" id="your_password" class="form-control" autocomplete="off" autocomplete="new-password" data-lpignore="true"></span>

            <b class="mini-header">Repeat Password</b>
            <span class="white-wrapper"><input type="password" id="repeat_password" class="form-control" autocomplete="off" autocomplete="new-password" data-lpignore="true"></span>

            <a class="btn btn-primary tag-manager-get-started" href="javascript:void(0);" style="display: inline-block; font-size: 1em;">Get Started&nbsp;&nbsp;&nbsp;<i class="fas fa-angle-double-right"></i></a>
        </form>
    </div>

<?php

}


echo '</div>';
?>