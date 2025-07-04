<?php

foreach($this->Hashtags->read(array(
    'LOWER(hashtaghashtag)' => strtolower($_GET['hashtaghashtag']),
)) as $i){

    echo '<h2>' . view_hashtag_title($i) . '</h2>';

    $preg_query = $this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
        'chainhashtagoutput' => $i['hashtagid'],
        'chainhandleinput' => 32103,
    ));


    //See apply to Handles:
    $apply_to = array();
    foreach($this->Chains->read(array(
            'chainhandletype' => 7545, //Following Add
        'chainhashtagoutput' => $i['hashtagid'],
    ), array('chainhandleinput')) as $this_tag){
        array_push($apply_to, intval($this_tag['chainhandleinput']));
    }


    if(count($preg_query)){



        if(isset($_GET['handlehandle'])){

            $responses = 0;
            $updated = 0;
            $removed = 0;

            echo '<p>HANDLES Applying against ['.$preg_query[0]['chainvalue'].'] results in:</p>';

            foreach($this->Handles->read(array(
                'LOWER(handlehandle)' => strtolower($_GET['handlehandle']),
            )) as $e){
                foreach($this->Chains->read(array(
                    'chainhandleinput' => $e['handleid'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                                ), array('chainhandleoutput'), 0) as $x) {

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
                                    'chainhandlecreator' => $handle_session['handleid'],
                                ));

                                foreach($apply_to as $apply_handleid){
                                    foreach($this->Chains->read(array(
                                        'chainhandleinput' => $apply_handleid,
                                        'chainhandleoutput' => $x['chainhandlecreator'],
                                        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                                                                        ), array(), 0) as $follow_appended) {
                                        $chains_updated++;
                                        $this->Chains->update($follow_appended['chainid'], array(
                                            'chainvalue' => $new_form,
                                            'chainhandlecreator' => $handle_session['handleid'],
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
                                foreach($apply_to as $apply_handleid){
                                    foreach($this->Chains->read(array(
                                        'chainhandleinput' => $apply_handleid,
                                        'chainhandleoutput' => $x['chainhandlecreator'],
                                        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                                                            ), array(), 0) as $follow_appended) {
                                        $chains_removed++;
                                        $this->Chains->delete($follow_appended['chainid']);
                                    }
                                }
                                echo 'Removed! ';
                            }
                        }

                        echo 'Handle ID '.$x['chainhandlecreator'].' ['.$x['chainvalue'].'] transforms to ['.$new_form.']<hr />';
                    }
                }
            }


            echo 'HANDLES '.$updated.'/'.$responses.' Updated & '.$removed.' removed! (Chains Removed: '.$chains_removed.' & Chains Updated: '.$chains_updated.')<hr /><hr /><hr />';

        }


        $responses = 0;
        $updated = 0;
        $removed = 0;

        echo '<p>Applying against ['.$preg_query[0]['chainvalue'].'] results in:</p>';

        foreach($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
            'LENGTH(chainvalue)>0' => null,
            'chainhashtaginput' => $i['hashtagid'],
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
                            'chainhandlecreator' => $handle_session['handleid'],
                        ));

                        foreach($apply_to as $apply_handleid){
                            foreach($this->Chains->read(array(
                                'chainhandleinput' => $apply_handleid,
                                'chainhandleoutput' => $x['chainhandlecreator'],
                                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                                            ), array(), 0) as $follow_appended) {
                                $chains_updated++;
                                $this->Chains->update($follow_appended['chainid'], array(
                                    'chainvalue' => $new_form,
                                    'chainhandlecreator' => $handle_session['handleid'],
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
                        foreach($apply_to as $apply_handleid){
                            foreach($this->Chains->read(array(
                                'chainhandleinput' => $apply_handleid,
                                'chainhandleoutput' => $x['chainhandlecreator'],
                                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                                            ), array(), 0) as $follow_appended) {
                                $chains_removed++;
                                $this->Chains->delete($follow_appended['chainid']);
                            }
                        }
                        echo 'Removed! ';
                    }
                }

                echo 'Handle ID '.$x['chainhandlecreator'].' ['.$x['chainvalue'].'] transforms to ['.$new_form.']<hr />';
            }
        }

        echo $updated.'/'.$responses.' Updated & '.$removed.' removed! (Chains Removed: '.$chains_removed.' & Chains Updated: '.$chains_updated.')<hr /><hr /><hr />';

    } else {

        echo 'Preg remove not set for this hashtag';

    }
}
