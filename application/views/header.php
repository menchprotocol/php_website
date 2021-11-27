<?php

$member_e = superpower_unlocked();
$first_segment = $this->uri->segment(1);
$i__id = ( isset($i_focus['i__id']) ? $i_focus['i__id'] : 0 );
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$e___13479 = $this->config->item('e___13479');
$e___14874 = $this->config->item('e___14874'); //COINS
$superpower_10939 = $member_e && superpower_active(10939, true);
$current_coin_id = current_coin_id();
$base_source = get_domain_setting(0);
$basic_header_footer = isset($basic_header_footer) && intval($basic_header_footer);
$login_url_path = ( isset($_SERVER['REQUEST_URI']) ? '?url='.urlencode($_SERVER['REQUEST_URI']) /* Append current URL for redirects */ : '' );
$logo = '/img/'.$current_coin_id.'.png';
?><!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />

    <meta name="theme-color" content="#f0f0f0">
    <link rel="icon" href="<?= $logo ?>">
    <link rel="mask-icon" href="<?= $logo ?>" color="#000000">
    <?php
    if(isset($_SERVER['SERVER_NAME'])){
        echo '<link rel="canonical" href="https://'.$_SERVER['SERVER_NAME'].get_server('REQUEST_URI').'" />';
    }
    ?>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= ( isset($title) ? $title : get_domain('m__title') ) ?></title>
    <?php
    echo '<script type="text/javascript">';
    //MEMBER VARIABLES
    echo ' var js_session_superpowers_activated = ' . json_encode( ($member_e && count($this->session->userdata('session_superpowers_activated'))) ? $this->session->userdata('session_superpowers_activated') : array() ) . '; ';
    echo ' var superpower_js_10939 = ' . intval(is_array($this->session->userdata('session_superpowers_activated')) && in_array(10939, $this->session->userdata('session_superpowers_activated'))) . '; ';
    echo ' var superpower_js_12701 = ' . intval(is_array($this->session->userdata('session_superpowers_activated')) && in_array(12701, $this->session->userdata('session_superpowers_activated'))) . '; ';
    echo ' var superpower_js_13422 = ' . intval(is_array($this->session->userdata('session_superpowers_activated')) && in_array(13422, $this->session->userdata('session_superpowers_activated'))) . '; ';

    echo ' var js_pl_id = ' . ( $member_e ? $member_e['e__id'] : '0' ) . '; ';
    echo ' var js_pl_name = \'' . ( $member_e ? str_replace('\'','\\\'',trim($member_e['e__title'])) : '' ) . '\'; ';
    echo ' var base_url = \'' . $this->config->item('base_url') . '\'; ';
    echo ' var base_source = ' . $base_source . '; ';

    //JAVASCRIPT PLATFORM MEMORYwq
    foreach($this->config->item('e___11054') as $x__type => $m){
        if(is_array($this->config->item('e___'.$x__type)) && count($this->config->item('e___'.$x__type))){
            echo ' var js_e___'.$x__type.' = ' . json_encode($this->config->item('e___'.$x__type)) . ';';
            echo ' var js_n___'.$x__type.' = ' . json_encode($this->config->item('n___'.$x__type)) . ';';
        }
    }

    echo '</script>';
    ?>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92774608-1"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/typeit@6.1.1/dist/typeit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/autosize@4.0.2/dist/autosize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@15.1.1/dist/lazyload.min.js"></script>
    <script type="module">

        import { EmojiButton } from 'https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@latest/dist/index.min.js';

        $(".emoji-input").each(function () {
            var x__type = $(this).attr('x__type');
            const picker = new EmojiButton();
            const trigger = document.querySelector('#emoji_pick_type'+x__type);
            picker.on('emoji', selection => {
                append_value($('.input_note_'+x__type), ' ' + selection.emoji);
                set_autosize($(this));
            });
            trigger.addEventListener('click', () => picker.togglePicker(trigger));
        });

        $(".emoji_edit").removeClass('hidden');
        $(".load_emoji_editor").click(function () {
            var x__id = $(this).attr('x__id');
            const picker = new EmojiButton();
            const trigger = document.querySelector('#emoji_pick_id'+x__id);
            picker.on('emoji', selection => {
                document.querySelector('#message_body_'+x__id).value += selection.emoji;
            });
            trigger.addEventListener('click', () => picker.togglePicker(trigger));
        });

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.37.0/autocomplete.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/algoliasearch/3.35.1/algoliasearch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.1/Sortable.min.js" type="text/javascript"></script>


    <link href="https://fonts.googleapis.com/css?family=Open+Sans|Quicksand:700|Roboto+Mono:500|Montserrat:800|Rubik|Bitter:800|Roboto+Slab:400|Righteous|Libre+Baskerville|Lato&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="<?= view_memory(6404,13577) ?>" crossorigin="anonymous">

    <link href="/application/views/global.css?v=dir3<?= view_memory(6404,11060) ?>" rel="stylesheet"/>
    <script src="/application/views/global.js?v=dir3<?= view_memory(6404,11060) ?>" type="text/javascript"></script>

    <?php
    $domain_background = get_domain_setting(28621);
    if(strlen($domain_background)){
        echo ' <style> body, .container, .chat-title span, div.dropdown-item, .mid-text-line span { background:'.$domain_background.' !important; } </style> ';
    }
    ?>

