<?php

foreach($this->Ideas->read(array(
    'LOWER(ideahashtag)' => strtolower($_GET['ideahashtag']),
)) as $i){

    echo '<h2>' . view_idea_title($i) . '</h2>';

    $preg_query = $this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
        'chainidearight' => $i['ideaid'],
        'chainsourceup' => 32103,
    ));


    //See apply to Sources:
    $apply_to = array();
    foreach($this->Chains->read(array(
            'chainsourcetype' => 7545, //Following Add
        'chainidearight' => $i['ideaid'],
    ), array('chainsourceup')) as $this_tag){
        array_push($apply_to, intval($this_tag['chainsourceup']));
    }


    if(count($preg_query)){



        if(isset($_GET['sourcehandle'])){

            $responses = 0;
            $updated = 0;
            $removed = 0;

            echo '<p>SOURCES Applying against ['.$preg_query[0]['chainvalue'].'] results in:</p>';

            foreach($this->Sources->read(array(
                'LOWER(sourcehandle)' => strtolower($_GET['sourcehandle']),
            )) as $e){
                foreach($this->Chains->read(array(
                    'chainsourceup' => $e['sourceid'],
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                                ), array('chainsourcedown'), 0) as $x) {

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
                                    'chainsourcecreator' => $source_session['sourceid'],
                                ));

                                foreach($apply_to as $apply_sourceid){
                                    foreach($this->Chains->read(array(
                                        'chainsourceup' => $apply_sourceid,
                                        'chainsourcedown' => $x['chainsourcecreator'],
                                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                                                                        ), array(), 0) as $follow_appended) {
                                        $chains_updated++;
                                        $this->Chains->update($follow_appended['chainid'], array(
                                            'chainvalue' => $new_form,
                                            'chainsourcecreator' => $source_session['sourceid'],
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
                                foreach($apply_to as $apply_sourceid){
                                    foreach($this->Chains->read(array(
                                        'chainsourceup' => $apply_sourceid,
                                        'chainsourcedown' => $x['chainsourcecreator'],
                                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                                                            ), array(), 0) as $follow_appended) {
                                        $chains_removed++;
                                        $this->Chains->delete($follow_appended['chainid']);
                                    }
                                }
                                echo 'Removed! ';
                            }
                        }

                        echo 'Source ID '.$x['chainsourcecreator'].' ['.$x['chainvalue'].'] transforms to ['.$new_form.']<hr />';
                    }
                }
            }


            echo 'SOURCES '.$updated.'/'.$responses.' Updated & '.$removed.' removed! (Chains Removed: '.$chains_removed.' & Chains Updated: '.$chains_updated.')<hr /><hr /><hr />';

        }


        $responses = 0;
        $updated = 0;
        $removed = 0;

        echo '<p>Applying against ['.$preg_query[0]['chainvalue'].'] results in:</p>';

        foreach($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'LENGTH(chainvalue)>0' => null,
            'chainidealeft' => $i['ideaid'],
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
                            'chainsourcecreator' => $source_session['sourceid'],
                        ));

                        foreach($apply_to as $apply_sourceid){
                            foreach($this->Chains->read(array(
                                'chainsourceup' => $apply_sourceid,
                                'chainsourcedown' => $x['chainsourcecreator'],
                                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                                            ), array(), 0) as $follow_appended) {
                                $chains_updated++;
                                $this->Chains->update($follow_appended['chainid'], array(
                                    'chainvalue' => $new_form,
                                    'chainsourcecreator' => $source_session['sourceid'],
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
                        foreach($apply_to as $apply_sourceid){
                            foreach($this->Chains->read(array(
                                'chainsourceup' => $apply_sourceid,
                                'chainsourcedown' => $x['chainsourcecreator'],
                                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                                            ), array(), 0) as $follow_appended) {
                                $chains_removed++;
                                $this->Chains->delete($follow_appended['chainid']);
                            }
                        }
                        echo 'Removed! ';
                    }
                }

                echo 'Source ID '.$x['chainsourcecreator'].' ['.$x['chainvalue'].'] transforms to ['.$new_form.']<hr />';
            }
        }

        echo $updated.'/'.$responses.' Updated & '.$removed.' removed! (Chains Removed: '.$chains_removed.' & Chains Updated: '.$chains_updated.')<hr /><hr /><hr />';

    } else {

        echo 'Preg remove not set for this idea';

    }
}
