<?php

$session_e = superpower_assigned();
$first_segment = $this->uri->segment(1);
$is_home = !strlen($first_segment);
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$current_mench = current_mench();
$is_ideator = superpower_assigned(10939);

?><!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/img/<?= ( !$first_segment && !$session_e ? 'mench' : $current_mench['x_name'] ) ?>.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= ( isset($title) ? $title : '' ) ?></title>


    <script type="text/javascript">
    <?php
    //PLAYER VARIABLES
    echo ' var js_session_superpowers_assigned = ' . json_encode( ($session_e && count($this->session->userdata('session_superpowers_assigned'))) ? $this->session->userdata('session_superpowers_assigned') : array() ) . '; ';
    echo ' var js_pl_id = ' . ( $session_e ? $session_e['e__id'] : 0 ) . '; ';
    echo ' var js_pl_name = \'' . ( $session_e ? $session_e['e__title'] : '' ) . '\'; ';
    echo ' var base_url = \'' . $this->config->item('base_url') . '\'; ';

    //JAVASCRIPT PLATFORM MEMORY
    foreach($this->config->item('e___11054') as $x__type => $m){
        if(count($this->config->item('e___'.$x__type))){
            echo ' var js_e___'.$x__type.' = ' . json_encode($this->config->item('e___'.$x__type)) . ';';
            echo ' var js_n___'.$x__type.' = ' . json_encode($this->config->item('n___'.$x__type)) . ';';
        }
    }
    ?>
    </script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/typeit@6.1.1/dist/typeit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/autosize@4.0.2/dist/autosize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@15.1.1/dist/lazyload.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.37.0/autocomplete.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/algoliasearch/3.35.1/algoliasearch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.1/Sortable.min.js" type="text/javascript"></script>

    <script src="/application/views/global.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

    <?php if($current_mench['x_name']=='discover'){ ?>
    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5ec369bdaa9dfe001ab3f797&product=custom-share-buttons&cms=website' async='async'></script>
    <?php } ?>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800|Roboto+Mono:wght@500|Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-IIED/eyOkM6ihtOiQsX2zizxFBphgnv1zbe1bKA+njdFzkr6cDNy16jfIKWu4FNH" crossorigin="anonymous">
    <link href="/application/views/global.css?v=<?= config_var(11060) ?>" rel="stylesheet"/>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92774608-1"></script>
    <script type="text/javascript">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-92774608-1');
    </script>

</head>

<body id="<?= 'font_size_'.$this->session->userdata('session_var_13491') ?>" class="<?= 'to'.$current_mench['x_name'] ?>">

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



