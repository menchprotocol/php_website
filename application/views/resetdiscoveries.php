<?php

//Confirm First
if(!isset($_GET['confirm'])){

    //Asl user to confirm:
    echo '<div class="alert alert-warning" role="alert">You are about to delete all discoveries for @'.$focus_e['e__handle'].'... Are you sure you want to continue?</div>';
    echo '<a href="'.view__app_link(6415).view__memory(42903,42902).$focus_e['e__handle'].'?confirm=1" class="btn btn-default">Confirm</a>';
    echo ' - OR - ';
    echo '<a href="'.view__memory(42903,42902).$focus_e['e__handle'].'" class="btn btn-default">Cancel & Return to @'.$focus_e['e__handle'].'</a>';

} else {

    //Fetch their current progress transactions:
    $progress_x = $this->Mench_ledger->fetch(array(
        'link_void' => 0, //Not Void
        'link_type IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //DISCOVERIES
        'link_player' => $focus_e['e__id'],
    ), array(), 0);

    if(count($progress_x) > 0){

        //Yes they did have some:
        $message = 'Deleted all '.count($progress_x).' discoveries';

        //Delete all progressions:
        foreach($progress_x as $progress_x){
            $this->Mench_ledger->update($progress_x['link_id'], array(), $focus_e['e__id']);
        }

    } else {

        //Nothing to do:
        $message = 'Nothing found to be removed';

    }

    //Show basic UI for now:
    echo $message;

    //return redirect_message(view__memory(42903,42902).$focus_e['e__handle'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-trash-alt"></i></span>'.$message.'</div>');


}

