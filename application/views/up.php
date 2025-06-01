<?php

echo '<h1>'.$focus_e['sourcevalue'].'</h1>';

echo '<div class="row justify-content">';
foreach ($this->Sources->read(array(
    'sourceid IN (' . join(',', source_up($focus_e['sourceid'])) . ')' => null,
)) as $e) {
    echo source_view(6255, $e);
}
echo '</div>';