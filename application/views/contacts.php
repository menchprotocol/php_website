<?php

//Generate list & settings:
$csv_output = '';
$fetch_now = array(intval($focus_e['userid']));
if(isset($_GET['add']) && is_numeric($_GET['add'])){
    array_push($fetch_now, intval($_GET['add']));
}
$fetch_fields = array(42584,30198,4783,3288);
$fetch_single_result = array(42584,30198,4783); //We only need a single result
$fetch_fields = array_merge($fetch_fields, $fetch_now);
$fetch_single_result = array_merge($fetch_single_result, $fetch_now);
$fetch_skip_if_missing = array(3288); //No point if no email!
$fetch_replace_username = array(42584); //Replace with username if no first name, must be part of $fetch_single_result as well to work
$unique_emails = array();

//First Name, Last Name, Email & Phone Number
foreach($fetch_fields as $fetch_field) {
    foreach ($this->Users->read(array(
        'userid' => $fetch_field,
    )) as $e) {
        $csv_output .= $e['username']."\t";
    }
}

$csv_output .= "\n";


//Now fetch all the child fields:
foreach($this->Chains->read(array(
    'chainuserinput IN (' . join(',', $fetch_now) . ')' => null, //USER CHAINS
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
), array('chainuseroutput'), 0) as $x) {

    //Fetch each field for this user:
    unset($new_lines);
    $new_lines[0] = ''; //Start with a single line for this user
    $must_skip = false;
    foreach($fetch_fields as $fetch_field){

        $results = $this->Chains->read(array(
            'chainuserinput' => $fetch_field,
            'chainuseroutput' => $x['userid'],
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array(), 0);

        if(in_array($fetch_field, $fetch_skip_if_missing) && (!count($results) || substr_count($results[0]['chainvalue'], '+'))){
            $must_skip = true;
            break;
        }
        

        if(in_array($fetch_field, $fetch_single_result)){
            
            if(count($results)){
                $new_lines[0] .= trim(str_replace("\n",' ',$results[0]['chainvalue']))."\t";
            } elseif(in_array($fetch_field, $fetch_replace_username)) {
                //Replace this with username:
                $new_lines[0] .= trim($x['username'])."\t";
            } else {
                $new_lines[0] .= "&nbsp;\t";
            }

        } else {

            //make sure its all unique
            if(in_array($fetch_field, $fetch_skip_if_missing)){
                $is_invalid = false;
                foreach($results as $result){
                    if(in_array(strtolower($result['chainvalue']), $unique_emails) || !filter_var(strtolower($result['chainvalue']), FILTER_VALIDATE_EMAIL)){
                        $is_invalid = true;
                        break;
                    } else {
                        array_push($unique_emails, strtolower($result['chainvalue']));
                    }
                }
            }
            if($is_invalid){
                $must_skip = true;
                break;
            }

            //We support multi results:
            $count = 0;
            //First replicate all rows:
            foreach($results as $result){
                if(!isset($new_lines[$count])){
                    $new_lines[$count] = $new_lines[($count-1)];
                }
                $count++;
            }

            //Now assign values:
            $count = 0;
            foreach($results as $result){
                $new_lines[$count] .= trim(str_replace("\n",' ',$result['chainvalue']))."\t";
                $count++;
            }

        }
    }

    if(!$must_skip){
        foreach($new_lines as $new_line){
            $csv_output .= $new_line."\n";
        }
    }

}


//Generate the contact list of the input post:
echo '<h1>Contact List</h1>';
echo '<textarea class="mono-space subscriber_data" style="background-color: #FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:233px; width: 100%; border-radius: 0px;">'.$csv_output.'</textarea>';
