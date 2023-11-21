<?php

if(isset($_GET['url'])){
    js_php_redirect($_GET['url'], 13);
} else {
    echo 'Missing URL input';
}
