<?php


//Focus Source:
echo '<div class="main_item view_12274 row justify-content">';
echo view_card_e(42287, $focus_e, null);
echo '</div>';


$e___focus = $this->config->item('e___32596');

$coins_count = array();
$body_content = '';


echo '<ul class="nav nav-tabs nav12274">';
foreach($this->config->item('e___31916') as $x__type => $m) {

    $superpowers_required = array_intersect($this->config->item('n___10957'), $m['m__following']);
    if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
        continue;
    }

    $coins_count[$x__type] = view_e_covers($x__type, $focus_e['e__id'], 0, false);
    if(!$coins_count[$x__type] && in_array($x__type, $this->config->item('n___12144'))){ continue; }

    $input_content = '';
    if(superpower_unlocked(10939)){

        if(in_array($x__type, $this->config->item('n___11028'))){

            //ADD SOURCES
            $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Search or Link @sources">
                    </div></div></div></div>';
            $body_content .= '<script> $(document).ready(function () { e_load_finder('.$x__type.'); }); </script>';

        } elseif(in_array($x__type, $this->config->item('n___42261'))){

            //ADD IDEAS
            $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__i algolia__ci dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Search or Link #ideas">
                    </div></div></div></div>';
            $body_content .= '<script> $(document).ready(function () { i_load_finder('.$x__type.'); }); </script>';

        }

    }

    if(in_array($x__type, $this->config->item('n___42945')) || $coins_count[$x__type]>0){

        $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'">'.$input_content.'<div class="tab_content"></div></div>';

        echo '<li class="nav-item thepill'.$x__type.'"><a class="nav-link handle_nav_'.$m['m__handle'].'" x__type="'.$x__type.'" href="#'.$m['m__handle'].'" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'. view_number($coins_count[$x__type]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$x__type.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';

    }
}
echo '</ul>';
echo $body_content;


$focus_tab = 0;
foreach($e___focus as $x__type => $m) {
    if(isset($coins_count[$x__type]) && $coins_count[$x__type] > 0){
        $focus_tab = $x__type;
        echo '<script> $(document).ready(function () { set_hashtag_if_empty(\''.$m['m__handle'].'\'); }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($e___focus as $x__type => $m) {
        $focus_tab = $x__type;
        echo '<script> $(document).ready(function () { set_hashtag_if_empty(\''.$m['m__handle'].'\'); }); </script>';
        break;
    }
}

?>

<script>
    $(document).ready(function () {
        load_hashtag_menu();
    });
</script>