<?php

$update_time = date("Y-m-d H:i:s");
$memory_text = '';
$memory_text .= "<?php\n\n";
$memory_text .= '//UPDATED: '.$update_time."\n\n";
$memory_text .= 'defined(\'BASEPATH\') OR exit(\'No direct script access allowed\');'."\n\n";

//CONFIG VARS
foreach($this->X_model->fetch(array(
    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__up' => 4527,
), array('x__down'), 0) as $en){

    //Now fetch all its children:
    $children = $this->X_model->fetch(array(
        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        'x__up' => $en['x__down'],
        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    ), array('x__down'), 0, 0, array('x__sort' => 'ASC', 'e__title' => 'ASC'));


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
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
            'x__down' => $child['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        ), array('x__up'), 0);
        foreach($child_parents as $cp_en){
            array_push($child_parent_ids, intval($cp_en['e__id']));
        }

        $memory_text .= '     '.$child['e__id'].' => array('."\n";
        $memory_text .= '        \'m_title\' => \''.(str_replace('\'','\\\'',$child['e__title'])).'\','."\n";
        $memory_text .= '        \'m_message\' => \''.(str_replace('\'','\\\'',$child['x__message'])).'\','."\n";
        $memory_text .= '        \'m_icon\' => \''.($child['e__icon']).'\','."\n";
        $memory_text .= '        \'m_profile\' => array('.join(',',$child_parent_ids).'),'."\n";
        $memory_text .= '     ),'."\n";

    }
    $memory_text .= ');'."\n";
}

//Now Save File:
$myfile = fopen("application/config/mench_memory.php", "w+") or die("Unable to open file!");
fwrite($myfile, $memory_text);
fclose($myfile);

echo '<div class="margin-top-down"><div class="msg alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully updated file, see details below.</div></div>';

//Show:
echo '<textarea style="background-color:#FFFFFF; color:#222222 !important; padding:20px; font-family: monospace; font-size:0.8em; height:377px; width: 100%; border-radius: 10px;">'.$memory_text.'</textarea>';