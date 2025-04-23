<?php

foreach($this->Ideas->read(array(
    'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
)) as $i){

    echo '<h2>' . view_idea_title($i) . '</h2>';

    $preg_query = $this->Links->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'chainidearight' => $i['ideaid'],
        'chainplayerup' => 32103,
    ));


    //See apply to Players:
    $apply_to = array();
    foreach($this->Links->read(array(
            'chainplayertype' => 7545, //Following Add
        'chainidearight' => $i['ideaid'],
    ), array('chainplayerup')) as $this_tag){
        array_push($apply_to, intval($this_tag['chainplayerup']));
    }


    if(count($preg_query)){



        if(isset($_GET['playerhandle'])){

            $responses = 0;
            $updated = 0;
            $removed = 0;

            echo '<p>SOURCES Applying against ['.$preg_query[0]['chaintext'].'] results in:</p>';

            foreach($this->Players->read(array(
                'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
            )) as $e){
                foreach($this->Links->read(array(
                    'chainplayerup' => $e['playerid'],
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                                ), array('chainplayerdown'), 0) as $x) {

                    $responses++;
                    $new_form = preg_replace($preg_query[0]['chaintext'], "", $x['chaintext'] );
                    $links_updated = 0;
                    $links_removed = 0;
                    if(strlen($new_form) != strlen($x['chaintext'])) {

                        if(strlen($new_form)){

                            $updated++;
                            if(isset($_GET['update'])){

                                $this->Links->update($x['chainid'], array(
                                    'chaintext' => $new_form,
                                    'chainplayercreator' => $player_session['playerid'],
                                ));

                                foreach($apply_to as $apply_playerid){
                                    foreach($this->Links->read(array(
                                        'chainplayerup' => $apply_playerid,
                                        'chainplayerdown' => $x['chainplayercreator'],
                                        'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                                                                        ), array(), 0) as $follow_appended) {
                                        $links_updated++;
                                        $this->Links->update($follow_appended['chainid'], array(
                                            'chaintext' => $new_form,
                                            'chainplayercreator' => $player_session['playerid'],
                                        ));
                                    }
                                }

                                echo 'Updated! ';
                            }

                        } else {

                            $removed++;
                            if(isset($_GET['update'])){

                                $this->Links->delete($x['chainid']);

                                //Also update follower link?
                                foreach($apply_to as $apply_playerid){
                                    foreach($this->Links->read(array(
                                        'chainplayerup' => $apply_playerid,
                                        'chainplayerdown' => $x['chainplayercreator'],
                                        'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                                                            ), array(), 0) as $follow_appended) {
                                        $links_removed++;
                                        $this->Links->delete($follow_appended['chainid']);
                                    }
                                }
                                echo 'Removed! ';
                            }
                        }

                        echo 'Player ID '.$x['chainplayercreator'].' ['.$x['chaintext'].'] transforms to ['.$new_form.']<hr />';
                    }
                }
            }


            echo 'SOURCES '.$updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

        }


        $responses = 0;
        $updated = 0;
        $removed = 0;

        echo '<p>Applying against ['.$preg_query[0]['chaintext'].'] results in:</p>';

        foreach($this->Links->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'LENGTH(chaintext)>0' => null,
            'chainidealeft' => $i['ideaid'],
        ), array(), 0) as $x) {
            $responses++;
            $new_form = preg_replace($preg_query[0]['chaintext'], "", $x['chaintext'] );
            $links_updated = 0;
            $links_removed = 0;
            if(strlen($new_form) != strlen($x['chaintext'])) {

                if(strlen($new_form)){

                    $updated++;
                    if(isset($_GET['update'])){

                        $this->Links->update($x['chainid'], array(
                            'chaintext' => $new_form,
                            'chainplayercreator' => $player_session['playerid'],
                        ));

                        foreach($apply_to as $apply_playerid){
                            foreach($this->Links->read(array(
                                'chainplayerup' => $apply_playerid,
                                'chainplayerdown' => $x['chainplayercreator'],
                                'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                                            ), array(), 0) as $follow_appended) {
                                $links_updated++;
                                $this->Links->update($follow_appended['chainid'], array(
                                    'chaintext' => $new_form,
                                    'chainplayercreator' => $player_session['playerid'],
                                ));
                            }
                        }
                        echo 'Updated! ';
                    }

                } else {

                    $removed++;
                    if(isset($_GET['update'])){

                        $this->Links->delete($x['chainid']);

                        //Also update follower link?
                        foreach($apply_to as $apply_playerid){
                            foreach($this->Links->read(array(
                                'chainplayerup' => $apply_playerid,
                                'chainplayerdown' => $x['chainplayercreator'],
                                'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE LINKS
                                            ), array(), 0) as $follow_appended) {
                                $links_removed++;
                                $this->Links->delete($follow_appended['chainid']);
                            }
                        }
                        echo 'Removed! ';
                    }
                }

                echo 'Player ID '.$x['chainplayercreator'].' ['.$x['chaintext'].'] transforms to ['.$new_form.']<hr />';
            }
        }

        echo $updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

    } else {

        echo 'Preg remove not set for this idea';

    }
}
