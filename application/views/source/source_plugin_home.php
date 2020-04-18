<?php

//List Plugins:
echo '<div class="list-group">';
foreach($this->config->item('en_all_6287') as $en_id => $m) {
    echo echo_basic_list_link($m, '/plugin/'.$en_id);
}
echo '</div>';