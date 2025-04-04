<?php


$is_ideation = array(4228, 31834, 42337, 33532, 44161, 40791, 44162, 40793, 32247, 32304, 33344, 30901, 42244, 42243);
$is_sourcing = array(41011, 4251, 44399, 33335, 42659, 42849, 44176, 44179, 42897, 32486, 4230, 32489, 42579, 42581, 42580, 42283, 42335, 42516, 42554, 42570, 42427, 42518, 42440, 42791);
$is_contribution = array(34513, 4250, 4983, 44396, 42292, 33600, 42272, 42274, 31840, 42275, 42282, 32488, 4258, 4260, 4259, 31835, 41949, 43941, 4256, 12896, 10573, 27984, 43513, 43514, 26600, 7545, 26599, 32235);
$is_discovery = array(44397, 44245, 43798, 43142, 42995, 42916, 42402, 42397, 29393, 42332, 41940, 39597, 35572, 32016, 4559, 31967, 31810, 31798, 31797, 7712, 6144, 31022, 6157, 26595, 4235, 12117, 4235, 7712, 27676, 27678, 29399);
$count_ideation = 0;
$count_sourcing = 0;
$count_contribution = 0;
$count_discovery = 0;
$count_other = 0;
$missing_ideation = 0;
$missing_sourcing = 0;
$missing_contribution = 0;
$missing_discovery = 0;
$missing_other = 0;
boost_power();

$count = 0;
$missing = 0;
foreach ($this->Links->readalt(array(
    'link_type IN (' . join(',', $is_contribution) . ')' => null, //Active Writes
), array(), 0, 0, array('link_id' => 'DESC')) as $x) {

    $count++;
    $is_missing = !count($this->Links->fetch(array(
        'linkplayertype' => $x['link_type'],
        'linkplayerup' => $x['link_up'],
        'linkplayerdown' => $x['link_down'],
        'linkidealeft' => ($x['link_left'] > 0 ? intval($x['link_left']) + 100000 : 0),
        'linkidearight' => ($x['link_right'] > 0 ? intval($x['link_right']) + 100000 : 0),
    )));
    if ($is_missing) {

        $missing++;
        echo print_r($x, true);
        echo 'WAS MISSING <hr />';

        /*
        $this->Links->create(array(
            'linktime' => $x['link_time'],
            'linkplayercreator' => $x['link_player'],
            'linkplayertype' => $x['link_type'],
            'linkplayerdomain' => $x['link_domain'],
            'linkplayerup' => $x['link_up'],
            'linkplayerdown' => $x['link_down'],
            'linkidealeft' => ($x['link_left'] > 0 ? intval($x['link_left']) + 100000 : 0),
            'linkidearight' => ($x['link_right'] > 0 ? intval($x['link_right']) + 100000 : 0),
            'linknumber' => $x['link_number'],
            'linktext' => $x['link_text'],
        ));
        */
    }

    if (in_array($x['link_type'], $is_ideation)) {
        $count_ideation++;
        if ($is_missing) {
            $missing_ideation++;
        }
    } elseif (in_array($x['link_type'], $is_sourcing)) {
        $count_sourcing++;
        if ($is_missing) {
            $missing_sourcing++;
        }
    } elseif (in_array($x['link_type'], $is_contribution)) {
        $count_contribution++;
        if ($is_missing) {
            $missing_contribution++;
        }
    } elseif (in_array($x['link_type'], $is_discovery)) {
        $count_discovery++;
        if ($is_missing) {
            $missing_discovery++;
        }
    } else {
        $count_other++;
        if ($is_missing) {
            $missing_other++;
        }
        echo print_r($x, true);
        echo 'WAS OTHER<hr />';
    }

}

echo $missing . '/' . $count . ' Total Missing:<br /><br />';

echo $missing_ideation . '/' . $count_ideation . ' $count_ideation<br />';
echo $missing_sourcing . '/' . $count_sourcing . ' $count_sourcing<br />';
echo $missing_contribution . '/' . $count_contribution . ' $count_contribution<br />';
echo $missing_discovery . '/' . $count_discovery . ' $count_discovery<br /><br />';

echo $missing_other . '/' . $count_other . ' $count_other<br />';




/*

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

*/