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




//Source Menu, to be populated with JS
$nav_content = '';
$body_content = '';
$coins_count = array();
$e___14874 = $this->config->item('e___14874'); //Coins
foreach($e___14874 as $x__type => $m) {
    $counter = view_coins_e($x__type, $e['e__id'], 0, false);
    if($counter > 0 || ( in_array($x__type , $this->config->item('n___28956')) && superpower_active(10939, true) )){
        $coins_count[$x__type] = $counter;
    }
    $nav_content .= '<li class="nav-item thepill'.$x__type.'"><a class="nav-link" active x__type="'.$x__type.'" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="'.number_format($counter, 0).' '.$m['m__title'].'" onclick="toggle_pills('.$x__type.')"><span class="icon-block-xxs">'.$m['m__cover'].'</span><span class="css__title hideIfEmpty xtypecounter'.$x__type.'" style="padding-right:4px;">'.view_number($counter) . '</span></a></li>';
    $body_content .= '<div class="headlinebody headline_body_'.$x__type.' hidden" item-counter="'.$counter.'"></div>';
}


echo '<ul class="nav nav-tabs nav12274">';
echo $nav_content;
echo '</ul>';
echo $body_content;


//Print results:
foreach($coins_count as $x__type => $counter) {
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


        <?php
        foreach($this->config->item('e___26005') as $x__type => $m) {
            if(isset($coins_count[$x__type]) && $coins_count[$x__type] > 0){
                echo 'toggle_pills('.$x__type.');';
                break;
            }
        }
        ?>


        set_autosize($('.texttype__lg.text__6197_'+current_id()));

    });


</script>
