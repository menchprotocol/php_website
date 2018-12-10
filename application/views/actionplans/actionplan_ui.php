<?php

//Prepare some variables to better understand out situation here:
$messages = $this->Db_model->i_fetch(array(
    'tr_in_child_id' => $c['in_id'],
    'i_status' => 1, //On start messages only
));
$has_outs = (count($k_outs) > 0);
//We want to show the child intents in specific conditions to ensure a step-by-step navigation by the user through the browser Action Plan
//(Note that the conversational UI already has this step-by-step navigation in mind, but the user has more flexibility in the Browser side)
$has_completion_info = (intval($c['c_require_url_to_complete']) || intval($c['c_require_notes_to_complete']));
$list_outs = (count($k_ins) == 0 || !($k_ins[0]['tr_status'] == 0) || intval($c['in_is_any']) || !$has_completion_info || count($messages) == 0);


if (count($k_ins) == 1) {
    //Inform the user of any completion requirements:
    $requirement_notes = echo_c_requirements($c);

    //Submission button visible after first button was clicked:
    $is_incomplete = ($k_ins[0]['tr_status'] <= 0 || ($k_ins[0]['tr_status'] == 1 && count($k_outs) == 0));
    $show_written_input = ($requirement_notes && $is_incomplete);
}


//Do we have a next item?
$next_button = null;
if ($w['tr_status'] == 1) {
    //Active subscription, attempt to find next item, which we should be able to find:
    $trs_next = $this->Db_model->k_next_fetch($w['tr_id']);
    if ($trs_next) {
        if ($trs_next[0]['in_id'] == $c['in_id']) {
            //$next_button = '<span style="font-size: 0.7em; padding-left:5px; display:inline-block;"><i class="fas fa-shield-check"></i> This is the next-in-line intent</span>';
            $next_button = null;
        } else {
            $next_button = '<a href="/my/actionplan/' . $trs_next[0]['tr_tr_parent_id'] . '/' . $trs_next[0]['in_id'] . '" class="btn ' . (count($k_ins) == 1 && !$show_written_input && !$is_incomplete ? 'btn-md btn-primary' : 'btn-xs btn-black') . '" data-toggle="tooltip" data-placement="top" title="Next intent-in-line is to ' . $trs_next[0]['c_outcome'] . '">Next-in-line <i class="fas fa-angle-right"></i></a>';
        }
    }
}

//Include JS file:
echo '<script src="/js/custom/actionplan-js.js?v=v' . $this->config->item('app_version') . '" type="text/javascript"></script>';

//Fetch parent tree all the way to the top of subscription tr_in_child_id
echo '<div class="list-group" style="margin-top: 10px;">';
foreach ($k_ins as $k) {
    echo echo_k($k, 1);
}
echo '</div>';


//Show title
echo '<h3 class="student-h3 primary-title">' . $c['c_outcome'] . '</h3>';

if (count($k_ins) == 0) {

    //Always hide messages on the subscription-level to have students focus on Action Plan
    $hide_messages = true;

    //This must be top level subscription, show subscription data:
    echo '<div class="sub_title">';
    echo echo_status('tr_status', $w['tr_status']);
    echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> ' . echo_diff_time($w['w_timestamp']) . ' ago';
    //TODO show subscription pace data such as start/end time, weekly rate & notification type
    echo '</div>';

} elseif (count($k_ins) == 1) {

    $hide_messages = ($has_completion_info && !in_array($k_ins[0]['tr_status'], $this->config->item('tr_status_incomplete')));

    //Show completion progress for the single parent intent:
    echo '<div class="sub_title">';

    echo echo_status('tr_status', $k_ins[0]['tr_status']);

    //Either show completion time or when it was completed:
    if ($k_ins[0]['k_last_updated']) {
        echo ' &nbsp;&nbsp;<i class="fas fa-calendar-check"></i> ' . echo_diff_time($k_ins[0]['k_last_updated']) . ' ago';
    } else {
        echo ' &nbsp;&nbsp;<i class="fas fa-clock"></i> ' . echo_hours($c['in_seconds']) . ' to complete';
    }

    if (strlen($k_ins[0]['tr_content']) > 0) {
        echo '<div style="margin:15px 0 0 3px;"><i class="fas fa-edit"></i> ' . echo_link(nl2br(htmlentities($k_ins[0]['tr_content']))) . '</div>';
    }

    echo '</div>';

}


