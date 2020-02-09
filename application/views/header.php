<?php
$session_en = superpower_assigned();
$current_mench = current_mench();
$first_segment = $this->uri->segment(1);
$en_all_11035 = $this->config->item('en_all_11035'); //MENCH PLAYER NAVIGATION
$en_all_2738 = $this->config->item('en_all_2738');

//Arrange based on current mench:
$en_all_2738_mench = array();
$did_find = false;
$found_at = 1; //1 or 2 or 3
foreach($en_all_2738 /* Player Statuses */ as $en_id => $m){
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

    foreach($en_all_2738 /* Player Statuses */ as $en_id => $m){
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
<html lang="en">
<head>

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/img/<?= $current_mench['x_name'] ?>.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= (isset($title) ? $title . ' | ' : '') ?>MENCH</title>

    <?php
    echo '<script type="text/javascript">';

    echo ' var js_session_superpowers_assigned = ' . json_encode( count($this->session->userdata('session_superpowers_assigned')) ? $this->session->userdata('session_superpowers_assigned') : array() ) . '; ';
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

<body class="<?= 'to'.$current_mench['x_name'] ?>">

<?php
//Any message we need to show here?
if (!isset($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}


if(strlen($flash_message) > 0) {
    echo '<div class="container '.( isset($hide_header) ? ' center-info ' : '' ).'" id="custom_message" style="padding-bottom: 0;">'.$flash_message.'</div>';
}


if(isset($custom_header)){
    echo $custom_header;
}


if(!isset($hide_header)){

    if(isset($session_en['en_id']) && !isset($basic_header)){
        ?>
        <!-- 3X NAVIGATION -->
        <div class="container show-while-searching fixed-bottom" style="padding-bottom: 0 !important;">
            <div class="row">
                <table id="MENCHmenu">
                    <tr>
                        <?php
                        $MENCHcolumn1 = 0;
                        foreach($en_all_2738_mench as $en_id => $m){

                            $MENCHcolumn1++;
                            $url_extension = null;
                            $mench_coin = strtolower($m['m_name']);
                            $is_current = ($current_mench['x_id']==$en_id);
                            $url = '/'.$mench_coin;

                            if(!$is_current && isset($in) && $in['in_id']!=config_var(12156) && in_array($current_mench['x_name'], array('read','idea'))){
                                if($current_mench['x_name']=='read' && $mench_coin=='idea'){
                                    $url = '/idea/'.$in['in_id'];
                                } elseif($current_mench['x_name']=='idea' && $mench_coin=='read'){
                                    $url = '/'.$in['in_id'];
                                }
                            }

                            echo '<td class="MENCHcolumn'.$MENCHcolumn1.' '.$mench_coin.'">';
                            echo '<a class="'.$mench_coin.' border-'.$mench_coin.( $is_current ? ' focustab ': '' ).'" href="'.$url.'">';

                            if($en_id==4536){

                                //PLAY
                                echo '<span class="icon-block">'.$session_en['en_icon'].'</span>';
                                if($current_mench['x_name']=='play'){
                                    echo '<span class="montserrat play en_name_first_'.$session_en['en_id'].'">'.one_two_explode('', ' ', $session_en['en_name']).'</span>';
                                } else {
                                    echo '<span class="montserrat play">ME</span>';
                                }

                            } else {

                                //READ IDEA
                                echo '<span class="icon-block">'.$m['m_icon'].'</span>';
                                echo '<span class="montserrat current_count"><i class="far fa-yin-yang fa-spin"></i></span> ';
                                if($is_current){
                                    echo '<span class="montserrat '.$mench_coin.'_name">' . $m['m_name'] . 'S</span>';
                                }

                            }

                            echo '</a>';
                            echo '</td>';

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
    <div class="container show-while-searching fixed-top" style="padding-bottom: 0 !important;">
        <div class="row">
            <table class="mench-navigation">
                <tr>

                    <td class="block-link block-logo"><img src="/img/mench.png" class="mench-logo mench-spin" /></td>

                    <td>
                        <div class="search-toggle hidden"><form id="searchFrontForm"><input class="form-control algolia_search" type="search" id="mench_search" data-lpignore="true" placeholder="<?= $en_all_11035[7256]['m_name'] ?>"></form></div>

                        <div class="supwerpower_view"><span class="mench-logo mench-text montserrat search-toggle <?= ( isset($basic_header) || 1 ? ' hidden ' : '' ) ?>">MENCH</span><?= ( count($this->session->userdata('session_superpowers_assigned')) ? '<a href="javascript:void(0);" onclick="$(\'.supwerpower_view\').toggleClass(\'hidden\');" class="gateway">&nbsp;</a>' : '' ) ?></div>

                        <div class="supwerpower_view hidden">
                            <div class="full-width">
                            <?php
                            if(count($this->session->userdata('session_superpowers_assigned'))){
                                foreach($this->config->item('en_all_10957') as $superpower_en_id => $m){
                                    if(superpower_assigned($superpower_en_id)){

                                        //Superpower already unlocked:
                                        echo '<a class="btn btn-sm btn-superpower icon-block superpower-frame-'.$superpower_en_id.' '.( in_array($superpower_en_id, $this->session->userdata('session_superpowers_activated')) ? 'active' : '' ).'" href="javascript:void();" onclick="toggle_superpower('.$superpower_en_id.')" title="'.$m['m_name'].' '.$m['m_desc'].' @'.$superpower_en_id.'">'.$m['m_icon'].'</a>';

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

                        echo '<td class="block-link '.superpower_active(10985).'"><a href="/play/play_admin" title="'.$en_all_11035[6287]['m_name'].'">'.$en_all_11035[6287]['m_icon'].'</a></td>';

                        //TODO Create feedback input
                        //echo '<td class="block-link '.superpower_active(10939).'"><a href="https://github.com/menchideas/platform/issues/new" target="_blank" title="'.$en_all_11035[12200]['m_name'].': '.$en_all_11035[12200]['m_desc'].'">'.$en_all_11035[12200]['m_icon'].'</a></td>';

                    } else {

                        //TERMS
                        //echo '<td class="block-link '.( isset($basic_header) ? ' hidden ' : '' ).'"><a href="/8263" title="'.$en_all_11035[7540]['m_name'].'">'.$en_all_11035[7540]['m_icon'].'</a></td>';

                        //Give option to sign
                        echo '<td class="block-link '.( isset($basic_header) ? ' hidden ' : '' ).'"><a href="/sign" title="'.$en_all_11035[4269]['m_name'].'">'.$en_all_11035[4269]['m_icon'].'</a></td>';

                    }
                    ?>

                </tr>
            </table>
        </div>
    </div>

<?php } ?>