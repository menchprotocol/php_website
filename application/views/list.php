<?php

if(!isset($_GET['hashtaghashtag'])){
    die('Missing Hahstag ID hashtaghashtag');
}

//Sheet
$handles___6287 = $this->config->item('handles___6287'); //APP
$handles___4737 = $this->config->item('handles___4737'); //Hahstag Types

$underdot_class = ( !isset($_GET['expand']) ? ' class="underdot" ' : '' );
$recursive_hashtag_ids = array();
$is_with_action_es = array();
$es_added = array();
$count = 0;
$body_content = '';
$count_totals = array(
    'e' => array(),
    'i' => array(),
);


//Generate list & settings:
$hashtag_settings = hashtag_settings($_GET['hashtaghashtag']);

echo '<h1>' . view_hashtag_title($hashtag_settings['i']) . '</h1>';
echo '<div class="center-frame hide-subline maxwidth hideIfEmpty remove_first_line">' . view_hashtag_value($hashtag_settings['i'], ( isset($handle_session['handleid']) ? $handle_session['handleid'] : 0 )) . '</div>';

echo 'Filter:';

foreach($hashtag_settings['query_string_filtered'] as $x){

    $body_content .= '<tr class="body_tr">';

    //HASHTAGS
    $hashtag_content = '';
    $this_quantity = 1;
    $name = '';
    foreach($hashtag_settings['hashtag_column'] as $hashtag_var){

        $discoveries = $this->Chains->read(array(
            'chainhashtaginput' => $hashtag_var['hashtagid'],
            'chainhandlecreator' => $x['handleid'],
            'chainhandletype IN (' . join(',', $this->config->item('handleids___31777')) . ')' => null, //DISCOVERIES
                ), array(), 1);

        if(count($discoveries)){

            if($this_quantity<2 && intval($discoveries[0]['chainkey'])>=2){
                $this_quantity = $discoveries[0]['chainkey'];
            }


        }

        $hashtag_content .= '<td title="'.$x['handlevalue'].' x '.view_hashtag_title($hashtag_var, true).'">'.( count($discoveries) ? ( strlen($discoveries[0]['chainvalue']) > 0 ? ( isset($_GET['expand']) ? '<p title="'.view_hashtag_title($hashtag_var, true).': '.$discoveries[0]['chainvalue'].'" data-placement="top" '.$underdot_class.'>'.$discoveries[0]['chainvalue'].'</p>' : '<span title="'.view_hashtag_title($hashtag_var, true).': '.$discoveries[0]['chainvalue'].' ['.$discoveries[0]['chaintime'].']" '.$underdot_class.'>✔️</span>'  ) : '<span title="'.view_hashtag_title($hashtag_var, true).' ['.$discoveries[0]['chaintime'].']">✔️</span>' )  : '').'</td>';

        if(count($discoveries)){
            if(!isset($count_totals['i'][$hashtag_var['hashtagid']])){
                $count_totals['i'][$hashtag_var['hashtagid']] = 0;
            }
            $count_totals['i'][$hashtag_var['hashtagid']]++;
        }

    }

    $this_quantity = $this_quantity-1;




    $plus_info = ' '.( $this_quantity > 0 ? '+'.$this_quantity : '' );

    $body_content .= '<td style="padding-top: 2px;"><span class="icon-block-xs">'.view_cover($x['handlecover'], true).'</span><a href="'.view_memory(42903,42902).$x['handlehandle'].'" style="font-weight:bold;">'.$x['handlevalue'].'</a>'.$name.$plus_info.'</td>';



    //HANDLES
    foreach($hashtag_settings['handle_column'] as $e){

        $require_writing = count($this->Chains->read(array(
            'chainhandleinput IN (' . join(',', $this->config->item('handleids___43510')) . ')' => null, //Require Written Answers
            'chainhandleoutput' => $e['handleid'],
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                )));

        $fetch_data = $this->Chains->read(array(
                    'chainhandleoutput' => $x['handleid'],
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            'chainhandleinput' => $e['handleid'],
        ));

        $message_clean = '';
        if(count($fetch_data)){
            if(strlen($fetch_data[0]['chainvalue'])){
                if(filter_var($fetch_data[0]['chainvalue'], FILTER_VALIDATE_URL)){
                    //Sheet Click to Expand
                    $message_clean = '<a '.$underdot_class.' href="'.$fetch_data[0]['chainvalue'].'" target="_blank" title="Open in a New Window">'.view_cover($e['handlecover'], '🔗️', ' ').'</a>';
                } elseif(!isset($_GET['expand']) && in_array($e['handleid'], $this->config->item('handleids___40945'))){
                    //Sheet Click to Expand
                    $message_clean = '<span class="click_2_see_'.$e['handleid'].'_'.$fetch_data[0]['chainid'].'"><a href="javascript:void(0);" onclick="$(\'.click_2_see_'.$e['handleid'].'_'.$fetch_data[0]['chainid'].'\').toggleClass(\'hidden\')" '.$underdot_class.' title="'.$fetch_data[0]['chainvalue'].' [Click to Expand]">'.view_cover($e['handlecover'], '✔️', ' ').'</a></span><span class="click_2_see_'.$e['handleid'].'_'.$fetch_data[0]['chainid'].' hidden">'.$fetch_data[0]['chainvalue'].'</span>';
                } elseif(isset($_GET['expand']) || $require_writing){
                    $message_clean = $fetch_data[0]['chainvalue'];
                } else {
                    $message_clean = '<span '.$underdot_class.' title="'.$fetch_data[0]['chainvalue'].'">'.view_cover($e['handlecover'], '✔️', ' ').'</span>';
                }
            } else {
                $message_clean = '<span class="icon-block-xs">'.view_cover($e['handlecover'], '✔️', ' ').'</span>';
            }
        }


        $body_content .= '<td title="'.$x['handlevalue'].' x '.$e['handlevalue'].'" class="'.( handle_session(10939) && !in_array($e['handleid'], $this->config->item('handleids___37695')) ? 'editable chainhandlecreator_'.$e['handleid'].'_'.$x['handleid'] : '' ).'" hashtagid="0" handleid="'.$e['handleid'].'" chainhandlecreator="'.$x['handleid'].'" require_writing="'.( $require_writing ? 1 : 0 ).'" chainid="'.$x['chainid'].'"><div class="limit_height">'.$message_clean.'</div></td>';

        if(strlen($message_clean)>0){

            if(!isset($count_totals['e'][$e['handleid']])){
                $count_totals['e'][$e['handleid']] = 0;
            }

            $count_totals['e'][$e['handleid']] = $count_totals['e'][$e['handleid']] + ( count($this->Chains->read(array(
                                    'chainhandleoutput' => $e['handleid'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                    'chainhandleinput IN (' . join(',', $this->config->item('handleids___39609')) . ')' => null, //ADDUP NUMBER
                ))) ? doubleval(preg_replace('/[^0-9.-]+/', '', $fetch_data[0]['chainvalue'])) : 1 );
        }
    }


    $body_content .= $hashtag_content;

    $body_content .= '</tr>';
    $count++;

}


$table_sortable = array('#th_primary','#th_done');
echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

echo '<tr style="font-weight:bold; vertical-align: baseline;">';
echo '<th id="th_primary" style="width:200px;">'.$count.' Handles</th>';
foreach($hashtag_settings['handle_column'] as $e){
    array_push($table_sortable, '#thhandle_'.$e['handleid']);
    echo '<th id="thhandle_'.$e['handleid'].'"><a class="icon-block-xs" href="'.view_memory(42903,42902).$e['handlehandle'].'" target="_blank" title="Open in New Window">'.view_cover($e['handlecover'], '✔️', ' ').'</a><span class="vertical_col"><span class="col_stat">'.( isset($count_totals['e'][$e['handleid']]) ? str_replace('.00','',number_format($count_totals['e'][$e['handleid']], 2)) : '0' ).'</span><i class="far fa-sort"></i>'.$e['handlevalue'].'</span></th>';
}
foreach($hashtag_settings['hashtag_column'] as $hashtag_var){

    $max_available = $this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42991')) . ')' => null, //Active Writes
        'chainhashtagoutput' => $hashtag_var['hashtagid'],
        'chainhandleinput' => 26189,
    ), array(), 1);
    $current_x = ( isset($count_totals['i'][$hashtag_var['hashtagid']]) ? $count_totals['i'][$hashtag_var['hashtagid']] : 0 );
    $max_limit = (count($max_available) && is_numeric($max_available[0]['chainvalue']) && intval($max_available[0]['chainvalue'])>0 ? intval($max_available[0]['chainvalue']) : 0 );

    array_push($table_sortable, '#th_hashtag_'.$hashtag_var['hashtagid']);

    echo '<th id="th_hashtag_'.$hashtag_var['hashtagid'].'"><div></div><a class="icon-block-xs" href="'.view_memory(42903,33286).$hashtag_var['hashtaghashtag'].'" target="_blank" title="Open in New Window">'.$handles___4737[$hashtag_var['hashtagtype']]['m__cover'].'</a><span class="vertical_col"><span class="col_stat '.( $max_limit ? ( $current_x>=$max_limit ? ''  : ( ($current_x/$max_limit)>=0.5 ? 'isgold' : 'isred' ) ) : '' ).'">'.$current_x.( $max_limit ? '/'.$max_limit : '').'</span><i class="far fa-sort"></i>'.( strlen($hashtag_var['chainvalue']) ? $hashtag_var['chainvalue'] : view_hashtag_title($hashtag_var, true) ).'</span></th>';

}
echo '</tr>';
echo $body_content;
echo '</table>';

