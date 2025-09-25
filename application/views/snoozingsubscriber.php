<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('users___28917') as $chainusertype => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->Chains->read(array(
            'chainuserinput' => $chainusertype,
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array('chainuseroutput'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['chaintime']))>(86400*intval($m['m__message']))){

                //Remove from Snooze:
                $this->Chains->delete($x['chainid'], $x['chainusercreator']);

                //Add to subscribers:
                $this->Chains->create(array(
                    'chainusertype' => 4230,
                    'chainusercreator' => $x['chainusercreator'],
                    'chainuserinput' => 4430, //Active Member
                    'chainuseroutput' => $x['chainusercreator'],
                ));

                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}