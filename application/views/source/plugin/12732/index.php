<?php

//SOURCE/IDEA SYNC STATUSES (Hope to get zero)

echo 'IDEA: '.nl2br(print_r($this->IDEA_model->in_match_ln_status($session_en['en_id']), true)).'<hr />';
echo 'SOURCE: '.nl2br(print_r($this->SOURCE_model->en_match_ln_status($session_en['en_id']), true)).'<hr />';
