<?php

//See if we need to redirect to starting point?
if($player_e && !superpower_unlocked(10939) && count($this->Ledger->fetch(array(
        'linkcreator' => $player_e['playerid'],
        'linktype' => 4235, //Get started
        'linkleft' => $focus_i['ideaid'],
    )))){
    //Player without editing superpowers has viewed an idea they have discovered already, so get them there:
    js_php_redirect('/'.$focus_i['ideahashtag'].'/start', 13);
}

//Focus Idea:
echo '<div class="view_12273 row justify-content">';
echo view_card_i(42288,  $focus_i);
echo '</div>';

if(superpower_unlocked(10939) || isset($_GET['open'])){
    echo view_idea_nav(false, $focus_i);
}

?>

<script>
    $(document).ready(function () {
        load_hashtag_menu();
        show_more(<?= $focus_i['ideaid'] ?>);
    });
</script>