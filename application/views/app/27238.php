<?php

if(!isset($_GET['e__id'])) {
    return view_json(array(
        'status' => 0,
        'message' => 'Missing e__id',
    ));
} else {
    $member_e = superpower_unlocked();
    if($member_e){

        //Logout first:
        session_delete();
        js_redirect('/-27283?e__id='.$_GET['e__id'].'&logout=1');

    } else {

        //Login as user:
        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
        ));

        if (!count($es) || !in_array($es[0]['e__type'], $this->config->item('n___7357') /* PUBLIC */)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Source is not public.',
            ));
        } else {

            //Make sure member:
            if(!count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //Source Links
                'x__up' => 4430, //Member
                'x__down' => $es[0]['e__id'],
            )))){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Source is not a member',
                ));
            } else {
                //Assign session & log transaction:
                $this->E_model->activate_session($es[0]);

                js_redirect('/@'.$es[0]['e__id']);
            }


        }
    }
}