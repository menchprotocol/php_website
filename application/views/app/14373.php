<?php

//Offers a permanent /-14373 Website URL for Terms which redirects to the Website Term...

$website_terms = $this->E_model->scissor_i(website_setting(0), 14373); //Website Terms
if(!count($website_terms)){
    //Default terms:
    $website_terms = $this->E_model->scissor_i(6404, 14373); //Default Terms
}

foreach($website_terms as $i_item) {
    js_redirect('/:'.$i_item['i__id'], 13);
    break;
}


