<?php

//Log source view:
$limit = view_memory(6404,11064);
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$this->X_model->create(array(
    'x__creator' => $member_e['e__id'],
    'x__type' => 4994, //Member Viewed Source
    'x__down' => $e['e__id'],
    'x__weight' => $new_order,
));



//Load Top:
$counter_top = view_e_covers(11030, $e['e__id'], 0, false);
echo '<div class="hideIfEmpty headline_body_11030" read-counter="'.$counter_top.'"></div>';
echo '<script type="text/javascript"> $(document).ready(function () { setTimeout(function () { load_tab(11030, true); }, 1587); }); </script>';



//Focus Source:
echo '<div class="main_item row justify-content">';
echo view_card_e(4251, $e, null);
echo '</div>';



$coins_count = array();
$body_content = '';
echo '<ul class="nav nav-tabs nav12274">';
foreach($this->config->item('e___14874') as $x__type => $m) {
    $coins_count[$x__type] = view_e_covers($x__type, $e['e__id'], 0, false);
    $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'"></div>';
    echo '<li class="nav-item thepill'.$x__type.'"><a class="nav-link" active x__type="'.$x__type.'" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'" onclick="toggle_pills('.$x__type.')">&nbsp;<span class="icon-block-xxs">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'. view_number($coins_count[$x__type]) . '</span></a></li>';
}
echo '</ul>';
echo $body_content;

$focus_menu = ( in_array($e['e__id'], $this->config->item('n___4527')) ? 'e___34649' : 'e___32596' );

$focus_tab = 0;
foreach($this->config->item($focus_menu) as $x__type => $m) {
    if(isset($coins_count[$x__type]) && $coins_count[$x__type] > 0){
        $focus_tab = $x__type;
        echo '<script type="text/javascript"> $(document).ready(function () { toggle_pills('.$focus_tab.'); }); </script>';
        break;
    }
}
if(!$focus_tab){
    foreach($this->config->item($focus_menu) as $x__type => $m) {
        $focus_tab = $x__type;
        echo '<script type="text/javascript"> $(document).ready(function () { toggle_pills('.$focus_tab.'); }); </script>';
        break;
    }
}

?>

<input type="hidden" id="page_limit" value="<?= $limit ?>" />
<input type="hidden" id="focus_card" value="12274" />
<input type="hidden" id="focus_id" value="<?= $e['e__id'] ?>" />
<script type="text/javascript">

    $(document).ready(function () {
        set_autosize($('.texttype__lg.text__6197_'+fetch_int_val('#focus_id')));
    });

    //Define file upload variables:
    var upload_control = $(".inputfile");
    var $input = $('.drag-box').find('input[type="file"]'),
        $label = $('.drag-box').find('label'),
        showFiles = function (files) {
            $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
        };

</script>
