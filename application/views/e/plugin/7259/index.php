<?php

//Idea Title Search & Replace

echo '<form method="GET" action="">';

echo '<div class="mini-header">Search String:</div>';
echo '<input type="text" class="form-control border maxout" name="search_for" value="'.@$_GET['search_for'].'"><br />';


$search_for_set = (isset($_GET['search_for']) && strlen($_GET['search_for'])>0);
$replace_with_set = ((isset($_GET['replace_with']) && strlen($_GET['replace_with'])>0) || (isset($_GET['append_text']) && strlen($_GET['append_text'])>0));
$qualifying_replacements = 0;
$completed_replacements = 0;
$replace_with_confirmed = false;

if($search_for_set){

    $matching_results = $this->I_model->fetch(array(
        'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        'LOWER(i__title) LIKE \'%'.strtolower($_GET['search_for']).'%\'' => null,
    ));

    //List the matching search:
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;" colspan="4">'.count($matching_results).' Results found</td>';
    echo '</tr>';


    if(count($matching_results) < 1){

        $replace_with_set = false;
        unset($_GET['confirm_statement']);
        unset($_GET['replace_with']);

    } else {

        $confirmation_keyword = 'Replace '.count($matching_results);
        $replace_with_confirmed = (isset($_GET['confirm_statement']) && strtolower($_GET['confirm_statement'])==strtolower($confirmation_keyword));

        echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
        echo '<td style="text-align: left;">#</td>';
        echo '<td style="text-align: left;">Matching Search</td>';
        echo '<td style="text-align: left;">'.( $replace_with_set ? 'Replacement' : '' ).'</td>';
        echo '<td style="text-align: left;">&nbsp;</td>';
        echo '</tr>';

        foreach($matching_results as $count=>$in){

            if($replace_with_set){
                //Do replacement:
                $append_text = @$_GET['append_text'];
                $new_outcome = str_replace($_GET['search_for'],$_GET['replace_with'],$in['i__title']).$append_text;
                $i__title_validation = i__title_validate($new_outcome);

                if($i__title_validation['status']){
                    $qualifying_replacements++;
                }
            }

            if($replace_with_confirmed && $i__title_validation['status']){
                //Update idea:
                $this->I_model->update($in['i__id'], array(
                    'i__title' => $i__title_validation['i_clean_title'],
                ), true, $user_e['e__id']);
            }

            echo '<tr class="panel-title down-border">';
            echo '<td style="text-align: left;">'.($count+1).'</td>';
            echo '<td style="text-align: left;">'.view_cache(4737 /* Idea Status */, $in['i__status'], true, 'right').' <a href="/i/i_go/'.$in['i__id'].'">'.$in['i__title'].'</a></td>';

            if($replace_with_set){

                echo '<td style="text-align: left;">'.$new_outcome.'</td>';
                echo '<td style="text-align: left;">'.( !$i__title_validation['status'] ? '<span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$i__title_validation['message'] : ( $replace_with_confirmed && $i__title_validation['status'] ? '<i class="fas fa-check-circle"></i> Outcome Updated' : '') ).'</td>';
            } else {
                //Show parents now:
                echo '<td style="text-align: left;">';


                //Loop through parents:
                $e___7585 = $this->config->item('e___7585'); // Idea Subtypes
                foreach($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                    'x__right' => $in['i__id'],
                ), array('x__left')) as $i_previous) {
                    echo '<span class="next_i_icon_' . $i_previous['i__id'] . '"><a href="/i/i_go/' . $i_previous['i__id'] . '" data-toggle="tooltip" title="' . $i_previous['i__title'] . '" data-placement="bottom">' . $e___7585[$i_previous['i__type']]['m_icon'] . '</a> &nbsp;</span>';
                }

                echo '</td>';
                echo '<td style="text-align: left;"></td>';
            }


            echo '</tr>';

        }
    }

    echo '</table>';
}


if($search_for_set && count($matching_results) > 0 && !$completed_replacements){
    //now give option to replace with:
    echo '<div class="mini-header">Replace With:</div>';
    echo '<input type="text" class="form-control border maxout" name="replace_with" value="'.@$_GET['replace_with'].'"><br />';

    //now give option to replace with:
    echo '<div class="mini-header">Append Text:</div>';
    echo '<input type="text" class="form-control border maxout" name="append_text" value="'.@$_GET['append_text'].'"><br />';
}

if($replace_with_set && !$completed_replacements){
    if($qualifying_replacements==count($matching_results) /*No Errors*/){
        //now give option to replace with:
        echo '<div class="mini-header">Confirm Replacement by Typing "'.$confirmation_keyword.'":</div>';
        echo '<input type="text" class="form-control border maxout" name="confirm_statement" value="'. @$_GET['confirm_statement'] .'"><br />';
    } else {
        echo '<div class="alert alert-danger"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Fix errors above to then apply search/replace</div>';
    }
}


echo '<input type="submit" class="btn btn-idea" value="Go">';
echo '</form>';
