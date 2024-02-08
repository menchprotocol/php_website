<?php

//Just Viewing:
$limit = view_memory(6404,11064);
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$e___11035 = $this->config->item('e___11035'); //Summary
$e___26005 = $this->config->item('e___26005');
$write_privacy_i = write_privacy_i($focus_i['i__hashtag']);
$this->X_model->create(array(
    'x__creator' => $member_e['e__id'],
    'x__type' => 4993, //Member Opened Idea
    'x__next' => $focus_i['i__id'],
    'x__weight' => $new_order,
));


if($write_privacy_i){
    echo '<div class="alert alert-default" role="alert"><span class="icon-block-xs">'.$e___11035[30795]['m__cover'].'</span>You can discover this idea in <a href="/'.$focus_i['i__hashtag'].'"><b><u>'.$e___11035[30795]['m__title'].'</u></b></a></div>';
}

//Focusing on a certain source?
if(isset($_GET['focus__e']) && superpower_unlocked(14005)){
    //Filtered Specific Source:
    $e_filters = $this->E_model->fetch(array(
        'e__id' => intval($_GET['focus__e']),
        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo view__focus__e($e_filters[0]);
    }
}



//Focus Source:
echo '<div class="main_item row justify-content">';
echo view_card_i(42288, 0, null, $focus_i);
echo '</div>';


$coins_count = array();
$body_content = '';

echo '<ul class="nav nav-tabs nav12273">';
foreach($this->config->item('e___31890') as $x__type => $m) {

    $coins_count[$x__type] = view_i_covers($x__type, $focus_i['i__id'], 0, false);
    if(!$coins_count[$x__type] && $x__type!=6255 & in_array($x__type, $this->config->item('n___12144'))){ continue; }
    $can_add = $write_privacy_i && in_array($x__type, $this->config->item('n___42262'));

    $input_content = '';
    if($can_add){

        if(in_array($x__type, $this->config->item('n___42261'))){

            $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="+ Add Source">
                    </div></div></div><div class="algolia_pad_finder row justify-content dropdown_'.$x__type.'"></div></div>';

        }

        $body_content .= '<script> $(document).ready(function () { load_finder(12273, '.$x__type.'); }); </script>';

    }

    if($can_add || $coins_count[$x__type]>0){
        $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'">'.$input_content.'<div class="tab_content"></div></div>';

        echo '<li class="nav-item thepill'.$x__type.'"><a class="nav-link handle_nav_'.$m['m__handle'].'" x__type="'.$x__type.'" href="#'.$m['m__handle'].'" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'.view_number($coins_count[$x__type]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$x__type.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';
    }

}
echo '</ul>';
echo $body_content;
$focus_tab = 0;

foreach($e___26005 as $x__type => $m) { //Load Focus Tab:
    if(isset($coins_count[$x__type]) && $coins_count[$x__type] > 0){
        $focus_tab = $x__type;
        echo '<script> $(document).ready(function () { set_hashtag_if_empty(\''.$m['m__handle'].'\'); }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($e___26005 as $x__type => $m) { //Load Focus Tab:
        $focus_tab = $x__type;
        echo '<script> $(document).ready(function () { set_hashtag_if_empty(\''.$m['m__handle'].'\'); }); </script>';
        break;
    }
}



?>

<div id="abc123"></div>
<input type="hidden" id="page_limit" value="<?= $limit ?>" />
<input type="hidden" id="focus_card" value="12273" />
<input type="hidden" id="focus_handle" value="<?= $focus_i['i__hashtag'] ?>" />
<input type="hidden" id="focus_id" value="<?= $focus_i['i__id'] ?>" />
<script>

    $(document).ready(function () {

        show_more(<?= $focus_i['i__id'] ?>);

        //Look for power editor updates:
        $('.x_set_class_text').keypress(function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code==13) {
                x_set_text(this);
                e.preventDefault();
            }
        }).change(function() {
            x_set_text(this);
        });

    });

</script>

