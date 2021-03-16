<?php

if(strlen($_GET['fa_regular'])){
    $detected = 0;
    $added = 0;

    $detected = substr_count($_GET['fa_regular'], "\n");

    echo 'REGULAR: Detected '.$detected.' icons and added '.$added.' of them.<br />';
}


if(strlen($_GET['fa_brand'])){

}



echo '<form method="get" action="">';

//Regular
echo '<p>Copy <a href="https://fontawesome.com/cheatsheet/pro/regular" target="_blank">Regular Font Awesome Cheatsheet</a> here:</p>';
echo '<textarea class="form-control text-edit border no-padding" name="fa_regular" data-lpignore="true" placeholder="Paste Cheatsheet..."></textarea><br />';


//Brands
echo '<p>Copy <a href="https://fontawesome.com/cheatsheet/pro/brands" target="_blank">Brand Font Awesome Cheatsheet</a> here:</p>';
echo '<textarea class="form-control text-edit border no-padding" name="fa_brand" data-lpignore="true" placeholder="Paste Cheatsheet..."></textarea><br />';

//Apply
echo '<div class="nav-controller"><div><button type="submit" class="controller-nav btn btn-lrg btn-6255 go-next top-margin" value="GO">UPDATE</button></div></div>';


echo '</form>';
