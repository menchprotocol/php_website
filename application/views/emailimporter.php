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

            foreach($this->Links->read(array(
                            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                'linkplayerup' => 3288, //Email
                'linktext' => trim(strtolower($email)),
            )) as $player_data){

                $found_emails++;

                //Do we need to add?
                if(isset($_POST['import_playerid']) && intval($_POST['import_playerid']) && !count($this->Links->read(array(
                                    'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                    'linkplayerup' => $_POST['import_playerid'],
                    'linkplayerdown' => $player_data['linkplayerdown'],
                )))){

                    $added_emails++;
                    $this->Links->create(array(
                        'linkplayertype' => 4230,
                        'linkplayercreator' => $player_e['playerid'],
                        'linkplayerup' => $_POST['import_playerid'],
                        'linkplayerdown' => $player_data['linkplayerdown'],
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


echo '<input type="number" class="form-control input_border border maxout" name="import_playerid" value="'.( isset($_POST['import_playerid']) ? $_POST['import_playerid'] : '' ).'" placeholder="Import Player ID"><br />';

//Apply
echo '<button type="submit" class="btn btn-lrg go-next top-margin">Map Emails</button>';

echo '</form>';
