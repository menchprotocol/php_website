<?php

if(isset($_GET['update_u_icons'])){

    $base_filters = array(
        'linkup IN (' . join(',', $this->config->item('playerids___30820')) . ')' => null, //Active Member
        'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
        'linkvoid' => 0, //Not Void
        );

    if(!isset($_GET['force'])) {
        $base_filters['(LENGTH(playercover) < 1 OR playercover IS NULL)'] = null;
    }

    $updated = 0;
    foreach($this->Menchledger->fetch($base_filters, array('linkdown'), 0) as $x){
        $updated += $this->Cacheplayers->update($x['playerid'], array(
            'playercover' => random_cover(12279),
        ));
    }
    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>'.$updated.' Member following updated with new random animal icons';
}

for($i=0;$i<750;$i++){
    echo '<span class="icon-block">'.view__cover(random_cover(12279), true).'</span>';
}