<?php

/*
 *
 * Cronjob to sync source icons
 *
 * */


//IF MISSING
$updated = 0;
foreach($this->config->item('e___12523') as $e__id => $m) {

    //Only supports icons:
    $icon_code = one_two_explode('class="','"',$m['m__cover']);
    if(!$icon_code || substr($icon_code, 0, 2)!='fa'){
        continue;
    }

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
            'e__cover' => $icon_code,
        ), true);
    }

}
echo $updated.' Icons updated across '.count($this->config->item('e___12523')).' sources.<br />';
