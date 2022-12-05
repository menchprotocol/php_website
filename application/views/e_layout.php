<?php

//Log source view:
$limit = view_memory(6404,11064);
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$this->X_model->create(array(
    'x__source' => $member_e['e__id'],
    'x__type' => 4994, //Member Viewed Source
    'x__down' => $e['e__id'],
    'x__spectrum' => $new_order,
));



//Load Top:
$counter_top = view_coins_e(11030, $e['e__id'], 0, false);
echo '<div class="hideIfEmpty headline_body_11030" read-counter="'.$counter_top.'"></div>';
echo '<script type="text/javascript"> $(document).ready(function () { setTimeout(function () { load_tab(12274, 11030, true); }, 233); }); </script>';



//Focus Source:
echo '<div class="main_item row justify-content">';
echo view_e(4251, $e, null);
echo '</div>';



$coins_count = array();
$body_content = '';
echo '<ul class="nav nav-tabs nav12274">';
foreach($this->config->item('e___14874') as $x__type => $m) {
    $coins_count[$x__type] = view_coins_e($x__type, $e['e__id'], 0, false);
    $body_content .= '<div class="headlinebody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'"></div>';
    echo '<li class="nav-item thepill'.$x__type.'"><a class="nav-link" active x__type="'.$x__type.'" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'" onclick="toggle_pills('.$x__type.')">&nbsp;<span class="icon-block-xxs">'.$m['m__cover'].'</span><span class="css__title hideIfEmpty xtypecounter'.$x__type.'">'. view_number($coins_count[$x__type]) . '</span></a></li>';
}
echo '</ul>';
echo $body_content;
$focus_tab = 0;
foreach($this->config->item('e___26005') as $x__type => $m) { //Load Focus Tab:
    if($coins_count[$x__type] > 0){
        $focus_tab = $x__type;
        echo '<script type="text/javascript"> $(document).ready(function () { toggle_pills('.$x__type.'); }); </script>';
        break;
    }
}

?>


<input type="hidden" id="focus_coin" value="12274" />
<input type="hidden" id="focus_id" value="<?= $e['e__id'] ?>" />
<script type="text/javascript">

    $(function () {
        var $win = $(window);
        $win.scroll(function () {
            var px_tp_top = parseInt($win.scrollTop());
            var px_tp_bottom = parseInt($(document).height() - ($win.height() + $win.scrollTop()));
            console.log('Pixels to TOP '+px_tp_top+' and BOTTOM '+px_tp_bottom);
            if (px_tp_top <= 377){
                //Load Top More, if any:
                view_load_page_e(11030, <?= ( $counter_top==$limit ? '0' : '1' ) ?>);
            } else if (px_tp_bottom <= 377) {
                view_load_page_e(<?= $focus_tab ?>, <?= ( $coins_count[$focus_tab]==$limit ? '0' : '1' ) ?>);
            }
        });
    });

    //Define file upload variables:
    var upload_control = $(".inputfile");
    var $input = $('.drag-box').find('input[type="file"]'),
        $label = $('.drag-box').find('label'),
        showFiles = function (files) {
            $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
        };

    $(document).ready(function () {
        set_autosize($('.texttype__lg.text__6197_'+current_id()));
    });

</script>
