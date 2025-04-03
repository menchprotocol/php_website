<?php


//Focus Player:
echo '<div class="view_12274 row justify-content">';
echo view_card_player(42287, $focus_e, null);
echo '</div>';


$players___focus = $this->config->item('players___32596');

$coins_count = array();
$body_content = '';


echo '<ul class="nav nav-tabs nav12274">';
foreach($this->config->item('players___31916') as $linkplayertype => $m) {

    $superpowers_required = array_intersect($this->config->item('playerids___10957'), $m['m__following']);
    if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
        continue;
    }

    $coins_count[$linkplayertype] = view_player_cards($linkplayertype, $focus_e['playerid'], 0, false);
    if(!$coins_count[$linkplayertype] && in_array($linkplayertype, $this->config->item('playerids___12144'))){ continue; }

    $input_content = '';
    if(superpower_unlocked(10939)){

        if(in_array($linkplayertype, $this->config->item('playerids___11028'))){

            //ADD SOURCES
            $input_content .= '<div class="new_list new-list-'.$linkplayertype.'"><div class="col-12 container-center"><div class="dropdown_'.$linkplayertype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Search or Link @Players">
                    </div></div></div></div>';
            $body_content .= '<script> $(document).ready(function () { player_load_finder('.$linkplayertype.'); }); </script>';

        } elseif(in_array($linkplayertype, $this->config->item('playerids___42261'))){

            //ADD IDEAS
            $input_content .= '<div class="new_list new-list-'.$linkplayertype.'"><div class="col-12 container-center"><div class="dropdown_'.$linkplayertype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__i algolia__ci dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Search or Link #ideas">
                    </div></div></div></div>';
            $body_content .= '<script> $(document).ready(function () { i_load_finder('.$linkplayertype.'); }); </script>';

        }

    }

    if(in_array($linkplayertype, $this->config->item('playerids___42945')) || $coins_count[$linkplayertype]>0){

        $body_content .= '<div class="headlinebody pillbody headline_body_'.$linkplayertype.' hidden" read-counter="'.$coins_count[$linkplayertype].'">'.$input_content.'<div class="tab_content"></div></div>';

        echo '<li class="nav-item thepill'.$linkplayertype.'"><a class="nav-link handle_nav_'.$m['m__handle'].'" linkplayertype="'.$linkplayertype.'" href="#'.$m['m__handle'].'" title="'.$m['m__title'].'">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$linkplayertype.'">'. view_number($coins_count[$linkplayertype]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$linkplayertype.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';

    }
}
echo '</ul>';
echo $body_content;


$focus_tab = 0;
foreach($players___focus as $linkplayertype => $m) {
    if(isset($coins_count[$linkplayertype]) && $coins_count[$linkplayertype] > 0){
        $focus_tab = $linkplayertype;
        echo '<script> $(document).ready(function () { if(!document.location.hash) { load_hashtag_menu(\''.$m['m__handle'].'\'); } }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($players___focus as $linkplayertype => $m) {
        $focus_tab = $linkplayertype;
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