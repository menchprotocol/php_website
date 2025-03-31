<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('e___28917') as $link_type => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->Mench_ledger->fetch(array(
            'link_up' => $link_type,
            'link_type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'link_void' => 0, //Not Void
        ), array('link_down'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['link_time']))>(86400*intval($m['m__message']))){

                //Remove from Snooze:
                $this->Mench_ledger->update($x['link_id'], array(), $x['link_player']);

                //Add to subscribers:
                $this->Mench_ledger->create(array(
                    'link_type' => 4230,
                    'link_player' => $x['link_player'],
                    'link_up' => 4430, //Active Member
                    'link_down' => $x['link_player'],
                ));

                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}