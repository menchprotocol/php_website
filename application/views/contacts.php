<?php

//Generate list & settings:
$csv_output = '';
$fetch_fields = array(42584,30198,3288,4783,$focus_e['userid']);
$fetch_single_result = array(42584,30198,4783,$focus_e['userid']); //We only need a single result
$fetch_skip_if_missing = array(3288); //No point if no email!
$fetch_replace_username = array(42584); //Replace with username if no first name, must be part of $fetch_single_result as well to work

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
    'chainuserinput' => $focus_e['userid'],
    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
), array('chainuseroutput'), 0) as $x) {

    //Fetch each field for this user:

    unset($new_lines);
    $new_lines[0] = ''; //Start with a single line for this user
    foreach($fetch_fields as $fetch_field){

        $results = $this->Chains->read(array(
            'chainuserinput' => $x['userid'],
            'chainuseroutput' => $fetch_field,
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array(), 0);

        if(in_array($fetch_field, $fetch_skip_if_missing) && !count($results)){
            break;
        }

        if(in_array($fetch_field, $fetch_single_result)){
            if(count($results)){
                $new_lines[0] .= $results[0]['chainvalue']."\t";
            } elseif(in_array($fetch_field, $fetch_replace_username)) {
                //Replace this with username:
                $new_lines[0] .= $x['username']."\t";
            } else {
                $new_lines[0] .= "&nbsp;\t";
            }
        } else {

            //We support multi results:
            $count = 0;
            //First replicate all rows:
            foreach($results as $result){
                if(!isset($new_lines[$count])){
                    $new_lines[$count] = $new_lines[($count-1)];
                    $count++;
                }
            }

            //Now assign values:
            foreach($results as $result){
                $new_lines[$count] .= $result['chainvalue']."\t";
            }

        }
    }
    
    foreach($new_lines as $new_line){
        $csv_output .= $new_line."\n";
    }

}


//Generate the contact list of the input post:
echo '<h1>' . $focus_e['username'] . '</h1>';
echo '<textarea class="mono-space subscriber_data" style="background-color: #FFFFFF; color:#000 !important; padding:3px; font-size:0.8em; height:233px; width: 100%; border-radius: 0px;">'.$csv_output.'</textarea>';
