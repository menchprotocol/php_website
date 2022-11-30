<?php

//Log source view:
$new_order = ( $this->session->userdata('session_page_count') + 1 );
$this->session->set_userdata('session_page_count', $new_order);
$this->X_model->create(array(
    'x__source' => $member_e['e__id'],
    'x__type' => 4994, //Member Viewed Source
    'x__down' => $e['e__id'],
    'x__spectrum' => $new_order,
));


//Always Load Followings at top
echo '<div class="headline_body_11030"></div>';


//Focus Source:
echo '<div class="main_item row justify-content">';
echo view_e(4251, $e, null, source_of_e($e['e__id']));
echo '</div>';


//Source Menu:
echo '<ul class="nav nav-tabs nav12274"></ul>';

$item_counts = array();
$e___14874 = $this->config->item('e___14874'); //Coins
foreach($e___14874 as $x__type => $m) {
    $coin_count = view_coins_e($x__type, $e['e__id'], 0, false);
    if($coin_count > 0 || ( in_array($x__type , $this->config->item('n___28956')) && superpower_active(10939, true) )){
        $item_counts[$x__type] = $coin_count;
    }
}

//Determine focus/auto-load tab, if any:
$focus_tab = 0;
foreach($this->config->item('e___26005') as $x__type => $m) {
    if(isset($item_counts[$x__type]) && $item_counts[$x__type] > 0){
        $focus_tab = $x__type;
        break;
    }
}

$focus_tab = 0; //TODO Remove later


//Print results:
foreach($item_counts as $x__type => $counter) {
    $x__type = ( $x__type==12274 ? 11029 : $x__type );
    //echo view_pill($x__type, $counter, $e___14874[$x__type], ($x__type==$focus_tab ? view_body_e($x__type, $counter, $e['e__id']) : null ), ($x__type==$focus_tab));
}

?>


<input type="hidden" id="focus__type" value="12274" />
<input type="hidden" id="focus__id" value="<?= $e['e__id'] ?>" />
<script type="text/javascript">

    //Define file upload variables:
    var upload_control = $(".inputfile");
    var $input = $('.drag-box').find('input[type="file"]'),
        $label = $('.drag-box').find('label'),
        showFiles = function (files) {
            $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace('{count}', files.length) : files[0].name);
        };

    $(document).ready(function () {

        //toggle_pills(11030);

        //Source Loader:
        //load_tab(<?= $focus_tab ?>);

        set_autosize($('.texttype__lg.text__6197_'+current_id()));

    });


</script>
