<?php
$session_en = superpower_assigned();
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
?><!doctype html>
<html lang="en">
<head>

    <!--

    JOIN MENCH @ https://mench.com/12747

    -->

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/img/mench-v2-16.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= (isset($title) ? $title . ' | ' : '') ?>MENCH</title>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800&display=swap" rel="stylesheet">

    <!--
    <link href="/css/lib/material-dashboard.css" rel="stylesheet"/>
    <link href="/css/lib/material-kit.css" rel="stylesheet"/>
    <script src="/js/lib/material.min.js" type="text/javascript"></script>
    <script src="/js/lib/material-dashboard.js" type="text/javascript"></script>
    <link href="/css/custom/styles.css?v=v<?= config_var(11060) ?>" rel="stylesheet"/>
    -->

    <link href="/css/custom/mench.css?v=v<?= config_var(11060) ?>" rel="stylesheet"/>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92774608-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-92774608-1');
    </script>

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
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous"></script>


    <script src="https://kit.fontawesome.com/fbf7f3ae67.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/typeit@6.1.1/dist/typeit.min.js"></script>
    <script src="/js/lib/jquery.textcomplete.min.js"></script>
    <script src="/js/lib/autosize.min.js"></script>
    <script src="/js/lib/autocomplete.jquery.min.js"></script>
    <script src="/js/lib/algoliasearch.min.js"></script>
    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
    <script src="/js/custom/platform-js.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>


    <script>
        window['_fs_debug'] = false;
        window['_fs_host'] = 'fullstory.com';
        window['_fs_script'] = 'edge.fullstory.com/s/fs.js';
        window['_fs_org'] = 'QMKCQ';
        window['_fs_namespace'] = 'FS';
        (function(m,n,e,t,l,o,g,y){
            if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
            g=m[e]=function(a,b,s){g.q?g.q.push([a,b,s]):g._api(a,b,s);};g.q=[];
            o=n.createElement(t);o.async=1;o.crossOrigin='anonymous';o.src='https://'+_fs_script;
            y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
            g.identify=function(i,v,s){g(l,{uid:i},s);if(v)g(l,v,s)};g.setUserVars=function(v,s){g(l,v,s)};g.event=function(i,v,s){g('event',{n:i,p:v},s)};
            g.shutdown=function(){g("rec",!1)};g.restart=function(){g("rec",!0)};
            g.log = function(a,b) { g("log", [a,b]) };
            g.consent=function(a){g("consent",!arguments.length||a)};
            g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
            g.clearUserCookie=function(){};
        })(window,document,window['_fs_namespace'],'script','user');
    </script>

</head>

<body class="<?= current_mench() ?>">

