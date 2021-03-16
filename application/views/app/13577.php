<?php

$processed = false;

if(isset($_POST['fa_regular']) && strlen($_POST['fa_regular'])){

    $processed = true;
    $detected = 0;
    $added = 0;

    foreach(explode("\n", $_POST['fa_regular']) as $line){
        $words = explode('	', $line);
        if(count($words)==3 ){

            $detected++;
            $icon_title = ucwords(str_replace('-',' ',$words[1]));
            $icon_code = 'far fa-'.$words[1];

            //Check if exists:
            if(!count($this->X_model->fetch(array( //SOURCE PROFILE
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => 14986, //REGULAR
                'e__cover' => $icon_code,
            ), array('x__down')))){
                $added++;
                echo '<span class="icon-block" title="'.$icon_title.' ['.$words[2].']"><i class="'.$icon_code.'"></i></span>';
                //ADD NEW:
                /*
                $this->E_model->create(array(
                    'e__title' => $icon_title,
                    'e__cover' => $icon_code,
                    'e__type' => 6181,
                ), true, ( $member_e ? $member_e['e__id'] : 7274 ));
                */
            }
        }
    }

    echo '<br /><br />'.$added.'/'.$detected.' REGULAR icons added.<br />';

}

if(isset($_POST['fa_brand']) && strlen($_POST['fa_brand'])) {

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




