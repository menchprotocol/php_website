<?php

$session_en = superpower_assigned();
$current_mench = current_mench();
$first_segment = $this->uri->segment(1);
$second_segment = $this->uri->segment(2);
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH  NAVIGATION
$en_all_2738 = $this->config->item('en_all_2738');

//Arrange based on current mench:
$en_all_2738_mench = array();
$did_find = false;
$found_at = 1; //1 or 2 or 3
foreach($en_all_2738 /* Source Status */ as $en_id => $m){
    if(!$did_find){
        if($current_mench['x_id']==$en_id){
            $did_find = true;
        } else {
            $found_at++;
        }
    }

    //Did we find?
    if($did_find){
        $en_all_2738_mench[$en_id] = $m;
    }
}
if($found_at > 1){

    $append_end = 1;

    foreach($en_all_2738 /* Source Status */ as $en_id => $m){
        //Append this:
        $en_all_2738_mench[$en_id] = $m;
        $append_end++;

        //We did it all?
        if($append_end==$found_at){
            break;
        }
    }
}

?><!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/img/<?= $current_mench['x_class'] ?>.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= ( isset($title) ? $title . ' | ' : '' ) ?>MENCH</title>

    <?php
    echo '<script type="text/javascript">';

    echo ' var js_session_superpowers_assigned = ' . json_encode( ($session_en && count($this->session->userdata('session_superpowers_assigned'))) ? $this->session->userdata('session_superpowers_assigned') : array() ) . '; ';
    echo ' var js_pl_id = ' . ( $session_en ? $session_en['en_id'] : 0 ) . '; ';
    echo ' var js_pl_name = \'' . ( $session_en ? $session_en['en_name'] : 'Unknown' ) . '\'; ';

    //LOAD JS CACHE:
    foreach($this->config->item('en_all_11054') as $en_id => $m){
        if(count($this->config->item('en_all_'.$en_id))){
            echo ' var js_en_all_'.$en_id.' = ' . json_encode($this->config->item('en_all_'.$en_id)) . '; ';
        }
    }


    //Random Messages:
    echo ' var random_loading_message = ' . json_encode(echo_random_message('loading_notify', true)) . '; ';
    echo ' var random_saving_message = ' . json_encode(echo_random_message('saving_notify', true)) . '; ';

    echo '</script>';
    ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-IIED/eyOkM6ihtOiQsX2zizxFBphgnv1zbe1bKA+njdFzkr6cDNy16jfIKWu4FNH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/typeit@6.1.1/dist/typeit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/autosize@4.0.2/dist/autosize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.37.0/autocomplete.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/algoliasearch/3.35.1/algoliasearch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.1/Sortable.min.js" type="text/javascript"></script>
    <script src="/application/views/mench.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800&display=swap" rel="stylesheet">


    <link href="/application/views/mench.css?v=v<?= config_var(11060) ?>" rel="stylesheet"/>
    <script type="text/javascript">
        if(js_pl_id>0){
            //https://help.fullstory.com/hc/en-us/articles/360020623294-FS-setUserVars-Recording-custom-user-data
            FS.identify(js_pl_id, {
                displayName: js_pl_name,
                uid: js_pl_id,
                profileURL: 'https://mench.com/source/'+js_pl_id
            });
        }
    </script>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92774608-1"></script>
    <script type="text/javascript">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-92774608-1');
    </script>
</head>

<body class="<?= 'to'.$current_mench['x_class'] ?>">

