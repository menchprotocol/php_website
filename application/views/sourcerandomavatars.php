<?php

if(isset($_GET['update_u_icons'])){

    $base_filters = array(
        'chainsourceup IN (' . join(',', $this->config->item('sourceids___30820')) . ')' => null, //Active Member
        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        );

    if(!isset($_GET['force'])) {
        $base_filters['(LENGTH(sourcecover) < 1 OR sourcecover IS NULL)'] = null;
    }

    $updated = 0;
    foreach($this->Chains->read($base_filters, array('chainsourcedown'), 0) as $x){
        $updated += $this->Sources->update($x['sourceid'], array(
            'sourcecover' => sourcecover_generator(12279),
        ));
    }
    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>'.$updated.' Member following updated with new random animal icons';
}

for($i=0;$i<750;$i++){
    echo '<span class="icon-block">'.view_cover(sourcecover_generator(12279), true).'</span>';
}