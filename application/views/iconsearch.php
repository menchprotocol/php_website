<?php

$icon_keyword = null;

if(isset($_GET['search_for'])){

    $icon_keyword = $_GET['search_for'];

} elseif(isset($_GET['sourcehandle']) && $_GET['sourcehandle']){

    $es = $this->Sources->read(array(
        'LOWER(sourcehandle)' => strtolower($_GET['sourcehandle']),
    ));
    if(!count($es)){
        return view_json(array(
            'status' => 0,
            'message' => 'Invalid Source ID #1'
        ));
    } elseif(!strlen($es[0]['sourcecover'])) {
        return view_json(array(
            'status' => 0,
            'message' => 'Source Missing Cover'
        ));
    }

    if(string_is_icon($es[0]['sourcecover'])){

        //Exclude Cover settings:
        $icon_keyword = 'fa-'.one_two_explode('fa-',' ',$es[0]['sourcecover']);

    } else {

        $icon_keyword = $es[0]['sourcecover'];

    }
}


//UI to compose a test message:
echo '<form method="GET" action="">';

echo '<div class="mini-header">Search String:</div>';
echo '<input type="text" class="form-control border maxout" name="search_for" value="'.$icon_keyword.'"><br />';
echo '<input type="submit" class="btn" value="Search">';


if($icon_keyword){

    $matching_results = $this->Sources->read(array(
        'LOWER(sourcecover) LIKE \'%'.strtolower($icon_keyword).'%\'' => null,
    ));

    //List the matching search:
    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';


    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;" colspan="2">'.count($matching_results).' Results found</td>';
    echo '</tr>';


    echo '<tr class="panel-title down-border" style="font-weight:bold !important;">';
    echo '<td style="text-align: left;">#</td>';
    echo '<td style="text-align: left;">Matching Search</td>';
    echo '</tr>';

    if(count($matching_results) > 0){

        $replaced = 0;
        foreach($matching_results as $count=>$en){

            if(isset($_GET['do_replace']) && isset($_GET['replace_with'])){
                $replaced += $this->Sources->update($en['sourceid'], array(
                    'sourcecover' => str_ireplace($icon_keyword, $_GET['replace_with'], $en['sourcecover']),
                ), $source_session['sourceid']);

            }

            echo '<tr class="panel-title down-border">';
            echo '<td style="text-align: left;">'.($count+1).'</td>';
            echo '<td style="text-align: left;"><span class="icon-block">'.view_cover($en['sourcecover']).'</span><a href="'.view_memory(42903,42902).$en['sourcehandle'].'">'.$en['sourcetext'].'</a></td>';
            echo '</tr>';

        }

        if($replaced > 0){
            echo '<span class="icon-block"><i class="far fa-check-circle"></i></span>Updated icons for '.$replaced.' Sources.';
        }

    }

    echo '</table>';


    echo '<div class="mini-header">Replace With:</div>';
    echo '<input type="text" class="form-control border maxout" name="replace_with" value="'.@$_GET['replace_with'].'"><br />';
    echo '<input type="submit" name="do_replace" class="btn" value="Replace">';
}


echo '</form>';