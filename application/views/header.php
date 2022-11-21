<?php

$member_e = superpower_unlocked();
$first_segment = $this->uri->segment(1);
$second_segment = $this->uri->segment(2);
$i__id = ( isset($i_focus['i__id']) ? $i_focus['i__id'] : 0 );
$e___11035 = $this->config->item('e___11035'); //NAVIGATION
$e___13479 = $this->config->item('e___13479');
$e___14874 = $this->config->item('e___14874'); //COINS
$superpower_10939 = $member_e && superpower_active(10939, true);
$current_coin_id = current_coin_id();
$domain__id = get_domain_setting(0);
$basic_header_footer = isset($basic_header_footer) && intval($basic_header_footer);
$login_url_path = ( isset($_SERVER['REQUEST_URI']) ? '?url='.urlencode($_SERVER['REQUEST_URI']) /* Append current URL for redirects */ : '' );
$domain_link = one_two_explode("\"","\"",get_domain('m__cover'));
$logo = ( filter_var($domain_link, FILTER_VALIDATE_URL) && !$superpower_10939 ? $domain_link : '/img/'.$current_coin_id.'.png' );
$bgVideo = null;

//Generate Body Class String:
$body_class = 'platform-'.$current_coin_id; //Always append current coin
foreach($this->config->item('e___13890') as $e__id => $m){
    $body_class .= ' custom_ui_'.$e__id.'_'.member_setting($e__id).' ';
}
if(in_array($domain__id, $this->config->item('n___30984'))){
    $body_class .= ' home_black_font ';
}


