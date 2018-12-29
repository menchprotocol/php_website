<?php

//Prepare some variables to better understand out situation here:
$on_start_messages = $this->Database_model->fn___tr_fetch(array(
    'tr_status >=' => 2, //Published+
    'tr_en_type_id' => 4231, //On-Start Messages
    'tr_in_child_id' => $value['in_id'],
), array(), 0, 0, array('tr_order' => 'ASC'));


//Fetch completion requirements:
$completion_requirements = $this->Database_model->fn___tr_fetch(array(
    'tr_en_type_id' => 4331, //Intent Response Limiters
    'tr_in_child_id' => $value['in_id'], //For this intent
    'tr_status >=' => 2, //Published+
    'tr_en_parent_id IN (' . join(',', $this->config->item('en_ids_4331')) . ')' => null, //The Requirement
));


$has_children = (count($actionplan_children) > 0);
//We want to show the child intents in specific conditions to ensure a step-by-step navigation by the user through the browser Action Plan
//(Note that the conversational UI already has this step-by-step navigation in mind, but the user has more flexibility in the Browser side)
$list_children = (count($actionplan_parents) == 0 || !($actionplan_parents[0]['tr_status'] == 0) || intval($in['in_is_any']) || count($completion_requirements)==0 || count($on_start_messages) == 0);


if (count($actionplan_parents) == 1) {
    //Inform the user of any completion requirements:
    $message_in_requirements = $this->Matrix_model->fn___in_completion_requirements($in['in_id']);

    //Submission button visible after first button was clicked:
    $is_incomplete = ($actionplan_parents[0]['tr_status'] < 1 || ($actionplan_parents[0]['tr_status'] == 1 && count($actionplan_children) == 0));
    $show_written_input = ($message_in_requirements && $is_incomplete);
}


//Do we have a next item?
$next_button = null;
if ($actionplan['tr_status'] == 1) {
    //Active Action Plan, attempt to find next item, which we should be able to find:
    $next_ins = $this->Matrix_model->fn___in_next_actionplan($actionplan['tr_id']);
    if ($next_ins) {
        if ($next_ins[0]['in_id'] == $in['in_id']) {
            //$next_button = '<span style="font-size: 0.7em; padding-left:5px; display:inline-block;"><i class="fas fa-shield-check"></i> This is the next-in-line intent</span>';
            $next_button = null;
        } else {
            $next_button = '<a href="/my/actionplan/' . $next_ins[0]['tr_tr_parent_id'] . '/' . $next_ins[0]['in_id'] . '" class="btn ' . (count($actionplan_parents) == 1 && !$show_written_input && !$is_incomplete ? 'btn-md btn-primary' : 'btn-xs btn-black') . '" data-toggle="tooltip" data-placement="top" title="Next intent-in-line is to ' . $next_ins[0]['in_outcome'] . '">Next-in-line <i class="fas fa-angle-right"></i></a>';
        }
    }
}

//Include JS file:
echo '<script src="/js/custom/actionplan-master-js.js?v=v' . $this->config->item('app_version') . '" type="text/javascript"></script>';

//Fetch parent tree all the way to the top of Action Plan tr_in_child_id
echo '<div class="list-group" style="margin-top: 10px;">';
foreach ($actionplan_parents as $k) {
    echo echo_k($k, 1);
}
echo '</div>';


//Show title
echo '<h3 class="master-h3 primary-title">' . $in['in_outcome'] . '</h3>';

if (count($actionplan_parents) == 0) {

    //Always hide messages on the Action Plan-level to have masters focus on Action Plan
    $hide_messages = true;

    //This must be top level Action Plan, show Action Plan data:
    echo '<div class="sub_title">';
    echo fn___echo_status('tr_status', $actionplan['tr_status']);
    echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> ' . fn___echo_time_difference($actionplan['w_timestamp']) . ' ago';
    //TODO show Action Plan pace data such as start/end time, weekly rate & notification type
    echo '</div>';

} elseif (count($actionplan_parents) == 1) {

    $hide_messages = (count($completion_requirements)>0 && !in_array($actionplan_parents[0]['tr_status'], $this->config->item('tr_status_incomplete')));

    //Show completion progress for the single parent intent:
    echo '<div class="sub_title">';

    echo fn___echo_status('tr_status', $actionplan_parents[0]['tr_status']);

    //Either show completion time or when it was completed:
    if ($actionplan_parents[0]['tr_timestamp']) {
        echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> ' . fn___echo_time_difference($actionplan_parents[0]['tr_timestamp']) . ' ago';
    } else {
        echo ' &nbsp;&nbsp;<i class="fas fa-clock"></i> ' . fn___echo_time_hours($in['in_seconds']) . ' to complete';
    }

    if (strlen($actionplan_parents[0]['tr_content']) > 0) {
        echo '<div style="margin:15px 0 0 3px;"><i class="fas fa-edit"></i> ' . fn___echo_link(nl2br(htmlentities($actionplan_parents[0]['tr_content']))) . '</div>';
    }

    echo '</div>';

}


