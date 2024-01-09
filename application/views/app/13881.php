<?php

$guide = 'First Column Full Name, 2nd Column Email, 3rd Column Phone Number (If any)... Also no headline row, start with data! ';
$default_val = '';

if(isset($_POST['import_e']) && strlen($_POST['import_e'])>0){

    echo 'Begind Processing Import Data:<hr />';

    //Guide:
    $default_val = $_POST['import_e'];
    $duplicate_check = array();
    $duplicate_email = array();
    $stats = array(
        'new_lines' => 0,
        'already_there' => 0,
        'unique_lines' => 0,
        'errors' => 0,
    );

    foreach(explode(PHP_EOL, $_POST['import_e']) as $count => $new_line){

        //Go through each column of this new line:
        $tabs = preg_split('/[\t,]/', $new_line);
        $full_name = $tabs[0];
        $email_address = strtolower($tabs[1]);
        $phone_number = trim($tabs[2]);
        $stats['new_lines']++;
        $md5_name = md5($full_name);
        $md5_email = md5($email_address);

        if(!strlen($full_name) || !strlen($email_address) || isset($duplicate_check[$md5_name]) || isset($duplicate_check[$md5_email])){
            //This is a duplicate line:
            continue;
        }

        $duplicate_check[$md5_name] = 1;
        $duplicate_check[$md5_email] = 1;

        //Now check email to make sure not a duplicate member:
        $email_e__id = 0;
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__message' => $email_address,
            'x__up' => 3288, //Email
        ), array('x__down')) as $email_found){
            //Existing Member, Add to This Website:
            $email_e__id = $email_found['e__id'];
            $this->E_model->regular_add_e(website_setting(0), $email_e__id);
            $stats['already_there']++;
            break;
        }

        if(!$email_e__id){
            //New line to insert:
            $member_result = $this->E_model->add_member($full_name, $email_address, $phone_number, null, 0, true);
            if(!$member_result['status']) {
                $stats['errors']++;
            } else {
                $stats['unique_lines']++;
            }
        }
    }

    print_r($stats);

}

echo $guide;
echo '<form method="POST" action="">';
echo '<textarea class="border  full-text" placeholder="Paste List Here" name="import_e">'.$default_val.'</textarea>';
echo '<button type="submit" class="btn btn-lrg top-margin"><i class="fas fa-plus-circle zq12274"></i> IMPORT</button>';
echo '</form>';