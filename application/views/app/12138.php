<?php

//Make sure valid category:
$e__id = ( isset($_GET['e__id']) ? intval($_GET['e__id']) : 0 );

if(!in_array($e__id, $this->config->item('n___12138'))){
    js_redirect('/', 13);
}

echo view_i_featured($e__id);
