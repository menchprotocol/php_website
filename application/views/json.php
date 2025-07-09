<?php

boost_power();
$mentions = $this->config->item('handles___13550');
$ideas = $this->config->item('handles___4486');
$handles___4737 = $this->config->item('handles___4737'); //Hashtag Types
$count = 0;


//Translator
$table = '<table class="table table-sm table-striped stats-table mini-stats-table" border="1">';

if($focus_i['hashtaghashtag']!='Discotique2025'){

    //HANDLE
    $chainhandleoutput = array();
    $stats = array(
        'handles_all' => 0,
        'handles_empty' => 0,
        'handles_delete' => 0,
        'handles_duplicate' => 0,
        'handles_empty_notvoid' => 0,
        'handles_void' => 0,
        'handles_voidcreaetor' => 0,
        'handles_void_cachevalid' => 0,
        'handles_valid_cachevoid' => 0,
    );

    foreach($this->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhandletype' => 12274,
    ), array(), 10, 0, array('chainid' => 'ASC')) as $x){

        $is_duplicate = in_array($x['chainhandleoutput'], $chainhandleoutput);
        if(!$is_duplicate){
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
        if($x['chainvoid']>0){
            $stats['handles_void']++;
        } elseif(!count($es)){
            $stats['handles_voidcreaetor']++;
        }
        if($x['chainvoid']>0 && count($es_cache)){
            $stats['handles_void_cachevalid']++;
        }
        if(!$x['chainvoid'] && !count($es_cache)){
            $stats['handles_valid_cachevoid']++;
        }


        //Fetch from Cache table:
        if(count($es_cache)){
            $current_value = '@'.$es_cache[0]['handlehandle']."\n".$es_cache[0]['handlevalue']."\n".$es_cache[0]['handlecover'];
        } else {
            $current_value = '@?'.$es_cache[0]['handlehandle']."\n".$es_cache[0]['handlevalue']."\n".$es_cache[0]['handlecover'];
        }

        if(!strlen(trim($es_cache[0]['handlevalue']))){
            $stats['handles_empty']++;
            if(!$x['chainvoid']){
                $stats['handles_empty_notvoid']++;
            }
        }

        $delete = $x['chainvoid']>0 || $is_duplicate || (!$x['chainvoid'] && !count($es)) || (!$x['chainvoid'] && !count($es_cache));
        if($delete){
            $stats['handles_delete']++;
        }

        $table .= '<tr>';
        $table .= '<td>'.$x['chainid'].'<br />V'.$x['chainvoid'].'/'.$count.'/'.
            ( $delete ? '[DELETE]' : '' ).
            ( $x['chainvoid']>0 ? '[VOID]' : '' ).
            ( $is_duplicate ? '[DUPLICATE]' : '' ).
            ( !$x['chainvoid'] && !count($es) ? '[handles_voidcreaetor]' : '' ).
            ( !$x['chainvoid'] && !count($es_cache) ? '[handles_valid_cachevoid]' : '' ).
            '</td>';
        $table .= '<td>T@'.$x['chainhandletype'].'<br />C@'.$x['chainhandlecreator'].'<br />@'.$x['chainhandleoutput'].'</td>';
        $table .= '<td><div>'.nl2br(trim(htmlentities($current_value))).'</div></td>';
        //$table .= '<td><div>'.nl2br(trim(htmlentities($current_value))).'</div></td>';
        $table .= '</tr>';
    }

} else {

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

    //HASHTAG
    foreach($this->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhandletype' => 12273,
    ), array(), 10, 0, array('chainid' => 'ASC')) as $x){

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

            $core_content = trim($is[0]['hashtagvalue']);
            //Fetch from Cache table:
            $current_value = '#'.$is[0]['hashtaghashtag']."\n".$is[0]['hashtagvalue'].' ';

        } else {

            $current_value = $x['chainvalue'].' ';
            $core_content = '';
            foreach($this->Chains->read(array(
                'chainhashtagoutput' => $x['chainhashtagoutput'],
                'chainhandleinput' => 32337,
                'LENGTH(chainvalue)>0' => null,
            ), array('chainhandleinput')) as $x2) {
                $current_value = '##'.$x2['chainvalue']."\n".$x['chainvalue'].' ';
            }

        }



        //Add Ideas:
        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___4486')) . ')' => null, //Ideas
            'chainhashtaginput' => $x['chainhashtagoutput'],
        ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC')) as $x2) {
            $current_value .= "\n".$ideas[$x2['chainhandletype']]['m__cover'].$x2['hashtaghashtag'];
            $core_content .= "\n".$ideas[$x2['chainhandletype']]['m__cover'].$x2['hashtaghashtag'];
        }


        //Add Idea Type:
        if(count($is) && $is[0]['hashtagtype']>0 && $is[0]['hashtagtype']!=6677 && isset($handles___4737[$is[0]['hashtagtype']]['m__handle'])){
            $current_value .= "\n@".$handles___4737[$is[0]['hashtagtype']]['m__handle'];
        }


        //Replace mentions?
        foreach($this->Chains->read(array(
            'chainhashtagoutput' => $x['chainhashtagoutput'],
            'chainhandletype' => 31835, //Mentions
        ), array('chainhandleinput')) as $x2){
            //$current_value = str_replace('@'.$x2['handlehandle'].' ', '@'.$x2['handleid'].' ', $current_value);
        }

        //Append authors:
        foreach($this->Chains->read(array(
            'chainhashtagoutput' => $x['chainhashtagoutput'],
            'chainhandleinput NOT IN ('.$x['chainhandlecreator'].',1,2,32337)' => null,
            'chainhandletype' => 4983, //Authors
        ), array('chainhandleinput')) as $x2){

            $core_content .= "\n@".$x2['handlehandle'];

            if (filter_var($x2['chainvalue'], FILTER_VALIDATE_URL)) {
                //Create URL:
                $current_value .= "\n@".$x2['handlehandle'];

                //Create new URL:
                /*
                $added_e = $this->Handles->create(array(
                    'handlehandle' => 'URL'.$url_key,
                    'handlevalue' => 'URL '.$url_key,
                    'handlecover' => 'fas fa-browser',
                ), $x['chainhandlecreator']);
                $current_value .= "\n@".$added_e['handle_create']['handlehandle'];
                */

                $current_value .= "\n@NEWURL".random_string(8);
            } else {
                $current_value .= "\n@".$x2['handlehandle'].( strlen($x2['chainvalue']) > 0 ? " ".$x2['chainvalue'] : "" );
            }
        }

        //Transform URLs:
        /*
        foreach($this->Chains->read(array(
            'chainhashtagoutput' => $x['chainhashtagoutput'],
            'chainhandleinput !=' => $x['chainhandlecreator'],
            'chainhandletype' => 4256,
        ), array('chainhandleinput')) as $x2){

            $url_key = random_string(8);

            //Create new URL:
            /*
            $added_e = $this->Handles->create(array(
                'handlehandle' => 'URL'.$url_key,
                'handlevalue' => 'URL '.$url_key,
                'handlecover' => 'fas fa-browser',
            ), $x['chainhandlecreator']);
            $current_value .= "\n@".$added_e['handle_create']['handlehandle'];
            *//*

        $current_value = str_replace(trim($x2['chainvalue']), '@URL'.$url_key, $current_value);
    }
    */

        //Append Media:
        foreach($this->Chains->read(array(
            'chainhashtagoutput' => $x['chainhashtagoutput'],
            'chainhandleinput !=' => $x['chainhandlecreator'],
            'chainhandletype IN (' . join(',', array(4258,4260,4259)) . ')' => null,
        ), array('chainhandleinput')) as $x2){
            $core_content .= "\n@".$x2['handlehandle'];
            $current_value .= "\n@".$x2['handlehandle'];
        }


        //Fetch Mentions
        foreach($this->Chains->read(array(
            'chainhashtagoutput' => $x['chainhashtagoutput'],
            'chainhandletype IN (' . join(',', array(7545, 26599, 10573, 41949, 1695880, 27984, 43513, 43514, 26600)) . ')' => null,
        ), array('chainhandleinput')) as $x2){
            $core_content .= "\n@".$mentions[$x2['chainhandletype']]['m__cover'].$x2['handlehandle'];
            $current_value .= "\n".$mentions[$x2['chainhandletype']]['m__cover'].$x2['handlehandle'];
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

        $table .= '<tr>';
        $table .= '<td>'.$x['chainid'].'<br />V'.$x['chainvoid'].'/'.$count.'/'.
            ( $delete ? '[DELETE]' : '' ).
            ( $x['chainvoid']>0 ? '[VOID]' : '' ).
            ( !strlen(trim($core_content)) ? '[EMPTY]' : '' ).
            ( $is_duplicate ? '[DUPLICATE]' : '' ).
            ( !$x['chainvoid'] && !count($es) ? '[hashtags_voidcreaetor]' : '' ).
            ( !$x['chainvoid'] && !count($is) ? '[hashtags_valid_cachevoid]' : '' ).
            '</td>';
        $table .= '<td>T@'.$x['chainhandletype'].'<br />C@'.$x['chainhandlecreator'].'<br />##'.$x['chainhashtagoutput'].'</td>';
        $table .= '<td><div>'.nl2br(trim(htmlentities($current_value))).'</div></td>';
        //$table .= '<td><div>'.nl2br(trim(htmlentities($current_value))).'</div></td>';
        $table .= '</tr>';
    }

}

$table .= '</table>';


print_r($stats);
echo $table;

//$_GET['skip_config'] = true;
//view_json($this->Chains->flat_tree($focus_i));
