<?php

//Idea Title Search & Replace

echo '<form method="GET" action="">';

echo '<div class="mini-header">Search String:</div>';
echo '<input type="text" class="form-control border maxout" name="search_for" value="'.@$_GET['search_for'].'"><br />';


$search_for_is_set = (isset($_GET['search_for']) && strlen($_GET['search_for'])>0);
$replace_with_is_set = ((isset($_GET['replace_with']) && strlen($_GET['replace_with'])>0) || (isset($_GET['append_text']) && strlen($_GET['append_text'])>0));
$qualifying_replacements = 0;
$completed_replacements = 0;
$replace_with_is_confirmed = false;

if($search_for_is_set){

    $matching_results = $this->IDEA_model->fetch(array(
        'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
        'LOWER(idea__title) LIKE \'%'.strtolower($_GET['search_for']).'%\'' => null,
    ));

    //List the matching search:
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;" colspan="4">'.count($matching_results).' Results found</td>';
    echo '</tr>';


    if(count($matching_results) < 1){

        $replace_with_is_set = false;
        unset($_GET['confirm_statement']);
        unset($_GET['replace_with']);

    } else {

        $confirmation_keyword = 'Replace '.count($matching_results);
        $replace_with_is_confirmed = (isset($_GET['confirm_statement']) && strtolower($_GET['confirm_statement'])==strtolower($confirmation_keyword));

        echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
        echo '<td style="text-align: left;">#</td>';
        echo '<td style="text-align: left;">Matching Search</td>';
        echo '<td style="text-align: left;">'.( $replace_with_is_set ? 'Replacement' : '' ).'</td>';
        echo '<td style="text-align: left;">&nbsp;</td>';
        echo '</tr>';

        foreach($matching_results as $count=>$in){

            if($replace_with_is_set){
                //Do replacement:
                $append_text = @$_GET['append_text'];
                $new_outcome = str_replace($_GET['search_for'],$_GET['replace_with'],$in['idea__title']).$append_text;
                $idea__title_validation = idea__title_validate($new_outcome);

                if($idea__title_validation['status']){
                    $qualifying_replacements++;
                }
            }

            if($replace_with_is_confirmed && $idea__title_validation['status']){
                //Update idea:
                $this->IDEA_model->update($in['idea__id'], array(
                    'idea__title' => $idea__title_validation['in_clean_title'],
                ), true, $session_en['source__id']);
            }

            echo '<tr class="panel-title down-border">';
            echo '<td style="text-align: left;">'.($count+1).'</td>';
            echo '<td style="text-align: left;">'.view_en_cache('sources__4737' /* Idea Status */, $in['idea__status'], true, 'right').' <a href="/idea/go/'.$in['idea__id'].'">'.$in['idea__title'].'</a></td>';

            if($replace_with_is_set){

                echo '<td style="text-align: left;">'.$new_outcome.'</td>';
                echo '<td style="text-align: left;">'.( !$idea__title_validation['status'] ? '<span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$idea__title_validation['message'] : ( $replace_with_is_confirmed && $idea__title_validation['status'] ? '<i class="fas fa-check-circle"></i> Outcome Updated' : '') ).'</td>';
            } else {
                //Show parents now:
                echo '<td style="text-align: left;">';


                //Loop through parents:
                $sources__7585 = $this->config->item('sources__7585'); // Idea Subtypes
                foreach($this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                    'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
                    'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
                    'read__right' => $in['idea__id'],
                ), array('idea_previous')) as $in_parent) {
                    echo '<span class="in_child_icon_' . $in_parent['idea__id'] . '"><a href="/idea/go/' . $in_parent['idea__id'] . '" data-toggle="tooltip" title="' . $in_parent['idea__title'] . '" data-placement="bottom">' . $sources__7585[$in_parent['idea__type']]['m_icon'] . '</a> &nbsp;</span>';
                }

                echo '</td>';
                echo '<td style="text-align: left;"></td>';
            }


            echo '</tr>';

        }
    }

    echo '</table>';
}


if($search_for_is_set && count($matching_results) > 0 && !$completed_replacements){
    //now give option to replace with:
    echo '<div class="mini-header">Replace With:</div>';
    echo '<input type="text" class="form-control border maxout" name="replace_with" value="'.@$_GET['replace_with'].'"><br />';

    //now give option to replace with:
    echo '<div class="mini-header">Append Text:</div>';
    echo '<input type="text" class="form-control border maxout" name="append_text" value="'.@$_GET['append_text'].'"><br />';
}

if($replace_with_is_set && !$completed_replacements){
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
