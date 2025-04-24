<?php

if (!isset($_GET['ideahashtag'])) {
    die('Missing Idea ID ideahashtag');
}

//Sheet
$players___11035 = $this->config->item('players___11035'); //Encyclopedia

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
$max_limit = 55;


if ((count($idea_settings['player_column']) + count($idea_settings['idea_column'])) > $max_limit) {

    echo '<div class="alert alert-warning" role="alert">You cannot have more than ' . $max_limit . ' active columns</div>';

} else {

    echo '<h1>' . view_idea_title($idea_settings['i']) . '</h1>';
    echo '<div class="center-frame hide-subline maxwidth hideIfEmpty remove_first_line">' . view_idea_chains($idea_settings['i'], (isset($player_session['playerid']) ? $player_session['playerid'] : 0)) . '</div>';

    foreach ($idea_settings['query_string_filtered'] as $x) {

        $body_content .= '<tr class="body_tr">';

        //IDEAS
        $idea_content = '';
        $this_quantity = 1;
        foreach ($idea_settings['idea_column'] as $idea_var) {

            $discoveries = $this->Chains->read(array(
                'chainidealeft' => $idea_var['ideaid'],
                'chainplayercreator' => $x['playerid'],
                'chainplayertype IN (' . join(',', $this->config->item('playerids___6255')) . ')' => null, //SUCCESSFUL DISCOVERIES
            ), array(), 1);

            $idea_content .= '<td title="' . $x['playertext'] . ' x ' . view_idea_title($idea_var, true) . '">';

            if (count($discoveries)) {

                if ($this_quantity < 2 && intval($discoveries[0]['chainnumber']) >= 2) {
                    $this_quantity = $discoveries[0]['chainnumber'];
                }

                $set_chaintext = '';
                foreach ($this->Chains->read(array(
                    'chainplayertype' => 33532, //Private Reply
                    'chainidealeft' => $idea_var['ideaid'],
                    'chainplayercreator' => $x['playerid'],
                ), array('chainidearight'), 0, 1, array('chainid' => 'DESC')) as $response) {
                    $set_chaintext = $response['ideatext'];
                }

                if ($set_chaintext) {

                    $idea_content .= (isset($_GET['expand']) ? '<p data-placement="top">' . $set_chaintext . '</p>' : '<span title="' . $set_chaintext . ' [' . $discoveries[0]['chaintime'] . ']">ℹ️️</span>');

                } elseif (strlen($discoveries[0]['chaintext']) > 0) {

                    $idea_content .= (isset($_GET['expand']) ? '<p data-placement="top" title="' . $discoveries[0]['chaintext'] . '">' . $discoveries[0]['chaintext'] . '</p>' : '<span title="' . view_idea_title($idea_var, true) . ': ' . $discoveries[0]['chaintext'] . ' [' . $discoveries[0]['chaintime'] . ']">ℹ️️</span>');

                } else {
                    $idea_content .= '<span title="' . view_idea_title($idea_var, true) . ' [' . $discoveries[0]['chaintime'] . ']">✔️</span>';
                }

            }

            $idea_content .= '</td>';


            if (count($discoveries) && (!count($idea_var['must_follow']) || count($idea_var['must_follow']) != count($this->Chains->read(array(
                        'chainplayerdown' => $x['playerid'],
                        'chainplayerup IN (' . join(',', $idea_var['must_follow']) . ')' => null,
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                    ))))) {
                if (!isset($count_totals['i'][$idea_var['ideaid']])) {
                    $count_totals['i'][$idea_var['ideaid']] = 0;
                }
                $count_totals['i'][$idea_var['ideaid']]++;
            }

        }

        $this_quantity = $this_quantity - 1;


        $body_content .= '<td style="padding-top: 2px;"><span class="icon-block-xs">' . view_cover($x['playercover'], true) . '</span><a href="' . view_memory(42903, 42902) . $x['playerhandle'] . '" style="font-weight:bold;">' . $x['playertext'] . '</a>' . ($this_quantity > 0 ? ' +' . $this_quantity : '') . '</td>';


        //SOURCES
        foreach ($idea_settings['player_column'] as $e) {

            $require_writing = count($this->Chains->read(array(
                'chainplayerup IN (' . join(',', $this->config->item('playerids___43510')) . ')' => null, //Require Written Answers
                'chainplayerdown' => $e['playerid'],
                'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
            )));

            $fetch_data = $this->Chains->read(array(
                'chainplayerdown' => $x['playerid'],
                'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                'chainplayerup' => $e['playerid'],
            ));

            $message_clean = '';
            if (count($fetch_data)) {
                if (strlen($fetch_data[0]['chaintext'])) {
                    if (filter_var($fetch_data[0]['chaintext'], FILTER_VALIDATE_URL)) {
                        //Sheet Click to Expand
                        $message_clean = '<a href="' . $fetch_data[0]['chaintext'] . '" target="_blank" title="Open in a New Window">' . view_cover($e['playercover'], '🔗️', ' ') . '</a>';
                    } elseif (!isset($_GET['expand']) && in_array($e['playerid'], $this->config->item('playerids___40945'))) {
                        //Sheet Click to Expand
                        $message_clean = '<span class="click_2_see_' . $e['playerid'] . '_' . $fetch_data[0]['chainid'] . '"><a href="javascript:void(0);" onclick="$(\'.click_2_see_' . $e['playerid'] . '_' . $fetch_data[0]['chainid'] . '\').toggleClass(\'hidden\')" title="' . $fetch_data[0]['chaintext'] . ' [Click to Expand]">' . view_cover($e['playercover'], '✔️', ' ') . '</a></span><span class="click_2_see_' . $e['playerid'] . '_' . $fetch_data[0]['chainid'] . ' hidden">' . $fetch_data[0]['chaintext'] . '</span>';
                    } elseif (isset($_GET['expand']) || $require_writing) {
                        $message_clean = $fetch_data[0]['chaintext'];
                    } else {
                        $message_clean = '<span title="' . $fetch_data[0]['chaintext'] . '">' . view_cover($e['playercover'], '✔️', ' ') . '</span>';
                    }
                } else {
                    $message_clean = '<span class="icon-block-xs">' . view_cover($e['playercover'], '✔️', ' ') . '</span>';
                }
            }


            if ($e['playerid'] == 44328) {
                //Fetch primary filter:
                foreach ($this->Chains->read(array(
                    'chainidealeft' => $focus_i['ideaid'],
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___44344')) . ')' => null, //Idea Filter Additions
                ), array('chainidearight'), 1) as $target_i) {
                    //See History for this user:
                    $message_clean = '<a href="' . view_app_chain(44328) . '/' . $target_i['ideahashtag'] . '@' . $x['playerhandle'] . '" target="_blank" title="' . $players___11035[44328]['m__title'] . '"><span class="icon-block-sm">' . $players___11035[44328]['m__cover'] . '</span></a>';
                }
            }

            $body_content .= '<td title="' . $x['playertext'] . ' x ' . $e['playertext'] . '" class="' . (player_session(10939) && !in_array($e['playerid'], $this->config->item('playerids___37695')) ? 'editable chainplayercreator_' . $e['playerid'] . '_' . $x['playerid'] : '') . '" ideaid="0" playerid="' . $e['playerid'] . '" chainplayercreator="' . $x['playerid'] . '" require_writing="' . ($require_writing ? 1 : 0) . '" chainid="' . $x['chainid'] . '"><div class="limit_height">' . $message_clean . '</div></td>';

            if (strlen($message_clean) > 0) {

                if (!isset($count_totals['e'][$e['playerid']])) {
                    $count_totals['e'][$e['playerid']] = 0;
                }

                $count_totals['e'][$e['playerid']] = $count_totals['e'][$e['playerid']] + (count($this->Chains->read(array(
                        'chainplayerdown' => $e['playerid'],
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                        'chainplayerup IN (' . join(',', $this->config->item('playerids___39609')) . ')' => null, //ADDUP NUMBER
                    ))) ? doubleval(preg_replace('/[^0-9.-]+/', '', $fetch_data[0]['chaintext'])) : 1);
            }
        }


        $body_content .= $idea_content;

        $body_content .= '</tr>';
        $count++;

    }


    $table_sortable = array('#th_primary');
    echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

    echo '<tr style="font-weight:bold; vertical-align: baseline;">';
    echo '<th id="th_primary" style="width:200px;">' . $count . ' Players</th>';

    foreach ($idea_settings['player_column'] as $e) {
        array_push($table_sortable, '#thplayer_' . $e['playerid']);
        echo '<th id="thplayer_' . $e['playerid'] . '"><a class="icon-block-xs" href="' . view_memory(42903, 42902) . $e['playerhandle'] . '" target="_blank" title="Open in New Window">' . (isset($count_totals['e'][$e['playerid']]) ? str_replace('.00', '', number_format($count_totals['e'][$e['playerid']], 2)) : '0') . '</a><span class="vertical_col">' . view_cover($e['playercover'], '✔️', ' ') . $e['playertext'] . '</span></th>';
    }

    foreach ($idea_settings['idea_column'] as $idea_var) {

        $max_available = $this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42991')) . ')' => null, //Active Writes
            'chainidearight' => $idea_var['ideaid'],
            'chainplayerup' => 26189,
        ), array(), 1);
        $current_x = (isset($count_totals['i'][$idea_var['ideaid']]) ? $count_totals['i'][$idea_var['ideaid']] : 0);
        $max_limit = (count($max_available) && is_numeric($max_available[0]['chaintext']) && intval($max_available[0]['chaintext']) > 0 ? intval($max_available[0]['chaintext']) : 0);

        array_push($table_sortable, '#th_idea_' . $idea_var['ideaid']);

        echo '<th id="th_idea_' . $idea_var['ideaid'] . '"><a class="icon-block-xs" href="' . view_memory(42903, 33286) . $idea_var['ideahashtag'] . '" target="_blank" title="Open in New Window" ' . ($max_limit ? ($current_x >= $max_limit ? '' : (($current_x / $max_limit) >= 0.5 ? 'isgold' : 'isred')) : '') . '">' . $current_x . ($max_limit ? '/' . $max_limit : '') . '</a><span class="vertical_col">' . (strlen($idea_var['chaintext']) ? $idea_var['chaintext'] : view_idea_title($idea_var, true)) . '</span></th>';

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
            background-color: #FFD961;
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

    </style>
    <script>

        $(document).ready(function () {

            $('.editable').click(function (e) {

                var require_writing = parseInt($(this).attr('require_writing'));
                var written_answer = '';
                if (require_writing) {

                    //return player_editor(playerid = 0, chainid = 0, $(this).attr('title'), $('.chainplayercreator_' + $(this).attr('playerid') + '_' + $(this).attr('chainplayercreator')).text());

                    written_answer = prompt($(this).attr('title') + ":", $('.chainplayercreator_' + $(this).attr('playerid') + '_' + $(this).attr('chainplayercreator')).text());
                    if (written_answer == null) {
                        return false;
                    }
                }

                var modify_data = {
                    ideaid: $(this).attr('ideaid'),
                    playerid: $(this).attr('playerid'),
                    chainplayercreator: $(this).attr('chainplayercreator'),
                    chainid: $(this).attr('chainid'),
                    require_writing: require_writing,
                    written_answer: written_answer,
                    js_request_uri: js_request_uri, //Always append to AJAX Calls
                };

                $('.chainplayercreator_' + modify_data['playerid'] + '_' + modify_data['chainplayercreator']).html('<i class="fas fa-yin-yang fa-spin"></i>');

                //Check email and validate:
                $.post("/controller/player_toggle_follow", modify_data, function (data) {

                    if (data.status) {

                        //Update Player id IF existed previously:
                        $('.chainplayercreator_' + modify_data['playerid'] + '_' + modify_data['chainplayercreator']).html(data.message);

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