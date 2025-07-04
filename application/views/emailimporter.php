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

            foreach($this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                'chainhandleinput' => 3288, //Email
                'chainvalue' => trim(strtolower($email)),
            )) as $handle_data){

                $found_emails++;

                //Do we need to add?
                if(isset($_POST['import_handleid']) && intval($_POST['import_handleid']) && !count($this->Chains->read(array(
                                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                    'chainhandleinput' => $_POST['import_handleid'],
                    'chainhandleoutput' => $handle_data['chainhandleoutput'],
                )))){

                    $added_emails++;
                    $this->Chains->create(array(
                        'chainhandletype' => 4230,
                        'chainhandlecreator' => $handle_session['handleid'],
                        'chainhandleinput' => $_POST['import_handleid'],
                        'chainhandleoutput' => $handle_data['chainhandleoutput'],
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


echo '<input type="number" class="form-control input_border border maxout" name="import_handleid" value="'.( isset($_POST['import_handleid']) ? $_POST['import_handleid'] : '' ).'" placeholder="Import Handle ID"><br />';

//Apply
echo '<button type="submit" class="btn btn-lrg go-next top-margin">Map Emails</button>';

echo '</form>';
