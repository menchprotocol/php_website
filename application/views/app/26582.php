<?php

if(!isset($_GET['i__id']) && !isset($_GET['e__id'])){

    echo 'Missing Idea ID (Append ?i__id=ID in URL) or Source ID (Append ?e__id=ID in URL)';

} else {

    //Fetch Sources who started or were blocked:
    $subs = '';
    $emails = '';
    $total_subs = 0;
    $already_added = array();
    if(isset($_GET['i__id'])){
        $is = $this->I_model->fetch(array(
            'i__id IN (' . $_GET['i__id'] . ')' => null,
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        ));
        if(count($is)){
            echo '<h2><a href="/i/i_go/'.$is[0]['i__id'].'"><u>'.$is[0]['i__title'].'</u></a></h2>';
        }
    }

    if(isset($_GET['e__id'])){
        $es = $this->E_model->fetch(array(
            'e__id IN (' . $_GET['e__id'] . ')' => null,
            'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if(count($es)){
            echo '<h2><a href="/@'.$es[0]['e__id'].'"><u>'.$es[0]['e__title'].'</u></a></h2>';
        }
    }


    $query = array();

    if(isset($_GET['i__id'])){
        $query = array_merge($query, $this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___26582')) . ')' => null,
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
            'x__left IN (' . $_GET['i__id'] . ')' => null, //PUBLIC
        ), array('x__source'), 0, 0, array('x__id' => 'DESC')));
    }

    if(isset($_GET['e__id'])){
        $query = array_merge($query, $this->X_model->fetch(array(
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
            'x__up IN (' . $_GET['e__id'] . ')' => null,
        ), array('x__down'), 0, 0, array('x__id' => 'DESC')));
    }

    foreach($query as $subscriber){

        //Make sure not already added AND not unsubscribed:
        if (in_array($subscriber['e__id'], $already_added) || count($this->X_model->fetch(array(
            'x__up' => 26583, //Unsubscribed
            'x__down' => $subscriber['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        )))) {
            continue;
        }

        //Any exclusions?
        if(isset($_GET['exclude_e']) && count($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__up IN (' . $_GET['exclude_e'] . ')' => null,
                'x__down' => $subscriber['e__id'],
            )))){
            continue;
        }

        if(isset($_GET['include_e']) && !count($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__up IN (' . $_GET['include_e'] . ')' => null,
                'x__down' => $subscriber['e__id'],
            )))){
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
        //$e_phone = ( count($e_phones) && strlen(preg_replace('/[^0-9]/', '', $e_phones[0]['x__message']))>=10 ? preg_replace('/[^0-9]/', '', $e_phones[0]['x__message']) : false );
        $e_phone = ( count($e_phones) && strlen($e_phones[0]['x__message'])>=10 ? $e_phones[0]['x__message'] : false );

        if(!isset($_GET['phone']) || (isset($_GET['phone']) && $e_phone)){
            //Add to sub list:
            $total_subs++;
            $subs .= one_two_explode('',' ', $subscriber['e__title'])."\t".$e_email."\t".$e_phone."\n";
            $emails .= ( strlen($emails) ? ", " : '' ).$e_email;
        }

    }

    echo '<div>Found '.$total_subs.' Subscribers:</div>';
    echo '<textarea class="mono-space" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:377px; width: 100%; border-radius: 10px;">'.$subs.'</textarea>';
    echo '<textarea class="mono-space" style="background-color:#FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:377px; width: 100%; border-radius: 10px;">'.$emails.'</textarea>';


}