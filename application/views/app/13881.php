<?php

$guide = 'First Column Full Name, 2nd Column Email, 3rd Column Phone Number (If any)... Also no headline row, start with data! ';
$default_val = '';

if(isset($_POST['import_sources']) && strlen($_POST['import_sources'])>0){

    echo 'Begind Processing Import Data:<hr />';

    //Guide:
    $default_val = $_POST['import_sources'];
    $stats = array(
        'new_lines' => 0,
        'unique_lines' => 0,
        'dup_index' => array(),
    );

    foreach(explode(PHP_EOL, $_POST['import_sources']) as $count => $new_line){

        $e__id = 0; //We must first identify this at the first volumn using e__title to them import the rest

        //Go through each column of this new line:
        $tabs = preg_split('/\s+/', $new_line);

        print_r($tabs);
        if($count > 10){
            break;
        } else {
            continue;
        }

        $follow_e = intval(substr($stats['commands'][$count], 1));

        $stats['unique_lines']++;


        $md5 = md5($tabs[1]);
        if(!isset($stats['dup_index'][$md5])){

            //This is new:
            $stats['unique_lines']++;

            //Import into DB & map ID for next columns:
            $stats['dup_index'][$md5] = 1;

            /*

            $focus_e = $this->E_model->create(array(
                'e__title' => $e__title_validate['e__title_clean'],
                'e__cover' => $e__cover,
                'e__type' => 6181, //Private
            ), true, $x__source);

             * */

        }

    }

    print_r($stats);

    echo '<hr />';

}

echo $guide;
echo '<form method="POST" action="">';
echo '<textarea class="border padded full-text" placeholder="Paste List Here" name="import_sources">'.$default_val.'</textarea>';
echo '<button type="submit" class="btn btn-lrg top-margin"><i class="fas fa-plus-circle zq12274"></i> IMPORT</button>';
echo '</form>';