<?php

foreach($this->config->item('en_all_12687') as $en_id => $m){
    echo '<p style="border-bottom:1px solid #999999; padding: 5px;"><b class="montserrat">'.$m['m_name'].'</b> '.echo_platform_message($en_id).'</p>';
}

?>