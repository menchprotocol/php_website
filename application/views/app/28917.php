<?php


foreach($this->config->item('e___28917') as $x__type => $m) {
    if(isset($m['m__message']) && intval($m['m__message'])>0){

        $total_members = 0;
        $unsnooze_members = 0;

        foreach($this->X_model->fetch(array(
            'x__up' => $_POST['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__down'), 0, 0, array(), 'x__id') as $x) {
            $total_members++;
            if((time()-strlen($x['x__time']))>(86400*intval($m['m__message']))){
                $unsnooze_members++;
            }
        }

        echo $unsnooze_members.'/'.$total_members.' Members Unsnoozing from '.$m['m__title'].': '.intval($m['m__message']).' Days<hr />';

        //Count Members, and any who are eligible to come back as a subscriber?

    }
}