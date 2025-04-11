<?php

$linkplayercreator = ( $player_session ? $player_session['playerid'] : 14068 /* GUEST */ );
//Log view:
$this->Links->create(array(
    'linkplayertype' => 1309378, //Idea Viewed
    'linkplayercreator' => $linkplayercreator,
    'linkplayerup' => $linkplayercreator,
    'linkidealeft' => $focus_i['ideaid'],
));

//See if we need to redirect to starting point?
if($player_session && !player_session(10939) && count($this->Links->read(array(
        'linkplayercreator' => $player_session['playerid'],
        'linkplayertype' => 4235, //Get started
        'linkidealeft' => $focus_i['ideaid'],
    )))){
    //Player without editing superpowers has viewed an idea they have idea_discovered already, so get them there:
    js_php_redirect('/'.$focus_i['ideahashtag'].'/start', 13);
}

//Focus Idea:
echo '<div class="view_12273 row justify-content">';
echo idea_view(42288,  $focus_i);
echo '</div>';

if(player_session(10939) || isset($_GET['open'])){
    echo view_idea_nav(false, $focus_i);
}

?>

<script>
    $(document).ready(function () {
        load_hashtag_menu();
        show_more(<?= $focus_i['ideaid'] ?>);
    });
</script>