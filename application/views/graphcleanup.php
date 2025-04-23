<?php


/*
 *


$_GET['disable_algolia'] = true;

$missing_ideas = array();
foreach($this->Ideas->read(array(), 0) as $idea_fix){
    if(!count($this->Links->read(array('chainid' => $idea_fix['ideaid'])))){
        array_push($missing_ideas, $idea_fix);
        //$this->Ideas->create($idea_fix);
    }
}
$missing_players = array();
foreach($this->Players->read(array(), 0) as $player_fix){
    if(!count($this->Links->read(array('chainid' => $player_fix['playerid'])))){
        array_push($missing_players, $player_fix);
        //$this->Players->create($player_fix);
    }
}

view_json(array(
    'ideas_missing' => count($missing_ideas),
    'players_missing' => count($missing_players),
    //'idea_list' => $missing_ideas,
    //'players_list' => $missing_players,
    //'idea_settings' => idea_settings($focus_i['ideahashtag'], false),
    //'history' => $this->Links->history($focus_i, $focus_e['playerid']),
));




if(0){

//Idea cache update

$edited = 0;
$edited_players = 0;
foreach($this->Ideas->read(array(
), 0) as $idea_fix){

    $this->Ideas->update($idea_fix['ideaid'], array(
        'ideacache' => ideacache($idea_fix['ideaid'], $idea_fix['ideatext']),
    ), $player_session['playerid']);

}

echo '<hr />Edited ['.$edited.']['.$edited_players.']<br />';

}

echo '<table>';
foreach($this->Links->read(array(
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___31919')) . ')' => null, //IDEA AUTHOR
), array(), 0, 0, array(
    'chainplayertype' => 'ASC',
    'chainplayerup' => 'ASC',
    'chainplayerdown' => 'ASC',
    'chainidearight' => 'ASC',
    'chainidealeft' => 'ASC',
    'chaintext' => 'ASC',
    'chainid' => 'DESC',
)) as $discover){

    $count++;
    if($previous && $previous['chainplayertype']==$discover['chainplayertype'] && $previous['chainplayerup']==$discover['chainplayerup'] && $previous['chainplayerdown']==$discover['chainplayerdown'] && $previous['chainidearight']==$discover['chainidearight'] && $previous['chainidealeft']==$discover['chainidealeft'] && trim(strtolower($previous['chaintext']))==trim(strtolower($discover['chaintext']))){

        $duplicate++;
        echo '<tr><td>'.$previous['chainplayercreator'].'</td><td>'.$players___4593[$previous['chainplayertype']]['m__title'].'</td><td>'.$previous['chaintime'].'</td><td>'.$previous['chainplayercreator'].'</td><td>'.$previous['chainplayerup'].'</td><td>'.$previous['chainplayerdown'].'</td><td>'.$previous['chainidearight'].'</td><td>'.$previous['chainidealeft'].'</td><td>'.$previous['chaintext'].'</td><td>'.$previous['chainplayertype'].'</td><td>'.$previous['chainplayertype'].'</td></tr>';
        echo '<tr style="background-color: #CCC;"><td>'.$discover['chainplayercreator'].'</td><td>'.$players___4593[$discover['chainplayertype']]['m__title'].'</td><td>'.$discover['chaintime'].'</td><td>'.$discover['chainplayercreator'].'</td><td>'.$discover['chainplayerup'].'</td><td>'.$discover['chainplayerdown'].'</td><td>'.$discover['chainidearight'].'</td><td>'.$discover['chainidealeft'].'</td><td>'.$discover['chaintext'].'</td><td>'.$discover['chainplayertype'].'</td><td>'.$discover['chainplayertype'].'</td></tr>';

        $this->db->query("DELETE FROM ideachain WHERE chainid=".$discover['chainid'].";");

    }

    $previous = $discover;
}

echo '</table>';
echo $duplicate.'/'.$count.' are duplicate';



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
        ), $player_session['playerid']);

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
                'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'chainidealeft' => $is[0]['ideaid'],
            ), array(), 0) as $x){
                if(!count($this->Links->read(array(
                    'chainplayerup' => $player_append['playerid'],
                    'chainplayerdown' => $x['chainplayercreator'],
                    'chaintext' => $x['chaintext'],
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                    )))){
                    //Increment Player link:
                    $completed++;
                    $this->Links->create(array(
                        'chainplayercreator' => ($player_session ? $player_session['playerid'] : $x['chainplayercreator']),
                        'chainplayerup' => $player_append['playerid'],
                        'chainplayerdown' => $x['chainplayercreator'],
                        'chaintext' => $x['chaintext'],
                        'chainplayertype' => 4230,
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

*/