<?php

$linkplayercreator = ( $player_active ? $player_active['playerid'] : 14068 /* GUEST */ );
//Log view:
$this->Links->create(array(
    'linkplayertype' => 1309378, //Idea Viewed
    'linkplayercreator' => $linkplayercreator,
    'linkplayerup' => $linkplayercreator,
    'linkidealeft' => $focus_i['ideaid'],
));

//See if we need to redirect to starting point?
if($player_active && !superpower_unlocked(10939) && count($this->Links->read(array(
        'linkplayercreator' => $player_active['playerid'],
        'linkplayertype' => 4235, //Get started
        'linkidealeft' => $focus_i['ideaid'],
    )))){
    //Player without editing superpowers has viewed an idea they have discovered already, so get them there:
    js_php_redirect('/'.$focus_i['ideahashtag'].'/start', 13);
}

//Focus Idea:
echo '<div class="view_12273 row justify-content">';
echo idea_view(42288,  $focus_i);
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