?>



<style>

    <?php if(!isset($_GET['expand'])){ echo ' #sortable_table td{ max-width: 89px !important; max-height: 89px !important; overflow: scroll; } '; } else { echo ' #sortable_table td{ font-size:1em !important; } '; } ?>


    <?php if(count($hashtag_settings['list_config'][34513])){ echo ' .container{ margin-left: 8px; max-width: calc(100% - 16px) !important; } '; } ?>

    .mini-header,
    #sortable_table td>p{
        display: block;
        max-width: 144px !important;
        max-height: 179px !important;
        overflow: scroll;
    }

    #sortable_table a {
        text-decoration: underline !important;
    }

    .maxwidth {
        max-width: 1200px !important;
    }

    /* CSS Adjustments for Printing View */
    #sortable_table .table-striped tr:nth-of-type(odd) td {
        background-color: #FFFFFF !important;
        -webkit-print-color-adjust:exact;
    }
    #sortable_table .table-striped td {
        border-bottom: 1px dotted #000000 !important;
        font-size: 1.15em;
    }
    .fa-filter, .fa-sort{
        font-size: 1.01em !important;
        margin-bottom: 3px;
    }
    #sortable_table th{
        cursor: ns-resize !important;
    }
    #sortable_table th, #sortable_table td{
        border: 1px solid #999999 !important;
    }

    #sortable_table th:hover, #sortable_table th:active{
        background-color: #FFF;
    }

    #sortable_table .body_tr:hover {
        background-color: #CCC;
    }
    #sortable_table .body_tr .editable:hover {
        background-color: #f5d981;
        cursor: pointer;
    }



    .vertical_col {
        writing-mode: tb-rl;
        white-space: nowrap;
        display: block;
        padding-bottom: 8px;
    }
    .col_stat{
        height:71px;
        display:inline-block;
        text-align: left;
        width: 8px;
    }


