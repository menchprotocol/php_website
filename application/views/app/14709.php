<?php

$is = $this->I_model->fetch(array(
    'i__id' => ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 ),
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
));

if(!count($is) || !$member_e){

    js_redirect('/', 13);

} else {

    $completion_rate = $this->X_model->completion_progress($member_e['e__id'], $is[0]);

    //Fetch their discoveries:
    if($completion_rate['completion_percentage'] < 100){

        $error_message = 'Idea not yet completed';
        $this->X_model->create(array(
            'x__source' => $member_e['e__id'],
            'x__type' => 4246, //Platform Bug Reports
            'x__up' => 14709,
            'x__left' => $is[0]['i__id'],
            'x__message' => $error_message,
        ));
        echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>'.$error_message.'</div>';

    } elseif(!count($this->X_model->fetch(array(
        'x__source' => $member_e['e__id'],
        'x__type IN (' . join(',', $this->config->item('n___12969')) . ')' => null, //MY DISCOVERIES
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        'i__id' => $is[0]['i__id'],
    ), array('x__left')))){

        $error_message = 'Idea not part of member discoveries';
        $this->X_model->create(array(
            'x__source' => $member_e['e__id'],
            'x__type' => 4246, //Platform Bug Reports
            'x__up' => 14709,
            'x__left' => $is[0]['i__id'],
            'x__message' => $error_message,
        ));
        echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>'.$error_message.'</div>';

    } else {

        //All good
        $e___14709 = $this->config->item('e___14709');


        //Show the discovery they competed:
        echo view_i(12969, null, $is[0]);



        //Rate
        echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14712]['m__icon'].'</span>'.$e___14709[14712]['m__title'].'</div>';
        echo '<div class="padded hideIfEmpty">'.$e___14709[14712]['m__message'].'</div>';
        foreach($this->config->item('e___14712') as $x__type => $m){
            echo '<div class="form-check">
                    <input class="form-check-input" type="radio" name="feedback_rating_14712" id="formRadio'.$x__type.'" value="'.$x__type.'">
                    <label class="form-check-label" for="formRadio'.$x__type.'"><span class="icon-block">' . $m['m__icon'] . '</span>' . $m['m__title'] . '</label>
                </div>';
        }



        //Write Feedback
        echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14720]['m__icon'].'</span>'.$e___14709[14720]['m__title'].'</div>';
        echo '<textarea class="form-control text-edit border" id="feedback_writing_14720" data-lpignore="true" placeholder="'.$e___14709[14720]['m__message'].'"></textarea>';




        //SHARE
        ?>
        <script>
            $(document).ready(function () {
                var new_url = "https://mench.com/<?= $is[0]['i__id'] ?>";
                addthis.update('share', 'url', new_url);
                addthis.url = new_url;
                addthis.toolbox(".addthis_inline_share_toolbox");
                $('.current_url').text(new_url);
            });

            function complete_spin(){
                $('.go-next').html('<i class="far fa-yin-yang fa-spin"></i>');
            }
        </script>
        <?php

        //Share
        echo '<div class="headline"><span class="icon-block">' . $e___14709[13024]['m__icon'] . '</span>' . $e___14709[13024]['m__title'] . '</div>';
        echo '<div class="current_url padded hideIfEmpty"></div>'; //URL
        echo '<div class="addthis_inline_share_toolbox"></div>'; //AddThis: Customize at www.addthis.com/dashboard




        //echo view_e_settings(14709, false);

        //Continious Updates
        echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14343]['m__icon'].'</span>'.$e___14709[14343]['m__title'].'</div>';
        echo '<div class="padded">'.$e___14709[14343]['m__message'].'</div>';



        //SAVE & NEXT
        echo '<div class="discover-controller top-margin"><a class="controller-nav btn btn-lrg btn-discover go-next" href="'.$e___14709[14721]['m__message'].'" onclick="complete_spin()">'.$e___14709[14721]['m__title'].' '.$e___14709[14721]['m__icon'].'</a></div>';

    }

}

