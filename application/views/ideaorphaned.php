<?php

//List orphans:
echo '<div class="row justify-content">';
foreach($this->Hashtags->read(array(
    ' NOT EXISTS (SELECT 1 FROM hashtagchain WHERE hashtagid=chainhashtagoutput AND chainvoid=0 AND chainhandletype IN (' . join(',', $this->config->item('handleids___4486')) . ')) ' => null,
), 0, 0) as $i) {
    echo hashtag_view(7260, $i);

}
echo '</div>';