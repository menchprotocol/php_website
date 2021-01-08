<?php


echo '<form method="GET" action="">';
echo '<div class="mini-header">Top Idea ID:</div>';
echo '<input type="text" class="form-control border" name="top_i__id" value="'.@$_GET['top_i__id'].'"><br />';
echo '<div class="mini-header">Idea ID:</div>';
echo '<input type="text" class="form-control border" name="i__id" value="'.@$_GET['i__id'].'"><br />';
echo '<input type="submit" class="btn btn-idea" value="Map Top Tree">';
echo '</form>';


if(isset($_GET['i__id']) && isset($_GET['top_i__id'])){
    echo '<br /><br /><div class="mini-header">Results:</div>';
    print_r($this->X_model->find_previous($member_e['e__id'], $_GET['top_i__id'], $_GET['i__id']));
}