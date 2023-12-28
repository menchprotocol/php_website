<?php

//Just Viewing:
$limit = view_memory(6404,11064);
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$write_access_i = write_access_i($focus_i['i__hashtag']);
$this->X_model->create(array(
    'x__creator' => $member_e['e__id'],
    'x__type' => 4993, //Member Opened Idea
    'x__right' => $focus_i['i__id'],
    'x__weight' => $new_order,
));


//Focusing on a certain source?
if(isset($_GET['focus__e']) && superpower_unlocked(14005)){
    //Filtered Specific Source:
    $e_filters = $this->E_model->fetch(array(
        'e__id' => intval($_GET['focus__e']),
        'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo view__focus__e($e_filters[0]);
    }
}


if(in_array(11019, $this->config->item('n___14686'))){

    //Load Top:
    $counter_top = view_i_covers(11019, $focus_i['i__id'], 0, false);
    if($write_access_i){
        echo '<div class="hideIfEmpty headline_body_11019" read-counter="'.$counter_top.'"><div class="tab_content"></div>'.( $write_access_i ? '<div class="new-list-11019"><div class="col-md-8 col-sm-10 col-12 container-center"><div class="dropdown_11019 list-adder ">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick add-input algolia_search dotransparent"
                               placeholder="'.$e___11035[31773]['m__title'].'">
                    </div></div></div><div class="algolia_pad_search row justify-content dropdown_11019"></div></div>' : '' ).'</div>';
    }

    echo '<script> $(document).ready(function () { initiate_algolia(); load_search(12273,11019); }); </script>';
    echo '<script> $(document).ready(function () { setTimeout(function () { load_tab(11019, true);  }, 377); }); </script>';

}



//Focus Source:
echo '<div class="main_item row justify-content">';
echo view_card_i(4250, 0, null, $focus_i);
echo '</div>';


$coins_count = array();
$body_content = '';
echo '<ul class="nav nav-tabs nav12273">';
foreach($this->config->item('e___41092') as $x__type => $m) {
    $coins_count[$x__type] = view_i_covers($x__type, $focus_i['i__id'], 0, false);
    if(!$coins_count[$x__type] && $x__type!=6255 & in_array($x__type, $this->config->item('n___12144'))){ continue; }

    $input_content = '';
    if($write_access_i){

        if(in_array($x__type, $this->config->item('n___11020'))){

            //IDEAS
            $input_content .= '<div class="new-list-'.$x__type.'"><div class="col-md-8 col-sm-10 col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                <div class="input-group border">
                    <input type="text"
                           class="form-control form-control-thick add-input algolia_search dotransparent"
                           placeholder="'.$e___11035[31772]['m__title'].'">
                </div></div></div><div class="algolia_pad_search dropdown_12273 row justify-content"></div></div>';

        } elseif($x__type==12274){

            $input_content .= '<div class="new-list-'.$x__type.'"><div class="col-md-8 col-sm-10 col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_search dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="'.$e___11035[14055]['m__title'].'">
                    </div></div></div><div class="algolia_pad_search row justify-content dropdown_'.$x__type.'"></div></div>';

        }

        $body_content .= '<script> $(document).ready(function () { load_search(12273, '.$x__type.'); }); </script>';

    }

    $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'">'.$input_content.'<div class="tab_content"></div></div>';

    echo '<li class="nav-item thepill'.$x__type.'"><a class="nav-link" x__type="'.$x__type.'" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'" onclick="toggle_pills('.$x__type.')">&nbsp;<span class="icon-block-xxs">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'.view_number($coins_count[$x__type]) . '</span></a></li>';
}
echo '</ul>';
echo $body_content;
$focus_tab = 0;
foreach($this->config->item('e___26005') as $x__type => $m) { //Load Focus Tab:
    if(isset($coins_count[$x__type]) && $coins_count[$x__type] > 0){
        $focus_tab = $x__type;
        echo '<script> $(document).ready(function () { toggle_pills('.$focus_tab.'); }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($this->config->item('e___26005') as $x__type => $m) { //Load Focus Tab:
        $focus_tab = $x__type;
        echo '<script> $(document).ready(function () { toggle_pills('.$focus_tab.'); }); </script>';
        break;
    }
}



?>


<input type="hidden" id="page_limit" value="<?= $limit ?>" />
<input type="hidden" id="focus_card" value="12273" />
<input type="hidden" id="focus_handle" value="<?= $focus_i['i__hashtag'] ?>" />
<input type="hidden" id="focus_id" value="<?= $focus_i['i__id'] ?>" />
<script>

    $(document).ready(function () {
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

