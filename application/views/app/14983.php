<?php

$e___14709 = $this->config->item('e___14709');
$e___11035 = $this->config->item('e___11035');

$is = $this->I_model->fetch(array(
    'i__id' => ( isset($_GET['i__id']) ? intval($_GET['i__id']) : 0 ),
    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
));


if(!$member_e){

    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Must be signed in.</div>';
    js_redirect('/', 13);

} elseif(!count($is)){

    //List all eligible:

    echo '<div class="headline top-margin"><span class="icon-block">&nbsp;</span>'.$e___11035[14730]['m__title'].'</div>';
    echo '<div class="row justify-content-center hideIfEmpty" id="list-in-14730">';
    foreach(view_coins_e(6255, $member_e['e__id'], 1) as $item){
        $completion_rate = $this->X_model->completion_progress($member_e['e__id'], $item);
        if($completion_rate['completion_percentage'] >= 100){
            echo view_i(14730, $item['i__id'], null, $item, false, null, $member_e, $completion_rate);
        }
    }
    echo '</div>';


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

        if(count($was_sibmitted)){
            //Editing, let them know:
            echo '<div class="padded">You first submitted your feedback for this discover ' . view_time_difference(strtotime($was_sibmitted[0]['x__time'])) . ' Ago</div>';
        }


        //100% COMPLETE
        echo '<div class="headline top-margin"><span class="icon-block">&nbsp;</span>'.$e___14709[14730]['m__title'].'</div>';
        echo '<div class="padded">'.str_replace('%s', $is[0]['i__title'], $e___14709[14730]['m__message']).'</div>';
        echo '<div class="padded">'.view_i(14730, $is[0]['i__id'], null, $is[0]).'</div>';



        //Continious Updates
        echo '<div class="headline top-margin"><span class="icon-block">&nbsp;</span>'.$e___14709[14343]['m__title'].'</div>';
        echo '<div class="padded">'.str_replace('%s', $is[0]['i__title'], $e___14709[14343]['m__message']).'</div>';



        //Rate
        echo '<div class="headline top-margin"><span class="icon-block">&nbsp;</span>'.$e___14709[14712]['m__title'].'</div>';
        echo '<div class="padded hideIfEmpty">'.$e___14709[14712]['m__message'].'</div>';
        foreach($this->config->item('e___14712') as $x__type => $m){
            echo '<div class="form-check">
                    <input class="form-check-input" type="radio" '.( count($was_sibmitted) && $was_sibmitted[0]['x__up']==$x__type ? ' checked="checked" ' : '' ) .' name="feedback_rating_14712" id="formRadio'.$x__type.'" value="'.$x__type.'">
                    <label class="form-check-label" for="formRadio'.$x__type.'"><span class="icon-block">' . $m['m__cover'] . '</span>' . $m['m__title'] . '</label>
                </div>';
        }


        //Paste Code
        echo '<div class="padded"><textarea class="form-control text-edit border no-padding" data-lpignore="true" placeholder="Paste FontAwesome Cheatsheet here"></textarea></div>';



        //Apply
        echo '<div class="discover-controller"><div><button type="submit" class="controller-nav btn btn-lrg btn-discover go-next top-margin" value="GO">UPDATE</button></div></div>';


        echo '</div>';



        echo '<div class="saving_feedback hidden top-margin">';
        echo '<div class="text-center platform-large">'.get_domain('m__cover').'</div>';
        echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
        echo '</div>';

    }
}
