<?php

if(!isset($_GET['i__id'])){
    die('Missing Idea ID i__id');
}


//Define the user to fetch their discoveries for this idea:
if(!isset($_GET['e__id']) || !intval($_GET['e__id'])){
    $_GET['e__id'] = $member_e['e__id'];
}


//Generate list & settings:
$list_settings = list_settings($_GET['i__id']);
echo '<h1>' . view_i_title($list_settings['i']) . '</h1>';
echo $list_settings['filters_ui'];


//List the idea:
print_r(array(
    'find_next' => $this->X_model->find_next($_GET['e__id'], $list_settings['i']['i__id'], $list_settings['i'], 0, false),
    'tree_progress' => $this->X_model->tree_progress($_GET['e__id'], $list_settings['i']),
));