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
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$following_count = view_coins_e(11030, $e['e__id'], 0, false);
echo view_headline(11030,  $following_count, $e___11035[11030], view_body_e(11030, $following_count, $e['e__id']), false);



//Focus Source:
echo '<div class="row justify-content">';
echo view_e(4251, $e, null, source_of_e($e['e__id']));
echo '</div>';


//Source Menu:
echo '<ul class="nav nav-pills nav12274"></ul>';

$item_counts = array();
$e___11089 = $this->config->item('e___11089');
foreach($e___11089 as $x__type => $m) {

    //Have Needed Superpowers?
    $require = 0;
    $missing = 0;
    $meeting = 0;
    foreach(array_intersect($this->config->item('n___10957'), $m['m__following']) as $superpower_required){
        $require++;
        if(superpower_active($superpower_required, true)){
            $meeting++;
        } else {
            $missing++;
        }
    }
    if($require && !$meeting){
        //RELAX: Meet any requirement and it would be shown
        continue;
    }

    $coin_count = view_coins_e($x__type, $e['e__id'], 0, false);
    if($coin_count > 0 || ( in_array($x__type , $this->config->item('n___28956')) && superpower_active(10939, true) )){
        $item_counts[$x__type] = $coin_count;
    }
}

//Determine focus/auto-load tab:
$focus_tab = 0;
foreach($this->config->item('e___26005') as $x__type => $m) {
    if(isset($item_counts[$x__type]) && $item_counts[$x__type] > 0){
        $focus_tab = $x__type;
        break;
    }
}


//Print results:
foreach($item_counts as $x__type => $counter) {
    echo view_pill($x__type, $counter, $e___11089[$x__type], ($x__type==$focus_tab ? view_body_e($x__type, $counter, $e['e__id']) : null ), ($x__type==$focus_tab));
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

        //Source Loader:
        load_tab(11030);
        load_tab(<?= $focus_tab ?>);

        set_autosize($('.texttype__lg.text__6197_'+current_id()));

    });


</script>
