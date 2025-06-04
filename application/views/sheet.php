<?php

if (!isset($_GET['ideahashtag'])) {
    die('Missing Idea ID ideahashtag');
}

//Sheet
$sources___11035 = $this->config->item('sources___11035'); //Encyclopedia

$recursive_idea_ids = array();
$is_with_action_es = array();
$es_added = array();
$count = 0;
$body_content = '';
$count_totals = array(
    'e' => array(),
    'i' => array(),
);

//Generate list & settings:
$idea_settings = idea_settings($_GET['ideahashtag']);
$max_limit = view_memory(6404,11064);


if ((count($idea_settings['source_column']) + count($idea_settings['idea_column'])) > $max_limit) {

    echo '<div class="alert alert-warning" role="alert">You cannot have more than ' . $max_limit . ' active columns</div>';

} else {

    echo '<h1>' . view_idea_title($idea_settings['i']) . '</h1>';
    echo '<div class="center-frame hide-subline maxwidth hideIfEmpty remove_first_line">' . view_idea_value($idea_settings['i'], (isset($source_session['sourceid']) ? $source_session['sourceid'] : 0)) . '</div>';

    foreach ($idea_settings['query_string_filtered'] as $x) {

        $body_content .= '<tr class="body_tr">';

        //IDEAS
        $idea_content = '';
        $this_quantity = 1;
        foreach ($idea_settings['idea_column'] as $idea_var) {

            $discoveries = $this->Chains->read(array(
                'chainidealeft' => $idea_var['ideaid'],
                'chainsourcecreator' => $x['sourceid'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            ), array(), 1);

            $idea_content .= '<td title="' . $x['sourcevalue'] . ' x ' . view_idea_title($idea_var, true) . '">';

            if (count($discoveries)) {

                if ($this_quantity < 2 && intval($discoveries[0]['chainkey']) >= 2) {
                    $this_quantity = $discoveries[0]['chainkey'];
                }

                $set_chainvalue = '';
                foreach ($this->Chains->read(array(
                    'chainsourcetype' => 33532, //Private Reply
                    'chainidealeft' => $idea_var['ideaid'],
                    'chainsourcecreator' => $x['sourceid'],
                ), array('chainidearight'), 0, 1, array('chainid' => 'DESC')) as $response) {
                    $set_chainvalue = $response['ideavalue'];
                }

                if ($set_chainvalue) {

                    $idea_content .= (isset($_GET['expand']) ? '<p data-placement="top">' . $set_chainvalue . '</p>' : '<span title="' . $set_chainvalue . ' [' . $discoveries[0]['chaintime'] . ']">ℹ️️</span>');

                } elseif (strlen($discoveries[0]['chainvalue']) > 0) {

                    $idea_content .= (isset($_GET['expand']) ? '<p data-placement="top" title="' . $discoveries[0]['chainvalue'] . '">' . $discoveries[0]['chainvalue'] . '</p>' : '<span title="' . view_idea_title($idea_var, true) . ': ' . $discoveries[0]['chainvalue'] . ' [' . $discoveries[0]['chaintime'] . ']">ℹ️️</span>');

                } else {
                    $idea_content .= '<span title="' . view_idea_title($idea_var, true) . ' [' . $discoveries[0]['chaintime'] . ']">✔️</span>';
                }

            }

            $idea_content .= '</td>';


            if (count($discoveries) && (!count($idea_var['must_follow']) || count($idea_var['must_follow']) != count($this->Chains->read(array(
                        'chainsourcedown' => $x['sourceid'],
                        'chainsourceup IN (' . join(',', $idea_var['must_follow']) . ')' => null,
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                    ))))) {
                if (!isset($count_totals['i'][$idea_var['ideaid']])) {
                    $count_totals['i'][$idea_var['ideaid']] = 0;
                }
                $count_totals['i'][$idea_var['ideaid']]++;
            }

        }

        $this_quantity = $this_quantity - 1;


        $body_content .= '<td style="padding-top: 2px;"><span class="icon-block-xs">' . view_cover($x['sourcecover'], true) . '</span><a href="' . view_memory(42903, 42902) . $x['sourcehandle'] . '" style="font-weight:bold;">' . $x['sourcevalue'] . '</a>' . ($this_quantity > 0 ? ' +' . $this_quantity : '') . '</td>';


        //SOURCES
        foreach ($idea_settings['source_column'] as $e) {

            $require_writing = count($this->Chains->read(array(
                'chainsourceup IN (' . join(',', $this->config->item('sourceids___43510')) . ')' => null, //Require Written Answers
                'chainsourcedown' => $e['sourceid'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            )));

            $fetch_data = $this->Chains->read(array(
                'chainsourcedown' => $x['sourceid'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                'chainsourceup' => $e['sourceid'],
            ));

            $message_clean = '';
            $view_cover =  view_cover($e['sourcecover'], '✔️', ' ');

            if (count($fetch_data)) {
                if (strlen($fetch_data[0]['chainvalue'])) {
                    if (filter_var($fetch_data[0]['chainvalue'], FILTER_VALIDATE_URL)) {
                        //Sheet Click to Expand
                        $message_clean = '<a href="' . $fetch_data[0]['chainvalue'] . '" target="_blank" title="Open in a New Window">' . view_cover($e['sourcecover'], '🔗️', ' ') . '</a>';
                    } elseif (!isset($_GET['expand']) && in_array($e['sourceid'], $this->config->item('sourceids___40945'))) {
                        //Sheet Click to Expand
                        $message_clean = '<span class="click_2_see_' . $e['sourceid'] . '_' . $fetch_data[0]['chainid'] . '"><a href="javascript:void(0);" onclick="$(\'.click_2_see_' . $e['sourceid'] . '_' . $fetch_data[0]['chainid'] . '\').toggleClass(\'hidden\')" title="' . $fetch_data[0]['chainvalue'] . ' [Click to Expand]">' . $view_cover . '</a></span><span class="click_2_see_' . $e['sourceid'] . '_' . $fetch_data[0]['chainid'] . ' hidden">' . $fetch_data[0]['chainvalue'] . '</span>';
                    } elseif (isset($_GET['expand']) || $require_writing) {
                        $message_clean = $fetch_data[0]['chainvalue'];
                    } else {
                        $message_clean = '<span title="' . $fetch_data[0]['chainvalue'] . '">' . $view_cover . '</span>';
                    }
                } else {
                    $message_clean = '<span class="icon-block-xs">' .$view_cover. '</span>';
                }
            }


            if ($e['sourceid'] == 44328) {
                //Fetch primary filter:
                foreach ($this->Chains->read(array(
                    'chainidealeft' => $focus_i['ideaid'],
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___44344')) . ')' => null, //Idea Filter Additions
                ), array('chainidearight'), 1) as $target_i) {
                    //See History for this user:
                    $message_clean = '<a href="' . view_app_chain(44328) . '/' . $target_i['ideahashtag'] . '@' . $x['sourcehandle'] . '" target="_blank" title="' . $sources___11035[44328]['m__title'] . '"><span class="icon-block-sm">' . $sources___11035[44328]['m__cover'] . '</span></a>';
                }
            }

            $body_content .= '<td title="' . $x['sourcevalue'] . ' x ' . $e['sourcevalue'] . '" class="' . (source_session(10939) && !in_array($e['sourceid'], $this->config->item('sourceids___37695')) ? 'editable chainsourcecreator_' . $e['sourceid'] . '_' . $x['sourceid'] : '') . '" ideaid="0" sourceid="' . $e['sourceid'] . '" chainsourcecreator="' . $x['sourceid'] . '" require_writing="' . ($require_writing ? 1 : 0) . '" chainid="' . $x['chainid'] . '"><div class="limit_height">' . $message_clean . '</div></td>'; //<div class="showonhover">'.( !$message_clean ? $view_cover : '' ).'</div>

            if (strlen($message_clean) > 0) {

                if (!isset($count_totals['e'][$e['sourceid']])) {
                    $count_totals['e'][$e['sourceid']] = 0;
                }

                $count_totals['e'][$e['sourceid']] = $count_totals['e'][$e['sourceid']] + (count($this->Chains->read(array(
                        'chainsourcedown' => $e['sourceid'],
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                        'chainsourceup IN (' . join(',', $this->config->item('sourceids___39609')) . ')' => null, //ADDUP NUMBER
                    ))) ? doubleval(preg_replace('/[^0-9.-]+/', '', $fetch_data[0]['chainvalue'])) : 1);
            }
        }


        $body_content .= $idea_content;

        $body_content .= '</tr>';
        $count++;

    }


    $table_sortable = array('#th_primary');
    echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

    echo '<tr style="font-weight:bold; vertical-align: baseline;">';
    echo '<th id="th_primary" style="width:200px;">' . $count . ' Sources</th>';

    foreach ($idea_settings['source_column'] as $e) {
        array_push($table_sortable, '#thsource_' . $e['sourceid']);
        echo '<th id="thsource_' . $e['sourceid'] . '" title="'.number_format($count_totals['e'][$e['sourceid']], 2).'"><a class="icon-block-xs" href="' . view_memory(42903, 42902) . $e['sourcehandle'] . '" target="_blank" title="Open in New Window">' . (isset($count_totals['e'][$e['sourceid']]) ? view_number($count_totals['e'][$e['sourceid']]) : '0') . '</a><span class="vertical_col">' . view_cover($e['sourcecover'], '✔️', ' ') . $e['sourcevalue'] . '</span></th>';
    }

    foreach ($idea_settings['idea_column'] as $idea_var) {

        $max_available = $this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $idea_var['ideaid'],
            'chainsourceup' => 26189,
        ), array(), 1);
        $current_x = (isset($count_totals['i'][$idea_var['ideaid']]) ? $count_totals['i'][$idea_var['ideaid']] : 0);
        $max_limit = (count($max_available) && is_numeric($max_available[0]['chainvalue']) && intval($max_available[0]['chainvalue']) > 0 ? intval($max_available[0]['chainvalue']) : 0);

        array_push($table_sortable, '#th_idea_' . $idea_var['ideaid']);

        echo '<th id="th_idea_' . $idea_var['ideaid'] . '"><a class="icon-block-xs" href="' . view_memory(42903, 33286) . $idea_var['ideahashtag'] . '" target="_blank" title="Open in New Window" ' . ($max_limit ? ($current_x >= $max_limit ? '' : (($current_x / $max_limit) >= 0.5 ? 'isgold' : 'isred')) : '') . '">' . $current_x . ($max_limit ? '/' . $max_limit : '') . '</a><span class="vertical_col">' . (strlen($idea_var['chainvalue']) ? $idea_var['chainvalue'] : view_idea_title($idea_var, true)) . '</span></th>';

    }
    echo '</tr>';
    echo $body_content;
    echo '</table>';

    ?>


    <style>

        <?php if(!isset($_GET['expand'])){ echo ' #sortable_table td{ max-width: 89px !important; max-height: 89px !important; overflow: scroll; } '; } else { echo ' #sortable_table td{ font-size:1em !important; } '; } ?>


        <?php if(isset($idea_settings['list_config'][34513]) && count($idea_settings['list_config'][34513])){ echo ' .container{ margin-left: 8px; max-width: calc(100% - 16px) !important; } '; } ?>

        .mini-header,
        #sortable_table td > p {
            display: block;
            max-width: 144px !important;
            max-height: 179px !important;
            overflow: scroll;
        }

        .maxwidth {
            max-width: 1200px !important;
        }

        /* CSS Adjustments for Printing View */
        #sortable_table .table-striped tr:nth-of-type(odd) td {
            background-color: #FFFFFF !important;
            -webkit-print-color-adjust: exact;
        }

        #sortable_table .table-striped td {
            border-bottom: 1px dotted #000000 !important;
            font-size: 1.15em;
        }

        .fa-filter173 {
            font-size: 1.01em !important;
            margin-bottom: 3px;
        }

        #sortable_table th {
            cursor: ns-resize !important;
        }

        #sortable_table th, #sortable_table td {
            border: 1px solid #999999 !important;
        }

        #sortable_table th:hover, #sortable_table th:active {
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

        .col_stat {
            height: 71px;
            display: inline-block;
            text-align: left;
            width: 8px;
        }

        .showonhover {
            display: none;
        }
        .editable:hover .showonhover {
            display: block;
            background-color: #f5d981;
            cursor: pointer;
            opacity: 0.67;
        }

    </style>
    <script>

        $(document).ready(function () {

            $('.editable').click(function (e) {

                var require_writing = parseInt($(this).attr('require_writing'));
                var written_answer = '';
                if (require_writing) {

                    //return source_editor(sourceid = 0, chainid = 0, $(this).attr('title'), $('.chainsourcecreator_' + $(this).attr('sourceid') + '_' + $(this).attr('chainsourcecreator')).text());

                    written_answer = prompt($(this).attr('title') + ":", $('.chainsourcecreator_' + $(this).attr('sourceid') + '_' + $(this).attr('chainsourcecreator')).text());
                    if (written_answer == null) {
                        return false;
                    }
                }

                var modify_data = {
                    ideaid: $(this).attr('ideaid'),
                    sourceid: $(this).attr('sourceid'),
                    chainsourcecreator: $(this).attr('chainsourcecreator'),
                    chainid: $(this).attr('chainid'),
                    require_writing: require_writing,
                    written_answer: written_answer,
                    js_request_uri: js_request_uri, //Always append to AJAX Calls
                };

                $('.chainsourcecreator_' + modify_data['sourceid'] + '_' + modify_data['chainsourcecreator']).html('<i class="fas fa-yin-yang fa-spin"></i>');

                //Check email and validate:
                $.post("/controller/source_toggle_follow", modify_data, function (data) {

                    if (data.status) {

                        //Update Source id IF existed previously:
                        $('.chainsourcecreator_' + modify_data['sourceid'] + '_' + modify_data['chainsourcecreator']).html(data.message);

                    } else {
                        alert('ERROR:' + data.message);
                    }
                });

            });

            var table = $('#sortable_table');
            $('<?= join(', ', $table_sortable) ?>')
                .each(function () {

                    var th = $(this),
                        thIndex = th.index(),
                        inverse = false;

                    th.click(function () {

                        table.find('td').filter(function () {

                            return $(this).index() === thIndex;

                        }).sortElements(function (a, b) {

                            return $.text([a]) < $.text([b]) ?
                                inverse ? -1 : 1
                                : inverse ? 1 : -1;

                        }, function () {

                            return this.parentNode;

                        });

                        inverse = !inverse;

                    });

                });
        });
    </script>


    <?php

}

?>