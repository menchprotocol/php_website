<?php

foreach($this->Cacheideas->fetch(array(
    'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
)) as $i){

    echo '<h2>' . view__idea_title($i) . '</h2>';

    $preg_query = $this->Menchledger->fetch(array(
        'linkvoid' => 0, //Not Void
        'linktype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
        'linkright' => $i['ideaid'],
        'linkup' => 32103,
    ));


    //See apply to Players:
    $apply_to = array();
    foreach($this->Menchledger->fetch(array(
        'linkvoid' => 0, //Not Void
        'linktype' => 7545, //Following Add
        'linkright' => $i['ideaid'],
    ), array('linkup')) as $this_tag){
        array_push($apply_to, intval($this_tag['linkup']));
    }


    if(count($preg_query)){



        if(isset($_GET['playerhandle'])){

            $responses = 0;
            $updated = 0;
            $removed = 0;

            echo '<p>SOURCES Applying against ['.$preg_query[0]['linktext'].'] results in:</p>';

            foreach($this->Cacheplayers->fetch(array(
                'LOWER(playerhandle)' => strtolower($_GET['playerhandle']),
            )) as $e){
                foreach($this->Menchledger->fetch(array(
                    'linkup' => $e['playerid'],
                    'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
                    'linkvoid' => 0, //Not Void
                ), array('linkdown'), 0) as $x) {

                    $responses++;
                    $new_form = preg_replace($preg_query[0]['linktext'], "", $x['linktext'] );
                    $links_updated = 0;
                    $links_removed = 0;
                    if(strlen($new_form) != strlen($x['linktext'])) {

                        if(strlen($new_form)){

                            $updated++;
                            if(isset($_GET['update'])){

                                $this->Menchledger->update($x['linkid'], array(
                                    'linktext' => $new_form,
                                ));

                                foreach($apply_to as $apply_playerid){
                                    foreach($this->Menchledger->fetch(array(
                                        'linkup' => $apply_playerid,
                                        'linkdown' => $x['linkplayer'],
                                        'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
                                        'linkvoid' => 0, //Not Void
                                    ), array(), 0) as $follow_appended) {
                                        $links_updated++;
                                        $this->Menchledger->update($follow_appended['linkid'], array(
                                            'linktext' => $new_form,
                                        ));
                                    }
                                }

                                echo 'Updated! ';
                            }

                        } else {

                            $removed++;
                            if(isset($_GET['update'])){

                                $this->Menchledger->update($x['linkid'], array());

                                //Also update follower link?
                                foreach($apply_to as $apply_playerid){
                                    foreach($this->Menchledger->fetch(array(
                                        'linkup' => $apply_playerid,
                                        'linkdown' => $x['linkplayer'],
                                        'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
                                        'linkvoid' => 0, //Not Void
                                    ), array(), 0) as $follow_appended) {
                                        $links_removed++;
                                        $this->Menchledger->update($follow_appended['linkid'], array());
                                    }
                                }
                                echo 'Removed! ';
                            }
                        }

                        echo 'Player ID '.$x['linkplayer'].' ['.$x['linktext'].'] transforms to ['.$new_form.']<hr />';
                    }
                }
            }


            echo 'SOURCES '.$updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

        }


        $responses = 0;
        $updated = 0;
        $removed = 0;

        echo '<p>Applying against ['.$preg_query[0]['linktext'].'] results in:</p>';

        foreach($this->Menchledger->fetch(array(
            'linkvoid' => 0, //Not Void
            'linktype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'LENGTH(linktext)>0' => null,
            'linkleft' => $i['ideaid'],
        ), array(), 0) as $x) {
            $responses++;
            $new_form = preg_replace($preg_query[0]['linktext'], "", $x['linktext'] );
            $links_updated = 0;
            $links_removed = 0;
            if(strlen($new_form) != strlen($x['linktext'])) {

                if(strlen($new_form)){

                    $updated++;
                    if(isset($_GET['update'])){

                        $this->Menchledger->update($x['linkid'], array(
                            'linktext' => $new_form,
                        ));

                        foreach($apply_to as $apply_playerid){
                            foreach($this->Menchledger->fetch(array(
                                'linkup' => $apply_playerid,
                                'linkdown' => $x['linkplayer'],
                                'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
                                'linkvoid' => 0, //Not Void
                            ), array(), 0) as $follow_appended) {
                                $links_updated++;
                                $this->Menchledger->update($follow_appended['linkid'], array(
                                    'linktext' => $new_form,
                                ));
                            }
                        }
                        echo 'Updated! ';
                    }

                } else {

                    $removed++;
                    if(isset($_GET['update'])){

                        $this->Menchledger->update($x['linkid'], array());

                        //Also update follower link?
                        foreach($apply_to as $apply_playerid){
                            foreach($this->Menchledger->fetch(array(
                                'linkup' => $apply_playerid,
                                'linkdown' => $x['linkplayer'],
                                'linktype IN (' . join(',', $this->config->item('playerids___32292')) . ')' => null, //SOURCE LINKS
                                'linkvoid' => 0, //Not Void
                            ), array(), 0) as $follow_appended) {
                                $links_removed++;
                                $this->Menchledger->update($follow_appended['linkid'], array());
                            }
                        }
                        echo 'Removed! ';
                    }
                }

                echo 'Player ID '.$x['linkplayer'].' ['.$x['linktext'].'] transforms to ['.$new_form.']<hr />';
            }
        }

        echo $updated.'/'.$responses.' Updated & '.$removed.' removed! (Links Removed: '.$links_removed.' & Links Updated: '.$links_updated.')<hr /><hr /><hr />';

    } else {

        echo 'Preg remove not set for this idea';

    }
}
