<?php
$session_en = superpower_assigned();
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
?><!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/mench.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= (isset($title) ? $title . ' | ' : '') ?>MENCH</title>

    <?php
    echo '<script type="text/javascript">';

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
    <script src="https://cdn.jsdelivr.net/npm/autosize@4.0.2/dist/autosize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.37.0/autocomplete.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/algoliasearch/3.35.1/algoliasearch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.1/Sortable.min.js" type="text/javascript"></script>
    <script src="/application/views/mench.js?v=v<?= config_var(11060) ?>" type="text/javascript"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800&display=swap" rel="stylesheet">
    <link href="/application/views/mench.css?v=v<?= config_var(11060) ?>" rel="stylesheet"/>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92774608-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-92774608-1');
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

                            echo '<td><a class="'.$identifier.' border-'.$identifier.( $this->uri->segment(1)==$handle || $this->uri->segment(1)==$identifier || ( $en_id==6205 && !$handle && is_numeric($this->uri->segment(1)) ) ? ' focustab ': '' ).'" href="/'.$identifier.( $en_id==4536 ? '/account' : '' ).'">' . ( $en_id==4536 ? '<span class="parent-icon icon-block">'.$session_en['en_icon'].'</span><span class="mn_name montserrat">'.one_two_explode('',' ',$session_en['en_name']).'</span> <span class="current_count montserrat show-max '.superpower_active(10983).'"><i class="far fa-yin-yang fa-spin"></i></span>' : '<span class="parent-icon icon-block">'.$m['m_icon'].'</span><span class="current_count montserrat"><i class="far fa-yin-yang fa-spin"></i></span><span class="montserrat show-max">&nbsp;' . $m['m_name'] . '</span>' ) .'</a></td>';

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

                    <td class="block-link block-logo"><a href="/"><img src="/mench.png" class="mench-logo mench-spin" /></a></td>

                    <td>
                        <div class="search-toggle hidden"><form id="searchFrontForm"><input class="form-control algolia_search" type="search" id="mench_search" data-lpignore="true" placeholder="<?= $en_all_11035[7256]['m_name'] ?>"></form></div>

                        <div class="supwerpower_view"><span class="mench-logo mench-text montserrat search-toggle <?= ( isset($basic_header) ? ' hidden ' : '' ) ?>"><a href="/" style="text-decoration: none;">MENCH</a><?= ( count($this->session->userdata('assigned_superpowers_en_ids')) ? '<a href="javascript:void(0);" onclick="$(\'.supwerpower_view\').toggleClass(\'hidden\');" class="gateway">|</a>' : '' ) ?></span></div>

                        <div class="supwerpower_view hidden">
                            <div class="full-width">
                            <?php
                            if(count($this->session->userdata('assigned_superpowers_en_ids'))){
                                foreach($this->config->item('en_all_10957') as $superpower_en_id => $m){
                                    if(superpower_assigned($superpower_en_id)){

                                        //Superpower already unlocked:
                                        echo '&nbsp;<a class="btn btn-sm btn-superpower icon-block superpower-frame-'.$superpower_en_id.' '.( in_array($superpower_en_id, $this->session->userdata('activate_superpowers_en_ids')) ? 'active' : '' ).'" href="javascript:void();" onclick="toggle_superpower('.$superpower_en_id.')" title="'.$m['m_name'].' '.$m['m_desc'].' @'.$superpower_en_id.'">'.$m['m_icon'].'</a>';

                                    }
                                }

                                //Option to revert back:
                                echo '&nbsp;<a class="btn btn-sm btn-superpower icon-block" style="cursor: alias !important;" href="javascript:void(0);" onclick="$(\'.supwerpower_view\').toggleClass(\'hidden\');" title="Back to Normal ;)"><i class="far fa-times"></i></a>';

                            }
                            ?>
                            </div>
                        </div>
                    </td>

                    <td class="block-link search-toggle <?= ( isset($basic_header) ? ' hidden ' : '' ) ?>"><a href="javascript:void(0);" onclick="load_searchbar();"><?= $en_all_11035[7256]['m_icon'] ?></a></td>

                    <?php

                    if (isset($session_en['en_id'])) {

                        $en_all_11035 = $this->config->item('en_all_11035');

                        echo '<td class="block-link '.superpower_active(10984).'"><a href="/play/play_admin" title="'.$en_all_11035[6287]['m_name'].'">'.$en_all_11035[6287]['m_icon'].'</a></td>';

                        //TODO Create feedback input
                        //echo '<td class="block-link '.superpower_active(10939).'"><a href="https://github.com/menchblogs/platform/issues/new" target="_blank" title="'.$en_all_11035[12200]['m_name'].': '.$en_all_11035[12200]['m_desc'].'">'.$en_all_11035[12200]['m_icon'].'</a></td>';

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