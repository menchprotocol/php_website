<?php

//Call the update function and passon possible values:
print_r(update_search(( $focus_post ? 12273 : ( $focus_e ? 12274 : null ) ), ( $focus_post ? $focus_post['postid'] : ( $focus_e ? $focus_e['userid'] : null ) )));