<?php
//Any message we need to show here?
if (!isset($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}
if(strlen($flash_message) > 0){
    echo '<div class="container" id="custom_message">'.$flash_message.'</div>';
}


if(isset($custom_header)){
    echo $custom_header;
}


if(!isset($hide_header) || !$hide_header){

    if(isset($session_en['en_id']) && !isset($basic_header)){
        ?>
        <!-- 3X NAVIGATION -->
        <div class="container show-while-searching fixed-bottom">
            <div class="row">
                <table class="three-menus">
                    <tr>
                        <?php
                        foreach($this->config->item('en_all_2738') as $en_id => $m){

                            $identifier = strtolower($m['m_name']);
                            $handle = ( $en_id==6205 ? '' : $identifier );

                            echo '<td><a class="'.$identifier.' border-'.$identifier.( $this->uri->segment(1)==$handle || ( $en_id==6205 && !$handle && is_numeric($this->uri->segment(1)) ) ? ' focustab ': '' ).'" href="/'.$handle.( isset($session_en['en_id']) && $en_id==4536 ? '/'.$session_en['en_id'] : '' ).'">' . ( isset($session_en['en_id']) ? ( $en_id==4536 ? '<span class="parent-icon icon-block">'.$session_en['en_icon'].'</span><span class="mn_name montserrat">'.one_two_explode('',' ',$session_en['en_name']).'</span>' : '<span class="parent-icon icon-block">'.$m['m_icon'].'</span><span class="current_count mn_name montserrat"><i class="far fa-yin-yang fa-spin"></i></span><span class="mn_name montserrat show-max">&nbsp;' . $m['m_name'] . '</span>' ) : '<span class="parent-icon icon-block">'.$m['m_icon'].'</span><span class="mn_name montserrat">' . $m['m_name'] . '</span>' ) .'</a></td>';

                        }
                        ?>
                    </tr>
                </table>
            </div>
        </div>

        <?php
    }
    ?>


    <!-- MENCH LINE -->
    <div class="container show-while-searching fixed-top">
        <div class="row">
            <table class="mench-navigation">
                <tr>

                    <td class="block-link block-logo"><a href="/"><img src="/img/mench-v2-128.png" class="mench-logo mench-spin" /></a></td>

                    <td>
                        <div class="supwerpower_view">

                            <span class="mench-logo mench-text montserrat search-toggle <?= ( isset($basic_header) ? ' hidden ' : '' ) ?>"><?= ( count($this->session->userdata('assigned_superpowers_en_ids')) ? 'ME<a href="javascript:void(0);" onclick="$(\'.supwerpower_view\').toggleClass(\'hidden\');" style="text-decoration: none;">N</a>CH' : '<a href="/" style="text-decoration: none;">MENCH</a>' ) ?></span>

                            <div class="search-toggle hidden"><form id="searchFrontForm"><input class="form-control algolia_search" type="search" id="mench_search" data-lpignore="true" placeholder="<?= $en_all_11035[7256]['m_name'] ?>"></form></div>

                        </div>
                        <div class="supwerpower_view hidden">
                            <div class="full-width">
                            <?php
                            if(count($this->session->userdata('assigned_superpowers_en_ids'))){
                                foreach($this->config->item('en_all_10957') as $superpower_en_id => $m){
                                    if(superpower_assigned($superpower_en_id)){

                                        $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $m['m_parents']);

                                        //Superpower already unlocked:
                                        echo '<a class="btn btn-sm btn-superpower icon-block-lg superpower-frame-'.$superpower_en_id.' '.( in_array($superpower_en_id, $this->session->userdata('activate_superpowers_en_ids')) ? 'active' : '' ).( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'" href="javascript:void();" onclick="toggle_superpower('.$superpower_en_id.')" title="'.$m['m_name'].' '.$m['m_desc'].' @'.$superpower_en_id.'">'.$m['m_icon'].'</a>';

                                    }
                                }
                            }
                            ?>
                            </div>
                        </div>
                    </td>

                    <td class="block-link search-toggle <?= ( isset($basic_header) ? ' hidden ' : '' ) ?>"><a href="javascript:void(0);" onclick="load_searchbar();"><?= $en_all_11035[7256]['m_icon'] ?></a></td>

                    <?php

                    if (isset($session_en['en_id'])) {

                        if(superpower_assigned(10984)){
                            $en_all_11035 = $this->config->item('en_all_11035');
                            echo '<td class="block-link '.superpower_active(10984).'"><a href="/play/admin_tools" title="'.$en_all_11035[6287]['m_name'].'">'.$en_all_11035[6287]['m_icon'].'</a></td>';
                        }

                    } else {

                        //TERMS
                        //echo '<td class="block-link '.( isset($basic_header) ? ' hidden ' : '' ).'"><a href="/8263" title="'.$en_all_11035[7540]['m_name'].'">'.$en_all_11035[7540]['m_icon'].'</a></td>';

                        //Give option to signin
                        echo '<td class="block-link '.( isset($basic_header) ? ' hidden ' : '' ).'"><a href="/signin" title="'.$en_all_11035[4269]['m_name'].'">'.$en_all_11035[4269]['m_icon'].'</a></td>';

                    }
                    ?>

                </tr>
            </table>
        </div>
    </div>

<?php } ?>