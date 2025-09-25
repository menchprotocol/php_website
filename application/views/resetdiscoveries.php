<?php

//Confirm First
if (!isset($_GET['confirm'])) {

    //Asl user to confirm:
    echo '<div class="alert alert-warning" role="alert">You are about to delete all discoveries for @' . $focus_e['userhandle'] . '... Are you sure you want to continue?</div>';
    echo '<a href="' . view_app_chain(6415) . view_memory(42903, 42902) . $focus_e['userhandle'] . '?confirm=1" class="btn btn-default">Confirm</a>';
    echo ' - OR - ';
    echo '<a href="' . view_memory(42903, 42902) . $focus_e['userhandle'] . '" class="btn btn-default">Cancel & Return to @' . $focus_e['userhandle'] . '</a>';

} else {

    //Fetch their current progress chains:
    $progress_x = $this->Ideachains->read(array(
        'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
        'chainusercreator' => $focus_e['userid'],
    ), array(), 0);

    if (count($progress_x) > 0) {

        //Yes they did have some:
        $message = 'Deleted all ' . count($progress_x) . ' discoveries';

        //Delete all progressions:
        foreach ($progress_x as $progress_x) {
            $this->Ideachains->delete($progress_x['chainid'], $focus_e['userid']);
        }

    } else {

        //Nothing to do:
        $message = 'Nothing found to be removed';

    }

    //Show basic UI for now:
    echo $message;

    //return get_redirected(view_memory(42903,42902).$focus_e['userhandle'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-trash-alt"></i></span>'.$message.'</div>');


}

