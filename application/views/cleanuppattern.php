<?php

foreach($this->Ideas->fetch(array(
    'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
)) as $i){

    echo '<h2>' . view_idea_title($i) . '</h2>';

    $preg_query = $this->Ledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkidearight' => $i['ideaid'],
        'linkplayerup' => 32103,
    ));


    //See apply to Players:
    $apply_to = array();
    foreach($this->Ledger->fetch(array(
            'linkplayertype' => 7545, //Following Add
        'linkidearight' => $i['ideaid'],
    ), array('linkplayerup')) as $this_tag){
        array_push($apply_to, intval($this_tag['linkplayerup']));
    }


    if(count($preg_query)){



        if(isset($_GET['playerhandle'])){

            $responses = 0;
            $updated = 0;
            $removed = 0;

            echo '<p>SOURCES Applying against ['.$preg_query[0]['linktext'].'] results in:</p>';

            foreach($this->Players->fetch(array(
                'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
            )) as $e){
                foreach($this->Ledger->fetch(array(
                    'linkplayerup' => $e['playerid'],
                    'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                                ), array('linkplayerdown'), 0) as $x) {

                    $responses++;
                    $new_form = preg_replace($preg_query[0]['linktext'], "", $x['linktext'] );
                    $links_updated = 0;
                    $links_removed = 0;
                    if(strlen($new_form) != strlen($x['linktext'])) {

                        if(strlen($new_form)){

                            $updated++;
                            if(isset($_GET['update'])){

                                $this->Ledger->update($x['linkid'], array(
                                    'linktext' => $new_form,
                                    'linkplayercreator' => $player_e['playerid'],
                                ));

                                foreach($apply_to as $apply_playerid){
                                    foreach($this->Ledger->fetch(array(
                                        'linkplayerup' => $apply_playerid,
                                        'linkplayerdown' => $x['linkplayercreator'],
                                        'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                                                                        ), array(), 0) as $follow_appended) {
                                        $links_updated++;
                                        $this->Ledger->update($follow_appended['linkid'], array(
                                            'linktext' => $new_form,
                                            'linkplayercreator' => $player_e['playerid'],
                                        ));
                                    }
                                }

                                echo 'Updated! ';
                            }

                        } else {

                            $removed++;
                            if(isset($_GET['update'])){

                                $this->Ledger->void($x['linkid']);

                                //Also update follower link?
                                foreach($apply_to as $apply_playerid){
                                    foreach($this->Ledger->fetch(array(
                                        'linkplayerup' => $apply_playerid,
                                        'linkplayerdown' => $x['linkplayercreator'],
                                        'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                                                            ), array(), 0) as $follow_appended) {
                                        $links_removed++;
                                        $this->Ledger->void($follow_appended['linkid']);
                                    }
                                }
                                echo 'Removed! ';
                            }
                        }

                        echo 'Player ID '.$x['linkplayercreator'].' ['.$x['linktext'].'] transforms to ['.$new_form.']<hr />';
                    }
                }
            }


            echo 'SOURCES '.$updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

        }


        $responses = 0;
        $updated = 0;
        $removed = 0;

        echo '<p>Applying against ['.$preg_query[0]['linktext'].'] results in:</p>';

        foreach($this->Ledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'LENGTH(linktext)>0' => null,
            'linkidealeft' => $i['ideaid'],
        ), array(), 0) as $x) {
            $responses++;
            $new_form = preg_replace($preg_query[0]['linktext'], "", $x['linktext'] );
            $links_updated = 0;
            $links_removed = 0;
            if(strlen($new_form) != strlen($x['linktext'])) {

                if(strlen($new_form)){

                    $updated++;
                    if(isset($_GET['update'])){

                        $this->Ledger->update($x['linkid'], array(
                            'linktext' => $new_form,
                            'linkplayercreator' => $player_e['playerid'],
                        ));

                        foreach($apply_to as $apply_playerid){
                            foreach($this->Ledger->fetch(array(
                                'linkplayerup' => $apply_playerid,
                                'linkplayerdown' => $x['linkplayercreator'],
                                'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                                            ), array(), 0) as $follow_appended) {
                                $links_updated++;
                                $this->Ledger->update($follow_appended['linkid'], array(
                                    'linktext' => $new_form,
                                    'linkplayercreator' => $player_e['playerid'],
                                ));
                            }
                        }
                        echo 'Updated! ';
                    }

                } else {

                    $removed++;
                    if(isset($_GET['update'])){

                        $this->Ledger->void($x['linkid']);

                        //Also update follower link?
                        foreach($apply_to as $apply_playerid){
                            foreach($this->Ledger->fetch(array(
                                'linkplayerup' => $apply_playerid,
                                'linkplayerdown' => $x['linkplayercreator'],
                                'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                                            ), array(), 0) as $follow_appended) {
                                $links_removed++;
                                $this->Ledger->void($follow_appended['linkid']);
                            }
                        }
                        echo 'Removed! ';
                    }
                }

                echo 'Player ID '.$x['linkplayercreator'].' ['.$x['linktext'].'] transforms to ['.$new_form.']<hr />';
            }
        }

        echo $updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

    } else {

        echo 'Preg remove not set for this idea';

    }
}