?><!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8" />

    <meta name="theme-color" content="#FFFFFF">
    <link rel="icon" id="favicon" href="<?= $logo ?>">
    <link rel="mask-icon" href="<?= $logo ?>" color="#000000">
    <?php
    if(isset($_SERVER['SERVER_NAME'])){
        echo '<link rel="canonical" href="https://'.$_SERVER['SERVER_NAME'].get_server('REQUEST_URI').'" />';
    }
    ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ( isset($title) ? $title.' | ' : '' ) . get_domain('m__title') ?></title>
    <?php

    //Do we have Google Analytics?
    $google_analytics_code = get_domain_setting(30033);
    if(strlen($google_analytics_code) > 0){
        echo '<script async src="https://www.googletagmanager.com/gtag/js?id='.$google_analytics_code.'"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \''.$google_analytics_code.'\');
</script>';
    }


    echo '<script type="text/javascript"> ';

    //MEMBER VARIABLES
    echo ' var js_session_superpowers_activated = ' . json_encode( ($member_e && count($this->session->userdata('session_superpowers_activated'))) ? $this->session->userdata('session_superpowers_activated') : array() ) . '; ';
    echo ' var superpower_js_10939 = ' . intval(is_array($this->session->userdata('session_superpowers_activated')) && in_array(10939, $this->session->userdata('session_superpowers_activated'))) . '; ';
    echo ' var superpower_js_12701 = ' . intval(is_array($this->session->userdata('session_superpowers_activated')) && in_array(12701, $this->session->userdata('session_superpowers_activated'))) . '; ';
    echo ' var superpower_js_13422 = ' . intval(is_array($this->session->userdata('session_superpowers_activated')) && in_array(13422, $this->session->userdata('session_superpowers_activated'))) . '; ';

    echo ' var js_pl_id = ' . ( $member_e ? $member_e['e__id'] : '0' ) . '; ';
    echo ' var js_pl_name = \'' . ( $member_e ? str_replace('\'','\\\'',trim($member_e['e__title'])) : '' ) . '\'; ';
    echo ' var base_url = \'' . $this->config->item('base_url') . '\'; ';
    echo ' var domain__id = "' . $domain__id . '"; ';

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
            console.log('EMOJI'+x__type);
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


    <?php
    if(is_new()){
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">';
        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>';
        echo '<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>';
    } else {
        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">';
        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>';
    }
    ?>

    <link rel="stylesheet" href="<?= view_memory(6404,13577) ?>" crossorigin="anonymous">

    <link href="/application/views/global.css?v=dir3<?= view_memory(6404,11060) ?>" rel="stylesheet"/>
    <script src="/application/views/global.js?v=dir3<?= view_memory(6404,11060) ?>" type="text/javascript"></script>


    <!-- Load Fonts Dynamically -->
    <?php
    echo '<style> ';

    //Font Helps:
    $e___29711 = $this->config->item('e___29711'); //Google Font Family
    $e___29763 = $this->config->item('e___29763'); //CSS Font Family

    $google_fonts = array();

    //Header Fonts
    foreach($this->config->item('e___14506') as $e__id => $m){
        if(isset($e___29711[$e__id]) && isset($e___29763[$e__id])){
            array_push($google_fonts, $e___29711[$e__id]['m__message']);
            echo '
            .custom_ui_14506_'.$e__id.'.css__title.itemsetting,
            .custom_ui_14506_'.$e__id.' h1,
            .custom_ui_14506_'.$e__id.' h2,
            .custom_ui_14506_'.$e__id.' .css__title,
            .custom_ui_14506_'.$e__id.' .headline,
            .custom_ui_14506_'.$e__id.' .btn,
            .custom_ui_14506_'.$e__id.' .algolia_pad_search,
            .custom_ui_14506_'.$e__id.' .progress-title,
            .custom_ui_14506_'.$e__id.' .mid-text-line span,
            .custom_ui_14506_'.$e__id.' .previous_answer,
            .custom_ui_14506_'.$e__id.' .nav-x,
            .custom_ui_14506_'.$e__id.' .texttype__lg,
            .custom_ui_14506_'.$e__id.' .texttype__lg::placeholder,
            .custom_ui_14506_'.$e__id.' .alert a,
            .custom_ui_14506_'.$e__id.' .pull-middle {
                font-family:'.$e___29763[$e__id]['m__message'].' !important;
            }
            ';
        }
    }


    //Content Fonts
    foreach($this->config->item('e___29700') as $e__id => $m){
        if(isset($e___29711[$e__id]) && isset($e___29763[$e__id])){
            array_push($google_fonts, $e___29711[$e__id]['m__message']);
            echo '
            .custom_ui_29700_'.$e__id.'.css__title.itemsetting,
            .custom_ui_29700_'.$e__id.' div,
            .custom_ui_29700_'.$e__id.' p,
            .custom_ui_29700_'.$e__id.' html,
            .custom_ui_29700_'.$e__id.' body,
            .custom_ui_29700_'.$e__id.' .doregular {
                font-family: '.$e___29763[$e__id]['m__message'].' !important;
            }
            ';
        }
    }


    $domain_background = get_domain_setting(28621);
    if(strlen($domain_background)){

        $apply_css = 'body, .container, .chat-title span, div.dropdown-item, .mid-text-line span';

        if(substr($domain_background, 0, 1)=='#'){

            echo 'body, .container, .chat-title span, div.dropdown-item, .mid-text-line span { ';
            echo 'background:'.$domain_background.' !important; ';
            echo '}';

        } elseif(substr($domain_background, 0, 2)=='//' && !strlen($first_segment)){

            //Video of photo?
            if(substr($domain_background, -4)=='.mp4'){
                //Is Video:
                $bgVideo = '<video autoplay loop muted playsinline class="video_contain"><source src="'.$domain_background.'" type="video/mp4"></video>';
            } else {

                //Is Photo:
                echo 'body { 
    background: url("'.$domain_background.'") no-repeat center center fixed !important; 
    background-size: cover !important;
    width: 100% !important;
    -webkit-background-size: cover !important;
    -moz-background-size: cover !important;
    -o-background-size: cover !important;
    top:0 !important;
      left:0 !important;
    height: 100% !important;
    ';
                echo '}';

                echo 'body:after{
      content:"" !important;
      position:fixed !important; /* stretch a fixed position to the whole screen */
      top:0 !important;
      left:0 !important;
      height:100vh !important; /* fix for mobile browser address bar appearing disappearing */
      right:0 !important;
      z-index:-1 !important; /* needed to keep in the background */
      background: url("'.$domain_background.'") no-repeat center center !important;
      -webkit-background-size: cover !important;
      -moz-background-size: cover !important;
      -o-background-size: cover !important;
      background-size: cover !important;
}';

            }

            echo '.container, .chat-title span, div.dropdown-item, .mid-text-line span { ';
            echo 'background: transparent !important; ';
            echo '}';

            echo ' .halfbg, .msg>span, .fixed-top { background: rgba(0, 0, 0, 0.69) !important; } ';
            echo ' .msg>span { display: block; padding: 3px 0; } ';
            //echo ' .halfbg, .msg>span, .msg { line-height: 150% !important; } ';
            echo ' .msg>span u, .msg>span a { line-height: 100% !important; padding:0 !important; } ';

            echo '.list-border, .msg { max-width: 880px; margin: 0 auto !important; }';
            echo ' @media (max-width:767px) { .list-border, .msg { max-width: calc(100% - 16px); margin: 0 auto; } }';

            //Force Dark Mode:
            $body_class = str_replace('custom_ui_13884_13885','custom_ui_13884_13886', $body_class);

        }
    }
    echo ' </style>';
    ?>

    <link href="https://fonts.googleapis.com/css?family=<?= join('|',$google_fonts) ?>&display=swap" rel="stylesheet">

