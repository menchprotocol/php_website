<?php

//Log source view:
$limit = view_memory(6404,11064);
$e___11035 = $this->config->item('e___11035'); //Encyclopedia
$write_privacy_e = write_privacy_e($focus_e['e__handle']);



//Focus Source:
echo '<div class="main_item view_12274 row justify-content">';
echo view_card_e(42287, $focus_e, null);
echo '</div>';


$focus_menu = ( in_array($focus_e['e__id'], $this->config->item('n___4527')) ? 'e___34649' : 'e___32596' );
$e___focus = $this->config->item($focus_menu);

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
    $can_add = superpower_unlocked(10939) && in_array($x__type, $this->config->item('n___42262'));

    $input_content = '';
    if($can_add){

        if(in_array($x__type, $this->config->item('n___11028'))){

            //ADD SOURCES
            $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="+ Add @source">
                    </div></div></div></div>';

        }

    }

    if($can_add || $coins_count[$x__type]>0){

        $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'">'.$input_content.'<div class="tab_content"></div></div>';

        echo '<li class="nav-item thepill'.$x__type.'"><a class="nav-link handle_nav_'.$m['m__handle'].'" x__type="'.$x__type.'" href="#'.$m['m__handle'].'" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'" data-toggle="tooltip" data-placement="top">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'. view_number($coins_count[$x__type]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$x__type.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';

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




<input type="hidden" id="page_limit" value="<?= $limit ?>" />
<input type="hidden" id="focus_handle" value="<?= $focus_e['e__handle'] ?>" />
<input type="hidden" id="focus__card" value="12274" />
<input type="hidden" id="focus__id" value="<?= $focus_e['e__id'] ?>" />
<script>

    $(document).ready(function () {
        set_autosize($('.text__6197_'+fetch_int_val('#focus__id')));
    });

</script>