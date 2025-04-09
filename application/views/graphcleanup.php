<?php

/*
 *

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
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___31919')) . ')' => null, //IDEA AUTHOR
), array(), 0, 0, array(
    'linkplayertype' => 'ASC',
    'linkplayerup' => 'ASC',
    'linkplayerdown' => 'ASC',
    'linkidearight' => 'ASC',
    'linkidealeft' => 'ASC',
    'linktext' => 'ASC',
    'linkid' => 'DESC',
)) as $discover){

    $count++;
    if($previous && $previous['linkplayertype']==$discover['linkplayertype'] && $previous['linkplayerup']==$discover['linkplayerup'] && $previous['linkplayerdown']==$discover['linkplayerdown'] && $previous['linkidearight']==$discover['linkidearight'] && $previous['linkidealeft']==$discover['linkidealeft'] && trim(strtolower($previous['linktext']))==trim(strtolower($discover['linktext']))){

        $duplicate++;
        echo '<tr><td>'.$previous['linkplayercreator'].'</td><td>'.$players___4593[$previous['linkplayertype']]['m__title'].'</td><td>'.$previous['linktime'].'</td><td>'.$previous['linkplayercreator'].'</td><td>'.$previous['linkplayerup'].'</td><td>'.$previous['linkplayerdown'].'</td><td>'.$previous['linkidearight'].'</td><td>'.$previous['linkidealeft'].'</td><td>'.$previous['linktext'].'</td><td>'.$previous['linkplayertype'].'</td><td>'.$previous['linkplayertype'].'</td></tr>';
        echo '<tr style="background-color: #CCC;"><td>'.$discover['linkplayercreator'].'</td><td>'.$players___4593[$discover['linkplayertype']]['m__title'].'</td><td>'.$discover['linktime'].'</td><td>'.$discover['linkplayercreator'].'</td><td>'.$discover['linkplayerup'].'</td><td>'.$discover['linkplayerdown'].'</td><td>'.$discover['linkidearight'].'</td><td>'.$discover['linkidealeft'].'</td><td>'.$discover['linktext'].'</td><td>'.$discover['linkplayertype'].'</td><td>'.$discover['linkplayertype'].'</td></tr>';

        $this->db->query("DELETE FROM menchledger WHERE linkid=".$discover['linkid'].";");

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
                'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
                'linkidealeft' => $is[0]['ideaid'],
            ), array(), 0) as $x){
                if(!count($this->Links->read(array(
                    'linkplayerup' => $player_append['playerid'],
                    'linkplayerdown' => $x['linkplayercreator'],
                    'linktext' => $x['linktext'],
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                    )))){
                    //Increment Player link:
                    $completed++;
                    $this->Links->create(array(
                        'linkplayercreator' => ($player_session ? $player_session['playerid'] : $x['linkplayercreator']),
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

*/