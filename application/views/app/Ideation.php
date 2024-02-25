<?php

//Just Viewing:
$access__i = access__i($focus_i['i__hashtag']);
$this->X_model->create(array(
    'x__player' => $player_e['e__id'],
    'x__type' => 4993, //Member Opened Idea
    'x__next' => $focus_i['i__id'],
));


if($access__i && count($this->X_model->fetch(array(
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $this->config->item('n___42350')) . ')' => null, //Active Writes
        'x__next' => $focus_i['i__id'],
        'x__following' => 4235,
    )))){
    $e___11035 = $this->config->item('e___11035'); //Encyclopedia
    echo '<div class="alert alert-default" role="alert"><span class="icon-block-sm">'.$e___11035[30795]['m__cover'].'</span>You can discover this idea in <a href="'.view_memory(42903,30795).$focus_i['i__hashtag'].'/'.view_memory(6404,4235).'"><b><u>'.$e___11035[30795]['m__title'].'</u></b></a></div>';
}

//Focusing on a certain source?
if(isset($_GET['focus__e']) && superpower_unlocked(12701)){
    //Filtered Specific Source:
    $e_filters = $this->E_model->fetch(array(
        'e__id' => intval($_GET['focus__e']),
        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo view__focus__e($e_filters[0]);
    }
}


//Focus Idea:
echo '<div class="main_item view_12273 row justify-content">';
echo view_card_i(42288,  $focus_i);
echo '</div>';


echo view_i_nav(false, $focus_i, $access__i);

?>

<script>
    $(document).ready(function () {
        show_more(<?= $focus_i['i__id'] ?>);
    });
</script>