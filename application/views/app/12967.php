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
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        '(LENGTH(e__cover) < 1 OR e__cover IS NULL)' => null, //Missing Icon
    ), array('x__down'), 0) as $en) {
        $updated++;
        $this->E_model->update($en['e__id'], array(
            'e__cover' => $m['m__cover'],
        ), true);
    }

}
echo $updated.' Icons updated across '.count($this->config->item('e___12523')).' sources.<br />';




//IF DIFFERENT
/*
 * Retired for now
 *
$updated = 0;
foreach($this->config->item('e___12968') as $e__id => $m) {
    //Update All Child Icons that are not the same:
    foreach($this->X_model->fetch(array(
        'x__up' => $e__id,
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        '(LENGTH(e__cover) < 1 OR e__cover IS NULL OR e__cover != \''.$m['m__cover'].'\')' => null, //Missing Icon
    ), array('x__down'), 0) as $en) {
        $updated++;
        echo 'Different @'.$en['e__id'].' ['.htmlentities($en['e__cover']).'] to ['.htmlentities($m['m__cover']).']<br />';
        $this->E_model->update($en['e__id'], array(
            'e__cover' => $m['m__cover'],
        ), true);
    }

}
echo $updated.' Icons updated across '.count($this->config->item('e___12968')).' IF DIFFERENT sources.<br />';

*/