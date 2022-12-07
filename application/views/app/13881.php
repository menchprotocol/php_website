<?php

$guide = 'First Column Full Name, 2nd Column Email, 3rd Column Phone Number (If any)... Also no headline row, start with data! ';
$default_val = '';

if(isset($_POST['import_sources']) && strlen($_POST['import_sources'])>0){

    echo 'Begind Processing Import Data:<hr />';

    //Guide:
    $default_val = $_POST['import_sources'];
    $duplicate_check = array();
    $duplicate_email = array();
    $stats = array(
        'new_lines' => 0,
        'unique_lines' => 0,
        'errors' => 0,
    );

    foreach(explode(PHP_EOL, $_POST['import_sources']) as $count => $new_line){

        //Go through each column of this new line:
        $tabs = preg_split('/[\t,]/', $new_line);
        $full_name = $tabs[0];
        $email_address = $tabs[1];
        $phone_number = $tabs[2];
        $stats['new_lines']++;
        $md5_name = md5($full_name);
        $md5_email = md5($email_address);

        if(!strlen($full_name) || !strlen($md5_email) || isset($duplicate_check[$md5_name]) || isset($duplicate_check[$md5_email])){
            //This is a duplicate line:
            continue;
        }

        $duplicate_check[$md5_name] = 1;
        $duplicate_check[$md5_email] = 1;


        //New line to insert:
        $member_result = $this->E_model->add_member($full_name, $email_address, $phone_number, null, 0, true);
        if(!$member_result['status']) {
            $stats['errors']++;
        } else {
            $stats['unique_lines']++;
        }

        break;

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