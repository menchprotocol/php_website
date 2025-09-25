<?php

//Call the update function and passon possible values:
print_r(update_algolia(( $focus_i ? 12273 : ( $focus_e ? 12274 : null ) ), ( $focus_i ? $focus_i['postid'] : ( $focus_e ? $focus_e['userid'] : null ) )));