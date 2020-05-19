<?php

/*
 *
 * Cronjob to sync source icons
 *
 * */


//IF NEW
$updated = 0;
foreach($this->config->item('en_all_12523') as $en_id => $m) {
    //Update All Child Icons that are not the same:
    foreach($this->TRANSACTION_model->fetch(array(
        'ln_profile_source_id' => $en_id,
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
        '(LENGTH(en_icon) < 1 OR en_icon IS NULL)' => null, //Missing Icon
    ), array('en_portfolio'), 0) as $en) {
        $updated++;
        $this->SOURCE_model->update($en['en_id'], array(
            'en_icon' => $m['m_icon'],
        ), true);
    }

}
echo $updated.' Icons updated across '.count($this->config->item('en_all_12523')).' IF NEW sources.<br />';




//IF DIFFERENT
$updated = 0;
foreach($this->config->item('en_all_12968') as $en_id => $m) {
    //Update All Child Icons that are not the same:
    foreach($this->TRANSACTION_model->fetch(array(
        'ln_profile_source_id' => $en_id,
        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
        'en_icon !=' => $m['m_icon'], //Different Icon
    ), array('en_portfolio'), 0) as $en) {
        $updated++;
        $this->SOURCE_model->update($en['en_id'], array(
            'en_icon' => $m['m_icon'],
        ), true);
    }

}
echo $updated.' Icons updated across '.count($this->config->item('en_all_12968')).' IF DIFFERENT sources.<br />';