</head>

<?php




echo '<body class="'.$body_class.'">';
echo $bgVideo;

//Load live chat?
$live_chat_page_id = get_domain_setting(12899);
if(strlen($live_chat_page_id)>10){
    ?>
    <!-- Messenger Chat Plugin Code -->
    <div id="fb-root"></div>
    <!-- Your Chat Plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat" ref="<?= ( $member_e ? $member_e['e__id'] : '' ) ?>">
    </div>
    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "<?= $live_chat_page_id ?>");
        chatbox.setAttribute("attribution", "biz_inbox");
    </script>
    <!-- Your SDK code -->
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml            : true,
                version          : 'v15.0'
            });
        };
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <?php
}



if(!$basic_header_footer){

    echo '<div class="sidebar hidden menu_click"><div style="padding:5px;"><span class="icon-block"><i class="far fa-yin-yang fa-spin"></i></span>Loading...</div></div>';

    //Do not show for /sign view
    ?>
    <div class="container fixed-top" style="padding-bottom: 0 !important; min-height: 38px;">
        <div class="row justify-content">
            <table class="platform-navigation">
                <tr>
                    <?php

                    echo '<td>';
                    echo '<div class="max_width">';

                    echo '<div class="left_nav top_nav" style="text-align: left;">';

                    //Domain Source
                    $domain_cover = get_domain('m__cover');
                    $domain_logo = one_two_explode('"','"', $domain_cover);
                    echo '<a href="/">'.( strlen($domain_cover) ? '<span class="icon-block platform-logo source_cover source_cover_mini mini_6197_'.get_domain_setting(0).'">'.view_cover(12274, $domain_logo, 1).'</span>' : '<span style="float: left; width: 5px; display: block;">&nbsp;</span>') . '<b class="css__title text-logo text__6197_'.$domain__id.'" style="padding-top: '.( filter_var($domain_logo, FILTER_VALIDATE_URL) ? '3px' : '7px' ).';">'.get_domain('m__title').'</b>'.'</a>';

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
                    echo '<span class="icon-block source_cover source_cover_mini">' . ( $member_e ? view_cover(12274,$member_e['e__cover'], 1) : $e___13479[$menu_type]['m__cover'] ) .'</span>';
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

                        if($x__type==26105 && $member_e) {

                            //Search
                            //$m['m__title'] = $member_e['e__title'];
                            //$m['m__cover'] = view_cover(12274,$member_e['e__cover']);
                            $href = 'href="/@'.$member_e['e__id'].'" ';

                        } elseif($x__type==7256) {

                            //Search
                            $href = 'href="javascript:void(0);" onclick="toggle_search()" ';

                        } elseif(in_array($x__type, $this->config->item('n___13566'))) {

                            //MODAL
                            $href = 'href="javascript:void(0);"';
                            $extra_class = ' trigger_modal ';

                        } elseif(in_array($x__type, $this->config->item('n___6287'))){

                            //APP
                            $href = 'href="/-'.$x__type.( $x__type==4269 ? $login_url_path : '' ).'"';

                        } elseif(in_array($x__type, $this->config->item('n___14925'))){

                            //Domain Setting
                            $setting_value =  get_domain_setting($x__type);
                            if($setting_value){
                                if($x__type==28615){
                                    //Phone
                                    $href = 'href="tel:'.preg_replace("/[^0-9]/", "", $setting_value).'"';
                                } elseif($x__type==28614){
                                    //Email
                                    $href = 'href="mailto:'.$setting_value.'"';
                                }
                            }

                        } elseif(substr($m['m__message'], 0, 1)=='/'){

                            //Direct Link
                            $href = 'href="'.$m['m__message'].'"';

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


echo '<div id="container_search" class="container hidden hideIfEmpty"><div class="row justify-content hideIfEmpty"></div></div>';
echo '<div id="container_content" class="container">';

//Any message we need to show here?
if (!isset($flash_message) || !strlen($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}

if(strlen($flash_message) > 0) {

    //Delete from Flash:
    $this->session->unmark_flash('flash_message');

    echo '<div class="'.( $basic_header_footer ? ' center-info ' : '' ).'" id="flash_message" style="padding-bottom: 10px;">'.$flash_message.'</div>';

}

?>

<div class="top_message_box hidden">

    <h5 class="css__title"><?= $e___11035[27963]['m__cover'].' '.$e___11035[27963]['m__title'] ?></h5>

    <input type="hidden" id="modal_i__id" value="0" />
    <textarea class="form-control msg note-textarea indifferent algolia_search new-note power_editor editing-mode emoji-input input_note_4231" x__type="4231" placeholder="Write..." style="margin:0; width:100%;"></textarea>
    <div class="note_error_4231 hideIfEmpty zq6255 msg alert alert-danger indifferent" style="margin:8px 0;"></div>

    <table>
        <tr>
            <td style="width: 100%;">
                <?php

                //CONTROLLER
                echo '<div class="no-padding add_notes_4231">';
                echo '<div class="add_notes_form note_pad indifferent">';
                echo '<form class="box box4231" method="post" enctype="multipart/form-data">';

                //UPLOAD
                echo '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType4231" />';
                echo '<label class="hidden"></label>';
                echo '<label class="btn inline-block btn-compact file_label_4231" for="fileIdeaType4231" title="'.$e___11035[13572]['m__title'].' '.$e___11035[13572]['m__message'].'"><span class="icon-block">'.$e___11035[13572]['m__cover'].'</span></label>';

                //GIF
                echo '<a class="btn btn-compact inline-block" href="javascript:void(0);" onclick="images_modal(4231)" title="'.$e___11035[14073]['m__title'].'"><span class="icon-block">'.$e___11035[14073]['m__cover'].'</span></a>';

                //EMOJI
                echo '<span class="btn btn-compact inline-block" id="emoji_pick_type4231" title="'.$e___11035[14038]['m__title'].'"><span class="icon-block">'.$e___11035[14038]['m__cover'].'</span></span>';


                echo '</form>';
                echo '</div>';
                echo '</div>';
                ?>
            </td>
            <td style="width: 50px;">
                <?= '<div class="save_button inline-block"><a href="javascript:save_message_27963()" class="btn btn-default save_notes_4231" style="width:104px;" title="Shortcut: Ctrl + Enter">'.$e___11035[14422]['m__cover'].' '.$e___11035[14422]['m__title'].'</a></div>' ?>
            </td>
        </tr>
    </table>
</div>
