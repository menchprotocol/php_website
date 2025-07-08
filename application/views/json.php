<?php

$stats = array(
    'hashtags_all' => 0,
    'hashtags_empty' => 0,
    'hashtags_void' => 0,
    'handles_all' => 0,
    'handles_void' => 0,
);
$mentions = $this->config->item('handles___13550');
$ideas = $this->config->item('handles___4486');
$handles___4737 = $this->config->item('handles___4737'); //Hashtag Types


//Translator
echo '<table class="table table-sm table-striped stats-table mini-stats-table" border="1">';

foreach($this->Chains->read(array(
    'chainvoid >=' => 0, //Any Chain
    'chainhandletype' => 12273,
    //'chainid' => 130806,
), array('chainhashtagoutput'), 0) as $x){

    //HASHTAG
    $stats['hashtags_all']++;
    if($x['chainvoid']>0){
        $stats['hashtags_void']++;
    }

    $core_idea = trim($x['hashtagvalue']);

    //Fetch from Cache table:
    $current_value = '#'.$x['hashtaghashtag']."\n".$x['hashtagvalue'].' ';

    //Add Ideas:
    foreach ($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___4486')) . ')' => null, //Ideas
        'chainhashtaginput' => $x['hashtagid'],
    ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC')) as $x2) {
        $current_value .= "\n".$ideas[$x2['chainhandletype']]['m__cover'].$x2['hashtaghashtag'];
        $core_idea .= "\n".$ideas[$x2['chainhandletype']]['m__cover'].$x2['hashtaghashtag'];
    }

    //Replace mentions?
    foreach($this->Chains->read(array(
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandletype' => 31835, //Mentions
    ), array('chainhandleinput')) as $x2){
        //$current_value = str_replace('@'.$x2['handlehandle'].' ', '@'.$x2['handleid'].' ', $current_value);
    }

    //Add Idea Type:
    if($x['hashtagtype']>0 && $x['hashtagtype']!=6677 && isset($handles___4737[$x['hashtagtype']]['m__handle'])){
        $current_value .= "\n@".$handles___4737[$x['hashtagtype']]['m__handle'];
    }

    //Append authors:
    foreach($this->Chains->read(array(
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandleinput !=' => $x['chainhandlecreator'],
        'chainhandleinput !=' => 1,
        'chainhandleinput !=' => 32337, //No hashtag
        'chainhandletype' => 4983, //Authors
    ), array('chainhandleinput')) as $x2){
        $core_idea .= "\n@".$x2['handlehandle'];
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

            $current_value .= "\n@URL".random_string(8);
        } else {
            $current_value .= "\n@".$x2['handlehandle'].( strlen($x2['chainvalue']) > 0 ? " ".$x2['chainvalue'] : "" );
        }
    }

    //Transform URLs:
    foreach($this->Chains->read(array(
        'chainhashtagoutput' => $x['hashtagid'],
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
        */

        $current_value = str_replace(trim($x2['chainvalue']), '@URL'.$url_key, $current_value);
    }


    //Append Media:
    foreach($this->Chains->read(array(
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandleinput !=' => $x['chainhandlecreator'],
        'chainhandletype IN (' . join(',', array(4258,4260,4259)) . ')' => null,
    ), array('chainhandleinput')) as $x2){
        $core_idea .= "\n@".$x2['handlehandle'];
        $current_value .= "\n@".$x2['handlehandle'];
    }


    //Fetch Mentions
    foreach($this->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhashtagoutput' => $x['hashtagid'],
        'chainhandletype IN (' . join(',', array(7545, 26599, 10573, 41949, 1695880, 27984, 43513, 43514, 26600)) . ')' => null,
    ), array('chainhandleinput')) as $x2){
        $core_idea .= "\n@".$mentions[$x2['chainhandletype']]['m__cover'].$x2['handlehandle'];
        $current_value .= "\n".$mentions[$x2['chainhandletype']]['m__cover'].$x2['handlehandle'];
    }

    if(!strlen(trim($core_idea))){
        $stats['hashtags_empty']++;
    }


    echo '<tr style="border-bottom: 1px solid #000000;">';
    echo '<td>'.$x['chainid'].'</td>';
    echo '<td>VOID '.$x['chainvoid'].'</td>';
    echo '<td>T@'.$x['chainhandletype'].'</td>';
    echo '<td>C@'.$x['chainhandlecreator'].'</td>';
    echo '<td>'.( !strlen(trim($core_idea)) ? '[EMPTY]' : '' ).$x['chainvalue'].'</td>';
    echo '<td>'.nl2br(trim($current_value)).'</td>';
    echo '</tr>';

}

//Show stats:
echo '<tr>';
echo '<td>#'.$stats['hashtags_all'].'</td>';
echo '<td>VOID '.$stats['hashtags_void'].'</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '</tr>';



/*
foreach($this->Chains->read(array(
    'chainvoid >=' => 0, //Any Chain
    'chainhandletype' => 12274,
), array('chainhandleoutput')) as $x){

    $new_value = '';

    //HANDLE
    $stats['hashtags_all']++;
    if($x['chainvoid']>0){
        $stats['hashtags_void']++;
    }

    $new_value = '#';
    //Fetch from Cache table:
    $references = $this->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainhandletype IN (' . join(',', array(12273,12274)) . ')' => null, //HANDLE CHAINS
    ), array());

    echo '<tr>';
    echo '<td>'.$x['chainid'].'</td>';
    echo '<td>VOID '.$x['chainvoid'].'</td>';
    echo '<td>@'.$x['chainhandletype'].'</td>';
    echo '<td>'.$x['chainvalue'].'</td>';
    echo '<td>'.$new_value.'</td>';
    echo '</tr>';

}


echo '<tr>';
echo '<td>@'.$stats['handles_all'].'</td>';
echo '<td>VOID '.$stats['handles_void'].'</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '<td>&nbsp;</td>';
echo '</tr>';

*/

echo '</table>';


//$_GET['skip_config'] = true;
//view_json($this->Chains->flat_tree($focus_i));
