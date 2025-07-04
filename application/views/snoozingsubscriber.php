<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('handles___28917') as $chainhandletype => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->Chains->read(array(
            'chainhandleinput' => $chainhandletype,
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array('chainhandleoutput'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['chaintime']))>(86400*intval($m['m__message']))){

                //Remove from Snooze:
                $this->Chains->delete($x['chainid'], $x['chainhandlecreator']);

                //Add to subscribers:
                $this->Chains->create(array(
                    'chainhandletype' => 4230,
                    'chainhandlecreator' => $x['chainhandlecreator'],
                    'chainhandleinput' => 4430, //Active Member
                    'chainhandleoutput' => $x['chainhandlecreator'],
                ));

                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}