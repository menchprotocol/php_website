<?php


//Focus Source:
echo '<div class="view__12274 row justify-content">';
echo view__card_e(42287, $focus_e, null);
echo '</div>';


$e___focus = $this->config->item('e___32596');

$coins_count = array();
$body_content = '';


echo '<ul class="nav nav-tabs nav12274">';
foreach($this->config->item('e___31916') as $linktype => $m) {

    $superpowers_required = array_intersect($this->config->item('n___10957'), $m['m__following']);
    if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
        continue;
    }

    $coins_count[$linktype] = view__e_covers($linktype, $focus_e['playerid'], 0, false);
    if(!$coins_count[$linktype] && in_array($linktype, $this->config->item('n___12144'))){ continue; }

    $input_content = '';
    if(superpower_unlocked(10939)){

        if(in_array($linktype, $this->config->item('n___11028'))){

            //ADD SOURCES
            $input_content .= '<div class="new_list new-list-'.$linktype.'"><div class="col-12 container-center"><div class="dropdown_'.$linktype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view__memory(6404,6197) . '"
                               placeholder="Search or Link @sources">
                    </div></div></div></div>';
            $body_content .= '<script> $(document).ready(function () { e_load_finder('.$linktype.'); }); </script>';

        } elseif(in_array($linktype, $this->config->item('n___42261'))){

            //ADD IDEAS
            $input_content .= '<div class="new_list new-list-'.$linktype.'"><div class="col-12 container-center"><div class="dropdown_'.$linktype.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__i algolia__ci dotransparent add-input"
                               maxlength="' . view__memory(6404,6197) . '"
                               placeholder="Search or Link #ideas">
                    </div></div></div></div>';
            $body_content .= '<script> $(document).ready(function () { i_load_finder('.$linktype.'); }); </script>';

        }

    }

    if(in_array($linktype, $this->config->item('n___42945')) || $coins_count[$linktype]>0){

        $body_content .= '<div class="headlinebody pillbody headline_body_'.$linktype.' hidden" read-counter="'.$coins_count[$linktype].'">'.$input_content.'<div class="tab_content"></div></div>';

        echo '<li class="nav-item thepill'.$linktype.'"><a class="nav-link handle_nav_'.$m['m__handle'].'" linktype="'.$linktype.'" href="#'.$m['m__handle'].'" title="'.$m['m__title'].'">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$linktype.'">'. view__number($coins_count[$linktype]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$linktype.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';

    }
}
echo '</ul>';
echo $body_content;


$focus_tab = 0;
foreach($e___focus as $linktype => $m) {
    if(isset($coins_count[$linktype]) && $coins_count[$linktype] > 0){
        $focus_tab = $linktype;
        echo '<script> $(document).ready(function () { if(!document.location.hash) { load_hashtag_menu(\''.$m['m__handle'].'\'); } }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($e___focus as $linktype => $m) {
        $focus_tab = $linktype;
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