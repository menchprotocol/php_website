<?php

//Update Emojis
$emoji_list = file_get_contents('https://unicode.org/emoji/charts/full-emoji-list.html');
$emojis = explode('<td class=\'chars\'>',$emoji_list);

foreach($emojis as $count => $emoji_html){
    $emoji_icon = one_two_explode('','</td>',$emoji_html);
    $emoji_name = ucwords(one_two_explode('<td class=\'name\'>','</td>',$emoji_html));
    echo $count.') '.$emoji_icon.' '.$emoji_name.'<br />';
}




