<?php

if(isset($_GET['update_u_icons'])){

    $base_filters = array(
        'x__following IN (' . njoin(30820) . ')' => null, //Active Member
        'x__type IN (' . njoin(32292) . ')' => null, //SOURCE LINKS
            );

    if(!isset($_GET['force'])) {
        $base_filters['(LENGTH(e__cover) < 1 OR e__cover IS NULL)'] = null;
    }

    $updated = 0;
    foreach($this->Ledger->read($base_filters, array('x__follower'), 0) as $x){
        $updated += $this->Sources->edit($x['e__id'], array(
            'e__cover' => random_cover(12279),
        ));
    }
    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>'.$updated.' Member following updated with new random animal icons';
}

for($i=0;$i<750;$i++){
    echo '<span class="icon-block">'.view_cover(random_cover(12279), true).'</span>';
}