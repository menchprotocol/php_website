<?php

//UI to compose a test message:
echo '<form method="GET" action="">';

echo '<div class="mini-header">Search String:</div>';
echo '<input type="text" class="form-control border maxout" name="search_for" value="'.@$_GET['search_for'].'"><br />';
echo '<input type="submit" class="btn btn-idea" value="Search">';


if(isset($_GET['search_for']) && strlen($_GET['search_for'])>0){

    $matching_results = $this->SOURCE_model->en_fetch(array(
        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
        'LOWER(en_icon) LIKE \'%'.strtolower($_GET['search_for']).'%\'' => null,
    ));

    //List the matching search:
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;" colspan="2">'.count($matching_results).' Results found</td>';
    echo '</tr>';


    if(count($matching_results) > 0){

        echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
        echo '<td style="text-align: left;">#</td>';
        echo '<td style="text-align: left;">Matching Search</td>';
        echo '</tr>';
        $replaced = 0;

        foreach($matching_results as $count=>$en){

            if(isset($_GET['do_replace']) && isset($_GET['replace_with'])){
                $replaced += $this->SOURCE_model->en_update($en['en_id'], array(
                    'en_icon' => str_ireplace($_GET['search_for'], $_GET['replace_with'], $en['en_icon']),
                ), false, $session_en['en_id']);

            }

            echo '<tr class="panel-title down-border">';
            echo '<td style="text-align: left;">'.($count+1).'</td>';
            echo '<td style="text-align: left;">'.echo_en_cache('en_all_6177' /* Source Status */, $en['en_status_source_id'], true, 'right').' <span class="icon-block">'.echo_en_icon($en['en_icon']).'</span><a href="/source/'.$en['en_id'].'">'.$en['en_name'].'</a></td>';
            echo '</tr>';

        }

        if($replaced > 0){
            echo '<div class="alert alert-success"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Updated icons for '.$replaced.' sources.</div>';
        }
    }

    echo '</table>';


    echo '<div class="mini-header">Replace With:</div>';
    echo '<input type="text" class="form-control border maxout" name="replace_with" value="'.@$_GET['replace_with'].'"><br />';
    echo '<input type="submit" name="do_replace" class="btn btn-idea" value="Replace">';
}


echo '</form>';