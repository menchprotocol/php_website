<?php

//Log source view:
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$this->X_model->create(array(
    'x__source' => $member_e['e__id'],
    'x__type' => 4994, //Member Viewed Source
    'x__down' => $e['e__id'],
    'x__spectrum' => $new_order,
));

?>

<input type="hidden" id="focus__type" value="12274" />
<input type="hidden" id="focus__id" value="<?= $e['e__id'] ?>" />
<script src="/application/views/e_layout.js?v=<?= view_memory(6404,11060) ?>" type="text/javascript"></script>

<?php

//Focus Source:
echo '<div class="row justify-content">';
echo view_e(4251, $e, null, source_of_e($e['e__id']));
echo '</div>';


//Source Menu:
echo '<ul class="nav nav-pills nav12274"></ul>';

$item_counts = array();
$e___11089 = $this->config->item('e___11089');
foreach($e___11089 as $x__type => $m) {

    //Have Needed Superpowers?
    $require = 0;
    $missing = 0;
    $meeting = 0;
    foreach(array_intersect($this->config->item('n___10957'), $m['m__profile']) as $superpower_required){
        $require++;
        if(superpower_active($superpower_required, true)){
            $meeting++;
        } else {
            $missing++;
        }
    }
    if($require && !$meeting){
        //RELAX: Meet any requirement and it would be shown
        continue;
    }

    $item_counts[$x__type] = view_coins_e($x__type, $e['e__id'], 0, false);
}

//Determine focus/auto-load tab:
$focus_tab = 0;
foreach($this->config->item('e___26005') as $x__type => $m) {
    if(isset($item_counts[$x__type]) && $item_counts[$x__type] > 0){
        $focus_tab = $x__type;
        break;
    }
}


//Print results:
foreach($item_counts as $x__type => $counter) {
    echo view_pill($x__type, $counter, $e___11089[$x__type], ($x__type==$focus_tab ? view_body_e($x__type, $counter, $e['e__id']) : null ), ($x__type==$focus_tab));
}

?>
