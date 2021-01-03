<?php

$e___14709 = $this->config->item('e___14709');

$is = $this->I_model->fetch(array(
    'i__id' => ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 ),
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
));


if(!count($is) || !$member_e){

    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Missing member and/or idea.</div>';
    js_redirect('/', 13);

} else {

    $completion_rate = $this->X_model->completion_progress($member_e['e__id'], $is[0]);

    //Fetch their discoveries:
    if($completion_rate['completion_percentage'] < 100){

        $error_message = 'Idea not yet completed. Redirecting now...';
        $this->X_model->create(array(
            'x__source' => $member_e['e__id'],
            'x__type' => 4246, //Platform Bug Reports
            'x__up' => 14709,
            'x__left' => $is[0]['i__id'],
            'x__message' => $error_message,
        ));
        echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>'.$error_message.'</div>';
        js_redirect('/'.$is[0]['i__id'], 2584);

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


        //100% COMPLETE
        echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14730]['m__icon'].'</span>'.$e___14709[14730]['m__title'].'</div>';
        echo '<div class="padded">'.str_replace('%s', $is[0]['i__title'], $e___14709[14730]['m__message']).'</div>';
        echo '<div class="padded">'.view_i(14730, null, $is[0]).'</div>';



        //Continious Updates
        echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14343]['m__icon'].'</span>'.$e___14709[14343]['m__title'].'</div>';
        echo '<div class="padded">'.str_replace('%s', $is[0]['i__title'], $e___14709[14343]['m__message']).'</div>';



        //Rate
        echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14712]['m__icon'].'</span>'.$e___14709[14712]['m__title'].'</div>';
        echo '<div class="padded hideIfEmpty">'.$e___14709[14712]['m__message'].'</div>';
        foreach($this->config->item('e___14712') as $x__type => $m){
            echo '<div class="form-check">
                    <input class="form-check-input" type="radio" '.( count($was_sibmitted) && $was_sibmitted[0]['x__up']==$x__type ? ' checked="checked" ' : '' ) .' name="feedback_rating_14712" id="formRadio'.$x__type.'" value="'.$x__type.'">
                    <label class="form-check-label" for="formRadio'.$x__type.'"><span class="icon-block">' . $m['m__icon'] . '</span>' . $m['m__title'] . '</label>
                </div>';
        }


        //Write Feedback
        echo '<div class="headline top-margin"><span class="icon-block">'.$e___14709[14720]['m__icon'].'</span>'.$e___14709[14720]['m__title'].'</div>';
        echo '<div class="padded"><textarea class="form-control text-edit border no-padding" id="feedback_writing_14720" data-lpignore="true" placeholder="'.$e___14709[14720]['m__message'].'">'.( count($was_sibmitted) ? $was_sibmitted[0]['x__message'] : '' ).'</textarea></div>';




        //SHARE
        ?>
        <script>

            function go_to_next(){
                return false;
                setTimeout(function () {
                    window.location = '<?= $e___14709[14721]['m__message'] ?>';
                }, 1597);
            }

            function save_feedback(){

                //See if we have any inputs to save:
                var update_x__id = <?= count($was_sibmitted) ? $was_sibmitted[0]['x__id'] : 0 ?>;
                var rating_e__id = parseInt($('input[name="feedback_rating_14712"]:checked').val());
                var feedback_text = $('#feedback_writing_14720').val().trim();

                //Show Loader:
                $('.submit_feedback').addClass('hidden');
                $('.saving_feedback').removeClass('hidden');

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


            var new_url = "https://mench.com/<?= $is[0]['i__id'] ?>";

            $(document).ready(function () {
                addthis.update('share', 'url', new_url);
                addthis.url = new_url;
                addthis.toolbox(".addthis_inline_share_toolbox");

                $('.share_url').text(new_url);

                set_autosize($('#feedback_writing_14720'));

            });

            function copy_share(){
                copyTextToClipboard(new_url);
            }

        </script>
        <?php

        //Share
        echo '<div class="headline top-margin"><span class="icon-block">' . $e___14709[13024]['m__icon'] . '</span>' . $e___14709[13024]['m__title'] . '</div>';
        echo '<div class="padded">'.str_replace('%s', $is[0]['i__title'], $e___14709[13024]['m__message']).'</div>';
        echo '<div class="padded"><a href="javascript:void();" onclick="copy_share()"><span class="share_url"></span>&nbsp;&nbsp;<i class="fa fa-gif-wrap was_copied">COPY</i></a></div>';
        echo '<div class="padded"><div class="addthis_inline_share_toolbox"></div></div>'; //AddThis: Customize at www.addthis.com/dashboard


        //SAVE & NEXT
        echo '<div class="discover-controller"><div><a class="controller-nav btn btn-lrg btn-discover go-next top-margin" href="javascript:void();" onclick="save_feedback()">'.$e___14709[14721]['m__title'].' '.$e___14709[14721]['m__icon'].'</a></div></div>';


        echo '</div>';



        echo '<div class="saving_feedback hidden top-margin">';
        echo '<div class="text-center"><img src="/img/mench.png" class="mench-spin mench-large" /></div>';
        echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
        echo '</div>';

    }
}
