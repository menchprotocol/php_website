<?php

die('Must update code to consider multiple types when updating...');

$x__source = ( $member_e ? $member_e['e__id'] : 7274 );



//fix:
$postfix = array(
    'fal' => ' Thin',
    'fas' => ' Bold',
    'fad' => ' Duoto',
);
$count = 0;
$animal = 0;
foreach($this->X_model->fetch(array(
    'x__up' => 14986,
    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
    'e__type IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //ACTIVE
), array('x__down'), 0, 0) as $e){

    $count++;
    foreach(array(
                'fal' => 20425,
                'fas' => 20426,
                'fad' => 20427,
            ) as $prefix => $type_id){

        //Create Icon:
        $added_e = $this->E_model->verify_create($e['e__title'].$postfix[$prefix], $x__source, str_replace('far ',$prefix.' ',$e['e__cover']));
        if($added_e['status']){

            //Link to proper folder:
            $this->X_model->create(array(
                'x__up' => $type_id,
                'x__type' => e_x__type(),
                'x__source' => $x__source,
                'x__down' => $added_e['new_e']['e__id'],
            ));

            if(in_array($e['e__id'], $this->config->item('n___12279'))){
                $this->X_model->create(array(
                    'x__up' => 12279, //Animals
                    'x__type' => e_x__type(),
                    'x__source' => $x__source,
                    'x__down' => $added_e['new_e']['e__id'],
                ));
                $animal++;
            }

            $added++;
            //echo '<span class="icon-block" title="'.$icon_title.'"><i class="'.$icon_code.'"></i></span>';

        }

    }
}

echo $count.' Icons scanned, '.$added.' added, '.$animal.' animals.';


$processed = false;
foreach(array(
    'far' => 14986,
    'fab' => 14988,
) as $key => $type_id){
    if(isset($_POST[$key]) && strlen($_POST[$key])){

        $processed = true;
        $detected = 0;
        $added = 0;

        foreach(explode("\n", $_POST[$key]) as $line){
            $words = explode('	', $line);
            if(count($words)==3 && strlen(trim($words[2]))==4){

                $detected++;
                $icon_title = ucwords(str_replace('-',' ',$words[1]));
                $icon_code = $key.' fa-'.$words[1];

                //Check if exists:
                if(!count($this->X_model->fetch(array( //SOURCE PROFILE
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__up' => $type_id,
                    'e__cover' => $icon_code,
                ), array('x__down')))){

                    //ADD NEW Source:
                    $added_e = $this->E_model->verify_create($icon_title, $x__source, $icon_code);
                    if($added_e['status']){

                        //Link to proper folder:
                        $this->X_model->create(array(
                            'x__up' => $type_id, //MEMBERS
                            'x__type' => e_x__type(),
                            'x__source' => $x__source,
                            'x__down' => $added_e['new_e']['e__id'],
                        ));

                        $added++;
                        echo '<span class="icon-block" title="'.$icon_title.'"><i class="'.$icon_code.'"></i></span>';

                    }
                }
            }
        }

        echo '<br /><br />'.$added.'/'.$detected.' '.strtoupper($key).' icons added.<br /><br />';

    }
}



if(!$processed){

    //SHow Form:
    echo '<form method="post" action="">';

    //Latest version
    echo '<p>Check <a href="https://fontawesome.com/pro" target="_blank"><u>Latest Version</u></a> in the middle of page & Update config value for <a href="/@13577" target="_blank"><u>@13577</u></a> ['.view_memory(6404,13577).']</p><br /><br />';

    //Regular
    echo '<p>Copy <a href="https://fontawesome.com/cheatsheet/pro/regular" target="_blank"><u>Regular Font Awesome Cheatsheet</u></a> here:</p>';
    echo '<textarea class="form-control text-edit border no-padding" style="height: 100px;" name="far" data-lpignore="true" placeholder="Paste Cheatsheet..."></textarea><br /><br />';


    //Brands
    echo '<p>Copy <a href="https://fontawesome.com/cheatsheet/pro/brands" target="_blank"><u>Brand Font Awesome Cheatsheet</u></a> here:</p>';
    echo '<textarea class="form-control text-edit border no-padding" style="height: 100px;" name="fab" data-lpignore="true" placeholder="Paste Cheatsheet..."></textarea><br /><br />';

    //Apply
    echo '<button type="submit" class="btn btn-lrg btn-6255 go-next top-margin">UPDATE</button>';


    echo '</form>';

}




