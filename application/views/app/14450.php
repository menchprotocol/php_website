<?php


echo '<form method="GET" action="">';
echo '<div class="mini-header">Top Blog ID:</div>';
echo '<input type="text" class="form-control border" name="top_i__id" value="'.@$_GET['top_i__id'].'"><br />';
echo '<div class="mini-header">Blog ID:</div>';
echo '<input type="text" class="form-control border" name="i__id" value="'.@$_GET['i__id'].'"><br />';
echo '<input type="submit" class="btn btn-blog" value="Map Top Tree">';
echo '</form>';


if(isset($_GET['i__id']) && isset($_GET['top_i__id'])){
    echo '<div class="row top-margin">';
    $find_previous = $this->X_model->find_previous($member_e['e__id'], $_GET['top_i__id'], $_GET['i__id']);
    if(count($find_previous)){
        foreach($find_previous as $i){
            echo view_i(14450, intval($_GET['top_i__id']), null, $i);
        }
        $is = $this->I_model->fetch(array(
            'i__id' => $_GET['i__id'],
        ));
        echo '<h1>' . view_i_title($is[0]) . '</h1>';
    }
    echo '</div>';
}