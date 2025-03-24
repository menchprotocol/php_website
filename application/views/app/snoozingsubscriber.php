<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('e___28917') as $LinkType => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->Mench_ledger->fetch(array(
            'LinkUp' => $LinkType,
            'LinkType IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('LinkDown'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['LinkTime']))>(86400*intval($m['m__message']))){

                //Remove from Snooze:
                $this->Mench_ledger->update($x['LinkId'], array(
                    'LinkPrivacy' => 6173, //Transaction Removed
                ), $x['LinkPlayer'], 28917 /* Unsnooze */);

                //Add to subscribers:
                $this->Mench_ledger->create(array(
                    'LinkType' => 4251,
                    'LinkPlayer' => $x['LinkPlayer'],
                    'LinkUp' => 4430, //Active Member
                    'LinkDown' => $x['LinkPlayer'],
                ));

                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}