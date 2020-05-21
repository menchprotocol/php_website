<?php

/*
 *
 * Cronjob to sync source icons
 *
 * */


//IF NEW
$updated = 0;
foreach($this->config->item('sources__12523') as $source__id => $m) {
    //Update All Child Icons that are not the same:
    foreach($this->READ_model->fetch(array(
        'read__up' => $source__id,
        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
        '(LENGTH(source__icon) < 1 OR source__icon IS NULL)' => null, //Missing Icon
    ), array('read__down'), 0) as $en) {
        $updated++;
        $this->SOURCE_model->update($en['source__id'], array(
            'source__icon' => $m['m_icon'],
        ), true);
    }

}
echo $updated.' Icons updated across '.count($this->config->item('sources__12523')).' IF NEW sources.<br />';




//IF DIFFERENT
$updated = 0;
foreach($this->config->item('sources__12968') as $source__id => $m) {
    //Update All Child Icons that are not the same:
    foreach($this->READ_model->fetch(array(
        'read__up' => $source__id,
        'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
        'source__icon !=' => $m['m_icon'], //Different Icon
    ), array('read__down'), 0) as $en) {
        $updated++;
        $this->SOURCE_model->update($en['source__id'], array(
            'source__icon' => $m['m_icon'],
        ), true);
    }

}
echo $updated.' Icons updated across '.count($this->config->item('sources__12968')).' IF DIFFERENT sources.<br />';