<?php

if(isset($_GET['update_u_icons'])){

    $base_filters = array(
        'chainhandleinput IN (' . join(',', $this->config->item('handleids___30820')) . ')' => null, //Active Member
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        );

    if(!isset($_GET['force'])) {
        $base_filters['(LENGTH(handlecover) < 1 OR handlecover IS NULL)'] = null;
    }

    $updated = 0;
    foreach($this->Chains->read($base_filters, array('chainhandleoutput'), 0) as $x){
        $updated += $this->Handles->update($x['handleid'], array(
            'handlecover' => handlecover_generator(12279),
        ));
    }
    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>'.$updated.' Member following updated with new random animal icons';
}

for($i=0;$i<750;$i++){
    echo '<span class="icon-block">'.view_cover(handlecover_generator(12279), true).'</span>';
}