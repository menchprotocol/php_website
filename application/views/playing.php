<?php

$chainplayercreator = ( $player_session ? $player_session['playerid'] : 14068 /* GUEST */ );
//Log view:
$this->Links->create(array(
    'chainplayertype' => 44176, //Player View
    'chainplayerup' => $focus_e['playerid'],
    'chainplayerdown' => $chainplayercreator,
    'chainplayercreator' => $chainplayercreator,
));

//Focus Player:
echo '<div class="view_12274 row justify-content">';
echo player_view(42287, $focus_e, null);
echo '</div>';

$players___focus = $this->config->item('players___32596');

$coins_count = array();
$body_content = '';


echo '<ul class="nav nav-tabs nav12274">';
foreach($this->config->item('players___31916') as $chainplayertype => $m) {

    $superpowers_required = array_intersect($this->config->item('playerids___10957'), $m['m__following']);
    if(count($superpowers_required) && !player_session(end($superpowers_required))){
        continue;
    }

    $coins_count[$chainplayertype] = players_query($chainplayertype, $focus_e['playerid'], 0, false);
    if(!$coins_count[$chainplayertype] && in_array($chainplayertype, $this->config->item('playerids___12144'))){ continue; }

    $input_content = '';
    if(player_session(10939)){

        if(in_array($chainplayertype, $this->config->item('playerids___11028'))){

            //ADD SOURCES
            $input_content .= '<div class="new_list new-list-'.$chainplayertype.'"><div class="col-12 container-center"><div class="dropdown_'.$chainplayertype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Create New or Link Existing @Players">
                    </div></div></div></div>';
            $body_content .= '<script> $(document).ready(function () { player_load_finder('.$chainplayertype.'); }); </script>';

        } elseif(0 && in_array($chainplayertype, $this->config->item('playerids___42261'))){

            //TODO Activate Later?
            //ADD IDEAS
            $input_content .= '<div class="new_list new-list-'.$chainplayertype.'"><div class="col-12 container-center"><div class="dropdown_'.$chainplayertype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__i algolia__ci dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Create New or Link Existing #ideas">
                    </div></div></div></div>';
            $body_content .= '<script> $(document).ready(function () { idea_load_search('.$chainplayertype.'); }); </script>';

        }

    }

    if(in_array($chainplayertype, $this->config->item('playerids___42945')) || $coins_count[$chainplayertype]>0){

        $body_content .= '<div class="headlinebody pillbody headline_body_'.$chainplayertype.' hidden" read-counter="'.$coins_count[$chainplayertype].'">'.$input_content.'<div class="tab_content"></div></div>';

        echo '<li class="nav-item thepill'.$chainplayertype.'"><a class="nav-link handle_nav_'.$m['m__handle'].'" chainplayertype="'.$chainplayertype.'" href="#'.$m['m__handle'].'" title="'.$m['m__title'].'">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$chainplayertype.'">'. view_number($coins_count[$chainplayertype]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$chainplayertype.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';

    }
}
echo '</ul>';
echo $body_content;


$focus_tab = 0;
foreach($players___focus as $chainplayertype => $m) {
    if(isset($coins_count[$chainplayertype]) && $coins_count[$chainplayertype] > 0){
        $focus_tab = $chainplayertype;
        echo '<script> $(document).ready(function () { if(!document.location.hash) { load_hashtag_menu(\''.$m['m__handle'].'\'); } }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($players___focus as $chainplayertype => $m) {
        $focus_tab = $chainplayertype;
        echo '<script> $(document).ready(function () { if(!document.location.hash) { load_hashtag_menu(\''.$m['m__handle'].'\'); } }); </script>';
        break;
    }
}

?>

<script>
    $(document).ready(function () {
        load_hashtag_menu();
    });
</script>