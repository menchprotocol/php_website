<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('e___28917') as $linktype => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->Mench_ledger->fetch(array(
            'linkup' => $linktype,
            'linktype IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'linkvoid' => 0, //Not Void
        ), array('linkdown'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['linktime']))>(86400*intval($m['m__message']))){

                //Remove from Snooze:
                $this->Mench_ledger->update($x['linkid'], array(), $x['linkplayer']);

                //Add to subscribers:
                $this->Mench_ledger->create(array(
                    'linktype' => 4230,
                    'linkplayer' => $x['linkplayer'],
                    'linkup' => 4430, //Active Member
                    'linkdown' => $x['linkplayer'],
                ));

                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}