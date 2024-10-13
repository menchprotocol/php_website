<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('e___28917') as $x__type => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->X_model->fetch(array(
            'x__following' => $x__type,
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        ), array('x__follower'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['x__time']))>(86400*intval($m['m__message']))){

                //Remove from Snooze:
                $this->X_model->update($x['x__id'], array(
                    'x__privacy' => 6173, //Transaction Removed
                ), $x['x__player'], 28917 /* Unsnooze */);

                //Add to subscribers:
                $this->X_model->create(array(
                    'x__type' => 4251,
                    'x__following' => 4430, //Active Member
                    'x__player' => $x['x__player'],
                    'x__follower' => $x['x__player'],
                ));

                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}