<?php

//Just Viewing:
$limit = view_memory(6404,11064);
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$this->X_model->create(array(
    'x__creator' => $member_e['e__id'],
    'x__type' => 4993, //Member Opened Idea
    'x__right' => $i['i__id'],
    'x__weight' => $new_order,
));


//Focusing on a certain source?
if(isset($_GET['load__e']) && superpower_active(14005, true)){
    //Filtered Specific Source:
    $e_filters = $this->E_model->fetch(array(
        'e__id' => intval($_GET['load__e']),
        'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo view__load__e($e_filters[0]);
    }
}






//Load Top:
$counter_top = view_i_covers(11019, $i['i__id'], 0, false);
echo '<div class="hideIfEmpty headline_body_11019" read-counter="'.$counter_top.'"></div>';
echo '<script type="text/javascript"> $(document).ready(function () { setTimeout(function () { load_tab(11019, true); }, 377); }); </script>';



//Focus Source:
echo '<div class="main_item row justify-content">';
echo view_card_i(4250, 0, null, $i);
echo '</div>';



$coins_count = array();
$body_content = '';
echo '<ul class="nav nav-tabs nav12273">';
foreach($this->config->item('e___41092') as $x__type => $m) {
    $coins_count[$x__type] = view_i_covers($x__type, $i['i__id'], 0, false);
    if(!$coins_count[$x__type] && $x__type!=6255 & in_array($x__type, $this->config->item('n___12144'))){ continue; }
    $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'"></div>';
    echo '<li class="nav-item thepill'.$x__type.'"><a class="nav-link" active x__type="'.$x__type.'" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'" onclick="toggle_pills('.$x__type.')">&nbsp;<span class="icon-block-xxs">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'.view_number($coins_count[$x__type]) . '</span></a></li>';
}
echo '</ul>';
echo $body_content;
$focus_tab = 0;
foreach($this->config->item('e___26005') as $x__type => $m) { //Load Focus Tab:
    if($coins_count[$x__type] > 0){
        $focus_tab = $x__type;
        echo '<script type="text/javascript"> $(document).ready(function () { toggle_pills('.$focus_tab.'); }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($this->config->item('e___26005') as $x__type => $m) { //Load Focus Tab:
        $focus_tab = $x__type;
        echo '<script type="text/javascript"> $(document).ready(function () { toggle_pills('.$focus_tab.'); }); </script>';
        break;
    }
}



?>


<input type="hidden" id="page_limit" value="<?= $limit ?>" />
<input type="hidden" id="focus_card" value="12273" />
<input type="hidden" id="focus_id" value="<?= $i['i__id'] ?>" />
<script type="text/javascript">

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

