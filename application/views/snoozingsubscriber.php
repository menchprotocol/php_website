<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('players___28917') as $chainplayertype => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->Chains->read(array(
            'chainplayerup' => $chainplayertype,
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainplayerdown'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['chaintime']))>(86400*intval($m['m__message']))){

                //Remove from Snooze:
                $this->Chains->delete($x['chainid'], $x['chainplayercreator']);

                //Add to subscribers:
                $this->Chains->create(array(
                    'chainplayertype' => 4230,
                    'chainplayercreator' => $x['chainplayercreator'],
                    'chainplayerup' => 4430, //Active Member
                    'chainplayerdown' => $x['chainplayercreator'],
                ));

                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}