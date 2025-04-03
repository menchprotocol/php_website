<?php

if(isset($_GET['update_u_icons'])){

    $base_filters = array(
        'linkplayerup IN (' . join(',', $this->config->item('playerids___30820')) . ')' => null, //Active Member
        'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        );

    if(!isset($_GET['force'])) {
        $base_filters['(LENGTH(playercover) < 1 OR playercover IS NULL)'] = null;
    }

    $updated = 0;
    foreach($this->Menchledger->fetch($base_filters, array('linkplayerdown'), 0) as $x){
        $updated += $this->Nodeplayers->update($x['playerid'], array(
            'playercover' => random_cover(12279),
        ));
    }
    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>'.$updated.' Member following updated with new random animal icons';
}

for($i=0;$i<750;$i++){
    echo '<span class="icon-block">'.view_cover(random_cover(12279), true).'</span>';
}