<?php

$update_time = date("Y-m-d H:i:s");
$memory_text = '';
$memory_text .= "<?php\n\n";
$memory_text .= '//UPDATED: '.$update_time."\n\n";
$memory_text .= 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'."\n\n";


if($memory_detected){

    //EASY:
    $n___7357 = $this->config->item('n___7357'); //LIMITED ACCESS
    $n___7359 = $this->config->item('n___7359');
    $n___33337 = $this->config->item('n___33337');

} else {

    //Define Manually (Might need to be updated)
    $n___7357 = array(41980, 6181, 4755);
    $n___7359 = array(6176);
    $n___33337 = array(41899, 4230, 40563, 32489, 41011, 32486);

}

//CONFIG VARS
foreach($this->X_model->fetch(array(
    'x__following' => 4527,
    'x__privacy IN (' . join(',', $n___7359) . ')' => null, //ACTIVE
    'x__type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
    'e__privacy IN (' . join(',', $n___7357) . ')' => null, //LIMITED ACCESS
), array('x__follower'), 0) as $en){

    //Now fetch all its followers:
    $down__e = $this->X_model->fetch(array(
        'x__following' => $en['x__follower'],
        'x__privacy IN (' . join(',', $n___7359) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
        'e__privacy IN (' . join(',', $n___7357) . ')' => null, //LIMITED ACCESS
    ), array('x__follower'), 0, 0, sort__e());


    //Generate raw IDs:
    $down_ids = array();
    $down_titles = array();
    foreach($down__e as $follower){
        array_push($down_ids , $follower['e__id']);
        array_push($down_titles , $follower['e__title']);
    }


    $prefix_common_words = prefix_common_words($down_titles); //Clean Titles
    $memory_text .= "\n".'//'.$en['e__title'].':'."\n";
    $memory_text .= '$config[\'n___'.$en['x__follower'].'\'] = array('.join(',',$down_ids).');'."\n";
    $memory_text .= '$config[\'e___'.$en['x__follower'].'\'] = array('.( strlen($prefix_common_words) ? ' //$prefix_common_words Removed = "'.trim($prefix_common_words).'"' : '' )."\n";
    foreach($down__e as $follower){

        //Fetch all followings for this follower:
        $down_up_ids = array(); //To be populated soon
        foreach($this->X_model->fetch(array(
            'x__follower' => $follower['e__id'],
            'x__privacy IN (' . join(',', $n___7359) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
            'e__privacy IN (' . join(',', $n___7357) . ')' => null, //LIMITED ACCESS
        ), array('x__following'), 0) as $cp_en){
            array_push($down_up_ids, intval($cp_en['e__id']));
        }

        $memory_text .= '     '.$follower['e__id'].' => array('."\n";
        $memory_text .= '        \'m__handle\' => \''.$follower['e__handle'].'\','."\n";
        $memory_text .= '        \'m__title\' => \''.(str_replace('\'','\\\'',str_replace($prefix_common_words,'',$follower['e__title']) )).'\','."\n";
        $memory_text .= '        \'m__message\' => \''.(str_replace('\'','\\\'',$follower['x__message'])).'\','."\n";
        $memory_text .= '        \'m__cover\' => \''.str_replace('\'','\\\'',view_cover($follower['e__cover'])).'\','."\n";
        $memory_text .= '        \'m__following\' => array('.join(',',$down_up_ids).'),'."\n";
        $memory_text .= '     ),'."\n";

    }
    $memory_text .= ');'."\n";

}

//Append all App Handlers for quick checking:
$memory_text .= "\n"."\n";
foreach($this->X_model->fetch(array(
    'x__following' => 42043, //Handle Cache
    'x__privacy IN (' . join(',', $n___7359) . ')' => null, //ACTIVE
    'x__type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
), array('x__follower'), 0) as $handle){

    $memory_text .= '$config[\'handle___'.$handle['e__id'].'\'] = array('."\n";
    foreach($this->X_model->fetch(array(
        'x__following' => $handle['e__id'],
        'x__privacy IN (' . join(',', $n___7359) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $n___33337) . ')' => null, //SOURCE LINKS
        'e__privacy IN (' . join(',', $n___7357) . ')' => null, //LIMITED ACCESS
    ), array('x__follower'), 0) as $app){
        $memory_text .= '     \''.$app['e__handle'].'\' => '.$app['e__id'].','."\n";
    }
    $memory_text .= ');'."\n";
}

$memory_text .= "\n"."\n";
$memory_text .= '$config[\'cache_time\'] = \''.time().'\';'."\n";
$memory_text .= '$config[\'cache_buster\'] = \''.md5($memory_text).'\';'."\n";

//Now Save File:
$file_location = "application/config/mench_memory.php";
$myfile = fopen($file_location, "w+") or die("Unable to open file: ".$file_location);
fwrite($myfile, $memory_text);
fclose($myfile);


echo '<div class="margin-top-down"><div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully updated memory & removed '.reset_cache($x__creator).' cached pages.</div></div>';


//Show:
echo '<textarea class="mono-space" style="background-color: #FFFFFF; color:#000000 !important; padding:20px; font-size:0.8em; height:377px; width: 100%; border-radius: 0px;">'.$memory_text.'</textarea>';