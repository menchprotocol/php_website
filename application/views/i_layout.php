<?php

//Just Viewing:
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$this->X_model->create(array(
    'x__source' => $member_e['e__id'],
    'x__type' => 4993, //Member Opened Idea
    'x__right' => $i['i__id'],
    'x__spectrum' => $new_order,
));


$e_of_i = e_of_i($i['i__id']);


//Focusing on a certain source?
if(isset($_GET['load__e']) && superpower_active(14005, true)){
    //Filtered Specific Source:
    $e_filters = $this->E_model->fetch(array(
        'e__id' => intval($_GET['load__e']),
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(count($e_filters)){
        echo view__load__e($e_filters[0]);
    }
}






//Load Top:
$counter_top = view_coins_i(11019, $i['i__id'], 0, false);
echo '<div class="hideIfEmpty headline_body_11019" read-counter="'.$counter_top.'"></div>';
echo '<script type="text/javascript"> $(document).ready(function () { setTimeout(function () { load_tab(12273, 11019, true); }, 987); }); </script>';



//Focus Source:
echo '<div class="main_item row justify-content">';
echo view_i(4250, 0, null, $i);
echo '</div>';



$coins_count = array();
$body_content = '';
echo '<ul class="nav nav-tabs nav12273">';
foreach($this->config->item('e___14874') as $x__type => $m) {
    $coins_count[$x__type] = view_coins_i($x__type, $i['i__id'], 0, false);
    $body_content .= '<div class="headlinebody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'"></div>';
    echo '<li class="nav-item thepill'.$x__type.'"><a class="nav-link" active x__type="'.$x__type.'" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'" onclick="toggle_pills('.$x__type.')"><span class="icon-block-xxs">'.$m['m__cover'].'</span><span class="css__title hideIfEmpty xtypecounter'.$x__type.'" style="padding-right:4px;">'.view_number($coins_count[$x__type]) . '</span></a></li>';
}
echo '</ul>';
echo $body_content;
foreach($this->config->item('e___14874') as $x__type => $m) { //Load Focus Tab:
    if($coins_count[$x__type] > 0){
        echo '<script type="text/javascript"> $(document).ready(function () { toggle_pills('.$x__type.'); }); </script>';
        break;
    }
}




/*
//Always Load Followings at top
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$following_count = view_coins_i(11019, $i['i__id'], 0, false);
echo view_headline(11019,  $following_count, $e___11035[11019], view_body_i(11019, $following_count, $i['i__id']), false);
if($e_of_i){
    echo '<div class="new-list-11019 list-adder '.superpower_active(10939).'">
                    <div class="input-group border">
                        <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-11019 .add-input\').focus();"><span class="icon-block">'.$e___11035[14016]['m__cover'].'</span></a>
                        <input type="text"
                               class="form-control form-control-thick add-input algolia_search dotransparent"
                               maxlength="' . view_memory(6404,4736) . '"
                               placeholder="'.$e___11035[14016]['m__title'].'">
                    </div><div class="algolia_pad_search row justify-content"></div></div>';
}
*/





?>

<style>
    <?= ( !$e_of_i ? '.note-editor {display:none;}' : '' ) ?>
</style>
<input type="hidden" id="focus_coin" value="12273" />
<input type="hidden" id="focus_id" value="<?= $i['i__id'] ?>" />
<script type="text/javascript">

    $(document).ready(function () {
        //Look for power editor updates:
        $('.x_set_class_text').keypress(function(e) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if (code == 13) {
                x_set_text(this);
                e.preventDefault();
            }
        }).change(function() {
            x_set_text(this);
        });

    });

</script>

