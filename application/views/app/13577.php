<?php

$processed = false;

if(isset($_GET['fa_regular']) && strlen($_GET['fa_regular'])){

    $processed = true;
    $detected = 0;
    $added = 0;

    foreach(explode("\n", $_GET['fa_regular']) as $line){
        $detected++;
        echo $line.'<hr />';
    }

    echo 'REGULAR: Detected '.$detected.' icons and added '.$added.' of them.<br />';
}

if(isset($_GET['fa_brand']) && strlen($_GET['fa_brand'])) {

    $processed = true;
    $detected = 0;
    $added = 0;
}



if(!$processed){

    //SHow Form:
    echo '<form method="post" action="">';

    //Regular
    echo '<p>Copy <a href="https://fontawesome.com/cheatsheet/pro/regular" target="_blank">Regular Font Awesome Cheatsheet</a> here:</p>';
    echo '<textarea class="form-control text-edit border no-padding" name="fa_regular" data-lpignore="true" placeholder="Paste Cheatsheet..."></textarea><br />';


    //Brands
    echo '<p>Copy <a href="https://fontawesome.com/cheatsheet/pro/brands" target="_blank">Brand Font Awesome Cheatsheet</a> here:</p>';
    echo '<textarea class="form-control text-edit border no-padding" name="fa_brand" data-lpignore="true" placeholder="Paste Cheatsheet..."></textarea><br />';

    //Apply
    echo '<button type="submit" class="controller-nav btn btn-lrg btn-6255 go-next top-margin" value="GO">UPDATE</button>';


    echo '</form>';

}




