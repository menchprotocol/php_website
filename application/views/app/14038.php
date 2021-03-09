<?php

//Update Emojis
$emoji_list = file_get_contents($fav_icon);
echo htmlentities($emoji_list);