//Show all messages:
if (count($messages) > 0) {
    $hide_messages_onload = (count($k_ins) == 0 || $k_ins[0]['tr_status'] <= 0);
    echo '<div class="tips_content message_content left-grey" style="display: ' . ($hide_messages ? 'none' : 'block') . ';">';
    echo '<h5 class="badge badge-hy"><i class="fas fa-comment-dots"></i> ' . count($messages) . ' Message' . echo__s(count($messages)) . ':</h5>';
    foreach ($messages as $i) {
        if ($i['i_status'] == 1) {
            echo '<div class="tip_bubble">';
            echo echo_i(array_merge($i, array(
                'tr_en_child_id' => $w['u_id'],
            )), $w['u_full_name']);
            echo '</div>';
        }
    }
    echo '</div>';

    if ($hide_messages) {
        //Show button to show messages:
        echo '<div class="left-grey"><a href="javascript:void(0);" onclick="$(\'.message_content\').toggle();" class="message_content btn btn-xs btn-black"><i class="fas fa-comment-dots"></i> See ' . count($messages) . ' Message' . echo__s(count($messages)) . '</a></div>';
    }
}


//Show completion options below messages:
if (count($k_ins) == 1 && ($has_completion_info || (!intval($c['in_is_any']) && !$has_outs))) {

    if (!$show_written_input && !$is_incomplete && strlen($k_ins[0]['tr_content']) > 0 /* For now only allow is complete */) {
        //Show button to make text visible:
        echo '<div class="left-grey"><a href="javascript:void(0);" onclick="$(\'.toggle_text\').toggle();" class="toggle_text btn btn-xs btn-black"><i class="fas fa-edit"></i> ' . ($is_incomplete ? 'Add Written Answer' : 'Modify Answer') . '</a></div>';
    }

    echo '<div class="left-grey">';
    echo '<form method="POST" action="/my/update_k_save">';

    echo '<input type="hidden" name="tr_id"  value="' . $k_ins[0]['tr_id'] . '" />';
    echo '<input type="hidden" name="is_from_messenger"  value="' . (isset($_GET['is_from_messenger']) ? 1 : 0) . '" />';

    //echo '<input type="hidden" name="k_key" value="'.md5($k_ins[0]['tr_id'].'k_key_SALT555').'" />'; //TODO Wire in for more security?!

    echo '<div class="toggle_text" style="' . ($show_written_input ? '' : 'display:none; ') . '">';
    if ($requirement_notes) {
        echo '<div style="color:#2b2b2b; font-size:0.7em; margin:0 !important; padding:0;"><i class="fas fa-exclamation-triangle"></i> ' . $requirement_notes . '</div>';
    }
    echo '<textarea name="tr_content" class="form-control maxout" style="padding:5px !important; margin:0 !important;">' . $k_ins[0]['tr_content'] . '</textarea>';
    echo '</div>';


    if ($has_outs && !$list_outs) {
        echo '<button type="submit" class="btn btn-primary"><i class="fas fa-check-square"></i> Got It, Continue <i class="fas fa-angle-right"></i></button>';
    } elseif ($is_incomplete) {
        echo '<button type="submit" name="k_next_redirect" value="1" class="btn btn-primary"><i class="fas fa-check-square"></i> Mark Complete & Go Next <i class="fas fa-angle-right"></i></button>';
    } elseif (!$show_written_input) {
        echo '<button type="submit" class="btn btn-primary toggle_text" style="display:none;"><i class="fas fa-edit"></i> Update Answer</button>';
    } else {
        echo '<button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Update Answer</button>';
    }

    echo '</form>';
    echo '</div>';
}

if ($has_outs && $list_outs) {
    echo '<div class="left-grey">';
    echo '<h5 class="badge badge-hy">' . ($c['in_is_any'] ? '<i class="fas fa-code-merge"></i> Choose One' : '<i class="fas fa-sitemap"></i> Complete All') . ':</h5>';
    echo '<div class="list-group">';
    foreach ($k_outs as $k) {
        echo echo_k($k, 0, ($c['in_is_any'] && $k['tr_status'] == 0 ? $c['in_id'] : 0));
    }
    echo '</div>';
    echo '</div>';
}


//Echo next button (if available):
echo $next_button;

//Give a skip option if not complete:
if (count($k_ins) == 1 && in_array($k_ins[0]['tr_status'], $this->config->item('tr_status_incomplete'))) {
    echo '<span class="skippable">or <a href="javascript:void(0);" onclick="confirm_skip(' . $w['tr_id'] . ',' . $c['in_id'] . ',' . $k_ins[0]['tr_id'] . ')">skip intent</a></span>';
}

?>