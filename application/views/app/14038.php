<?php

//Update Emojis
$emoji_list = file_get_contents('https://unicode.org/emoji/charts/full-emoji-list.html');
$emojis = explode('<td class=\'chars\'>',$emoji_list);
$added = 0;
$there = 0;
$error = 0;
$list = '';

foreach($emojis as $count => $emoji_html){
    if(!$count){
        continue;
    }
    $emoji_name = ucwords(one_two_explode('<td class=\'name\'>','</td>',$emoji_html));
    $emoji_icon = one_two_explode('','</td>',$emoji_html);
    foreach(array('Flag: ','"',':',',') as $remove){
        $emoji_name = str_replace($remove, '', $emoji_name);
    }

    $list .= $count.') ['.$emoji_icon.'] '.$emoji_name;

    $emoji_exists = $this->X_model->fetch(array(
        'e__cover' => $emoji_icon,
        'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__up' => 14038,
    ), array('x__down'), 0);

    if(!count($emoji_exists)){

        //Add Emoji:
        $new_emoji = $this->E_model->create(array(
            'e__title' => $emoji_name,
            'e__cover' => $emoji_icon,
            'e__access' => 28951,
        ), $member_e['e__id']);

        if(count($new_emoji)){

            //Add Link:
            $this->X_model->create(array(
                'x__up' => 14038,
                'x__down' => $new_emoji['e__id'],
                'x__type' => 4230,
            ));

            $added++;
            $list .= ' [ADDED]';

        } else {
            $error++;
            $list .= ' [ERROR]';
        }

    } else {
        $there++;
        $list .= ' [THRER]';
    }


    $list .= '<br />';

}

echo 'Error: '.$error.'<br />';
echo 'New: '.$added.'<br />';
echo 'There: '.$there.'<br />';
echo 'Total: '.number_format(($there + $added), 0).'<br /><hr />';
echo $list;

