<?php

$user_e = superpower_unlocked();
$first_segment = $this->uri->segment(1);
$i__id = is_numeric($first_segment) && $first_segment!=view_memory(6404,14002) ? intval($first_segment) : 0;
$e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
$e___10876 = $this->config->item('e___10876'); //Mench Website
$e___13479 = $this->config->item('e___13479');
$current_mench = current_mench();
$simple_header = ( is_numeric($first_segment) && $current_mench['x_id']==6255 ? ' header-drop hidden' : '' );
?><!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/img/<?= ( !$first_segment ? 'mench' : $current_mench['x_name'] ) ?>.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= ( isset($title) ? $title : 'MENCH' ) ?></title>


    <script type="text/javascript">
    <?php
    //USER VARIABLES
    echo ' var js_session_superpowers_activated = ' . json_encode( ($user_e && count($this->session->userdata('session_superpowers_activated'))) ? $this->session->userdata('session_superpowers_activated') : array() ) . '; ';
    echo ' var superpower_js_12701 = ' . intval(is_array($this->session->userdata('session_superpowers_activated')) && in_array(12701, $this->session->userdata('session_superpowers_activated'))) . '; ';
    echo ' var superpower_js_13422 = ' . intval(is_array($this->session->userdata('session_superpowers_activated')) && in_array(13422, $this->session->userdata('session_superpowers_activated'))) . '; ';
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
    <script type="module">
        import { EmojiButton } from 'https://cdn.jsdelivr.net/npm/@joeattardi/emoji-button@latest/dist/index.min.js';

        $(".emoji-input").each(function () {
            var note_type_id = $(this).attr('note_type_id');
            const picker = new EmojiButton();
            const trigger = document.querySelector('#emoji_pick_type'+note_type_id);
            picker.on('emoji', selection => {
                document.querySelector('#x__message'+note_type_id).value += selection.emoji;
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.37.0/autocomplete.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/algoliasearch/3.35.1/algoliasearch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.1/Sortable.min.js" type="text/javascript"></script>

    <script src="/application/views/global.js?v=<?= view_memory(6404,11060) ?>" type="text/javascript"></script>


    <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=5ec369bdaa9dfe001ab3f797&product=custom-share-buttons&cms=website' async='async'></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>


    <link href="https://fonts.googleapis.com/css?family=Montserrat:800|Roboto+Mono:wght@500|Rubik&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.13.0/css/all.css" integrity="sha384-IIED/eyOkM6ihtOiQsX2zizxFBphgnv1zbe1bKA+njdFzkr6cDNy16jfIKWu4FNH" crossorigin="anonymous">
    <link href="/application/views/global.css?v=<?= view_memory(6404,11060) ?>" rel="stylesheet"/>

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92774608-1"></script>
    <script type="text/javascript">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-92774608-1');
    </script>

</head>

<?php
//Generate Body Class String:
$body_class = 'mench-'.$current_mench['x_name']; //Always append current mench
foreach($this->config->item('e___13890') as $e__id => $m){
    $session_var = $this->session->userdata('session_custom_ui_'.$e__id);
    if(!$session_var){
        //Find the default value:
        $account_defaults = array_intersect($this->config->item('n___13889'), $this->config->item('n___'.$e__id));
        if(count($account_defaults)){
            $session_var = end($account_defaults);
        } else {
            //Could not find the default value
            continue;
        }
    }
    $body_class .= ' custom_ui_'.$e__id.'_'.$session_var.' ';
}
echo '<body class="'.$body_class.'">';

//Load live chat?
if(intval(view_memory(6404,12899))){
    ?>

    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml            : true,
                version          : 'v8.0'
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
         attribution=setup_tool
         page_id="381488558920384"
         greeting_dialog_display="hide"
         ref="<?= ( $user_e ? $user_e['e__id'] : '' ) ?>"
         theme_color="#222222">
    </div>
    <div class="chat-title"><span><?= $e___11035[12899]['m__title'] ?></span></div>

    <?php
}



//Any message we need to show here?
if (!isset($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}


if(strlen($flash_message) > 0) {

    //Delete from Flash:
    $this->session->unmark_flash('flash_message');

    echo '<div class="container '.( isset($hide_header) ? ' center-info ' : '' ).'" id="flash_message" style="padding-bottom: 10px;">'.$flash_message.'</div>';

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
                    echo '<div class="max_width">';
                    echo '<div class="'.$simple_header.'">';

                    //MENCH
                    echo '<div class="left_nav mench_nav"><span class="inline-block pull-left"><a href="'.home_url($current_mench['x_id']).'"><img src="/img/mench.png" class="mench-logo mench-spin" /><b class="montserrat text-logo">MENCH</b></a></span></div>';

                    //SEARCH
                    echo '<div class="left_nav search_nav hidden"><form id="searchFrontForm"><input class="form-control algolia_search white-border" type="search" id="mench_search" data-lpignore="true" placeholder="'.$e___11035[7256]['m__title'].'"></form></div>';

                    echo '</div>';
                    echo '</div>';
                    echo '</td>';

                    if(intval(view_memory(6404,12678))){

                        //Search button
                        echo '<td class="block-x"><a href="javascript:void(0);" onclick="toggle_search()" style="margin-left: 0;"><span class="'.$simple_header.'"><span class="search_icon">'.$e___11035[7256]['m__icon'].'</span><span class="search_icon hidden" title="'.$e___11035[13401]['m__title'].'">'.$e___11035[13401]['m__icon'].'</span></span></a></td>';

                    }

                    //MENU
                    $menu_type = ( $user_e ? 12500 : 14372 );
                    echo '<td class="block-menu">';
                    echo '<div class="dropdown inline-block">';
                    echo '<button type="button" class="btn no-side-padding header-click" id="dropdownMenuButton'.$menu_type.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    echo '<span class="icon-block">' .$e___13479[$menu_type]['m__icon'].'</span>';
                    echo '</button>';
                    echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$menu_type.'">';
                    foreach($this->config->item('e___'.$menu_type) as $x__type => $m) {

                        //Skip superpowers if not unlocked
                        if($x__type==10957 && !count($this->session->userdata('session_superpowers_unlocked'))){
                            continue;
                        }

                        $superpower_actives = array_intersect($this->config->item('n___10957'), $m['m__profile']);
                        $extra_class = null;
                        $text_class = null;

                        if(in_array($x__type, $this->config->item('n___10876'))){

                            //Fetch URL:
                            $href = 'href="'.$e___10876[$x__type]['m__message'].'"';

                        } elseif($x__type==12899) {

                            //FEEDBACK SUPPORT
                            $href = 'href="javascript:void(0);"';
                            $extra_class = ' icon_12899 ';

                        } else {

                            continue;

                        }

                        //Navigation
                        echo '<a '.$href.' class="dropdown-item montserrat doupper '.extract_icon_color($m['m__icon']).( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).$extra_class.'"><span class="icon-block">'.$m['m__icon'].'</span><span class="'.$text_class.'">'.$m['m__title'].'</span></a>';

                    }

                    echo '</div>';
                    echo '</div>';
                    echo '</td>';

                    ?>
                </tr>
            </table>
        </div>
    </div>

<?php } ?>