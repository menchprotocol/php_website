<?php

if(isset($_POST['import_sources'])){
    echo strlen($_POST['import_sources']);
    echo '<hr />';
}

echo '<form method="POST" action="">';
echo '<textarea class="border padded" placeholder="" id="import_sources"></textarea>';
echo '<button type="submit" class="btn btn-lrg top-margin">UPDATE</button>';
echo '</form>';