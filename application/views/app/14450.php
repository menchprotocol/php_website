<?php


echo '<form method="GET" action="">';
echo '<div class="mini-header">Top Idea ID:</div>';
echo '<input type="text" class="form-control border" name="top_i__hashtag" value="'.@$_GET['top_i__hashtag'].'"><br />';
echo '<div class="mini-header">Idea ID:</div>';
echo '<input type="text" class="form-control border" name="i__hashtag" value="'.@$_GET['i__hashtag'].'"><br />';
echo '<input type="submit" class="btn btn-12273" value="Map Top Tree">';
echo '</form>';


if(isset($_GET['i__hashtag']) && isset($_GET['top_i__hashtag'])){
    foreach($this->I_model->fetch(array(
        'i__hashtag' => $_GET['top_i__hashtag'],
    )) as $top_i){
        foreach($this->I_model->fetch(array(
            'i__hashtag' => $_GET['i__hashtag'],
        )) as $i){
            echo '<div class="row justify-content">';
            $find_previous = $this->X_model->find_previous($member_e['e__id'], $top_i['i__hashtag'], $i['i__id']);
            if(count($find_previous)){
                foreach($find_previous as $i){
                    echo view_card_i(6255, $top_i['i__hashtag'], null, $i);
                }
                echo '<h1>' . view_i_title($i) . '</h1>';
            }
            echo '</div>';
        }
    }

}