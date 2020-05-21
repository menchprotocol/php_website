<?php

if(isset($_GET['update_user_icons'])){

    $base_filters = array(
        'read__up' => 4430, //Players
        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
    );

    if(!isset($_GET['force'])) {
        $base_filters['(LENGTH(source__icon) < 1 OR source__icon IS NULL)'] = null;
    }

    $updated = 0;
    foreach($this->READ_model->fetch($base_filters, array('read__down'), 0) as $mench_user){
        $updated += $this->SOURCE_model->update($mench_user['source__id'], array(
            'source__icon' => random_avatar(),
        ));
    }
    echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>'.$updated.' User profiles updated with new random animal icons';
}

for($i=0;$i<750;$i++){
    echo '<span class="icon-block">'.random_avatar().'</span>';
}