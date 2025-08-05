<?php

boost_power();
$mentions = $this->config->item('handles___13550');
$ideas = $this->config->item('handles___4486');
$handles___4737 = $this->config->item('handles___4737'); //Hashtag Types
$count = 0;


//Translator
$table = '<table class="table table-sm table-striped stats-table mini-stats-table" border="1">';


if($focus_i['hashtagterm']=='Discotique2024') {



    //Update
    /*
    $this->Chains->create(array(
        'chainhandletype' => 43513,
        'chainhandlecreator' => 1,
        'chainhashtagoutput' => 1733119,
        'chainhandleinput' => 1636421, //Discotique Leaders 25
    ));
    $this->Chains->create(array(
        'chainhandletype' => 43513,
        'chainhandlecreator' => 1,
        'chainhashtagoutput' => 1733121,
        'chainhandleinput' => 27093, //Trusted
    ));
    foreach(array(1733119,
                1733121,
                1733123,
                1733125,
                1733127,
                1733129) as $daysofweek){
        $this->Chains->create(array(
            'chainhandletype' => 4983,
            'chainhandlecreator' => 1,
            'chainhashtagoutput' => $daysofweek,
            'chainhandleinput' => 26189, //Required
            'chainvalue' => 5,
        ));
    }


    foreach(array(1734987,1733038) as $required_hashtagid){
        $this->Chains->create(array(
            'chainhandletype' => 4983,
            'chainhandlecreator' => 1,
            'chainhashtagoutput' => $required_hashtagid,
            'chainhandleinput' => 28239, //Required
        ));
    }
*/


    echo 'done done';

    /*
    foreach($this->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhandletype' => 12273,
    ), array(''), 0, 0, array('chainid' => 'ASC')) as $x){

    }

    foreach ($this->Hashtags->read(array(
        'LOWER(hashtagterm)' => strtolower(view_valid_handle_hashtag($action_command1)),
    )) as $i) {

    }
    $this->Hashtags->update($ref['hashtagid'], array(
        'hashtagtext' => str_replace('#' . $is[0]['hashtagterm'], '#' . trim($_POST['save_hashtagterm']), $ref['hashtagtext']),
        'hashtagupdated' => 1,
    ), $handle_session['handleid']);
    */

} elseif($focus_i['hashtagterm']=='Discotique2025') {

    //HANDLE
    $chainhandleoutput = array();
    $stats = array(
        'handles_all' => 0,
        'handles_delete' => 0,
        'handles_duplicate' => 0,
        'handles_void' => 0,
        'handles_voidcreaetor' => 0,
        'handles_void_cachevalid' => 0,
        'handles_valid_cachevoid' => 0,
    );

    foreach ($this->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhandletype' => 12274,
    ), array(), 377, 0, array('chainid' => 'ASC')) as $x) {

        $is_duplicate = in_array($x['chainhandleoutput'], $chainhandleoutput);
        if (!$is_duplicate) {
            array_push($chainhandleoutput, $x['chainhandleoutput']);
        } else {
            $stats['handles_duplicate']++;
        }

        $count++;
        $es_cache = $this->Handles->read(array(
            'handleid' => $x['chainhandleoutput'],
        ));
        $es = $this->Handles->read(array(
            'handleid' => $x['chainhandlecreator'],
        ));

        $stats['handles_all']++;
        if ($x['chainvoid'] > 0) {
            $stats['handles_void']++;
        } elseif (!count($es)) {
            $stats['handles_voidcreaetor']++;
        }
        if ($x['chainvoid'] > 0 && count($es_cache)) {
            $stats['handles_void_cachevalid']++;
        }
        if (!$x['chainvoid'] && !count($es_cache)) {
            $stats['handles_valid_cachevoid']++;
        }


        //Fetch from Cache table:
        if (count($es_cache)) {
            $hashtagtext = '@' . $es_cache[0]['handleterm'] . "\n" . $es_cache[0]['handlename'] . "\n" . $es_cache[0]['handlecover'];
        } else {
            $hashtagtext = '@???' . $x['chainvalue'] . "\n" . $x['chainvalue'] . "\nfar fa-handle";
        }

        //Append Description if any
        foreach ($this->Chains->read(array(
            'LENGTH(chainvalue) > 0' => null,
            'chainhandleinput IN (11035,42628)' => null,
            'chainhandleoutput' => $x['chainhandleoutput'],
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array(), 0, 0) as $social_chain) {
            $hashtagtext .= "\n" . $social_chain['chainvalue'];
        }

        $delete = $x['chainvoid'] > 0 || $is_duplicate || (!$x['chainvoid'] && !count($es)) || (!$x['chainvoid'] && !count($es_cache));
        if ($delete) {
            $stats['handles_delete']++;
        }

        $table .= '<tr>';
        $table .= '<td>' . $x['chainid'] . '<br />V' . $x['chainvoid'] . '/' . $count . '/' .
            ($delete ? '[DELETE]' : '') .
            ($x['chainvoid'] > 0 ? '[VOID]' : '') .
            ($is_duplicate ? '[DUPLICATE]' : '') .
            (!$x['chainvoid'] && !count($es) ? '[handles_voidcreaetor]' : '') .
            (!$x['chainvoid'] && !count($es_cache) ? '[handles_valid_cachevoid]' : '') .
            '</td>';
        $table .= '<td>T@' . $x['chainhandletype'] . '<br />C@' . $x['chainhandlecreator'] . '<br />@' . $x['chainhandleoutput'] . '</td>';
        $table .= '<td><div style="max-width:233px;">' . nl2br(trim(htmlentities($hashtagtext))) . '</div></td>';
        //$table .= '<td><div style="max-width:233px;">'.nl2br(trim(htmlentities($hashtagtext))).'</div></td>';
        $table .= '</tr>';
    }

} elseif(0){

    //Scan/Fix 3x Media Links

    $table .= '<tr>';
    $table .= '<td>ID</td>';
    $table .= '<td>Type</td>';
    $table .= '<td>NOW</td>';
    $table .= '<td>FIXED</td>';
    $table .= '<td>DELETE?</td>';
    $table .= '</tr>';

    $success = array(
        1326 => 0,
        4258 => 0,
        4259 => 0,
        4260 => 0,
    );
    $fail = array(
        1326 => 0,
        4258 => 0,
        4259 => 0,
        4260 => 0,
    );
    $fixed = array(
        1326 => 0,
        4258 => 0,
        4259 => 0,
        4260 => 0,
    );
    $delete = array(
        1326 => 0,
        4258 => 0,
        4259 => 0,
        4260 => 0,
    );


    foreach ($this->Chains->read(array(
        'chainhandleinput IN (' . join(',', $this->config->item('handleids___1735577')) . ')' => null, //HANDLE DISPLAY
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
    ), array('chainhandleoutput'), 0, 0, array('chainhandleinput' => 'ASC')) as $x) {

        $must_delete = false;
        $newchainvalue = false;

        if ($x['chainhandleinput'] == 1326) {

            if(filter_var($x['chainvalue'], FILTER_VALIDATE_URL)){
                $success[$x['chainhandleinput']]++;
            } else {
                $fail[$x['chainhandleinput']]++;
                //See if we can find it?
                foreach ($this->Chains->read(array(
                    'chainhandleoutput' => $x['chainhandleoutput'],
                    'LENGTH(chainvalue) > 0' => null,
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                ), array('chainhandleinput'), 0, 0, array('chainhandleinput' => 'ASC')) as $x2) {
                    if(filter_var($x2['chainvalue'], FILTER_VALIDATE_URL)){
                        $fixed[$x['chainhandleinput']]++;
                        $newchainvalue = $x2['chainvalue'];
                        break;
                    }
                }
                if(!$newchainvalue){
                    $must_delete = true;
                }
            }

        } elseif ($x['chainhandleinput'] == 4258) {

            //Video
            if(strlen($x['chainvalue'])){
                $success[$x['chainhandleinput']]++;
            } else {
                $fail[$x['chainhandleinput']]++;
                //See if we can find it?
                foreach ($this->Chains->read(array(
                    'chainhandleinput' => 42660, //Media Public ID
                    'chainhandleoutput' => $x['chainhandleoutput'],
                    'LENGTH(chainvalue) > 0' => null,
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                ), array('chainhandleoutput'), 0, 0, array('chainhandleinput' => 'ASC')) as $x2) {
                    $fixed[$x['chainhandleinput']]++;
                    $newchainvalue = $x2['chainvalue'];
                    break;
                }
                if(!$newchainvalue){
                    $must_delete = true;
                }
            }

        } elseif ($x['chainhandleinput'] == 4259) {

            //Audio
            if(filter_var($x['chainvalue'], FILTER_VALIDATE_URL)){
                $success[$x['chainhandleinput']]++;
            } else {
                $fail[$x['chainhandleinput']]++;
                //See if we can find it?
                foreach ($this->Chains->read(array(
                    'chainhandleinput' => 42693, //Secure URL
                    'chainhandleoutput' => $x['chainhandleoutput'],
                    'LENGTH(chainvalue) > 0' => null,
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                ), array('chainhandleoutput'), 0, 0, array('chainhandleinput' => 'ASC')) as $x2) {
                    if(filter_var($x2['chainvalue'], FILTER_VALIDATE_URL)){
                        $fixed[$x['chainhandleinput']]++;
                        $newchainvalue = $x2['chainvalue'];
                        break;
                    }
                }
                if(!$newchainvalue){
                    $must_delete = true;
                }
            }

        } elseif ($x['chainhandleinput'] == 4260) {

            //Image
            if(filter_var($x['chainvalue'], FILTER_VALIDATE_URL)){
                $success[$x['chainhandleinput']]++;
            } else {
                $fail[$x['chainhandleinput']]++;
                //See if we can find it?
                foreach ($this->Chains->read(array(
                    'chainhandleoutput' => $x['chainhandleoutput'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                ), array('chainhandleinput'), 0) as $x2) {
                    if(filter_var($x2['chainvalue'], FILTER_VALIDATE_URL)){
                        $fixed[$x['chainhandleinput']]++;
                        $newchainvalue = $x2['chainvalue'];
                        break;
                    }
                }
                if(!$newchainvalue){
                    if(filter_var($x['handlecover'], FILTER_VALIDATE_URL)){
                        $fixed[$x['chainhandleinput']]++;
                        $newchainvalue = $x['handlecover'];
                    } else {
                        $must_delete = true;
                    }
                }
            }

        } else {

            $must_delete = true;

        }


        if(strlen($newchainvalue) && 0){
            $this->Chains->update($x['chainid'], array(
                'chainvalue' => $newchainvalue,
            ));
        } elseif($must_delete && 0){
            //Delete chain:
            $this->Chains->delete($x['chainid']);
        }



        $table .= '<tr>';

        $table .= '<td>'.$x['chainid'].'</td>';
        $table .= '<td>'.$x['chainhandleinput'].'</td>';
        $table .= '<td><div style="max-width:233px;">'.$x['chainvalue'].'</div></td>';
        $table .= '<td><div style="max-width:233px;">'.( strlen($newchainvalue) ? 'FIXED: ' : '' ).$newchainvalue.'</div></td>';
        $table .= '<td>'.( $must_delete ? 'DELETE' : '' ).'</td>';

        $table .= '</tr>';

        if($must_delete){
            $delete[$x['chainhandleinput']]++;
        }

    }

    $table .= '</table>';


    echo 'Success:';
    print_r($success);
    echo '<hr />Fail:';
    print_r($fail);
    echo '<hr />Fixed:';
    print_r($fixed);
    echo '<hr />Delete:';
    print_r($delete);
    echo $table;

} else {

    //HASHTAGS
    $table .= '<tr>';
    $table .= '<td>&nbsp;</td>';
    $table .= '<td>&nbsp;</td>';
    $table .= '<td><div style="max-width:233px;">INPUT</div></td>'; //RAW
    $table .= '<td><div style="max-width:233px;">hashtagchain</div></td>'; //RAW
    $table .= '<td><div style="max-width:233px;">hashtagtext</div></td>'; //TEXT
    $table .= '<td><div style="max-width:233px;">hashtagdiscover</div></td>'; //DISCOVERY
    $table .= '<td><div style="max-width:233px;">hashtagedit</div></td>'; //EDITOR
    $table .= '</tr>';

    $chainhashtagoutput = array();
    $stats = array(
        'hashtags_all' => 0,
        'hashtags_empty' => 0,
        'hashtags_delete' => 0,
        'hashtags_duplicate' => 0,
        'hashtags_empty_notvoid' => 0,
        'hashtags_void' => 0,
        'hashtags_voidcreaetor' => 0,
        'hashtags_void_cachevalid' => 0,
        'hashtags_valid_cachevoid' => 0,
    );

    $has_media = false;
    foreach($this->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhandletype' => 12273,
    ), array(), 0, 0, array('chainid' => 'ASC')) as $x){

        $is_duplicate = in_array($x['chainhashtagoutput'], $chainhashtagoutput);

        if(!$is_duplicate){
            array_push($chainhashtagoutput, $x['chainhashtagoutput']);
        } else {
            $stats['hashtags_duplicate']++;
        }

        $count++;
        $is = $this->Hashtags->read(array(
            'hashtagid' => $x['chainhashtagoutput'],
        ));
        $es = $this->Handles->read(array(
            'handleid' => $x['chainhandlecreator'],
        ));

        $stats['hashtags_all']++;
        if($x['chainvoid']>0){
            $stats['hashtags_void']++;
        } elseif(!count($es)){
            $stats['hashtags_voidcreaetor']++;
        }
        if($x['chainvoid']>0 && count($is)){
            $stats['hashtags_void_cachevalid']++;
        }
        if(!$x['chainvoid'] && !count($is)){
            $stats['hashtags_valid_cachevoid']++;
        }


        if(count($is)){

            $core_content = trim($is[0]['hashtagtext']);
            $hashtagtext = $is[0]['hashtagtext'].' ';

        } else {

            $hashtagtext = $x['chainvalue'];
            $core_content = '';

        }

        //Add Ideas:
        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___4486')) . ')' => null, //Ideas
            'chainhashtaginput' => $x['chainhashtagoutput'],
        ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC')) as $x2) {
            $hashtagtext .= "\n".$ideas[$x2['chainhandletype']]['m__cover'].$x2['hashtagterm'];
            $core_content .= "\n".$ideas[$x2['chainhandletype']]['m__cover'].$x2['hashtagterm'];
        }

        //Add Idea Type:
        if(count($is) && $is[0]['hashtagtype']>0 && $is[0]['hashtagtype']!=6677 && isset($handles___4737[$is[0]['hashtagtype']]['m__handle'])){
            $hashtagtext .= "\n@".$handles___4737[$is[0]['hashtagtype']]['m__handle'];
        }

        //Append authors:
        foreach($this->Chains->read(array(
            'chainhashtagoutput' => $x['chainhashtagoutput'],
            'chainhandleinput NOT IN ('.$x['chainhandlecreator'].',1,2,32337)' => null,
            'chainhandletype' => 4983, //Authors
        ), array('chainhandleinput')) as $x2){
            $core_content .= "\n@".$x2['handleterm'];
            if (strlen($x2['chainvalue'] > 0)) {
                $core_content .= " ".$x2['chainvalue'];
            }
        }

        //Append Media:
        $this_media = false;
        foreach($this->Chains->read(array(
            'chainhashtagoutput' => $x['chainhashtagoutput'],
            'chainhandleinput !=' => $x['chainhandlecreator'],
            'chainhandletype IN (' . join(',', array(4258,4260,4259)) . ')' => null,
        ), array('chainhandleinput')) as $x2){
            $core_content .= "\n@".$x2['handleterm'];
            $hashtagtext .= "\n@".$x2['handleterm'];
            $this_media = true;
        }

        if($this_media){
            $has_media = true;
        }


        //Fetch Mentions
        foreach($this->Chains->read(array(
            'chainhashtagoutput' => $x['chainhashtagoutput'],
            'chainhandletype IN (' . join(',', array(7545, 26599, 10573, 41949, 1695880, 27984, 43513, 43514, 26600)) . ')' => null,
        ), array('chainhandleinput')) as $x2){
            $core_content .= "\n@".$mentions[$x2['chainhandletype']]['m__cover'].$x2['handleterm'];
            $hashtagtext .= "\n".$mentions[$x2['chainhandletype']]['m__cover'].$x2['handleterm'];
        }

        if(!strlen(trim($core_content))){
            $stats['hashtags_empty']++;
            if(!$x['chainvoid']){
                $stats['hashtags_empty_notvoid']++;
            }
        }

        $delete = $x['chainvoid']>0 || !strlen(trim($core_content)) || $is_duplicate || (!$x['chainvoid'] && !count($es)) || (!$x['chainvoid'] && !count($is));
        if($delete){
            $stats['hashtags_delete']++;
        }

        $hashtag_cache = hashtag_cache(0, $hashtagtext);

        $table .= '<tr>';

        $table .= '<td>'.$x['chainid'].'<br />V'.$x['chainvoid'].'/'.$count.'/'.
            ( $delete ? '[DELETE]' : '' ).
            ( $x['chainvoid']>0 ? '[VOID]' : '' ).
            ( $this_media ? '[ISMEDIA]' : '' ).
            ( !strlen(trim($core_content)) ? '[EMPTY]' : '' ).
            ( $is_duplicate ? '[DUPLICATE]' : '' ).
            ( $is_duplicate ? '[DUPLICATE]' : '' ).
            ( !$x['chainvoid'] && !count($es) ? '[hashtags_voidcreaetor]' : '' ).
            ( !$x['chainvoid'] && !count($is) ? '[hashtags_valid_cachevoid]' : '' ).
            '</td>';

        $table .= '<td>T@'.$x['chainhandletype'].'<br />C@'.$x['chainhandlecreator'].'<br />##'.$x['chainhashtagoutput'].'</td>';

        $table .= '<td><div style="max-width:233px;">'.nl2br($hashtagtext).'</div></td>'; //INPUT
        $table .= '<td><div style="max-width:233px;">'.nl2br($hashtag_cache['hashtagchain']).'</div></td>'; //RAW
        $table .= '<td><div style="max-width:233px;">'.nl2br($hashtag_cache['hashtagtext']).'</div></td>'; //TEXT
        $table .= '<td><div style="max-width:233px;">'.($hashtag_cache['hashtagdiscover']).'</div></td>'; //DISCOVER
        $table .= '<td><div style="max-width:233px;">'.($hashtag_cache['hashtagedit']).'</div></td>'; //EDIT
        $table .= '</tr>';

        $this->Hashtags->update($x['chainhashtagoutput'], array(
            'hashtagupdated' => 1,
            'hashtagtext' => $hashtag_cache['hashtagtext'],
            'hashtagdiscover' => $hashtag_cache['hashtagdiscover'],
            'hashtagedit' => $hashtag_cache['hashtagedit'],
        ));
        $this->Hashtags->update($x['chainhashtagoutput'], array(
            'chainvalue' => $hashtag_cache['hashtagchain'],
            'chainvoid' => 0,
        ));

    }


    if($has_media){
        /*
        $this->Hashtags->update($x['chainhashtagoutput'], array(
            'hashtagtype' => $_POST['save_hashtagtype'],
        ), $handle_session['handleid']);
        */
    }

}

$table .= '</table>';


print_r($stats);
echo $table;

//$_GET['skip_config'] = true;
//view_json($this->Chains->flat_tree($focus_i));
