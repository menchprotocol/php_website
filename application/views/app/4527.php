<?php

$update_time = date("Y-m-d H:i:s");
$memory_text = '';
$memory_text .= "<?php\n\n";
$memory_text .= '//UPDATED: '.$update_time."\n\n";
$memory_text .= 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'."\n\n";


if($memory_detected){

    //EASY:
    $n___7357 = $this->config->item('n___7357');
    $n___7359 = $this->config->item('n___7359');
    $n___4592 = $this->config->item('n___4592');
    $e___28649 = $this->config->item('e___28649');

} else {

    //Define Manually (Might need to be updated)
    $n___7357 = array(6181,28951,29448,29459);
    $n___7359 = array(6176);
    $n___4592 = array(4259,4257,4261,4260,4319,7657,4230,4255,4318,4256,4258,12827,26090,26091,26092,26123,26155);

}

//CONFIG VARS
foreach($this->X_model->fetch(array(
    'x__up' => 4527,
    'x__status IN (' . join(',', $n___7359) . ')' => null, //ACTIVE
    'x__type IN (' . join(',', $n___4592) . ')' => null, //SOURCE LINKS
    'e__type IN (' . join(',', $n___7357) . ')' => null, //ACTIVE
), array('x__down'), 0) as $en){

    //Now fetch all its children:
    $child__e = $this->X_model->fetch(array(
        'x__up' => $en['x__down'],
        'x__status IN (' . join(',', $n___7359) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $n___4592) . ')' => null, //SOURCE LINKS
        'e__type IN (' . join(',', $n___7357) . ')' => null, //ACTIVE
    ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC'));




    //Generate raw IDs:
    $child_ids = array();
    foreach($child__e as $child){
        array_push($child_ids , $child['e__id']);
    }

    $memory_text .= "\n".'//'.$en['e__title'].':'."\n";
    $memory_text .= '$config[\'n___'.$en['x__down'].'\'] = array('.join(',',$child_ids).');'."\n";
    $memory_text .= '$config[\'e___'.$en['x__down'].'\'] = array('."\n";
    foreach($child__e as $child){

        //Fetch all parents for this child:
        $child_parent_ids = array(); //To be populated soon
        foreach($this->X_model->fetch(array(
            'x__down' => $child['e__id'],
            'x__status IN (' . join(',', $n___7359) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $n___4592) . ')' => null, //SOURCE LINKS
            'e__type IN (' . join(',', $n___7357) . ')' => null, //ACTIVE
        ), array('x__up'), 0) as $cp_en){
            array_push($child_parent_ids, intval($cp_en['e__id']));
        }

        //Manual term removal?
        if($memory_detected && in_array($en['x__down'], $this->config->item('n___28649'))) {
            $child['e__title'] = trim(str_replace($e___28649[$en['x__down']]['m__message'], '', $child['e__title']));
        }

        $memory_text .= '     '.$child['e__id'].' => array('."\n";
        $memory_text .= '        \'m__title\' => \''.(str_replace('\'','\\\'',$child['e__title'])).'\','."\n";
        $memory_text .= '        \'m__message\' => \''.(str_replace('\'','\\\'',$child['x__message'])).'\','."\n";
        $memory_text .= '        \'m__cover\' => \''.view_cover(12274, $child['e__cover']).'\','."\n";
        $memory_text .= '        \'m__profile\' => array('.join(',',$child_parent_ids).'),'."\n";
        $memory_text .= '     ),'."\n";

    }
    $memory_text .= ');'."\n";


    $memory_text .= '$config[\'x___'.$en['x__down'].'\'] = array('."\n";
    if($memory_detected){
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__up' => $en['x__down'],
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC', 'i__title' => 'ASC')) as $link_i){
            $memory_text .= '     '.$link_i['i__id'].' => array('."\n";
            $memory_text .= '        \'m__title\' => \''.(str_replace('\'','\\\'',$link_i['i__title'])).'\','."\n";
            $memory_text .= '        \'m__message\' => \''.(str_replace('\'','\\\'',$link_i['x__message'])).'\','."\n";
            $memory_text .= '        \'m__cover\' => \''.view_cover(12274, $link_i['i__cover']).'\','."\n";
            $memory_text .= '     ),'."\n";
        }
    }
    $memory_text .= ');'."\n";

}

//Now Save File:
$file_location = "application/config/mench_memory.php";
$myfile = fopen($file_location, "w+") or die("Unable to open file: ".$file_location);
fwrite($myfile, $memory_text);
fclose($myfile);


echo '<div class="margin-top-down"><div class="msg alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully updated memory & removed '.reset_cache($x__source).' cached pages.</div></div>';


//Show:
echo '<textarea class="mono-space" style="background-color:#FFFFFF; color:#222222 !important; padding:20px; font-size:0.8em; height:377px; width: 100%; border-radius: 0;">'.$memory_text.'</textarea>';