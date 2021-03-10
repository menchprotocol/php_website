<?php

//Update Emojis
$emoji_list = file_get_contents('https://unicode.org/emoji/charts/full-emoji-list.html');

echo substr_count($emoji_list, '<td class=\'chars\'>');