//Show all messages:
if (count($on_start_messages) > 0) {
    $hide_messages_onload = (count($actionplan_parents) == 0 || $actionplan_parents[0]['tr_status'] < 1);
    echo '<div class="tips_content message_content left-grey" style="display: ' . ($hide_messages ? 'none' : 'block') . ';">';
    echo '<h5 class="badge badge-hy"><i class="fas fa-comment-dots"></i> ' . count($on_start_messages) . ' Message' . fn___echo__s(count($on_start_messages)) . ':</h5>';
    foreach ($on_start_messages as $tr) {
        if ($tr['tr_status'] == 1) {
            echo '<div class="tip_bubble">';
            echo $this->Chat_model->fn___echo_message($tr['tr_content'], $actionplan);
            echo '</div>';
        }
    }
    echo '</div>';

    if ($hide_messages) {
        //Show button to show messages:
        echo '<div class="left-grey"><a href="javascript:void(0);" onclick="$(\'.message_content\').toggle();" class="message_content btn btn-xs btn-black"><i class="fas fa-comment-dots"></i> See ' . count($on_start_messages) . ' Message' . fn___echo__s(count($on_start_messages)) . '</a></div>';
    }
}


//Show completion options below messages:
if (count($actionplan_parents) == 1 && (count($completion_requirements)>0 || (!intval($in['in_is_any']) && !$has_children))) {

    if (!$show_written_input && !$is_incomplete && strlen($actionplan_parents[0]['tr_content']) > 0 /* For now only allow is complete */) {
        //Show button to make text visible:
        echo '<div class="left-grey"><a href="javascript:void(0);" onclick="$(\'.toggle_text\').toggle();" class="toggle_text btn btn-xs btn-black"><i class="fas fa-edit"></i> ' . ($is_incomplete ? 'Add Written Answer' : 'Modify Answer') . '</a></div>';
    }

    echo '<div class="left-grey">';
    echo '<form method="POST" action="/my/update_k_save">';

    echo '<input type="hidden" name="tr_id"  value="' . $actionplan_parents[0]['tr_id'] . '" />';

    //echo '<input type="hidden" name="k_key" value="'.md5($actionplan_parents[0]['tr_id'].'k_key_SALT555').'" />'; //TODO Wire in for more security?!

    echo '<div class="toggle_text" style="' . ($show_written_input ? '' : 'display:none; ') . '">';
    if ($message_in_requirements) {
        echo '<div style="color:#2b2b2b; font-size:0.7em; margin:0 !important; padding:0;"><i class="fas fa-exclamation-triangle"></i> ' . $message_in_requirements . '</div>';
    }
    echo '<textarea name="tr_content" class="form-control maxout" style="padding:5px !important; margin:0 !important;">' . $actionplan_parents[0]['tr_content'] . '</textarea>';
    echo '</div>';


    if ($has_children && !$list_children) {
        echo '<button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i> Got It, Continue <i class="fas fa-angle-right"></i></button>';
    } elseif ($is_incomplete) {
        echo '<button type="submit" name="fn___in_next_actionplan" value="1" class="btn btn-primary"><i class="fas fa-check-square"></i> Mark Complete & Go Next <i class="fas fa-angle-right"></i></button>';
    } elseif (!$show_written_input) {
        echo '<button type="submit" class="btn btn-primary toggle_text" style="display:none;"><i class="fas fa-edit"></i> Update Answer</button>';
    } else {
        echo '<button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Update Answer</button>';
    }

    echo '</form>';
    echo '</div>';
}

if ($has_children && $list_children) {
    echo '<div class="left-grey">';
    echo '<h5 class="badge badge-hy">' . ($in['in_is_any'] ? '<i class="fas fa-code-merge"></i> Choose One' : '<i class="fas fa-sitemap"></i> Complete All') . ':</h5>';
    echo '<div class="list-group">';
    foreach ($actionplan_children as $k) {
        echo echo_k($k, 0, ($in['in_is_any'] && $k['tr_status'] == 0 ? $in['in_id'] : 0));
    }
    echo '</div>';
    echo '</div>';
}


//Echo next button (if available):
echo $next_button;

//Give a skip option if not complete:
if (count($actionplan_parents) == 1 && in_array($actionplan_parents[0]['tr_status'], $this->config->item('tr_status_incomplete'))) {
    echo '<span class="skippable">or <a href="javascript:void(0);" onclick="confirm_skip(' . $actionplan['tr_id'] . ',' . $in['in_id'] . ',' . $actionplan_parents[0]['tr_id'] . ')">skip intent</a></span>';
}

?>