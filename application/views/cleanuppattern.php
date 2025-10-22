<?php

foreach($this->Posts->read(array(
    'LOWER(posthashtag)' => strtolower($_GET['posthashtag']),
)) as $i){

    echo '<h2>' . view_post_title($i) . '</h2>';

    $preg_query = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainpostinput' => $i['postid'],
        'chainuserinput' => 32103,
    ));


    //See apply to Users:
    $apply_to = array();
    foreach($this->Chains->read(array(
            'chainusertype' => 7545, //Following Add
        'chainpostinput' => $i['postid'],
    ), array('chainuserinput')) as $this_tag){
        array_push($apply_to, intval($this_tag['chainuserinput']));
    }


    if(count($preg_query)){



        if(isset($_GET['userhandle'])){

            $responses = 0;
            $updated = 0;
            $removed = 0;

            echo '<p>USERS Applying against ['.$preg_query[0]['chainvalue'].'] results in:</p>';

            foreach($this->Users->read(array(
                'LOWER(userhandle)' => strtolower($_GET['userhandle']),
            )) as $e){
                foreach($this->Chains->read(array(
                    'chainuserinput' => $e['userid'],
                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                                ), array('chainuseroutput'), 0) as $x) {

                    $responses++;
                    $new_form = preg_replace($preg_query[0]['chainvalue'], "", $x['chainvalue'] );
                    $chains_updated = 0;
                    $chains_removed = 0;
                    if(strlen($new_form) != strlen($x['chainvalue'])) {

                        if(strlen($new_form)){

                            $updated++;
                            if(isset($_GET['update'])){

                                $this->Chains->update($x['chainid'], array(
                                    'chainvalue' => $new_form,
                                    'chainusercreator' => $user_session['userid'],
                                ));

                                foreach($apply_to as $apply_userid){
                                    foreach($this->Chains->read(array(
                                        'chainuserinput' => $apply_userid,
                                        'chainuseroutput' => $x['chainusercreator'],
                                        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                                                                        ), array(), 0) as $follow_appended) {
                                        $chains_updated++;
                                        $this->Chains->update($follow_appended['chainid'], array(
                                            'chainvalue' => $new_form,
                                            'chainusercreator' => $user_session['userid'],
                                        ));
                                    }
                                }

                                echo 'Updated! ';
                            }

                        } else {

                            $removed++;
                            if(isset($_GET['update'])){

                                $this->Chains->delete($x['chainid']);

                                //Also update follower chain?
                                foreach($apply_to as $apply_userid){
                                    foreach($this->Chains->read(array(
                                        'chainuserinput' => $apply_userid,
                                        'chainuseroutput' => $x['chainusercreator'],
                                        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                                                            ), array(), 0) as $follow_appended) {
                                        $chains_removed++;
                                        $this->Chains->delete($follow_appended['chainid']);
                                    }
                                }
                                echo 'Removed! ';
                            }
                        }

                        echo 'User ID '.$x['chainusercreator'].' ['.$x['chainvalue'].'] transforms to ['.$new_form.']<hr />';
                    }
                }
            }


            echo 'USERS '.$updated.'/'.$responses.' Updated & '.$removed.' removed! (Chains Removed: '.$chains_removed.' & Chains Updated: '.$chains_updated.')<hr /><hr /><hr />';

        }


        $responses = 0;
        $updated = 0;
        $removed = 0;

        echo '<p>Applying against ['.$preg_query[0]['chainvalue'].'] results in:</p>';

        foreach($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            'LENGTH(chainvalue)>0' => null,
            'chainpostinput' => $i['postid'],
        ), array(), 0) as $x) {
            $responses++;
            $new_form = preg_replace($preg_query[0]['chainvalue'], "", $x['chainvalue'] );
            $chains_updated = 0;
            $chains_removed = 0;
            if(strlen($new_form) != strlen($x['chainvalue'])) {

                if(strlen($new_form)){

                    $updated++;
                    if(isset($_GET['update'])){

                        $this->Chains->update($x['chainid'], array(
                            'chainvalue' => $new_form,
                            'chainusercreator' => $user_session['userid'],
                        ));

                        foreach($apply_to as $apply_userid){
                            foreach($this->Chains->read(array(
                                'chainuserinput' => $apply_userid,
                                'chainuseroutput' => $x['chainusercreator'],
                                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                                            ), array(), 0) as $follow_appended) {
                                $chains_updated++;
                                $this->Chains->update($follow_appended['chainid'], array(
                                    'chainvalue' => $new_form,
                                    'chainusercreator' => $user_session['userid'],
                                ));
                            }
                        }
                        echo 'Updated! ';
                    }

                } else {

                    $removed++;
                    if(isset($_GET['update'])){

                        $this->Chains->delete($x['chainid']);

                        //Also update follower chain?
                        foreach($apply_to as $apply_userid){
                            foreach($this->Chains->read(array(
                                'chainuserinput' => $apply_userid,
                                'chainuseroutput' => $x['chainusercreator'],
                                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                                            ), array(), 0) as $follow_appended) {
                                $chains_removed++;
                                $this->Chains->delete($follow_appended['chainid']);
                            }
                        }
                        echo 'Removed! ';
                    }
                }

                echo 'User ID '.$x['chainusercreator'].' ['.$x['chainvalue'].'] transforms to ['.$new_form.']<hr />';
            }
        }

        echo $updated.'/'.$responses.' Updated & '.$removed.' removed! (Chains Removed: '.$chains_removed.' & Chains Updated: '.$chains_updated.')<hr /><hr /><hr />';

    } else {

        echo 'Preg remove not set for this post';

    }
}
