<?php


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
echo '<div class="nav-controller"><div><button type="submit" class="controller-nav btn btn-lrg btn-6255 go-next top-margin" value="GO">UPDATE</button></div></div>';


echo '</div>';



echo '<div class="saving_feedback hidden top-margin">';
echo '<div class="text-center platform-large">'.get_domain('m__cover').'</div>';
echo '<p style="margin-top:13px; text-align: center;">'.view_shuffle_message(12694).'</p>';
echo '</div>';

