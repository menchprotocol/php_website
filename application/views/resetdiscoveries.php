<?php

//Confirm First
if(!isset($_GET['confirm'])){

    //Asl user to confirm:
    echo '<div class="alert alert-warning" role="alert">You are about to delete all discoveries for @'.$focus_e['playerhandle'].'... Are you sure you want to continue?</div>';
    echo '<a href="'.view__app_link(6415).view__memory(42903,42902).$focus_e['playerhandle'].'?confirm=1" class="btn btn-default">Confirm</a>';
    echo ' - OR - ';
    echo '<a href="'.view__memory(42903,42902).$focus_e['playerhandle'].'" class="btn btn-default">Cancel & Return to @'.$focus_e['playerhandle'].'</a>';

} else {

    //Fetch their current progress transactions:
    $progress_x = $this->Menchledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___31777')) . ')' => null, //DISCOVERIES
        'linkplayer' => $focus_e['playerid'],
    ), array(), 0);

    if(count($progress_x) > 0){

        //Yes they did have some:
        $message = 'Deleted all '.count($progress_x).' discoveries';

        //Delete all progressions:
        foreach($progress_x as $progress_x){
            $this->Menchledger->update($progress_x['linkid'], array(), $focus_e['playerid']);
        }

    } else {

        //Nothing to do:
        $message = 'Nothing found to be removed';

    }

    //Show basic UI for now:
    echo $message;

    //return redirect_message(view__memory(42903,42902).$focus_e['playerhandle'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-trash-alt"></i></span>'.$message.'</div>');


}