</style>
<script>

    $(document).ready(function () {

        $('.editable').click(function (e) {

            var require_writing = parseInt($(this).attr('require_writing'));
            var written_answer = '';
            if(require_writing){

                //return handle_editor(handleid = 0, chainid = 0, $(this).attr('title'), $('.chainhandlecreator_' + $(this).attr('handleid') + '_' + $(this).attr('chainhandlecreator')).text());

                written_answer = prompt($(this).attr('title') + ":", $('.chainhandlecreator_' + $(this).attr('handleid') + '_' + $(this).attr('chainhandlecreator')).text());
                if(written_answer == null){
                    return false;
                }
            }

            var modify_data = {
                hashtagid: $(this).attr('hashtagid'),
                handleid: $(this).attr('handleid'),
                chainhandlecreator: $(this).attr('chainhandlecreator'),
                chainid: $(this).attr('chainid'),
                require_writing: require_writing,
                written_answer: written_answer,
                js_request_uri: js_request_uri, //Always append to AJAX Calls
            };

            $('.chainhandlecreator_' + modify_data['handleid'] + '_' + modify_data['chainhandlecreator']).html('<i class="fas fa-yin-yang fa-spin"></i>');

            //Check email and validate:
            $.post("/controller/handle_toggle_follow", modify_data, function (data) {

                if (data.status) {

                    //Update Handle id IF existed previously:
                    $('.chainhandlecreator_' + modify_data['handleid'] + '_' + modify_data['chainhandlecreator']).html(data.message);

                } else {
                    alert('ERROR:' + data.message);
                }
            });

        });

        var table = $('#sortable_table');
        $('<?= join(', ', $table_sortable) ?>')
            .each(function(){

                var th = $(this),
                    thIndex = th.index(),
                    inverse = false;

                th.click(function(){

                    table.find('td').filter(function(){

                        return $(this).index() === thIndex;

                    }).sortElements(function(a, b){

                        return $.text([a]) < $.text([b]) ?
                            inverse ? -1 : 1
                            : inverse ? 1 : -1;

                    }, function(){

                        return this.parentNode;

                    });

                    inverse = !inverse;

                });

            });
    });
</script>
