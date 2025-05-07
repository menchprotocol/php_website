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
                            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                'chainsourceup' => 3288, //Email
                'chainvalue' => trim(strtolower($email)),
            )) as $source_data){

                $found_emails++;

                //Do we need to add?
                if(isset($_POST['import_sourceid']) && intval($_POST['import_sourceid']) && !count($this->Chains->read(array(
                                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                    'chainsourceup' => $_POST['import_sourceid'],
                    'chainsourcedown' => $source_data['chainsourcedown'],
                )))){

                    $added_emails++;
                    $this->Chains->create(array(
                        'chainsourcetype' => 4230,
                        'chainsourcecreator' => $source_session['sourceid'],
                        'chainsourceup' => $_POST['import_sourceid'],
                        'chainsourcedown' => $source_data['chainsourcedown'],
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


echo '<input type="number" class="form-control input_border border maxout" name="import_sourceid" value="'.( isset($_POST['import_sourceid']) ? $_POST['import_sourceid'] : '' ).'" placeholder="Import Source ID"><br />';

//Apply
echo '<button type="submit" class="btn btn-lrg go-next top-margin">Map Emails</button>';

echo '</form>';
