<?php

view_json(array(
    'idea_settings' => idea_settings($focus_i['ideahashtag'], false),
    //'history' => $this->Links->history($focus_i, $focus_e['playerid']),
));
