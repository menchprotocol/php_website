<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('players___28917') as $linktype => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->Ledger->fetch(array(
            'linkup' => $linktype,
            'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
        ), array('linkdown'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['linktime']))>(86400*intval($m['m__message']))){

                //Remove from Snooze:
                $this->Ledger->update($x['linkid'], array(), $x['linkcreator']);

                //Add to subscribers:
                $this->Ledger->create(array(
                    'linktype' => 4230,
                    'linkcreator' => $x['linkcreator'],
                    'linkup' => 4430, //Active Member
                    'linkdown' => $x['linkcreator'],
                ));

                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}