<?php

if(isset($_POST['list_emails']) && strlen($_POST['list_emails'])){

    //Process emails:
    $total_emails = 0;
    $found_emails = 0;
    $added_emails = 0;
    $emails = explode("\n",$_POST['list_emails']);

    foreach($emails as $email){

        if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {

            $total_emails++;
            //echo $email.'<hr />';

            foreach($this->Ideachains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                'chainuserinput' => 3288, //Email
                'chainvalue' => trim(strtolower($email)),
            )) as $user_data){

                $found_emails++;

                //Do we need to add?
                if(isset($_POST['import_userid']) && intval($_POST['import_userid']) && !count($this->Ideachains->read(array(
                                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                    'chainuserinput' => $_POST['import_userid'],
                    'chainuseroutput' => $user_data['chainuseroutput'],
                )))){

                    $added_emails++;
                    $this->Ideachains->create(array(
                        'chainusertype' => 4230,
                        'chainusercreator' => $user_session['userid'],
                        'chainuserinput' => $_POST['import_userid'],
                        'chainuseroutput' => $user_data['chainuseroutput'],
                    ));

                }

                break;

            }

        }
    }

    echo $total_emails.' Emails Scanned<br />';
    echo $found_emails.' Emails Found<br />';
    echo $added_emails.' Emails Added<br />';

}

//SHow Form:
echo '<form method="POST" action="">';

echo '<textarea class="form-control border no-padding" style="height:200px;" name="list_emails" data-lpignore="true" placeholder="Paste Emails (One per line)">'.( isset($_POST['list_emails']) ? $_POST['list_emails'] : '' ).'</textarea><br /><br />';


echo '<input type="number" class="form-control input_border border maxout" name="import_userid" value="'.( isset($_POST['import_userid']) ? $_POST['import_userid'] : '' ).'" placeholder="Import User ID"><br />';

//Apply
echo '<button type="submit" class="btn btn-lrg go-next top-margin">Map Emails</button>';

echo '</form>';
