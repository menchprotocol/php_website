<?php

$user_e = superpower_assigned();
$first_segment = $this->uri->segment(1);
$i__id = is_numeric($first_segment) && $first_segment!=config_var(12137) ? intval($first_segment) : 0;
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$e___10876 = $this->config->item('e___10876'); //Mench Website
$e___13479 = $this->config->item('e___13479');
$current_mench = current_mench();

?><!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/img/<?= ( !$first_segment ? 'mench' : $current_mench['x_name'] ) ?>.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= ( isset($title) ? $title : '' ) ?></title>


    <script type="text/javascript">
    <?php
    //USER VARIABLES
    echo ' var js_session_superpowers_assigned = ' . json_encode( ($user_e && count($this->session->userdata('session_superpowers_assigned'))) ? $this->session->userdata('session_superpowers_assigned') : array() ) . '; ';
    echo ' var js_pl_id = ' . ( isset($user_e['e__id']) ? $user_e['e__id'] : '0' ) . '; ';
    echo ' var js_pl_name = \'' . ( $user_e ? $user_e['e__title'] : '' ) . '\'; ';
    echo ' var base_url = \'' . $this->config->item('base_url') . '\'; ';

    //JAVASCRIPT PLATFORM MEMORY
    foreach($this->config->item('e___11054') as $x__type => $m){
        if(is_array($this->config->item('e___'.$x__type)) && count($this->config->item('e___'.$x__type))){
            echo ' var js_e___'.$x__type.' = ' . json_encode($this->config->item('e___'.$x__type)) . ';';
            echo ' var js_n___'.$x__type.' = ' . json_encode($this->config->item('n___'.$x__type)) . ';';
        }
    }
    ?>
    </script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/typeit@6.1.1/dist/typeit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/autosize@4.0.2/dist/autosize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@15.1.1/dist/lazyload.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.37.0/autocomplete.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/algoliasearch/3.35.1/algoliasearch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.1/Sortable.min.js" type="text/javascript"></script>

    <script src="/application/views/global.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>

    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5ec369bdaa9dfe001ab3f797&product=custom-share-buttons&cms=website' async='async'></script>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

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

<body id="<?= 'font_size_'.$this->session->userdata('session_var_13491') ?>">

<?php
//Any message we need to show here?
if (!isset($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}


if(strlen($flash_message) > 0) {

    //Delete from Flash:
    $this->session->unmark_flash('flash_message');

    echo '<div class="container '.( isset($hide_header) ? ' center-info ' : '' ).'" id="flash_message" style="padding-bottom: 0;">'.$flash_message.'</div>';
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

                    echo '<td>';

                    //MENCH LOGO
                    if ($user_e) {
                        echo '<div style="padding: 5px 0;"><a href="'.home_url().'" class="montserrat"><span class="icon-block">'.$user_e['e__icon'].'</span>'.$user_e['e__title'].'</a></div>';
                    } else {
                        echo '<div class="mench_nav left_nav"><span class="inline-block pull-left"><a href="'.home_url().'"><img src="/img/mench.png" class="mench-logo mench-spin" /><b class="montserrat text-logo">MENCH</b></a></span></div>';
                    }

                    //SEARCH BAR (initially hidden)
                    echo '<div class="left_nav search_nav hidden"><form id="searchFrontForm"><input class="form-control algolia_search" type="search" id="mench_search" data-lpignore="true" placeholder="'.$e___11035[7256]['m_title'].'"></form></div>';

                    echo '</td>';



                    if(intval(config_var(12678))){

                        //Search button
                        echo '<td class="block-x"><a href="javascript:void(0);" onclick="toggle_search()" style="margin-left: 0;"><span class="search_icon">'.$e___11035[7256]['m_icon'].'</span><span class="search_icon hidden" title="'.$e___11035[13401]['m_title'].'">'.$e___11035[13401]['m_icon'].'</span></a></td>';

                    }


                    if (!$user_e) {

                        //GUESTS

                        //FEEDBACK SUPPORT
                        //echo '<td class="block-x"><a class="icon_12899" href="javascript:void(0);" title="'.$e___11035[12899]['m_title'].'">'.$e___11035[12899]['m_icon'].'</a></td>';

                        //Sign In/Up
                        echo '<td class="block-x"><a href="'.( $i__id > 0 ? '/x/x_start/'.$i__id : '/e/signin' ).'" class="montserrat">'.$e___13479[4269]['m_icon'].'</a></td>';

                    } else {

                        //USER LOGGED-IN
                        echo '<td class="block-menu">';
                        echo '<div class="dropdown inline-block">';
                        echo '<button type="button" class="btn no-side-padding" id="dropdownMenuButton12500" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        echo '<span class="icon-block">' .$e___13479[12500]['m_icon'].'</span>';
                        echo '</button>';

                        echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton12500">';
                        foreach($this->config->item('e___12500') as $x__type => $m) {

                            //Skip superpowers if not assigned
                            if($x__type==10957 && !count($this->session->userdata('session_superpowers_assigned'))){
                                continue;
                            }

                            $superpower_actives = array_intersect($this->config->item('n___10957'), $m['m_profile']);
                            $extra_class = null;
                            $text_class = null;

                            if(in_array($x__type, $this->config->item('n___10876'))){

                                //Fetch URL:
                                $href = 'href="'.$e___10876[$x__type]['m_message'].'"';

                            } elseif($x__type==6225){

                                $m['m_icon'] = $user_e['e__icon'];
                                $m['m_title'] = $user_e['e__title'];
                                $href = 'href="/@'.$user_e['e__id'].'"';

                            } elseif($x__type==12899) {

                                //FEEDBACK SUPPORT
                                $href = 'href="javascript:void(0);"';
                                $extra_class = ' icon_12899 ';

                            } else {

                                continue;

                            }

                            //Navigation
                            echo '<a '.$href.' class="dropdown-item montserrat doupper '.extract_icon_color($m['m_icon']).( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).$extra_class.'"><span class="icon-block">'.$m['m_icon'].'</span><span class="'.$text_class.'">'.$m['m_title'].'</span></a>';

                        }

                        echo '</div>';
                        echo '</div>';
                        echo '</td>';

                    }
                    ?>
                </tr>
            </table>
        </div>
    </div>

<?php } ?>