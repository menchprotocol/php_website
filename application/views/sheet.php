<?php

if (!isset($_GET['posthashtag'])) {
    die('Missing Post ID posthashtag');
}

//Sheet
$users___11035 = $this->config->item('users___11035'); //Encyclopedia
$recursive_post_ids = array();
$is_with_action_es = array();
$es_added = array();
$count = 0;
$body_content = '';
$count_totals = array(
    'e' => array(),
    'i' => array(),
);

//Generate list & settings:
$post_settings = post_settings($_GET['posthashtag']);
$max_limit = view_memory(6404,11064);


if ((count($post_settings['user_column']) + count($post_settings['post_column'])) > $max_limit) {

    echo '<div class="alert alert-warning" role="alert">You cannot have more than ' . $max_limit . ' active columns</div>';

} else {

    if($standalone){
        echo '<h1>' . view_post_title($post_settings['i']) . '</h1>';

        echo '<div class="hide-subline maxwidth hideIfEmpty remove_first_line">' . view_post_value($post_settings['i'], (isset($user_session['userid']) ? $user_session['userid'] : 0), false, true, true) . '</div>';
    }


    foreach ($post_settings['query_string_filtered'] as $x) {

        $body_content .= '<tr class="body_tr">';

        //POSTS
        $post_content = '';
        $this_quantity = 1;
        foreach ($post_settings['post_column'] as $post_var) {

            $discoveries = $this->Chains->read(array(
                'chainpostinput' => $post_var['postid'],
                'chainusercreator' => $x['userid'],
                'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            ), array(), 1);

            $post_content .= '<td title="' . $x['username'] . ' x ' . view_post_title($post_var, true) . '">';

            if (count($discoveries)) {

                if ($this_quantity < 2 && intval($discoveries[0]['chainkey']) >= 2) {
                    $this_quantity = $discoveries[0]['chainkey'];
                }

                $set_chainvalue = '';
                foreach ($this->Chains->read(array(
                    'chainusertype' => 4228,
                    'chainpostoutput' => $post_var['postid'],
                    'chainusercreator' => $x['userid'],
                ), array('chainpostinput'), 0, 1, array('chainid' => 'DESC')) as $response) {
                    $set_chainvalue = $response['postmessage'];
                }

                if ($set_chainvalue) {

                    $post_content .= (isset($_GET['expand']) ? '<p data-placement="top">' . $set_chainvalue . '</p>' : '<span title="' . $set_chainvalue . ' [' . $discoveries[0]['chaintime'] . ']">ℹ️️</span>');

                } elseif (strlen($discoveries[0]['chainvalue']) > 0) {

                    $post_content .= (isset($_GET['expand']) ? '<p data-placement="top" title="' . $discoveries[0]['chainvalue'] . '">' . $discoveries[0]['chainvalue'] . '</p>' : '<span title="' . view_post_title($post_var, true) . ': ' . $discoveries[0]['chainvalue'] . ' [' . $discoveries[0]['chaintime'] . ']">ℹ️️</span>');

                } else {
                    $post_content .= '<span title="' . view_post_title($post_var, true) . ' [' . $discoveries[0]['chaintime'] . ']">✔️</span>';
                }

            }

            $post_content .= '</td>';


            if (count($discoveries)) {
                if (!isset($count_totals['i'][$post_var['postid']])) {
                    $count_totals['i'][$post_var['postid']] = 0;
                }
                $count_totals['i'][$post_var['postid']]++;
            }

        }

        $this_quantity = $this_quantity - 1;

        $body_content .= '<td style="padding-top: 2px;"><span class="icon-block-xs">' . view_cover($x['usercover'], true) . '</span><a href="' . view_memory(42903, 42902) . $x['userhandle'] . '" style="font-weight:bold;">' . $x['username'] . '</a>' . ($this_quantity > 0 ? ' +' . $this_quantity : '') . '</td>';

        //USERS
        foreach ($post_settings['user_column'] as $e) {

            $require_writing = count($this->Chains->read(array(
                'chainuserinput IN (' . join(',', $this->config->item('userids___43510')) . ')' => null, //Require Written Answers
                'chainuseroutput' => $e['userid'],
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            )));

            $fetch_data = $this->Chains->read(array(
                'chainuseroutput' => $x['userid'],
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                'chainuserinput' => $e['userid'],
            ));

            $message_clean = '';
            $view_cover =  view_cover($e['usercover'], '✔️', ' ');

            if (count($fetch_data)) {
                if (strlen($fetch_data[0]['chainvalue'])) {
                    if (filter_var($fetch_data[0]['chainvalue'], FILTER_VALIDATE_URL)) {
                        //Sheet Click to Expand
                        $message_clean = '<a href="' . $fetch_data[0]['chainvalue'] . '" target="_blank" title="Open in a New Window">' . view_cover($e['usercover'], '🔗️', ' ') . '</a>';
                    } elseif (!isset($_GET['expand']) && in_array($e['userid'], $this->config->item('userids___40945'))) {
                        //Sheet Click to Expand
                        $message_clean = '<span class="click_2_see_' . $e['userid'] . '_' . $fetch_data[0]['chainid'] . '"><a href="javascript:void(0);" onclick="$(\'.click_2_see_' . $e['userid'] . '_' . $fetch_data[0]['chainid'] . '\').toggleClass(\'hidden\')" title="' . $fetch_data[0]['chainvalue'] . ' [Click to Expand]">' . $view_cover . '</a></span><span class="click_2_see_' . $e['userid'] . '_' . $fetch_data[0]['chainid'] . ' hidden">' . $fetch_data[0]['chainvalue'] . '</span>';
                    } elseif (isset($_GET['expand']) || $require_writing) {
                        $message_clean = $fetch_data[0]['chainvalue'];
                    } else {
                        $message_clean = '<span title="' . $fetch_data[0]['chainvalue'] . '">' . $view_cover . '</span>';
                    }
                } else {
                    $message_clean = '<span class="icon-block-xs">' .$view_cover. '</span>';
                }
            }


            if ($e['userid'] == 44328) {
                //Fetch primary filter:
                foreach ($this->Chains->read(array(
                    'chainpostinput' => $focus_i['postid'],
                    'chainusertype IN (' . join(',', $this->config->item('userids___44344')) . ')' => null, //Post Filter Additions
                ), array('chainpostoutput'), 1) as $target_i) {

                    //See History for this user:
                    $message_clean = '<a href="' . view_app_chain(44328) . '/' . $target_i['posthashtag'] . '@' . $x['userhandle'] . '" target="_blank" title="' . $users___11035[44328]['m__title'] . '"><span class="icon-block-sm">' . $users___11035[44328]['m__cover'] . '</span></a>';

                }
            }

            $body_content .= '<td title="' . $x['username'] . ' x ' . $e['username'] . '" class="' . (user_session(10939) && !in_array($e['userid'], $this->config->item('userids___37695')) ? 'editable chainusercreator_' . $e['userid'] . '_' . $x['userid'] : '') . '" postid="0" userid="' . $e['userid'] . '" chainusercreator="' . $x['userid'] . '" require_writing="' . ($require_writing ? 1 : 0) . '" chainid="' . $x['chainid'] . '"><div class="limit_height">' . $message_clean . '</div></td>'; //<div class="showonhover">'.( !$message_clean ? $view_cover : '' ).'</div>

            if (strlen($message_clean) > 0) {

                if (!isset($count_totals['e'][$e['userid']])) {
                    $count_totals['e'][$e['userid']] = 0;
                }

                $count_totals['e'][$e['userid']] = $count_totals['e'][$e['userid']] + (count($this->Chains->read(array(
                        'chainuseroutput' => $e['userid'],
                        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                        'chainuserinput IN (' . join(',', $this->config->item('userids___39609')) . ')' => null, //ADDUP NUMBER
                    ))) ? doubleval(preg_replace('/[^0-9.-]+/', '', $fetch_data[0]['chainvalue'])) : 1);
            }
        }



        //Also show mixed column:
        if(!count($post_settings['post_column']) && !count($post_settings['user_column'])) {

            foreach ($post_settings['mixed_column'] as $this_var) {

                if (isset($this_var['userid'])) {

                    $require_writing = count($this->Chains->read(array(
                        'chainuserinput IN (' . join(',', $this->config->item('userids___43510')) . ')' => null, //Require Written Answers
                        'chainuseroutput' => $this_var['userid'],
                        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                    )));

                    $fetch_data = $this->Chains->read(array(
                        'chainuseroutput' => $x['userid'],
                        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                        'chainuserinput' => $this_var['userid'],
                    ));

                    $message_clean = '';
                    $view_cover =  view_cover($this_var['usercover'], '✔️', ' ');

                    if (count($fetch_data)) {
                        if (strlen($fetch_data[0]['chainvalue'])) {
                            if (filter_var($fetch_data[0]['chainvalue'], FILTER_VALIDATE_URL)) {
                                //Sheet Click to Expand
                                $message_clean = '<a href="' . $fetch_data[0]['chainvalue'] . '" target="_blank" title="Open in a New Window">' . view_cover($this_var['usercover'], '🔗️', ' ') . '</a>';
                            } elseif (!isset($_GET['expand']) && in_array($this_var['userid'], $this->config->item('userids___40945'))) {
                                //Sheet Click to Expand
                                $message_clean = '<span class="click_2_see_' . $this_var['userid'] . '_' . $fetch_data[0]['chainid'] . '"><a href="javascript:void(0);" onclick="$(\'.click_2_see_' . $this_var['userid'] . '_' . $fetch_data[0]['chainid'] . '\').toggleClass(\'hidden\')" title="' . $fetch_data[0]['chainvalue'] . ' [Click to Expand]">' . $view_cover . '</a></span><span class="click_2_see_' . $this_var['userid'] . '_' . $fetch_data[0]['chainid'] . ' hidden">' . $fetch_data[0]['chainvalue'] . '</span>';
                            } elseif (isset($_GET['expand']) || $require_writing) {
                                $message_clean = $fetch_data[0]['chainvalue'];
                            } else {
                                $message_clean = '<span title="' . $fetch_data[0]['chainvalue'] . '">' . $view_cover . '</span>';
                            }
                        } else {
                            $message_clean = '<span class="icon-block-xs">' .$view_cover. '</span>';
                        }
                    }


                    if ($this_var['userid'] == 44328) {
                        //Fetch primary filter:
                        foreach ($this->Chains->read(array(
                            'chainpostinput' => $focus_i['postid'],
                            'chainusertype IN (' . join(',', $this->config->item('userids___44344')) . ')' => null, //Post Filter Additions
                        ), array('chainpostoutput'), 1) as $target_i) {

                            //See History for this user:
                            $message_clean = '<a href="' . view_app_chain(44328) . '/' . $target_i['posthashtag'] . '@' . $x['userhandle'] . '" target="_blank" title="' . $users___11035[44328]['m__title'] . '"><span class="icon-block-sm">' . $users___11035[44328]['m__cover'] . '</span></a>';

                        }
                    }

                    $body_content .= '<td title="' . $x['username'] . ' x ' . $this_var['username'] . '" class="' . (user_session(10939) && !in_array($this_var['userid'], $this->config->item('userids___37695')) ? 'editable chainusercreator_' . $this_var['userid'] . '_' . $x['userid'] : '') . '" postid="0" userid="' . $this_var['userid'] . '" chainusercreator="' . $x['userid'] . '" require_writing="' . ($require_writing ? 1 : 0) . '" chainid="' . $x['chainid'] . '"><div class="limit_height">' . $message_clean . '</div></td>'; //<div class="showonhover">'.( !$message_clean ? $view_cover : '' ).'</div>

                    if (strlen($message_clean) > 0) {

                        if (!isset($count_totals['e'][$this_var['userid']])) {
                            $count_totals['e'][$this_var['userid']] = 0;
                        }

                        $count_totals['e'][$this_var['userid']] = $count_totals['e'][$this_var['userid']] + (count($this->Chains->read(array(
                                'chainuseroutput' => $this_var['userid'],
                                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                                'chainuserinput IN (' . join(',', $this->config->item('userids___39609')) . ')' => null, //ADDUP NUMBER
                            ))) ? doubleval(preg_replace('/[^0-9.-]+/', '', $fetch_data[0]['chainvalue'])) : 1);
                    }

                } elseif (isset($this_var['postid'])) {

                    $discoveries = $this->Chains->read(array(
                        'chainpostinput' => $this_var['postid'],
                        'chainusercreator' => $x['userid'],
                        'chainusertype IN (' . join(',', $this->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                    ), array(), 1);

                    $post_content .= '<td title="' . $x['username'] . ' x ' . view_post_title($this_var, true) . '">';

                    if (count($discoveries)) {

                        if ($this_quantity < 2 && intval($discoveries[0]['chainkey']) >= 2) {
                            $this_quantity = $discoveries[0]['chainkey'];
                        }

                        $set_chainvalue = '';
                        foreach ($this->Chains->read(array(
                            'chainusertype' => 4228,
                            'chainpostoutput' => $this_var['postid'],
                            'chainusercreator' => $x['userid'],
                        ), array('chainpostinput'), 0, 1, array('chainid' => 'DESC')) as $response) {
                            $set_chainvalue = $response['postmessage'];
                        }

                        if ($set_chainvalue) {

                            $post_content .= (isset($_GET['expand']) ? '<p data-placement="top">' . $set_chainvalue . '</p>' : '<span title="' . $set_chainvalue . ' [' . $discoveries[0]['chaintime'] . ']">ℹ️️</span>');

                        } elseif (strlen($discoveries[0]['chainvalue']) > 0) {

                            $post_content .= (isset($_GET['expand']) ? '<p data-placement="top" title="' . $discoveries[0]['chainvalue'] . '">' . $discoveries[0]['chainvalue'] . '</p>' : '<span title="' . view_post_title($this_var, true) . ': ' . $discoveries[0]['chainvalue'] . ' [' . $discoveries[0]['chaintime'] . ']">ℹ️️</span>');

                        } else {
                            $post_content .= '<span title="' . view_post_title($this_var, true) . ' [' . $discoveries[0]['chaintime'] . ']">✔️</span>';
                        }

                    }

                    $post_content .= '</td>';


                    if (count($discoveries)) {
                        if (!isset($count_totals['i'][$this_var['postid']])) {
                            $count_totals['i'][$this_var['postid']] = 0;
                        }
                        $count_totals['i'][$this_var['postid']]++;
                    }

                }
            }
        }

        $body_content .= $post_content;

        $body_content .= '</tr>';
        $count++;

    }


    $table_sortable = array('#th_primary');
    echo '<table style="font-size:0.8em;" id="sortable_table" class="table table-sm table-striped image-mini">';

    echo '<tr style="font-weight:bold; vertical-align: baseline;">';
    echo '<th id="th_primary" style="width:200px;">' . $count . ' Members</th>';

    foreach ($post_settings['user_column'] as $e) {
        array_push($table_sortable, '#thuser_' . $e['userid']);
        echo '<th id="thuser_' . $e['userid'] . '" title="'.(isset($count_totals['e'][$e['userid']]) ? number_format($count_totals['e'][$e['userid']], 2) : '').'"><a class="icon-block-xs" href="' . view_memory(42903, 42902) . $e['userhandle'] . '" target="_blank" title="Open in New Window">' . (isset($count_totals['e'][$e['userid']]) ? view_number($count_totals['e'][$e['userid']]) : '0') . '</a><span class="vertical_col">' . view_cover($e['usercover'], '✔️', ' ') . $e['username'] . '</span></th>';
    }

    foreach ($post_settings['post_column'] as $post_var) {

        $max_available = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $post_var['postid'],
            'chainuserinput' => 26189,
        ), array(), 1);
        $current_x = (isset($count_totals['i'][$post_var['postid']]) ? $count_totals['i'][$post_var['postid']] : 0);
        $max_limit = (count($max_available) && is_numeric($max_available[0]['chainvalue']) && intval($max_available[0]['chainvalue']) > 0 ? intval($max_available[0]['chainvalue']) : 0);

        array_push($table_sortable, '#th_post_' . $post_var['postid']);

        echo '<th id="th_post_' . $post_var['postid'] . '"><a class="icon-block-xs" href="' . view_memory(42903, 33286) . $post_var['posthashtag'] . '" target="_blank" title="Open in New Window" ' . ($max_limit ? ($current_x >= $max_limit ? '' : (($current_x / $max_limit) >= 0.5 ? 'isgold' : 'isred')) : '') . '">' . $current_x . ($max_limit ? '/' . $max_limit : '') . '</a><span class="vertical_col">' . (strlen($post_var['chainvalue']) ? $post_var['chainvalue'] : view_post_title($post_var, true)) . '</span></th>';

    }

    if(!count($post_settings['post_column']) && !count($post_settings['user_column'])){
        foreach ($post_settings['mixed_column'] as $this_var) {
            if(isset($this_var['userid'])){
                array_push($table_sortable, '#thuser_' . $this_var['userid']);
                echo '<th id="thuser_' . $this_var['userid'] . '" title="'.(isset($count_totals['e'][$this_var['userid']]) ? number_format($count_totals['e'][$this_var['userid']], 2) : '').'"><a class="icon-block-xs" href="' . view_memory(42903, 42902) . $this_var['userhandle'] . '" target="_blank" title="Open in New Window">' . (isset($count_totals['e'][$this_var['userid']]) ? view_number($count_totals['e'][$this_var['userid']]) : '0') . '</a><span class="vertical_col">' . view_cover($this_var['usercover'], '✔️', ' ') . $this_var['username'] . '</span></th>';
            } elseif(isset($this_var['postid'])){
                $max_available = $this->Chains->read(array(
                    'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
                    'chainpostoutput' => $this_var['postid'],
                    'chainuserinput' => 26189,
                ), array(), 1);
                $current_x = (isset($count_totals['i'][$this_var['postid']]) ? $count_totals['i'][$this_var['postid']] : 0);
                $max_limit = (count($max_available) && is_numeric($max_available[0]['chainvalue']) && intval($max_available[0]['chainvalue']) > 0 ? intval($max_available[0]['chainvalue']) : 0);

                array_push($table_sortable, '#th_post_' . $this_var['postid']);

                echo '<th id="th_post_' . $this_var['postid'] . '"><a class="icon-block-xs" href="' . view_memory(42903, 33286) . $this_var['posthashtag'] . '" target="_blank" title="Open in New Window" ' . ($max_limit ? ($current_x >= $max_limit ? '' : (($current_x / $max_limit) >= 0.5 ? 'isgold' : 'isred')) : '') . '">' . $current_x . ($max_limit ? '/' . $max_limit : '') . '</a><span class="vertical_col">' . (strlen($this_var['chainvalue']) ? $this_var['chainvalue'] : view_post_title($this_var, true)) . '</span></th>';
            }
        }
    }

    echo '</tr>';
    echo $body_content;
    echo '</table>';

    ?>


    <style>

        <?php if(!isset($_GET['expand'])){ echo ' #sortable_table td{ max-width: 89px !important; max-height: 89px !important; overflow: scroll; } '; } else { echo ' #sortable_table td{ font-size:1em !important; } '; } ?>




        /* .container{ margin-left: 8px; max-width: calc(100% - 16px) !important; } */

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
            border: 1px solid #000000 !important;
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

                    //return user_editor(userid = 0, chainid = 0, $(this).attr('title'), $('.chainusercreator_' + $(this).attr('userid') + '_' + $(this).attr('chainusercreator')).text());

                    written_answer = prompt($(this).attr('title') + ":", $('.chainusercreator_' + $(this).attr('userid') + '_' + $(this).attr('chainusercreator')).text());
                    if (written_answer == null) {
                        return false;
                    }
                }

                var modify_data = {
                    postid: $(this).attr('postid'),
                    userid: $(this).attr('userid'),
                    chainusercreator: $(this).attr('chainusercreator'),
                    chainid: $(this).attr('chainid'),
                    require_writing: require_writing,
                    written_answer: written_answer,
                    js_request_uri: js_request_uri, //Always append to AJAX Calls
                };

                $('.chainusercreator_' + modify_data['userid'] + '_' + modify_data['chainusercreator']).html('<i class="fas fa-yin-yang fa-spin"></i>');

                //Check email and validate:
                $.post("/controller/user_toggle_follow", modify_data, function (data) {

                    if (data.status) {

                        //Update User id IF existed previously:
                        $('.chainusercreator_' + modify_data['userid'] + '_' + modify_data['chainusercreator']).html(data.message);

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