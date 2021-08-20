<?php

if(!isset($_GET['e__id'])) {
    echo 'Must define e__id';
} else {
    $member_e = superpower_unlocked();
    if($member_e){

        //Logout first:
        session_delete();
        redirect_message('/-27283?e__id='.$_GET['e__id'].'&logout=1');

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
            //Assign session & log transaction:
            $this->E_model->activate_session($es[0]);

            redirect_message('/@'.$es[0]['e__id']);
        }
    }
}