<?php


//Various cleanup functions
echo @$_GET['playerhandle'];


if(isset($_GET['action']) && $_GET['action']=='idea_messages'){

    //Sync Ideas & Players
    $stats = array(
        'cached_ideas' => 0,
        'active_ideas' => 0,
        'missing_creation' => 0,
    );

    $edited = 0;
    $edited_players = 0;
    foreach($this->Ideas->read(array(
    ), 0) as $idea_fix){

        $this->Ideas->update($idea_fix['ideaid'], array(
            'ideacache' => ideacache($idea_fix['ideaid'], $idea_fix['ideatext']),
        ), $player_e['playerid']);

    }

    echo '<hr />Edited ['.$edited.']['.$edited_players.']<br />';


} elseif(isset($_GET['action']) && $_GET['action']=='import_discovery') {


    //Import Discoveries?
    $flash_message = '';
    if(isset($_GET['playerhandle'])){
        foreach($this->Players->read(array(
            'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
        )) as $player_append){
            $completed = 0;
            foreach($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'linkidealeft' => $is[0]['ideaid'],
            ), array(), 0) as $x){
                if(!count($this->Links->read(array(
                    'linkplayerup' => $player_append['playerid'],
                    'linkplayerdown' => $x['linkplayercreator'],
                    'linktext' => $x['linktext'],
                    'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                    )))){
                    //Increment Player link:
                    $completed++;
                    $this->Links->create(array(
                        'linkplayercreator' => ($player_e ? $player_e['playerid'] : $x['linkplayercreator']),
                        'linkplayerup' => $player_append['playerid'],
                        'linkplayerdown' => $x['linkplayercreator'],
                        'linktext' => $x['linktext'],
                        'linkplayertype' => 4230,
                    ));
                }
            }

            $flash_message = '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span> '.$completed.' Players who played this idea added to @'.$player_append['playerhandle'].'</div>';
        }
    }

} else {

    //SHow list of actions:
    echo 'Enter GET action value to process...';

}