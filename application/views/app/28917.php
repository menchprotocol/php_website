<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('e___28917') as $x__type => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->X_model->fetch(array(
            'x__up' => $x__type,
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        ), array('x__down'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['x__time']))>(86400*intval($m['m__message']))){
                $this->X_model->update($x['x__id'], array(
                    'x__status' => 6173, //Transaction Removed
                ), $x['x__source'], 28917 /* Unsnooze */);
                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}