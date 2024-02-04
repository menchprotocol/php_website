<?php

if(isset($_GET['update_u_icons'])){

    $base_filters = array(
        'x__following IN (' . join(',', $this->config->item('n___30820')) . ')' => null, //Active Member
        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    );

    if(!isset($_GET['force'])) {
        $base_filters['(LENGTH(e__cover) < 1 OR e__cover IS NULL)'] = null;
    }

    $updated = 0;
    foreach($this->X_model->fetch($base_filters, array('x__follower'), 0) as $x){
        $updated += $this->E_model->update($x['e__id'], array(
            'e__cover' => random_cover(12279),
        ));
    }
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>'.$updated.' Member following updated with new random animal icons';
}

for($i=0;$i<750;$i++){
    echo '<span class="icon-block">'.view_cover(random_cover(12279), true).'</span>';
}