if(!isset($hide_header)){
    //Do not show for /sign view
    ?>

    <!-- MENCH LINE -->
    <div class="container fixed-top" style="padding-bottom: 0 !important;">
        <div class="row">
            <table class="mench-navigation">
                <tr>
                    <?php

                    //MENCH HEADER
                    foreach($this->config->item('e___13357') as $x__type => $m) {

                        if ($x__type==2738){

                            //OPEN LEFT NAVIGATION:
                            echo '<td><div class="mench_nav left_nav">';

                            //MENCH LOGO
                            if(!$is_ideator){
                                //Link to Discoveries:
                                echo '<span class="inline-block pull-left"><a href="/"><img src="/img/mench.png" class="mench-logo mench-spin" /><b class="montserrat text-logo">MENCH</b></a></span>';
                            }

                        } elseif(in_array($x__type, $this->config->item('n___12467')) && $is_ideator){

                            //Mench Coins
                            if($x__type==12274){
                                $page_url = 'href="/@'.$session_e['e__id'].'"';
                            } elseif($x__type==12273){
                                $page_url = 'href="/~'.( is_numeric($first_segment) && e_owns_i($first_segment) ? $first_segment : '' ).'"';
                            } elseif($x__type==6255){
                                $page_url = 'href="/"';
                            }

                            $class = trim(extract_icon_color($m['m_icon']));
                            $is_active = ($current_mench['x_name']==$class);
                            echo '<div class="btn-group pull-left mench_coin '.$class.' border-' . $class.( $is_active ? ' active ' : '' ).'">';
                            echo '<a class="btn ' . $class . '" '.$page_url.'>';
                            echo '<span class="icon-block">' . $m['m_icon'] . '</span>';
                            echo view_number($this->config->item('s___'.$x__type)).' ';
                            echo '<span class="montserrat ' . $class . '_name show-max">' . $m['m_name'] . '</span>';
                            echo '</a>';
                            echo '</div>';

                        } elseif($x__type==7256){

                            //SEARCH

                            //CLOSE LEFT NAVIGATION:
                            echo '</div>';

                            //Search Bar
                            echo '<div class="left_nav search_nav hidden"><form id="searchFrontForm"><input class="form-control algolia_search" type="search" id="mench_search" data-lpignore="true" placeholder="'.$m['m_name'].'"></form></div>';

                            echo '</td>';

                            if(intval(config_var(12678))){

                                //Search button
                                echo '<td class="block-link"><a href="javascript:void(0);" onclick="toggle_search()" style="margin-left: 0;"><span class="search_icon">'.$m['m_icon'].'</span><span class="search_icon hidden" title="'.$e___11035[13401]['m_name'].'">'.$e___11035[13401]['m_icon'].'</span></a></td>';
                            }

                        } elseif($x__type==13479){

                            //MEMBER NAVIGATION:
                            $e___13479 = $this->config->item('e___13479');

                            if (!$session_e) {

                                //GUESTS

                                //FEEDBACK SUPPORT
                                //echo '<td class="block-link"><a class="icon_12899" href="javascript:void(0);" title="'.$e___11035[12899]['m_name'].'">'.$e___11035[12899]['m_icon'].'</a></td>';

                                //Sign In/Up
                                //<span class="show-max">'.$e___13479[4269]['m_name'].'&nbsp;</span>
                                //block-sign-link
                                echo '<td class="block-link"><a href="/e/signin" class="montserrat">'.$e___13479[4269]['m_icon'].'</a></td>';

                            } else {

                                //MEMBERS

                                $e___10876 = $this->config->item('e___10876'); //Mench Website
                                $load_menu = 12500;

                                echo '<td class="block-menu">';
                                echo '<div class="dropdown inline-block">';
                                echo '<button type="button" class="btn no-side-padding" id="dropdownMenuButton'.$load_menu.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                echo '<span class="icon-block">' .$e___13479[$load_menu]['m_icon'].'</span>';
                                echo '</button>';

                                echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$load_menu.'">';
                                foreach($this->config->item('e___'.$load_menu) as $x__type2 => $m2) {

                                    //Skip superpowers if not assigned
                                    if($x__type2==10957 && !count($this->session->userdata('session_superpowers_assigned'))){
                                        continue;
                                    } elseif($x__type2==6415 && !$is_home){
                                        //Deleting discovers only available on Discoveries home
                                        continue;
                                    }

                                    $superpower_actives = array_intersect($this->config->item('n___10957'), $m2['m_parents']);
                                    $extra_class = null;
                                    $text_class = null;

                                    if(in_array($x__type2, $this->config->item('n___10876'))){

                                        //Fetch URL:
                                        $page_url = 'href="'.$e___10876[$x__type2]['m_desc'].'"';

                                    } elseif($x__type2==13449) {

                                        //SET SOURCE TO PLAYER
                                        $x__type2 = $session_e['e__id'];
                                        $page_url = 'href="/@'.$x__type2.'"';
                                        $m2['m_name'] = $session_e['e__title'];
                                        $m2['m_icon'] = $session_e['e__icon'];
                                        $text_class = 'text__6197_'.$x__type2;

                                    } elseif($x__type2==12899) {

                                        //FEEDBACK SUPPORT
                                        $page_url = 'href="javascript:void(0);"';
                                        $extra_class = ' icon_12899 ';

                                    } elseif($x__type2==6415) {

                                        //CLEAR DISCOVERIES
                                        $page_url = 'href="javascript:void(0)" onclick="$(\'.clear-xy-list\').toggleClass(\'hidden\')"';

                                    } else {

                                        continue;

                                    }

                                    //Navigation
                                    echo '<a '.$page_url.' class="dropdown-item montserrat doupper '.extract_icon_color($m2['m_icon']).( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).$extra_class.'"><span class="icon-block">'.$m2['m_icon'].'</span><span class="'.$text_class.'">'.$m2['m_name'].'</span></a>';

                                }

                                echo '</div>';
                                echo '</div>';
                                echo '</td>';

                            }
                        }
                    }
                    ?>
                </tr>
            </table>
        </div>
    </div>

<?php } ?>