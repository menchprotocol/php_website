<?php

//UI to compose a test message:
echo '<form method="GET" action="">';

echo '<div class="mini-header">Search String:</div>';
echo '<input type="text" class="form-control border maxout" name="search_for" value="'.@$_GET['search_for'].'"><br />';
echo '<input type="submit" class="btn btn-idea" value="Search">';


if(isset($_GET['search_for']) && strlen($_GET['search_for'])>0){

    $matching_results = $this->SOURCE_model->fetch(array(
        'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
        'LOWER(source__icon) LIKE \'%'.strtolower($_GET['search_for']).'%\'' => null,
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
                $replaced += $this->SOURCE_model->update($en['source__id'], array(
                    'source__icon' => str_ireplace($_GET['search_for'], $_GET['replace_with'], $en['source__icon']),
                ), false, $session_en['source__id']);

            }

            echo '<tr class="panel-title down-border">';
            echo '<td style="text-align: left;">'.($count+1).'</td>';
            echo '<td style="text-align: left;">'.view_en_cache('sources__6177' /* Source Status */, $en['source__status'], true, 'right').' <span class="icon-block">'.view_source__icon($en['source__icon']).'</span><a href="/source/'.$en['source__id'].'">'.$en['source__title'].'</a></td>';
            echo '</tr>';

        }

        if($replaced > 0){
            echo '<span class="icon-block"><i class="fas fa-check-circle"></i></span>Updated icons for '.$replaced.' sources.';
        }
    }

    echo '</table>';


    echo '<div class="mini-header">Replace With:</div>';
    echo '<input type="text" class="form-control border maxout" name="replace_with" value="'.@$_GET['replace_with'].'"><br />';
    echo '<input type="submit" name="do_replace" class="btn btn-idea" value="Replace">';
}


echo '</form>';