<script>
    var in_loaded_id = <?= $in['in_id'] ?>;
    var session_en_id = <?= ( isset($session_en['en_id']) ? intval($session_en['en_id']) : 0 ) ?>;
</script>
<script src="/js/custom/in_landing_page.js?v=v<?= config_var(11060) ?>"
        type="text/javascript"></script>


<div class="container">
<?php

echo '<h1>' . echo_in_outcome($in['in_outcome']) . '</h1>';

//Overview:
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


//MESSAGES:
foreach ($this->READ_model->ln_fetch(array(
    'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    'ln_type_player_id' => 4231, //Blog Note Messages
    'ln_child_blog_id' => $in['in_id'],
), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {
    echo $this->READ_model->dispatch_message($ln['ln_content']);
}



if(in_array($in['in_type_player_id'], $this->config->item('en_ids_12107'))){

    //Give option to choose a child path:
    echo '<div class="list-group" style="margin-top:30px;">';
    $in__children = $this->READ_model->ln_fetch(array(
        'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_player_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        'ln_type_player_id' => 4228, //Blog Link Regular Step
        'ln_parent_blog_id' => $in['in_id'],
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
    foreach ($in__children as $child_in) {
        echo echo_in_read($child_in, null, $in['in_id']);
    }
    echo '</div>';

} else {

    if(!isset($session_en['en_id'])){

        echo '<div style="padding-bottom:40px;" class="inline-block"><a class="btn btn-read" href="/signin/'.$in['in_id'].'">START READING <i class="fas fa-angle-right"></i></a></div>';

    } elseif(count($this->READ_model->ln_fetch(array(
            'ln_creator_player_id' => $session_en['en_id'],
            'ln_type_player_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
            'ln_status_player_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_parent_blog_id' => $in['in_id'],
        ))) > 0){

        //Find next blog based on player's reading list:
        $next_in_id = $this->READ_model->read__blog_next_find($session_en['en_id'], $in);

        if($next_in_id > 0){
            echo '<div style="padding-bottom:40px;" class="inline-block"><a class="btn btn-read" href="/'.$next_in_id.'">NEXT <i class="fas fa-angle-right"></i></a></div>';
        } else {
            //They seemed to have completed reading this:
            echo '<div class="montserrat read doupper"><i class="fas fa-check-circle ispink"></i> YOU HAVE COMPLETED THIS READ</div>';
        }

    } else {

        echo '<div style="padding-bottom:40px;" class="inline-block"><a class="btn btn-read" href="/read/'.$in['in_id'].'">START READING <i class="fas fa-angle-right"></i></a></div>';

    }


}

echo '<div style="padding-bottom:40px;" class="inline-block pull-right '.superpower_active(10939).'"><a class="btn btn-blog" href="/blog/'.$in['in_id'].'">EDIT <i class="fas fa-pen-square"></i></a></div>';


?>

</div>
