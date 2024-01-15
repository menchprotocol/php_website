<?php

//Log source view:
$limit = view_memory(6404,11064);
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$write_privacy_e = write_privacy_e($e['e__handle']);
$this->X_model->create(array(
    'x__creator' => $member_e['e__id'],
    'x__type' => 4994, //Member Viewed Source
    'x__down' => $e['e__id'],
    'x__weight' => $new_order,
));


//Website redirect hack:
if(!$member_e['e__id'] && in_array($e['e__id'], $this->config->item('n___14870')) && $e['e__id']!=website_setting(0)){
    $e___14870 = $this->config->item('e___14870'); //Website Partner
    js_php_redirect('https://'.$e___14870[$e['e__id']]['m__message'], 13);
}



//Focus Source:
echo '<div class="main_item row justify-content">';
echo view_card_e(42287, $e, null);
echo '</div>';




$social_ui = null;
foreach($this->config->item('e___14036') as $e__id => $m){
    foreach($this->X_model->fetch(array(
        'x__up' => $e__id,
        'x__down' => $e['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array(), 0, 0) as $social_link){

        //Determine link type:
        if(filter_var($social_link['x__message'], FILTER_VALIDATE_URL)){
            //We made sure not the current website:
            $social_url = $social_link['x__message'];
        } elseif(filter_var($social_link['x__message'], FILTER_VALIDATE_EMAIL)){
            $social_url = 'mailto:'.$social_link['x__message'];
        } elseif(strlen(preg_replace("/[^0-9]/", "", $social_link['x__message'])) > 5){
            //Phone
            $social_url = 'tel:'.preg_replace("/[^0-9]/", "", $social_link['x__message']);
        } else {
            //Unknown!
            continue;
        }

        //Append to links:
        $social_ui .= '<li><a href="'.$social_url.'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'">'.$m['m__cover'].'</a></li>';


    }
}
if($social_ui){
    echo '<div class="source-social">';
    echo '<ul>';
    echo $social_ui;
    echo '</ul>';
    echo '</div>';
}



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
            $input_content .= '<div class="new-list-'.$x__type.'"><div class="col-12 container-center"><div class="list-group"><div class="list-group-item dropdown_'.$x__type.' list-adder">
                <div class="input-group border">
                    <input type="text"
                           class="form-control form-control-thick algolia_finder dotransparent add-input"
                           placeholder="+ Add Idea">
                </div></div></div></div><div class="algolia_pad_finder row justify-content dropdown_'.$x__type.'"></div></div>';

        } elseif(in_array($x__type, $this->config->item('n___11028'))){

            //ADD SOURCES
            $input_content .= '<div class="new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
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

        echo '<li class="nav-item thepill'.$x__type.'"><a class="nav-link" x__type="'.$x__type.'" href="javascript:void(0);" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'" onclick="toggle_pills('.$x__type.')">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'. view_number($coins_count[$x__type]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$x__type.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';

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

        $('.toggle_i_checkbox').change(function() {
            console.log('hi');
            //$(this).prop('checked')
        });
        $('.toggle_e_checkbox').change(function() {
            console.log('bye');
            //$(this).prop('checked')
        });

        set_autosize($('.text__6197_'+fetch_int_val('#focus_id')));
    });

    //Define file upload variables:
    var upload_control = $(".inputfile");
    var $input = $('.drag-box').find('input[type="file"]'),
        $label = $('.drag-box').find('label'),
        showFiles = function (files) {
            $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
        };

</script>