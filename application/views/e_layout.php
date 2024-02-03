<?php

//Log source view:
$limit = view_memory(6404,11064);
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$e___11035 = $this->config->item('e___11035'); //Summary
$write_privacy_e = write_privacy_e($e['e__handle']);
$this->X_model->create(array(
    'x__creator' => $member_e['e__id'],
    'x__type' => 4994, //Member Viewed Source
    'x__down' => $e['e__id'],
    'x__weight' => $new_order,
));



//Focus Source:
echo '<div class="main_item row justify-content">';
echo view_card_e(42287, $e, null);
echo '</div>';


$focus_menu = ( in_array($e['e__id'], $this->config->item('n___4527')) ? 'e___34649' : 'e___32596' );
$e___focus = $this->config->item($focus_menu);

$coins_count = array();
$body_content = '';


echo '<ul class="nav nav-tabs nav12274">';
foreach($this->config->item('e___31916') as $x__type => $m) {
    $coins_count[$x__type] = view_e_covers($x__type, $e['e__id'], 0, false);
    if(!$coins_count[$x__type] && in_array($x__type, $this->config->item('n___12144'))){ continue; }
    $can_add = superpower_unlocked(10939) && in_array($x__type, $this->config->item('n___42262'));

    $input_content = '';
    if($can_add){

        if(in_array($x__type, $this->config->item('n___42261'))){

            //ADD IDEAS
            $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="list-group"><div class="list-group-item dropdown_'.$x__type.' list-adder">
                <div class="input-group border">
                    <input type="text"
                           class="form-control form-control-thick algolia_finder dotransparent add-input"
                           placeholder="+ Add Idea">
                </div></div></div></div><div class="algolia_pad_finder row justify-content dropdown_'.$x__type.'"></div></div>';

        } elseif(in_array($x__type, $this->config->item('n___11028'))){

            //ADD SOURCES
            $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="+ Add Source">
                    </div></div></div><div class="algolia_pad_finder row justify-content dropdown_'.$x__type.'"></div></div>';

        }

        $body_content .= '<script> $(document).ready(function () { load_finder(12274, '.$x__type.'); }); </script>';

    }

    if($can_add || $coins_count[$x__type]>0){

        $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'">'.$input_content.'<div class="tab_content"></div></div>';

        echo '<li class="nav-item thepill'.$x__type.'"><a class="nav-link" x__type="'.$x__type.'" href="javascript:void(0);" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'" data-toggle="tooltip" data-placement="top" onclick="toggle_pills('.$x__type.')">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'. view_number($coins_count[$x__type]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$x__type.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';

    }
}
echo '</ul>';
echo $body_content;


$focus_tab = 0;
foreach($e___focus as $x__type => $m) {
    if(isset($coins_count[$x__type]) && $coins_count[$x__type] > 0){
        $focus_tab = $x__type;
        echo '<script> $(document).ready(function () { toggle_pills('.$focus_tab.'); }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($e___focus as $x__type => $m) {
        $focus_tab = $x__type;
        echo '<script> $(document).ready(function () { toggle_pills('.$focus_tab.'); }); </script>';
        break;
    }
}

?>

<input type="hidden" id="page_limit" value="<?= $limit ?>" />
<input type="hidden" id="focus_handle" value="<?= $e['e__handle'] ?>" />
<input type="hidden" id="focus_card" value="12274" />
<input type="hidden" id="focus_id" value="<?= $e['e__id'] ?>" />
<script>

    $(document).ready(function () {
        set_autosize($('.text__6197_'+fetch_int_val('#focus_id')));
    });

</script>