<?php

//Update Emojis
$emoji_list = file_get_contents('https://unicode.org/emoji/charts/full-emoji-list.html');
$emojis = explode('<td class=\'chars\'>',$emoji_list);

foreach($emojis as $count => $emoji_html){
    if(!$count){
        continue;
    }
    $emoji_icon = one_two_explode('','</td>',$emoji_html);
    $emoji_name = ucwords(one_two_explode('<td class=\'name\'>','</td>',$emoji_html));
    foreach(array('Flag: ','"',':',',') as $remove){
        $emoji_name = str_replace($remove, '', $emoji_name);
    }

    //See if we have it:
    $es = $this->E_model->fetch(array(
        'LOWER(e__title)' => strtolower($emoji_name),
        'e__cover' => $emoji_icon,
        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
    ));
    if(!count($es)){
        $es = $this->E_model->fetch(array(
            'e__cover' => $emoji_icon,
            'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
    }


    echo $count.') ['.$emoji_icon.'] '.$emoji_name.( count($es) ? '[FOUND '.count($es).': @'.$es[0]['e__id'].']' : '' ).'<br />';


}




