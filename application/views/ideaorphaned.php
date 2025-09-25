<?php

//List orphans:
echo '<div class="row justify-content">';
foreach($this->Posts->read(array(
    ' NOT EXISTS (SELECT 1 FROM ideachains WHERE postid=chainpostoutput AND chainvoid=0 AND chainusertype IN (' . join(',', $this->config->item('userids___4486')) . ')) ' => null,
), 0, 0) as $i) {
    echo post_view(7260, $i);

}
echo '</div>';