<?php
//Any message we need to show here?
if (!isset($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}


if(strlen($flash_message) > 0) {

    //Delete from Flash:
    $this->session->unmark_flash('flash_message');

    echo '<div class="container '.( isset($hide_header) ? ' center-info ' : '' ).'" id="custom_message" style="padding-bottom: 0;">'.$flash_message.'</div>';
}


if(isset($custom_header)){
    echo $custom_header;
}


if(!isset($hide_header)){
    ?>

    <!-- MENCH LINE -->
    <div class="container fixed-top" style="padding-bottom: 0 !important;">
        <div class="row">
            <table class="mench-navigation">
                <tr>

                    <?php
                    //MENCH LOGO
                    if (!$session_en) {
                        echo '<td class="block-link block-logo"><a href="/"><img src="/img/mench.png" class="mench-logo mench-spin" /><b class="montserrat text-logo">MENCH</b></a></td>';
                    }
                    ?>

                    <td>

                        <?php

                        echo '<div class="main_nav mench_nav">';
                        if($session_en){

                            //Navigation Controller:
                            $coin_counts = array();
                            $nav_controller = array(
                                6205 => 12648, //READ
                                4536 => 12646, //SOURCE
                                4535 => 12647, //IDEA
                            );
                            $count_controller = array(
                                6205 => 6255, //READ
                                4536 => 12274, //SOURCE
                                4535 => 12273, //IDEA
                            );

                            //Show Mench Menu:
                            foreach ($en_all_2738_mench as $en_id => $m) {


                                $url_extension = null;
                                $is_current_mench = ($current_mench['x_id'] == $en_id);
                                $this_mench = current_mench(strtolower($m['m_name']));
                                $primary_url = 'href="/' . $this_mench['x_name'].'"';
                                $coin_counts[$en_id] = count_ln_type($count_controller[$en_id]);


                                if (!$is_current_mench && isset($in) && in_array($this_mench['x_name'], array('read', 'idea'))) {
                                    if ($current_mench['x_name'] == 'read' && $this_mench['x_name'] == 'idea' && $in['in_id']!=config_var(12156) && superpower_active(12674, true) ) {
                                        $primary_url = 'href="/idea/' . $in['in_id'].'"';
                                    } elseif ($current_mench['x_name'] == 'idea' && $this_mench['x_name'] == 'read') {
                                        $primary_url = 'href="javascript:void(0);" onclick="go_to_read('.$in['in_id'].')"';
                                    }
                                }


                                echo '<div class="btn-group mench_coin ' . $this_mench['x_class'] . ' border-' . $this_mench['x_class'].($is_current_mench ? ' focustab ' : '').'">';
                                echo '<a class="btn ' . $this_mench['x_class'] . '" ' . $primary_url . '>';
                                echo '<span class="icon-block">' . $m['m_icon'] . '</span>';
                                echo '<span class="montserrat ' . $this_mench['x_class'] . '_name '.( $is_current_mench ? '' : 'show-max' ).'">' . $m['m_name'] . '&nbsp;</span>';
                                echo '<span class="montserrat" title="'.$coin_counts[$en_id].'">'.echo_number($coin_counts[$en_id]).'</span>';
                                echo '</a>';
                                echo '</div>';

                            }
                        }
                        echo '</div>';
                        ?>


                        <div class="main_nav search_nav hidden"><form id="searchFrontForm" style="margin-top:5px;"><input class="form-control algolia_search" type="search" id="mench_search" data-lpignore="true" placeholder="<?= $en_all_11035[7256]['m_name'] ?>"></form></div>

                    </td>

                    <?php

                    //Search
                    if(intval(config_var(12678))){
                        echo '<td class="block-link '.( isset($basic_header) ? ' hidden ' : '' ).'"><a href="javascript:void(0);" onclick="toggle_search()"><span class="search_icon">'.$en_all_11035[7256]['m_icon'].'</span><span class="search_icon hidden"><i class="far fa-times"></i></span></a></td>';
                    }

                    //Account
                    if ($session_en) {

                        //Player Menu
                        $en_all_this = $this->config->item('en_all_12500');
                        $en_all_4527 = $this->config->item('en_all_4527'); //Platform Memory
                        $en_all_10876 = $this->config->item('en_all_10876'); //Mench Website

                        echo '<td class="block-menu">';
                        echo '<div class="dropdown inline-block">';
                        echo '<button type="button" class="btn no-side-padding" id="dropdownMenuButton12500" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        echo '<span class="icon-block">' .$en_all_4527[12500]['m_icon'].'</span>';
                        echo '</button>';

                        echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton12500">';

                        $coin_breaker = false;
                        foreach ($en_all_this as $en_id => $m) {

                            //Skip superpowers if not assigned
                            if($en_id==10957 && !count($this->session->userdata('session_superpowers_assigned'))){
                                continue;
                            } elseif($en_id==7291 && intval($this->session->userdata('session_6196_sign'))){
                                //Messenger sign in does not allow Signout:
                                continue;
                            }

                            $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);


                            //Fetch URL:
                            if(in_array($en_id, $this->config->item('en_ids_10876'))){

                                $en_all_10876 = $this->config->item('en_all_10876'); //Mench Website
                                $page_url = $en_all_10876[$en_id]['m_desc'];

                            } elseif($en_id==12205) {

                                //Profile Page:
                                $page_url = '/source/'.$session_en['en_id'];

                            } elseif(in_array($en_id, $this->config->item('en_ids_12467'))) {

                                //HACK FOR MENCH COIN MENU
                                if($en_id==12273){
                                    //IDEA
                                    $counts = $coin_counts[4535];
                                    $source_field = 'ln_parent_source_id';
                                } elseif($en_id==6255){
                                    //READ
                                    $counts = $coin_counts[6205];
                                    $source_field = 'ln_creator_source_id';
                                } elseif($en_id==12274){
                                    //SOURCE
                                    $counts = $coin_counts[4536];
                                    $source_field = 'ln_creator_source_id';
                                }

                                if(!$counts){
                                    continue;
                                }

                                if(!$coin_breaker){
                                    $coin_breaker = true;
                                    echo '<div class="dropdown-divider"></div>';
                                }

                                //MENCH COIN
                                $page_url = '/ledger?ln_status_source_id='.join(',', $this->config->item('en_ids_7359')).'&ln_type_source_id='.join(',', $this->config->item('en_ids_'.$en_id)).'&'.$source_field.'='.$session_en['en_id'];

                                //APPEND COUNT:
                                $m['m_name'] = echo_number($counts).' '.$m['m_name'];

                            } else {

                                continue;

                            }

                            //Navigation
                            echo '<a href="'.$page_url.'" class="dropdown-item montserrat doupper '.extract_icon_color($m['m_icon']).( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</a>';

                        }

                        echo '</div>';
                        echo '</div>';
                        echo '</td>';

                    } else {

                        //Sign In/Up
                        echo '<td class="block-link '.( isset($basic_header) ? ' hidden ' : '' ).'"><a href="/source/sign" title="'.$en_all_11035[4269]['m_name'].'">'.$en_all_11035[4269]['m_icon'].'</a></td>';

                    }
                    ?>

                </tr>
            </table>
        </div>
    </div>

<?php } ?>