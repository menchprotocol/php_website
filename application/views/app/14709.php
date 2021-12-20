<?php

$e___14709 = $this->config->item('e___14709');
$e___11035 = $this->config->item('e___11035');

$is = $this->I_model->fetch(array(
    'i__id' => ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 ),
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
));


if(!$member_e){

    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Must be signed in.</div>';
    js_redirect(home_url(), 13);

} elseif(!count($is)){

    //List all eligible:

    echo '<div class="headline top-margin"><span class="icon-block">'.$e___11035[14730]['m__cover'].'</span>'.$e___11035[14730]['m__title'].'</div>';
    echo '<div class="row justify-content hideIfEmpty" id="list-in-14730">';
    foreach(view_coins_e(6255, $member_e['e__id'], 1) as $item){
        $completion_rate = $this->X_model->completion_progress($member_e['e__id'], $item);
        if($completion_rate['completion_percentage'] >= 100){
            echo view_i(14730, $item['i__id'], null, $item, null, $member_e, $completion_rate);
        }
    }
    echo '</div>';


} else {

    $completion_rate = $this->X_model->completion_progress($member_e['e__id'], $is[0]);

    //Fetch their discoveries:
    if($completion_rate['completion_percentage'] < 99){

        //Go to the top and find the next idea:
        $next_i__id = $this->X_model->find_next($member_e['e__id'], $is[0]['i__id'], $is[0]);
        if($next_i__id > 0){

            js_redirect('/'.$is[0]['i__id'].'/'.$next_i__id, 0);

        } else {

            //This should not happen
            $error_message = 'Idea not yet completed. Redirecting now...';
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => 4246, //Platform Bug Reports
                'x__up' => 14709,
                'x__left' => $is[0]['i__id'],
                'x__message' => $error_message,
            ));

            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>'.$error_message.'</div>';
            js_redirect('/'.$is[0]['i__id'], 2584);

        }


    } else {

        //See if submitted before?
        $was_sibmitted = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 14709, //RATE DISCOVERY
            'x__source' => $member_e['e__id'],
            'x__right' => $is[0]['i__id'],
        ), array());


        //Allow to submit now:


        echo '<div class="submit_feedback">';

        if(count($was_sibmitted)){
            //Editing, let them know:
            echo '<div class="padded">You first submitted your feedback for this discover ' . view_time_difference(strtotime($was_sibmitted[0]['x__time'])) . ' Ago</div>';
        }


        echo view_headline(14730, null, $e___14709[14730], '<div class="row justify-content">'.view_i(14730, $is[0]['i__id'], null, $is[0]).'</div>', true);


        //Continious Updates
        echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14343]['m__cover'].'</span>'.$e___14709[14343]['m__title'].'</div>';
        echo '<div class="padded">'.str_replace('%s', $is[0]['i__title'], $e___14709[14343]['m__message']).'</div>';



        //Rate & Feedback:
        echo '<div class="padded share_feedback hidden"><a href="javascript:void();" onclick="$(\'.share_feedback\').toggleClass(\'hidden\');"><u>Share, Rate & Review</u></a></div>';

        echo '<div class="share_feedback">';


        //Share
        echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[13024]['m__cover'].'</span>' . $e___14709[13024]['m__title'] . '</div>';
        echo '<div class="padded">'.$is[0]['i__title'].':</div>';
        echo '<div class="padded"><input type="url" class="form-control border share_url" value=""></div>';


        //SOCIAL FOOTER
        $social_id = intval(get_domain_setting(14904));
        if($social_id){
            echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14904]['m__cover'].'</span>'.$e___14709[14904]['m__title'].'</div>';
            echo '<div class="padded hideIfEmpty">'.$e___14709[14904]['m__message'].'</div>';
            echo '<ul class="social2-footer">';
            foreach($this->config->item('e___'.$social_id) as $e__id => $m) {
                echo '<li><a href="/-14904?e__id='.$e__id.'" title="'.$m['m__title'].'" data-toggle="tooltip" data-placement="top">'.$m['m__cover'].'</a></li>';
            }
            echo '</ul>';
        }



        //Rate
        if(0){
            echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14712]['m__cover'].'</span>'.$e___14709[14712]['m__title'].'</div>';
            echo '<div class="padded hideIfEmpty">'.$e___14709[14712]['m__message'].'</div>';
            foreach($this->config->item('e___14712') as $x__type => $m){
                echo '<div class="form-check">
                    <input class="form-check-input" type="radio" '.( count($was_sibmitted) && $was_sibmitted[0]['x__up']==$x__type ? ' checked="checked" ' : '' ) .' name="feedback_rating_14712" id="formRadio'.$x__type.'" value="'.$x__type.'">
                    <label class="form-check-label" for="formRadio'.$x__type.'"><span class="icon-block">' . $m['m__cover'] . '</span>' . $m['m__title'] . '</label>
                </div>';
            }
        }



        //Write Feedback
        echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14720]['m__cover'].'</span>'.$e___14709[14720]['m__title'].'</div>';
        echo '<div class="padded"><textarea class="form-control text-edit border no-padding" id="feedback_writing_14720" data-lpignore="true" placeholder="'.$e___14709[14720]['m__message'].'">'.( count($was_sibmitted) ? $was_sibmitted[0]['x__message'] : '' ).'</textarea></div>';




        //SHARE
        ?>
        <script>

            function go_to_next(){
                window.location = '/@'+js_pl_id;
            }

            function save_feedback(){

                //See if we have any inputs to save:
                var update_x__id = <?= count($was_sibmitted) ? $was_sibmitted[0]['x__id'] : 0 ?>;
                var rating_e__id = parseInt($('input[name="feedback_rating_14712"]:checked').val());
                var feedback_text = $('#feedback_writing_14720').val().trim();

                if(update_x__id > 0 || rating_e__id > 0 || feedback_text.length > 0){

                    //Create/Update:
                    $.post("/app/app_14709", {
                        i__id: <?= $is[0]['i__id'] ?>,
                        x__source: js_pl_id,
                        update_x__id:update_x__id,
                        rating_e__id:rating_e__id,
                        feedback_text:feedback_text,
                    }, function (data) {

                        //Redirect after saving::
                        go_to_next();

                    });

                } else {

                    //Just Redirect:
                    go_to_next();

                }

            }


            var new_url = "https://<?= get_server('SERVER_NAME') ?>/<?= $is[0]['i__id'] ?>";

            $(document).ready(function () {

                $('.share_url').val(new_url);

                set_autosize($('#feedback_writing_14720'));

            });


        </script>
        <?php
        echo '</div>';




        //SAVE & NEXT
        echo '<div class="nav-controller"><div><a class="controller-nav btn btn-lrg btn-6255 go-next top-margin" href="javascript:void();" onclick="save_feedback()">'.$e___14709[14721]['m__title'].' '.$e___14709[14721]['m__cover'].'</a></div></div>';


        echo '</div>';

    }
}
