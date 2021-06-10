<?php

if(!isset($_GET['i__id'])){

    echo 'Missing Idea ID (Append ?i__id=ID in URL)';

} else {

    //Fetch Sources who started or were blocked:
    $subs = '';
    $total_subs = 0;
    $already_added = array();
    $filters = array(
        'x__type IN (' . join(',', $this->config->item('n___26582')) . ')' => null,
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
    );
    if(substr_count($_GET['i__id'], ',') > 0){
        //Multiple IDs:
        $filters['x__left IN (' . $_GET['i__id'] . ')'] = null;
    } else {
        $filters['x__left'] = $_GET['i__id'];
    }

    foreach($this->X_model->fetch($filters, array('x__source'), 0, 0, array('x__id' => 'DESC')) as $subscriber){

        //Make sure not already added AND not unsubscribed:
        if (in_array($subscriber['e__id'], $already_added) || count($this->X_model->fetch(array(
            'x__up' => 26583, //Unsubscribed
            'x__down' => $subscriber['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        )))) {
            continue;
        }

        array_push($already_added, $subscriber['e__id']);

        //Fetch email & phone:
        $e_emails = $this->X_model->fetch(array(
            'x__up' => 3288, //Email
            'x__down' => $subscriber['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ));
        $e_email = ( count($e_emails) && filter_var($e_emails[0]['x__message'], FILTER_VALIDATE_EMAIL) ? $e_emails[0]['x__message'] : false );
        $e_phones = $this->X_model->fetch(array(
            'x__up' => 4783, //Phone
            'x__down' => $subscriber['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ));
        $e_phone = ( count($e_phones) && strlen(preg_replace('/[^0-9]/', '', $e_phones[0]['x__message']))>=10 ? preg_replace('/[^0-9]/', '', $e_phones[0]['x__message']) : false );

        preg_replace('/[^0-9]/', '', $string)

        if($e_email || $e_phone){
            //Add to sub list:
            $total_subs++;
            $subs .= one_two_explode('',' ', $subscriber['e__title'])."\t".( count($e_emails) ? $e_emails[0]['x__message'] : '' )."\t".( count($e_phones) ? $e_phones[0]['x__message'] : '' )."\n";
        }

    }

    echo '<div>Found '.$total_subs.' Subscribers:</div>';
    echo '<textarea class="mono-space" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:377px; width: 100%; border-radius: 10px;">'.$subs.'</textarea>';


}