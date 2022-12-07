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
        'phone_lines' => 0,
        'errors' => 0,
        'dup_index' => array(),
    );

    foreach(explode(PHP_EOL, $_POST['import_sources']) as $count => $new_line){

        $e__id = 0; //We must first identify this at the first volumn using e__title to them import the rest

        //Go through each column of this new line:
        $tabs = preg_split('/[\t,]/', $new_line);
        $full_name = $tabs[0];
        $email_address = $tabs[1];
        $phone_number = $tabs[2];
        $stats['new_lines']++;
        $md5 = md5($full_name);

        if(isset($stats['dup_index'][$md5])){
            //This is a duplicate line:
            continue;
        }

        $stats['dup_index'][$md5] = 1;


        //New line to insert:
        $member_result = $this->E_model->add_member($full_name, $email_address, null, 0, true);
        if(!$member_result['status']) {
            $stats['errors']++;
            continue;
        }

        if(strlen($phone_number)){
            $stats['phone_lines']++;
            $this->X_model->create(array(
                'x__up' => 4783, //Active Member
                'x__type' => e_x__type($phone_number),
                'x__message' => $phone_number,
                'x__source' => $member_result['e']['e__id'],
                'x__down' => $member_result['e']['e__id'],
            ));
        }

        $stats['unique_lines']++;

    }


    print_r($stats);
    print_r($member_result);

    echo '<hr />';

}

echo $guide;
echo '<form method="POST" action="">';
echo '<textarea class="border padded full-text" placeholder="Paste List Here" name="import_sources">'.$default_val.'</textarea>';
echo '<button type="submit" class="btn btn-lrg top-margin"><i class="fas fa-plus-circle zq12274"></i> IMPORT</button>';
echo '</form>';