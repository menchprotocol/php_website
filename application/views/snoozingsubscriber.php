<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('sources___28917') as $chainsourcetype => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->Chains->read(array(
            'chainsourceup' => $chainsourcetype,
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainsourcedown'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['chaintime']))>(86400*intval($m['m__message']))){

                //Remove from Snooze:
                $this->Chains->delete($x['chainid'], $x['chainsourcecreator']);

                //Add to subscribers:
                $this->Chains->create(array(
                    'chainsourcetype' => 4230,
                    'chainsourcecreator' => $x['chainsourcecreator'],
                    'chainsourceup' => 4430, //Active Member
                    'chainsourcedown' => $x['chainsourcecreator'],
                ));

                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}