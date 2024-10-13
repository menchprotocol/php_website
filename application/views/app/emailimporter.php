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

            foreach($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__following' => 3288, //Email
                'x__message' => trim(strtolower($email)),
            )) as $e_data){

                $found_emails++;

                //Do we need to add?
                if(isset($_POST['import_e__id']) && intval($_POST['import_e__id']) && !count($this->X_model->fetch(array(
                            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__following' => $_POST['import_e__id'],
                    'x__follower' => $e_data['x__follower'],
                )))){

                    $added_emails++;
                    $this->X_model->create(array(
                        'x__type' => 4251,
                        'x__player' => $player_e['e__id'],
                        'x__following' => $_POST['import_e__id'],
                        'x__follower' => $e_data['x__follower'],
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


echo '<input type="number" class="form-control input_border border maxout" name="import_e__id" value="'.( isset($_POST['import_e__id']) ? $_POST['import_e__id'] : '' ).'" placeholder="Import Source ID"><br />';

//Apply
echo '<button type="submit" class="btn btn-lrg go-next top-margin">Map Emails</button>';

echo '</form>';
