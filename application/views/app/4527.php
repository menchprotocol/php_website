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

} else {

    //Define Manually (Might need to be updated)
    $n___7357 = array(6181);
    $n___7359 = array(6176);
    $n___4592 = array(4259,4257,4261,4260,4319,7657,4230,4255,4318,4256,4258,12827);

}

//CONFIG VARS
foreach($this->X_model->fetch(array(
    'x__up' => 4527,
    'x__status IN (' . join(',', $n___7359) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $n___4592) . ')' => null, //SOURCE LINKS
    'e__type IN (' . join(',', $n___7357) . ')' => null, //PUBLIC
), array('x__down'), 0) as $en){

    //Now fetch all its children:
    $children = $this->X_model->fetch(array(
        'x__up' => $en['x__down'],
        'x__status IN (' . join(',', $n___7359) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $n___4592) . ')' => null, //SOURCE LINKS
        'e__type IN (' . join(',', $n___7357) . ')' => null, //PUBLIC
    ), array('x__down'), 0, 0, array('x__spectrum' => 'ASC', 'e__title' => 'ASC'));


    //Find common base, if allowed:
    $common_prefix = i_calc_common_prefix($children, 'e__title');

    //Generate raw IDs:
    $child_ids = array();
    foreach($children as $child){
        array_push($child_ids , $child['e__id']);
    }

    $memory_text .= "\n".'//'.$en['e__title'].':'."\n";
    $memory_text .= '$config[\'n___'.$en['x__down'].'\'] = array('.join(',',$child_ids).');'."\n";
    $memory_text .= '$config[\'e___'.$en['x__down'].'\'] = array('."\n";
    foreach($children as $child){

        //Do we have an omit command?
        if(strlen($common_prefix) > 0){
            $child['e__title'] = trim(substr($child['e__title'], strlen($common_prefix)));
        }

        //Fetch all parents for this child:
        $child_parent_ids = array(); //To be populated soon
        $child_parents = $this->X_model->fetch(array(
            'x__down' => $child['e__id'],
            'x__status IN (' . join(',', $n___7359) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $n___4592) . ')' => null, //SOURCE LINKS
            'e__type IN (' . join(',', $n___7357) . ')' => null, //PUBLIC
        ), array('x__up'), 0);
        foreach($child_parents as $cp_en){
            array_push($child_parent_ids, intval($cp_en['e__id']));
        }

        $memory_text .= '     '.$child['e__id'].' => array('."\n";
        $memory_text .= '        \'m__title\' => \''.(str_replace('\'','\\\'',$child['e__title'])).'\','."\n";
        $memory_text .= '        \'m__message\' => \''.(str_replace('\'','\\\'',$child['x__message'])).'\','."\n";
        $memory_text .= '        \'m__cover\' => \''.($child['e__cover']).'\','."\n";
        $memory_text .= '        \'m__profile\' => array('.join(',',$child_parent_ids).'),'."\n";
        $memory_text .= '     ),'."\n";

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
echo '<textarea class="mono-space" style="background-color:#FFFFFF; color:#222222 !important; padding:20px; font-size:0.8em; height:377px; width: 100%; border-radius: 10px;">'.$memory_text.'</textarea>';