<?php

//Log source view:
$limit = view_memory(6404,11064);
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$write_access_e = write_access_e($e['e__id']);
$this->X_model->create(array(
    'x__creator' => $member_e['e__id'],
    'x__type' => 4994, //Member Viewed Source
    'x__down' => $e['e__id'],
    'x__weight' => $new_order,
));



//Load Top:
$counter_top = view_e_covers(11030, $e['e__id'], 0, false);

echo '<div class="hideIfEmpty headline_body_11030" read-counter="'.$counter_top.'"><div class="tab_content"></div>'.( $write_access_e ? '<div class="new-list-11030"><div class="col-md-8 col-sm-10 col-12 container-center"><div class="dropdown_11030 list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_search dotransparent add-input e-adder"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="'.$e___11035[31774]['m__title'].'">
                    </div></div></div><div class="algolia_pad_search row justify-content dropdown_11030"></div></div>' : '' ).'</div>';
echo '<script type="text/javascript"> $(document).ready(function () { setTimeout(function () { load_tab(11030, true); }, 377); initiate_algolia(); i_load_search(11019); }); </script>';


//Focus Source:
echo '<div class="main_item row justify-content">';
echo view_card_e(4251, $e, null);
echo '</div>';



$coins_count = array();
$body_content = '';
echo '<ul class="nav nav-tabs nav12274">';
foreach($this->config->item('e___41091') as $x__type => $m) {
    $coins_count[$x__type] = view_e_covers($x__type, $e['e__id'], 0, false);
    if(!$coins_count[$x__type] && in_array($x__type, $this->config->item('n___12144'))){ continue; }

    $input_content = '';
    if($write_access_e){

        if($x__type==12273){

            //IDEAS
            $input_content .= '<div class="new-list-'.$x__type.'"><div class="col-md-8 col-sm-10 col-12 container-center"><div class="list-group"><div class="list-group-item dropdown_'.$x__type.' list-adder">
                <div class="input-group border">
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent add-input i-adder"
                           maxlength="' . view_memory(6404,4736) . '"
                           placeholder="'.$e___11035[14016]['m__title'].'">
                </div></div></div></div><div class="algolia_pad_search row justify-content dropdown_'.$x__type.'"></div></div>';

        } elseif($x__type==12274){

            $input_content .= '<div class="new-list-'.$x__type.'"><div class="col-md-8 col-sm-10 col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_search dotransparent add-input e-adder"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="'.$e___11035[31775]['m__title'].'">
                    </div></div></div><div class="algolia_pad_search row justify-content dropdown_'.$x__type.'"></div></div>';

        }

        $body_content .= '<script type="text/javascript"> $(document).ready(function () { e_load_search('.$x__type.'); }); </script>';

    }



    $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'">'.$input_content.'<div class="tab_content"></div></div>';
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

        $('.toggle_i_checkbox').change(function() {
            console.log('hi');
            //$(this).prop('checked')
        });
        $('.toggle_e_checkbox').change(function() {
            console.log('bye');
            //$(this).prop('checked')
        });

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