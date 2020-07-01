<?php

/*
 *
 * Cronjob to sync source icons
 *
 * */


//IF MISSING
$updated = 0;
foreach($this->config->item('e___12523') as $e__id => $m) {
    //Update All Child Icons that are not the same:
    foreach($this->X_model->fetch(array(
        'x__up' => $e__id,
        'x__type IN (' . join(',', $this->config->item('e___n_4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('e___n_7360')) . ')' => null, //ACTIVE
        'e__status IN (' . join(',', $this->config->item('e___n_7358')) . ')' => null, //ACTIVE
        '(LENGTH(e__icon) < 1 OR e__icon IS NULL)' => null, //Missing Icon
    ), array('x__down'), 0) as $en) {
        $updated++;
        $this->E_model->update($en['e__id'], array(
            'e__icon' => $m['m_icon'],
        ), true);
    }

}
echo $updated.' Icons updated across '.count($this->config->item('e___12523')).' IF NEW sources.<br />';




//IF DIFFERENT
$updated = 0;
foreach($this->config->item('e___12968') as $e__id => $m) {
    //Update All Child Icons that are not the same:
    foreach($this->X_model->fetch(array(
        'x__up' => $e__id,
        'x__type IN (' . join(',', $this->config->item('e___n_4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('e___n_7360')) . ')' => null, //ACTIVE
        'e__status IN (' . join(',', $this->config->item('e___n_7358')) . ')' => null, //ACTIVE
        '(LENGTH(e__icon) < 1 OR e__icon IS NULL OR e__icon != \''.$m['m_icon'].'\')' => null, //Missing Icon
    ), array('x__down'), 0) as $en) {
        $updated++;
        echo 'Different @'.$en['e__id'].' ['.htmlentities($en['e__icon']).'] to ['.htmlentities($m['m_icon']).']<br />';
        $this->E_model->update($en['e__id'], array(
            'e__icon' => $m['m_icon'],
        ), true);
    }

}
echo $updated.' Icons updated across '.count($this->config->item('e___12968')).' IF DIFFERENT sources.<br />';