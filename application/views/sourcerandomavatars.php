<?php

if(isset($_GET['update_u_icons'])){

    $base_filters = array(
        'chainuserinput IN (' . join(',', $this->config->item('userids___30820')) . ')' => null, //Active Member
        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        );

    if(!isset($_GET['force'])) {
        $base_filters['(LENGTH(usercover) < 1 OR usercover IS NULL)'] = null;
    }

    $updated = 0;
    foreach($this->Ideachains->read($base_filters, array('chainuseroutput'), 0) as $x){
        $updated += $this->Users->update($x['userid'], array(
            'usercover' => usercover_generator(12279),
        ));
    }
    echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>'.$updated.' Member following updated with new random animal icons';
}

for($i=0;$i<750;$i++){
    echo '<span class="icon-block">'.view_cover(usercover_generator(12279), true).'</span>';
}