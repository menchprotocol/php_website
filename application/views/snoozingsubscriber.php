<?php

//Auto unsnooze members who is time for them to get unsnoozed.

foreach($this->config->item('players___28917') as $linkplayertype => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->Links->read(array(
            'linkplayerup' => $linkplayertype,
            'linkplayertype IN (' . join(',', $this->list_link_sourcing) . ')' => null, //SOURCE LINKS
        ), array('linkplayerdown'), 0) as $x) {
            $total_members++;
            if((time()-strtotime($x['linktime']))>(86400*intval($m['m__message']))){

                //Remove from Snooze:
                $this->Links->delete($x['linkid'], $x['linkplayercreator']);

                //Add to subscribers:
                $this->Links->create(array(
                    'linkplayertype' => 4230,
                    'linkplayercreator' => $x['linkplayercreator'],
                    'linkplayerup' => 4430, //Active Member
                    'linkplayerdown' => $x['linkplayercreator'],
                ));

                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozed from '.$m['m__title'].'<hr />';

    }
}