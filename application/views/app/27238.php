<?php

if(!isset($_GET['e__handle']) || !strlen($_GET['e__handle'])) {
    return view_json(array(
        'status' => 0,
        'message' => 'Missing e__handle',
    ));
} else {

    //Login as user:
    $es = $this->E_model->fetch(array(
        'LOWER(e__handle)' => strtolower($_GET['e__handle']),
    ));

    if (!count($es) || !in_array($es[0]['e__privacy'], $this->config->item('n___7358'))) {
        return view_json(array(
            'status' => 0,
            'message' => 'Source is not active.',
        ));
    } else {

        //Make sure member:
        if(!count($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__following IN (' . join(',', $this->config->item('n___32537')) . ')' => null, //Interested Member
            'x__follower' => $es[0]['e__id'],
        )))){

            return view_json(array(
                'status' => 0,
                'message' => 'Source is not an interested member',
            ));

        } else {

            session_delete();

            //Assign session & log transaction:
            $this->E_model->activate_session($es[0]);

            js_php_redirect('/@'.$es[0]['e__handle']);
        }


    }
}