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

    $matching_results = $this->IDEA_model->in_fetch(array(
        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
        'LOWER(in_title) LIKE \'%'.strtolower($_GET['search_for']).'%\'' => null,
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
                $new_outcome = str_replace($_GET['search_for'],$_GET['replace_with'],$in['in_title']).$append_text;
                $in_titlevalidation = $this->IDEA_model->in_titlevalidate($new_outcome);

                if($in_titlevalidation['status']){
                    $qualifying_replacements++;
                }
            }

            if($replace_with_is_confirmed && $in_titlevalidation['status']){
                //Update idea:
                $this->IDEA_model->in_update($in['in_id'], array(
                    'in_title' => $in_titlevalidation['in_cleaned_outcome'],
                ), true, $session_en['en_id']);
            }

            echo '<tr class="panel-title down-border">';
            echo '<td style="text-align: left;">'.($count+1).'</td>';
            echo '<td style="text-align: left;">'.echo_en_cache('en_all_4737' /* Idea Status */, $in['in_status_source_id'], true, 'right').' <a href="/idea/'.$in['in_id'].'">'.$in['in_title'].'</a></td>';

            if($replace_with_is_set){

                echo '<td style="text-align: left;">'.$new_outcome.'</td>';
                echo '<td style="text-align: left;">'.( !$in_titlevalidation['status'] ? ' <i class="fad fa-exclamation-triangle"></i> Alert: '.$in_titlevalidation['message'] : ( $replace_with_is_confirmed && $in_titlevalidation['status'] ? '<i class="fas fa-check-circle"></i> Outcome Updated' : '') ).'</td>';
            } else {
                //Show parents now:
                echo '<td style="text-align: left;">';


                //Loop through parents:
                $en_all_7585 = $this->config->item('en_all_7585'); // Idea Subtypes
                foreach ($this->DISCOVER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
                    'ln_next_idea_id' => $in['in_id'],
                ), array('in_parent')) as $in_parent) {
                    echo '<span class="in_child_icon_' . $in_parent['in_id'] . '"><a href="/idea/' . $in_parent['in_id'] . '" data-toggle="tooltip" title="' . $in_parent['in_title'] . '" data-placement="bottom">' . $en_all_7585[$in_parent['in_type_source_id']]['m_icon'] . '</a> &nbsp;</span>';
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
        echo '<div class="alert alert-danger"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>Fix errors above to then apply search/replace</div>';
    }
}


echo '<input type="submit" class="btn btn-idea" value="Go">';
echo '</form>';
