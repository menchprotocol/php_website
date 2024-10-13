<?php

//UI to compose a test message:
echo '<form method="GET" action="">';

echo '<div class="mini-header">Search String:</div>';
echo '<input type="text" class="form-control input_border border maxout" name="search_for" value="'.@$_GET['search_for'].'"><br />';



$search_for_set = (isset($_GET['search_for']) && strlen($_GET['search_for'])>0);
$replace_with_set = ((isset($_GET['replace_with']) && strlen($_GET['replace_with'])>0) || (isset($_GET['append_text']) && strlen($_GET['append_text'])>0));
$replace_with_confirmed = false;

if($search_for_set){

    $matching_results = $this->Sources->read(array(
            'e__title LIKE \'%'.$_GET['search_for'].'%\'' => null,
    ));

    //List the matching search:
    echo '<div>'.count($matching_results).' sources Found</div>';
    if(count($matching_results) < 1){

        $replace_with_set = false;
        unset($_GET['confirm_statement']);
        unset($_GET['replace_with']);

    } else {

        $replaced = 0;
        $confirmation_keyword = 'Replace '.count($matching_results);
        $replace_with_confirmed = (isset($_GET['confirm_statement']) && strtolower($_GET['confirm_statement'])==strtolower($confirmation_keyword));

        echo '<div class="row">';
        foreach($matching_results as $count=>$en){

            if($replace_with_set){

                //Do replacement:
                $append_text = @$_GET['append_text'];
                $en['e__title'] = str_ireplace($_GET['search_for'],$_GET['replace_with'],$en['e__title']).$append_text;

                if($replace_with_confirmed){
                    //Update idea:
                    $res = $this->Sources->edit($en['e__id'], array(
                        'e__title' => $en['e__title'],
                    ), true, $player_e['e__id']);
                    $replaced++;
                }
            }

            echo view_card_e(12730, $en, null);
        }
        echo '</div>';

        if($replaced > 0){
            echo '<div class="alert alert-danger" role="alert">Replaced '.$replaced.' titles</div>';
        }

    }

}


if($search_for_set && count($matching_results) > 0){
    //now give option to replace with:
    echo '<div class="mini-header">Replace With:</div>';
    echo '<input type="text" class="form-control input_border border maxout" name="replace_with" value="'.@$_GET['replace_with'].'"><br />';

    //now give option to replace with:
    echo '<div class="mini-header">Append Text:</div>';
    echo '<input type="text" class="form-control input_border border maxout" name="append_text" value="'.@$_GET['append_text'].'"><br />';
}

if($replace_with_set){
    //now give option to replace with:
    echo '<div class="mini-header">Confirm Replacement by Typing "'.$confirmation_keyword.'":</div>';
    echo '<input type="text" class="form-control input_border border maxout" name="confirm_statement" value="'. @$_GET['confirm_statement'] .'"><br />';
}


echo '<input type="submit" class="btn btn-default" value="GO">';
echo '</form>';
