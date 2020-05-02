<?php

$session_en = superpower_assigned();
$first_segment = $this->uri->segment(1);
$second_segment = $this->uri->segment(2);
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
$en_all_2738 = $this->config->item('en_all_2738');
$current_mench = current_mench();

?><!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/img/<?= $current_mench['x_class'] ?>.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= ( isset($title) ? $title : '' ) ?></title>


    <script type="text/javascript">
    <?php
    //PLAYER VARIABLES
    echo ' var js_session_superpowers_assigned = ' . json_encode( ($session_en && count($this->session->userdata('session_superpowers_assigned'))) ? $this->session->userdata('session_superpowers_assigned') : array() ) . '; ';
    echo ' var js_pl_id = ' . ( $session_en ? $session_en['en_id'] : 0 ) . '; ';
    echo ' var js_pl_name = \'' . ( $session_en ? $session_en['en_name'] : '' ) . '\'; ';

    //JAVASCRIPT PLATFORM MEMORY
    foreach($this->config->item('en_all_11054') as $en_id => $m){
        if(count($this->config->item('en_all_'.$en_id))){
            echo ' var js_en_all_'.$en_id.' = ' . json_encode($this->config->item('en_all_'.$en_id)) . ';';
            echo ' var js_en_ids_'.$en_id.' = ' . json_encode($this->config->item('en_ids_'.$en_id)) . ';';
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

    <script src="/application/views/mench.js?v=<?= config_var(11060) ?>" type="text/javascript"></script>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800|Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-IIED/eyOkM6ihtOiQsX2zizxFBphgnv1zbe1bKA+njdFzkr6cDNy16jfIKWu4FNH" crossorigin="anonymous">
    <link href="/application/views/mench.css?v=<?= config_var(11060) ?>" rel="stylesheet"/>

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



if(!isset($hide_header)){
    //Do not show for /sign view
    ?>

    <!-- MENCH LINE -->
    <div class="container fixed-top" style="padding-bottom: 0 !important;">
        <div class="row">
            <table class="mench-navigation <?= ( !$session_en ? 'guest' : '' ) ?>">
                <tr>
                    <td>
                        <?php

                        //MAIN NAVIGATION
                        echo '<div class="main_nav mench_nav">';
                        if(!$session_en){

                            //LOGO ONLY
                            echo '<a href="/"><img src="/img/mench.png" class="mench-logo mench-spin" /><b class="montserrat text-logo">MENCH</b></a>';

                        } else {

                            //Show Mench Menu:
                            foreach($this->config->item('en_all_12893') as $en_id => $m) {

                                $is_current_mench = ( $_SERVER['REQUEST_URI'] == $m['m_desc'] || ( is_numeric($first_segment) && $en_id==7347 /* READS */ ) || ( $first_segment=='idea' && is_numeric($second_segment) && $en_id==12898 /* PUBLISH */ ) );
                                $class = extract_icon_color($m['m_icon']);


                                if($en_id==12749) {
                                    $focus_in_id = ( is_numeric($first_segment) ? $first_segment : ( !$first_segment ? config_var(12156) : 0 ) );
                                    if( $focus_in_id>0 && in_is_source($focus_in_id) ){
                                        //Contribute to Idea
                                        $m['m_desc'] = '/idea/'.$focus_in_id;
                                    } else {
                                        continue;
                                    }
                                }

                                echo '<div class="btn-group mench_coin '.$class.' border-' . $class.($is_current_mench ? ' focustab ' : '').'">';
                                echo '<a class="btn ' . $class . '" href="' . $m['m_desc'] .'">';
                                echo '<span class="icon-block">' . $m['m_icon'] . '</span>';
                                echo '<span class="montserrat ' . $class . '_name show-max">' . $m['m_name'] . '&nbsp;</span>';
                                echo '</a>';
                                echo '</div>';

                            }

                        }
                        echo '</div>';

                        //Search Bar
                        echo '<div class="main_nav search_nav hidden"><form id="searchFrontForm"><input class="form-control algolia_search" type="search" id="mench_search" data-lpignore="true" placeholder="'.$en_all_11035[7256]['m_name'].'"></form></div>';

                        ?>
                    </td>

                    <?php

                    //Account
                    if ($session_en) {

                        //Search
                        if(intval(config_var(12678))){
                            echo '<td class="block-link"><a href="javascript:void(0);" onclick="toggle_search()" style="margin-left: 0;"><span class="search_icon">'.$en_all_11035[7256]['m_icon'].'</span><span class="search_icon hidden"><i class="far fa-times"></i></span></a></td>';
                        }

                        //Player Menu
                        $en_all_4527 = $this->config->item('en_all_4527'); //Platform Memory
                        $en_all_10876 = $this->config->item('en_all_10876'); //Mench Website

                        echo '<td class="block-menu">';
                        echo '<div class="dropdown inline-block">';
                        echo '<button type="button" class="btn no-side-padding" id="dropdownMenuButton12500" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        echo '<span class="icon-block">' .$en_all_4527[12500]['m_icon'].'</span>';
                        echo '</button>';

                        echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton12500">';

                        foreach($this->config->item('en_all_12500') as $en_id => $m) {

                            //Skip superpowers if not assigned
                            if($en_id==10957 && !count($this->session->userdata('session_superpowers_assigned'))){
                                continue;
                            } elseif($en_id==6415 && !($first_segment=='read' && !$second_segment)){
                                //Deleting reads only available on Reads home
                                continue;
                            }

                            $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);


                            //Fetch URL:
                            if(in_array($en_id, $this->config->item('en_ids_10876'))){

                                $en_all_10876 = $this->config->item('en_all_10876'); //Mench Website
                                $page_url = 'href="'.$en_all_10876[$en_id]['m_desc'].'"';

                            } elseif($en_id==12205) {

                                //Profile Page:
                                $page_url = 'href="/source/'.$session_en['en_id'].'"';

                                //Update Name & Avatar:
                                $m['m_name'] = $session_en['en_name'];
                                $m['m_icon'] = $session_en['en_icon'];

                            } elseif($en_id==12899) {

                                //FEEDBACK SUPPORT
                                $page_url = 'href="javascript:void(0);" id="icon_12899"';

                            } elseif($en_id==6415) {

                                //CLEAR READS
                                $page_url = 'href="javascript:void(0)" onclick="$(\'.clear-reads-list\').toggleClass(\'hidden\')"';

                            } elseif(in_array($en_id, $this->config->item('en_ids_12467'))) {

                                $counts = count_ln_type($en_id);
                                if(!$counts){
                                    continue;
                                }

                                //HACK FOR MENCH COIN MENU
                                if($en_id==12273){
                                    //IDEA
                                    $source_field = 'ln_profile_source_id';
                                } elseif($en_id==6255){
                                    //READ
                                    $source_field = 'ln_creator_source_id';
                                } elseif($en_id==12274){
                                    //SOURCE
                                    if($counts < 2){
                                        //If 1 then only themselves, which is covered with @12205
                                        continue;
                                    }
                                    $source_field = 'ln_creator_source_id';
                                }

                                //MENCH COIN
                                $page_url = 'href="/ledger?ln_status_source_id='.join(',', $this->config->item('en_ids_7359')).'&ln_type_source_id='.join(',', $this->config->item('en_ids_'.$en_id)).'&'.$source_field.'='.$session_en['en_id'].'"';

                                //APPEND COUNT:
                                $m['m_name'] = echo_number($counts).' '.$m['m_name'];

                            } else {

                                continue;

                            }

                            //Navigation
                            echo '<a '.$page_url.' class="dropdown-item montserrat doupper '.extract_icon_color($m['m_icon']).( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</a>';

                        }

                        echo '</div>';
                        echo '</div>';
                        echo '</td>';

                    } else {

                        //FEEDBACK SUPPORT
                        echo '<td class="block-link"><a id="icon_12899" href="javascript:void(0);" title="'.$en_all_11035[12899]['m_name'].'">'.$en_all_11035[12899]['m_icon'].'</a></td>';

                        //Sign In/Up
                        echo '<td class="block-link"><a href="/source/sign" title="'.$en_all_11035[4269]['m_name'].'">'.$en_all_11035[4269]['m_icon'].'</a></td>';

                    }

                    ?>
                </tr>
            </table>
        </div>
    </div>

<?php } ?>