<?php

//List Crons:
echo '<div class="list-group">';
foreach($this->config->item('en_all_7274') as $en_id => $m) {
    echo echo_basic_list_link($m, '/plugin/'.$en_id);
}
echo '</div>';