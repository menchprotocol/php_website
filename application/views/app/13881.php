<?php

$guide = 'First Column Full Name, 2nd Column Email, 3rd Column Phone Number (If any)... Also no headline row, start with data! ';

if(isset($_POST['import_sources'])){

    echo 'Begind Processing Import Data:<hr />';

    //Guide:
    $stats = array(
        'new_lines' => 0,
        'unique_lines' => 0,
        'dup_index' => array(),
    );

    foreach(explode(PHP_EOL, $_POST['skuList']) as $count => $new_line){

        $e__id = 0; //We must first identify this at the first volumn using e__title to them import the rest

        //Go through each column of this new line:
        $tabs = preg_split('/\s+/', $new_line);

        print_r($tabs);
        break;

        $follow_e = intval(substr($stats['commands'][$count], 1));



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
echo '<textarea class="border padded" placeholder="Paste List Here" name="import_sources" style="width: 100%; height: 200px;"></textarea>';
echo '<button type="submit" class="btn btn-lrg top-margin"><i class="fas fa-plus-circle zq12274"></i> IMPORT</button>';
echo '</form>';