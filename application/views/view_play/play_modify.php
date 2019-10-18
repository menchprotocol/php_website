
<?php $en_all_6206 = $this->config->item('en_all_6206'); //Entity Table ?>
<?php $en_all_4341 = $this->config->item('en_all_4341'); //Link Table ?>
<?php $en_all_7368 = $this->config->item('en_all_7368'); //Trainer App ?>
<?php $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION ?>


<script>
    //Set global variables:
    var en_focus_filter = -1; //No filter, show all
    var en_focus_id = <?= $entity['en_id'] ?>;
    var en_all_4592 = <?= json_encode($this->config->item('en_all_4592')) ?>;
</script>
<style>
    .en_child_icon_<?= $entity['en_id'] ?>{ display:none; }
</style>
<script src="/js/custom/play_modify.js?v=v<?= config_value(11060) ?>"
        type="text/javascript"></script>

<div class="container">

    <?php

    echo '<div class="row">';

        //COL 1
        echo '<div class="col-lg-8 col-md-7"><h1>'.$entity['en_icon'].' '.$entity['en_name'].'</h1></div>';

        //COL 2
        echo '<div class="col-lg-4 col-md-5">';
            echo '<div class="first_title center-right">';

            echo echo_dropdown(6177, $entity['en_status_entity_id'], true);

            //Show Signout Button IF LOGGED-IN PLAYER ON OWN ACCOUNT
            if(isset($session_en['en_id']) && $session_en['en_id']==$entity['en_id']){
                echo '<a href="/play/myaccount" class="btn btn-sm btn-primary btn-five inline-block" data-toggle="tooltip" data-placement="top" title="'.$en_all_11035[6225]['m_desc'].'">'.$en_all_11035[6225]['m_icon'].' '.$en_all_11035[6225]['m_name'].'</a>';

                echo '<a href="/signout" class="btn btn-sm btn-primary btn-five inline-block" data-toggle="tooltip" data-placement="top" title="'.$en_all_11035[7291]['m_name'].'">'.$en_all_11035[7291]['m_icon'].'</a>';
            }


            //REFERENCES
            $en_count_references = en_count_references($entity['en_id']);
            if(count($en_count_references) > 0){

                $en_all_6194 = $this->config->item('en_all_6194');

                //Show this entities connections:
                $ref_count = 0;
                echo '<div class="'.require_superpower(10989 /* PEGASUS */).'">';
                foreach($en_count_references as $en_id=>$en_count){
                    echo '<a href="/read/history?any_en_id=' . $en_id . '" data-toggle="tooltip" data-placement="top" title="This entity is referenced as '.$en_all_6194[$en_id]['m_name'].' '.number_format($en_count, 0).' times">'.$en_all_6194[$en_id]['m_icon'] . ' '. echo_number($en_count).'</a>&nbsp;&nbsp;';
                    $ref_count++;
                }
                echo '</div>';
            }

            echo '</div>';
        echo '</div>';
    echo '</div>';




    $en_id = 11033;
    $tab_content = '';

    echo '<div class="row">';
    echo '<div class="col-md">';
    echo '<ul class="nav nav-pill nav-pill-sm pill menu_bar">';

    foreach ($this->config->item('en_all_'.$en_id) as $en_id2 => $m2){

        if(in_array(11040 , $m2['m_parents'])){
            //Display drop down menu:
            echo '<li class="nav-item dropdown '.require_superpower(assigned_superpower($m2['m_parents'])).'">';
            echo '<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"></a>';
            echo '<div class="dropdown-menu">';
            foreach ($this->config->item('en_all_'.$en_id2) as $en_id3 => $m3){
                echo '<a class="dropdown-item" target="_blank" href="' . $m3['m_desc'] . $entity['en_id'] . '"><span class="icon-block en-icon">'.$m3['m_icon'].'</span> '.$m3['m_name'].'</a>';
            }
            echo '</div>';
            echo '</li>';
            continue;
        }





        //Determine counter:
        $default_active = false;
        $show_tab_names = (in_array($en_id2, $this->config->item('en_ids_11084')));
        $counter = null; //Assume no counters
        $this_tab = '';




        //PLAY

        if($en_id2==11030){

            //PLAY TREE PROFILE
            $fetch_11030 = $this->READ_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
                'ln_child_entity_id' => $entity['en_id'],
            ), array('en_parent'), 0, 0, array('ln_up_order' => 'ASC'));

            $counter = count($fetch_11030);
            $default_active = true;

            $this_tab .= '<div id="list-parent" class="list-group ">';
            foreach ($fetch_11030 as $en) {
                $this_tab .= echo_en($en, 2, true);
            }

            //Input to add new parents:
            $this_tab .= '<div id="new-parent" class="en-item list-group-item '.require_superpower(10989 /* PEGASUS */).'">
                    <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search" data-lpignore="true" placeholder="Add Entity/URL"></div>
                    <div class="algolia_search_pad hidden"><span>Search existing entities, create a new entity or paste a URL...</span></div>
            </div>';

            $this_tab .= '</div>';

        } elseif($en_id2==11029){

            //PLAY TREE PROJECTS

            //COUNT TOTAL
            $child_links = $this->READ_model->ln_fetch(array(
                'ln_parent_entity_id' => $entity['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
            ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');
            $counter = $child_links[0]['en__child_count'];


            $fetch_11029 = $this->READ_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
                'ln_parent_entity_id' => $entity['en_id'],
            ), array('en_child'), config_value(11064), 0, array('ln_order' => 'ASC', 'en_name' => 'ASC'));

            $this_tab .= '<div id="list-children" class="list-group">';

            foreach ($fetch_11029 as $en) {
                $this_tab .= echo_en($en, 2, false);
            }
            if ($counter > count($fetch_11029)) {
                $this_tab .= echo_en_load_more(1, config_value(11064), $counter);
            }

            //Input to add new child:
            $this_tab .= '<div id="new-children" class="en-item list-group-item '.require_superpower(10989 /* PEGASUS */).'">
            <div class="form-group is-empty"><input type="text" class="form-control new-input algolia_search" data-lpignore="true" placeholder="Add Entity/URL"></div>
            <div class="algolia_search_pad hidden"><span>Search existing entities, create a new entity or paste a URL...</span></div>
    </div>';
            $this_tab .= '</div>';

        } elseif(in_array($en_id2, array(7347,6146))){

            //READER READS & BOOKMARKS
            $item_counters = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_'.$en_id2)) . ')' => null,
                'ln_creator_entity_id' => $entity['en_id'],
            ), array(), 1, 0, array(), 'COUNT(ln_id) as totals');

            $counter = $item_counters[0]['totals'];

        } elseif(in_array($en_id2, $this->config->item('en_ids_4485'))){

            //BLOG NOTE
            $item_counters = $this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_entity_id' => $en_id2,
                '(ln_creator_entity_id='.$entity['en_id'].' OR ln_child_entity_id='.$entity['en_id'].' OR ln_parent_entity_id='.$entity['en_id'].')' => null,
            ), array(), 1, 0, array(), 'COUNT(ln_id) as totals');

            $counter = $item_counters[0]['totals'];

        }

        //Don't show empty tabs:
        if(!is_null($counter) && $counter < 1 && !$show_tab_names){
            continue;
        }


        echo '<li class="nav-item"><a class="nav-link tab-nav-'.$en_id.' tab-head-'.$en_id2.' '.( $default_active ? ' active ' : '' ).require_superpower(assigned_superpower($m2['m_parents'])).'" href="#loadtab-'.$en_id.'-'.$en_id2.'" onclick="loadtab('.$en_id.','.$en_id2.')" data-toggle="tooltip" data-placement="top" title="'.( $show_tab_names ? '' : $m2['m_name'] ).'">'.$m2['m_icon'].( is_null($counter) ? '' : ' <span class="counter-'.$en_id2.'">'.echo_number($counter).'</span>' ).( $show_tab_names ? ' '.$m2['m_name'] : '' ).'</a></li>';


        $tab_content .= '<div class="tab-content tab-group-'.$en_id.' tab-data-'.$en_id2.( $default_active ? '' : ' hidden ' ).'">';
        $tab_content .= $this_tab;
        $tab_content .= '</div>';

    }
    echo '</ul>';
    echo $tab_content;
    echo '</div>';






    echo '</div>';

    ?>







    <div class="row">

    <div class="col-sm-6">

        <?php


        echo '<div id="entity-box" class="list-group">';
        echo echo_en($entity, 1);
        echo '</div>';



        //Children:
        echo '<table width="100%" style="margin-top:10px;"><tr>';
        echo '<td style="width:170px;">';

            echo '<span class="' . require_superpower(10989 /* PEGASUS */) . '"><a href="javascript:void(0);" onclick="$(\'.mass_modify\').toggleClass(\'hidden\');mass_action_ui();" style="text-decoration: none; margin-left: 5px;"  data-toggle="tooltip" data-placement="right" title="Mass Update Children"><i class="fal fa-list-alt" style="font-size: 1.2em; color: #2b2b2b;"></i></a></span>';

            echo '</td>';


        echo '<td style="text-align: right;">';
        echo '<div class="btn-group btn-group-sm" style="margin-top:-5px;" role="group">';

        //Fetch current count for each status from DB:
        $child_en_filters = $this->READ_model->ln_fetch(array(
            'ln_parent_entity_id' => $entity['en_id'],
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
        ), array('en_child'), 0, 0, array('en_status_entity_id' => 'ASC'), 'COUNT(en_id) as totals, en_status_entity_id', 'en_status_entity_id');


        //Only show filtering UI if we find child entities with different statuses (Otherwise no need to filter):
        if (count($child_en_filters) > 0 && $child_en_filters[0]['totals'] < $entity['en__child_count']) {

            //Load status definitions:
            $en_all_6177 = $this->config->item('en_all_6177'); //Entity Statuses

            //Show fixed All button:
            echo '<a href="#" onclick="en_filter_status(-1)" class="btn btn-default btn-play u-status-filter u-status--1" data-toggle="tooltip" data-placement="top" title="View all entities"><i class="fas fa-at"></i><span class="hide-small"> All</span> [<span class="counter-11029">' . $entity['en__child_count'] . '</span>]</a>';

            //Show each specific filter based on DB counts:
            foreach ($child_en_filters as $c_c) {
                $st = $en_all_6177[$c_c['en_status_entity_id']];
                echo '<a href="#status-' . $c_c['en_status_entity_id'] . '" onclick="en_filter_status(' . $c_c['en_status_entity_id'] . ')" class="btn btn-default u-status-filter u-status-' . $c_c['en_status_entity_id'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m_desc'] . '">' . $st['m_icon'] . '<span class="hide-small"> ' . $st['m_name'] . '</span> [<span class="count-u-status-' . $c_c['en_status_entity_id'] . '">' . $c_c['totals'] . '</span>]</a>';
            }

        }

        echo '</div>';
        echo '</td>';
        echo '</tr></table>';



        echo '<form class="mass_modify hidden" method="POST" action="" style="width: 100% !important;"><div class="inline-box">';


            $dropdown_options = '';
            $input_options = '';
            foreach ($this->config->item('en_all_4997') as $action_en_id => $mass_action_en) {

                $dropdown_options .= '<option value="' . $action_en_id . '">' .$mass_action_en['m_name'] . '</option>';


                //Start with the input wrapper:
                $input_options .= '<span id="mass_id_'.$action_en_id.'" class="inline-block hidden mass_action_item">';

                $input_options .= '<i class="fal fa-info-circle" data-toggle="tooltip" data-placement="right" title="'.$mass_action_en['m_desc'].'"></i> ';

                if(in_array($action_en_id, array(5000, 5001, 10625))){

                    //String Find and Replace:

                    //Find:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" placeholder="Search" style="width: 145px;" class="form-control border">';

                    //Replace:
                    $input_options .= '<input type="text" name="mass_value2_'.$action_en_id.'" placeholder="Replace" stycacle="width: 145px;" class="form-control border">';


                } elseif(in_array($action_en_id, array(5981, 5982))){

                    //Entity search box:

                    //String command:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" style="width:300px;" placeholder="Search entities..." class="form-control algolia_search en_quick_search border">';

                    //We don't need the second value field here:
                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';


                } elseif($action_en_id == 5003){

                    //Entity Status update:

                    //Find:
                    $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set Condition...</option>';
                    $input_options .= '<option value="*">Update All Statuses</option>';
                    foreach($this->config->item('en_all_6177') /* Entity Statuses */ as $en_id => $m){
                        $input_options .= '<option value="'.$en_id.'">Update All '.$m['m_name'].'</option>';
                    }
                    $input_options .= '</select>';

                    //Replace:
                    $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set New Status...</option>';
                    foreach($this->config->item('en_all_6177') /* Entity Statuses */ as $en_id => $m){
                        $input_options .= '<option value="'.$en_id.'">Set to '.$m['m_name'].'</option>';
                    }
                    $input_options .= '</select>';


                } elseif($action_en_id == 5865){

                    //Link Status update:

                    //Find:
                    $input_options .= '<select name="mass_value1_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set Condition...</option>';
                    $input_options .= '<option value="*">Update All Statuses</option>';
                    foreach($this->config->item('en_all_6186') /* Link Statuses */ as $en_id => $m){
                        $input_options .= '<option value="'.$en_id.'">Update All '.$m['m_name'].'</option>';
                    }
                    $input_options .= '</select>';

                    //Replace:
                    $input_options .= '<select name="mass_value2_'.$action_en_id.'" class="form-control border">';
                    $input_options .= '<option value="">Set New Status...</option>';
                    foreach($this->config->item('en_all_6186') /* Link Statuses */ as $en_id => $m){
                        $input_options .= '<option value="'.$en_id.'">Set to '.$m['m_name'].'</option>';
                    }
                    $input_options .= '</select>';


                } else {

                    //String command:
                    $input_options .= '<input type="text" name="mass_value1_'.$action_en_id.'" style="width:300px;" placeholder="String..." class="form-control border">';

                    //We don't need the second value field here:
                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_en_id.'" value="" />';

                }

                $input_options .= '</span>';

            }

            echo '<select class="form-control border inline-block" name="mass_action_en_id" id="set_mass_action">';
            echo $dropdown_options;
            echo '</select>';

            echo $input_options;

            echo '<input type="submit" value="Apply" class="btn btn-play inline-block">';

        echo '</div></form>';


        ?>
    </div>

    <div class="col-sm-6">
        <?php $this->load->view('view_play/en_modify'); ?>
    </div>

</div>

<div style="height: 50px;">&nbsp;</div>

</div>