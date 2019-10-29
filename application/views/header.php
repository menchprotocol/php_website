<?php $session_en = en_auth(); ?><!doctype html>
<html lang="en">
<head>

    <!--

    JOIN MENCH @ https://mench.com/12747

    -->

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/img/mench-v2-16.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= (isset($title) ? $title . ' | ' : '') ?>MENCH</title>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800|Open+Sans:300,400,600|Nanum+Gothic+Coding&display=swap" rel="stylesheet">

    <!--
    <link href="/css/lib/material-dashboard.css" rel="stylesheet"/>
    <link href="/css/lib/material-kit.css" rel="stylesheet"/>
    <script src="/js/lib/material.min.js" type="text/javascript"></script>
    <script src="/js/lib/material-dashboard.js" type="text/javascript"></script>
    <link href="/css/custom/styles.css?v=v<?= config_var(11060) ?>" rel="stylesheet"/>
    -->

    <link href="/css/custom/mench.css?v=v<?= config_var(11060) ?>" rel="stylesheet"/>


    <?php
    //JS DATA
    echo '<script type="text/javascript">';

    //PLAYER
    echo ' var js_assigned_superpowers_en_ids = ' . json_encode( count($this->session->userdata('assigned_superpowers_en_ids')) ? $this->session->userdata('assigned_superpowers_en_ids') : array() ) . '; ';
    echo ' var js_pl_id = ' . ( isset($session_en['en_id']) ? $session_en['en_id'] : 0 ) . '; ';

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/fbf7f3ae67.js" crossorigin="anonymous"></script>
    <script src="/js/lib/jquery.textcomplete.min.js"></script>
    <script src="/js/lib/autocomplete.jquery.min.js"></script>
    <script src="/js/lib/algoliasearch.min.js"></script>
    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
    <script src="/js/custom/global-js.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
    <script src="/js/custom/platform-js.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
</head>

<body>

<?php
//Any message we need to show here?
if (!isset($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}
if(strlen($flash_message) > 0){
    echo '<div class="container" id="custom_message">'.$flash_message.'</div>';
}
?>

    <?php if(!isset($hide_header) || !$hide_header){

        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION

        ?>



        <!-- MENCH LINE -->
        <div class="container show-while-searching fixed-bottom">
            <div class="row">
                <table class="three-menus">
                    <tr>
                        <?php
                        foreach($this->config->item('en_all_2738') as $en_id => $m){

                            $handle = strtolower($m['m_name']);
                            $play_logged_in = ($en_id==4536 && isset($session_en['en_id']));

                            echo '<td valign="bottom"><a class="'.$handle.' border-'.$handle.( $this->uri->segment(1)==$handle ? ' background-'.$handle: null ).'" href="/'.$handle.(intval($this->uri->segment(2) && isset($session_en['en_id']) && (($this->uri->segment(1)=='blog' && $en_id==6205) || ($this->uri->segment(1)=='read' && $en_id==4535))) ? '/'.$this->uri->segment(2) : '' ).'">' . $m['m_icon'] . '<span class="mn_name montserrat">' . $m['m_name'] . '</span><span class="inline-block">'.( $play_logged_in ? '' : ' <span class="current_count mono"><i class="far fa-yin-yang fa-spin"></i></span>' ).' '.( $play_logged_in ? trim(one_two_explode('',' ', $session_en['en_name'])) : '<span class="mono">'.$m['m_desc'].'</span>' ).'</span></a></td>';

                        }
                        ?>
                    </tr>
                </table>
            </div>
        </div>





        <!-- 3X NAVIGATION -->
        <div class="container show-while-searching fixed-top">
            <div class="row">
                <table class="mench-navigation">
                    <tr>

                        <?php

                        $mench_logo = '<img src="/img/mench-v2-128.png" class="mench-logo mench-spin" />';
                        echo '<td class="block-link block-logo">'. ( count($this->session->userdata('assigned_superpowers_en_ids')) ? '<a href="javascript:void(0);" onclick="$(\'.supwerpower_view\').toggleClass(\'hidden\');">'.$mench_logo.'</a>' : $mench_logo ) .'</td>';

                        ?>

                        <td>
                            <div class="supwerpower_view">

                                <span class="mench-logo montserrat search-toggle">MENCH</span>

                                <div class="search-toggle hidden"><form id="searchFrontForm"><input class="form-control algolia_search" type="search" id="mench_search" data-lpignore="true" placeholder="<?= $en_all_11035[7256]['m_name'] ?>"></form></div>

                            </div>
                            <div class="supwerpower_view hidden">

                                <?php
                                if(count($this->session->userdata('assigned_superpowers_en_ids'))){
                                    foreach($this->config->item('en_all_10957') as $superpower_en_id => $m){
                                        if(en_auth($superpower_en_id)){
                                            echo '<a class="btn btn-sm btn-superpower icon-block-lg superpower-frame-'.$superpower_en_id.' '.( in_array($superpower_en_id, $this->session->userdata('activate_superpowers_en_ids')) ? 'active' : '' ).'" href="javascript:void();" onclick="toggle_superpower('.$superpower_en_id.')" title="'.$m['m_name'].' '.$m['m_desc'].'">'.$m['m_icon'].'</a>';
                                        }
                                    }
                                }
                                ?>

                            </div>
                        </td>

                        <td class="block-link search-toggle"><a class="supwerpower_view" href="javascript:void(0);" onclick="load_searchbar();"><?= $en_all_11035[7256]['m_icon'] ?></a></td>

                        <?php

                        if (isset($session_en['en_id'])) {

                            echo '<td class="block-link"><a href="/play/'.$session_en['en_id'].'" data-toggle="tooltip" data-placement="top" title="'.$session_en['en_name'].'">'.$session_en['en_icon'].'</a></td>';

                        } else {

                            //TERMS
                            echo '<td class="block-link"><a href="/read/8263" title="'.$en_all_11035[7540]['m_name'].'">'.$en_all_11035[7540]['m_icon'].'</a></td>';

                            //Give option to signin
                            echo '<td class="block-link"><a href="/play/signin" data-toggle="tooltip" data-placement="top" title="'.$en_all_11035[4269]['m_name'].'">'.$en_all_11035[4269]['m_icon'].'</a></td>';

                        }
                        ?>

                    </tr>
                </table>
            </div>
        </div>





        <!-- SEARCH -->
        <div class="searchpad container hidden">
            <h1>SEARCH PAD</h1>
            <div id="searchresults"></div>
        </div>

    <?php } ?>