</head>

<?php



//Generate Body Class String:
$body_class = 'platform-'.$current_coin_id; //Always append current coin
foreach($this->config->item('e___13890') as $e__id => $m){
    $body_class .= ' custom_ui_'.$e__id.'_'.member_setting($e__id).' ';
}
echo '<body class="'.$body_class.'">';


//Load live chat?
$live_chat_page_id = get_domain_setting(12899);
if(strlen($live_chat_page_id)>10){
    ?>
    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml            : true,
                version          : 'v12.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

    <!-- Your Chat App code -->
    <div class="fb-customerchat"
         page_id="<?= $live_chat_page_id ?>"
         greeting_dialog_display="hide"
         welcome_screen_greeting="Hi"
         ref="<?= ( $member_e ? $member_e['e__id'] : '' ) ?>"
         theme_color="#000000">
    </div>
    <?php

    /*<div class="chat-title"><span><?= $e___11035[12899]['m__title'] ?></span></div>*/

}



if(!$basic_header_footer){

    $show_side_menu = false;

    if(superpower_unlocked(28651)){
        $e___28646 = $this->config->item('e___28646');
        $domain_list = intval(substr(get_domain_setting(28646), 1));
        $show_side_menu = $domain_list;
    }

    echo '<div class="sidebar hidden"><div style="padding:5px;"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading...</div></div>';

    //Do not show for /sign view
    ?>
    <div class="container fixed-top" style="padding-bottom: 0 !important;">
        <div class="row justify-content-center">
            <table class="platform-navigation">
                <tr>
                    <?php

                    echo '<td class="block-x">';
                    if($member_e) {

                        //Load Left Menu
                        echo '<a href="javascript:void(0);" onclick="toggle_left_menu()" style="margin-left: 0;" class="icon-block">'.$e___11035[( strlen(intval($first_segment))==strlen($first_segment) ? 28658 : 28646 )]['m__cover'].'</a>';

                    } else {

                        //Login:
                        echo '<a href="/-4269'.$login_url_path.'" style="margin-left: 0;" class="icon-block">'.$e___11035[4269]['m__cover'].'</a>';

                    }
                    echo '</td>';


                    echo '<td>';
                    echo '<div class="max_width">';

                    echo '<div class="left_nav top_nav" style="text-align: center;">';

                    //$my_source = '<span class="platform-circle icon-block mini_6197_'.$member_e['e__id'].'">'.view_cover(12274,$member_e['e__cover']).'</span><span class="css__title text-logo"><b class="text__6197_'.$member_e['e__id'].'">'.$member_e['e__title'].'</b>'.( 0 /* Disabled for now */ && $superpower_10939 && $first_segment!='@'.$member_e['e__id'] ? ' <span style="font-size: 0.75em; display: inline-block;">'.view_coins_e($current_coin_id, $member_e['e__id']).'</span>' : '' ).'</span>';
                    //Domain Source
                    //'.($member_e ? '/@'.$member_e['e__id'] : '/' ).'
                    $domain_cover = get_domain('m__cover');
                    echo '<a href="/">'.( strlen($domain_cover) ? '<span class="icon-block platform-logo mini_6197_'.get_domain_setting(0).'">'.get_domain('m__cover').'</span>' : '<span style="float: left; width: 5px; display: block;">&nbsp;</span>') . '<b class="css__title text-logo text__6197_'.$base_source.'">'.get_domain('m__title').'</b>'.'</a>';

                    echo '</div>';


                    //SEARCH
                    echo '<div class="left_nav search_nav hidden"><form id="searchFrontForm"><input class="form-control algolia_search" type="search" id="top_search" data-lpignore="true" placeholder="'.$e___11035[7256]['m__title'].'"></form></div>';

                    echo '</div>';
                    echo '</td>';

                    echo '<td class="block-x search_icon hidden"><a href="javascript:void(0);" onclick="toggle_search()" style="margin-left: 0;">'.$e___11035[13401]['m__cover'].'</a></td>';

                    //MENU
                    $menu_type = ( $member_e ? 12500 : 14372 );
                    echo '<td class="block-menu">';
                    echo '<div class="dropdown inline-block">';
                    echo '<button type="button" class="btn no-side-padding" id="dropdownMenuButton'.$menu_type.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    echo '<span class="icon-block">' . $e___13479[$menu_type]['m__cover'] .'</span>';
                    echo '</button>';
                    echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$menu_type.'">';
                    foreach($this->config->item('e___'.$menu_type) as $x__type => $m) {

                        $superpower_actives = array_intersect($this->config->item('n___10957'), $m['m__profile']);
                        if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
                            continue;
                        }

                        $hosted_domains = array_intersect($this->config->item('n___14870'), $m['m__profile']);
                        if(count($hosted_domains) && !in_array(get_domain_setting(0), $hosted_domains)){
                            continue;
                        }

                        $extra_class = null;
                        $text_class = null;

                        if($x__type==7256) {

                            //Search
                            $href = 'href="javascript:void(0);" onclick="toggle_search()" ';

                        } elseif(in_array($x__type, $this->config->item('n___13566'))) {

                            //MODAL
                            $href = 'href="javascript:void(0);"';
                            $extra_class = ' trigger_modal ';

                        } elseif(in_array($x__type, $this->config->item('n___6287'))){

                            //APP
                            $href = 'href="/-'.$x__type.( $x__type==4269 ? $login_url_path : '' ).'"';

                        } else {

                            continue;

                        }

                        //Navigation
                        echo '<a '.$href.' x__type="'.$x__type.'" class="dropdown-item css__title '.$extra_class.'"><span class="icon-block">'.$m['m__cover'].'</span><span class="'.$text_class.'">'.$m['m__title'].'</span></a>';

                    }

                    echo '</div>';
                    echo '</div>';
                    echo '</td>';

                    ?>
                </tr>
            </table>
        </div>
    </div>

<?php

}


echo '<div id="container_search" class="container hidden headline-height hideIfEmpty"><div class="row justify-content-center hideIfEmpty"></div></div>';
echo '<div id="container_content" class="container">';

//Any message we need to show here?
if (!isset($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}

if(strlen($flash_message) > 0) {

    //Delete from Flash:
    $this->session->unmark_flash('flash_message');

    echo '<div class="'.( $basic_header_footer ? ' center-info ' : '' ).'" id="flash_message" style="padding-bottom: 10px;">'.$flash_message.'</div>';

}

?>