<?php
$user_session = user_session();
$first_segment = $this->uri->segment(1);
$user_segment = view_valid_user_user($first_segment);
$second_segment = $this->uri->segment(2);
$users___11035 = $this->config->item('users___11035'); //Encyclopedia
$users___14870 = $this->config->item('users___14870'); //Website Partner
$website_id = website_setting(0);
$website_favicon = website_setting(31887);
$basic_header_footer = in_array($app_userid, $this->config->item('userids___14562'));
$domain_chain = one_two_explode("\"", "\"", get_domain('m__cover'));
$logo = ($website_favicon ? $website_favicon : (filter_var($domain_chain, FILTER_VALIDATE_URL) ? $domain_chain : 'https://s3foundation.s3.us-west-2.amazonaws.com/yin-yang-solid.svg'));
$bgVideo = null;

// Website
$domain_cover = get_domain('m__cover');
$domain_logo = (substr_count($domain_cover, '"') > 0 ? one_two_explode('"', '"', $domain_cover) : $domain_cover);
$is_emoji = (!filter_var($domain_logo, FILTER_VALIDATE_URL) && !string_is_icon($domain_logo));

//Generate Body Class String:
$body_class = ' app__' . $app_userid . ' '; //Always append current coin
foreach ($this->config->item('users___13890') as $userid => $m) {
    if ($user_session) {
        //Look at their session:
        $body_class .= ' custom_ui_' . $userid . '_' . $this->session->userdata('session_custom_ui_' . $userid) . ' ';
    } else {

        $this_class = '';

        //Fetch Website Defaults:
        foreach (array_intersect($this->config->item('userids___' . $userid), $users___14870[$website_id]['m__following']) as $focususer_id) {
            $this_class = ' custom_ui_' . $userid . '_' . $focususer_id . ' ';
        }

        //If not found, fetch platform defaults:
        if (!strlen($this_class)) {
            $users___4527 = $this->config->item('users___4527');
            foreach (array_intersect($this->config->item('userids___' . $userid), $users___4527[6404]['m__following']) as $focususer_id) {
                $this_class = ' custom_ui_' . $userid . '_' . $focususer_id . ' ';
            }
        }

        $body_class .= $this_class;
    }
}


if ($website_id == 39599) {
    $body_class .= ' center-align dark-theme ';
}


if(!$basic_header_footer){

?><!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">

    <meta name="theme-color" content="#FFFFFF">
    <link rel="icon" id="favicon" href="<?= $logo ?>">
    <?php

    //Block search engines from indexing anything other than the home page:
    if ($app_userid != 14565) {
        echo '<meta name="robots" content="noindex, nofollow">';
    }

    if ($is_emoji) {
        echo '<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>' . $domain_logo . '</text></svg>">';
    } else {
        echo '<link rel="mask-cover" href="' . $logo . '" color="#000000">';
    }

    if (isset($_SERVER['SERVER_NAME'])) {
        echo '<link rel="canonical" href="https://' . $_SERVER['SERVER_NAME'] . get_server('REQUEST_URI') . '">';
    }
    ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <?php

    //Font Helps:
    $users___29763 = $this->config->item('users___29763'); //CSS Font Family
    $users___29711 = $this->config->item('users___29711'); //Google Font Family
    $users___14506 = $this->config->item('users___14506');
    $google_fonts = array();


    //Do we have Google Analytics?
    $google_analytics_code = website_setting(30033);
    if (strlen($google_analytics_code) > 0) {
        echo view_google_tag($google_analytics_code);
    }


    //Do we have Google Tags or second google analytics?
    $google_tag_code = website_setting(38216);
    if (strlen($google_tag_code) > 0) {
        echo view_google_tag($google_tag_code);
    }


    echo '<script> ';
    //JS VARIABLES

    echo ' var js_pl_id = ' . ($user_session && isset($user_session['userid']) ? $user_session['userid'] : '0') . '; ';
    echo ' var js_pl_user = \'' . ($user_session && isset($user_session['userhandle']) ? $user_session['userhandle'] : '') . '\'; ';
    echo ' var js_pl_name = \'' . ($user_session && isset($user_session['username']) ? str_replace('\'', '\\\'', trim($user_session['username'])) : '') . '\'; ';
    echo ' var js_request_uri = \'' . $_SERVER['REQUEST_URI'] . '\'; ';
    echo ' var universal_search_enabled = ' . intval($this->config->item('universal_search_enabled')) . '; ';
    echo ' var website_id = "' . $website_id . '"; ';
    echo ' var js_session_superpowers_unlocked = ' . json_encode(($user_session ? $this->session->userdata('session_superpowers_unlocked') : array())) . ';';
    echo ' var search_and_filter = ( js_session_superpowers_unlocked.includes(12701) ? \'\' : \' AND ( _tags:public_index \' + ( js_pl_id > 0 ? \'OR _tags:z_\' + js_pl_id : \'\' ) + \') \' ); ';

    //JAVASCRIPT PLATFORM MEMORY
    foreach ($this->config->item('users___11054') as $chainusertype => $m) {
        if (is_array($this->config->item('users___' . $chainusertype))) {
            echo ' var js_users___' . $chainusertype . ' = ' . json_encode($this->config->item('users___' . $chainusertype)) . ';';
            echo ' var js_userids___' . $chainusertype . ' = ' . json_encode($this->config->item('userids___' . $chainusertype)) . ';';
        }
    }
    echo '</script>';


    //Latest version of twitter bootstrap:
    echo view_memory(6404, 4523);
    ?>

    <link href="/application/views/website.css?cache_time=<?= $this->config->item('cache_time') ?>" rel="stylesheet">

    <script type="module">

        //Emoji selector:
        import insertText from 'https://cdn.jsdelivr.net/npm/insert-text-at-cursor@0.3.0/index.js'

        const picker_i = new EmojiMart.Picker({
            theme: 'light', onEmojiSelect: (res, _) => {
                //Insert into post text box:
                insertText($(".save_postmessageraw"), res.native);
                //We keep it open!
            }
        });
        const picker_e = new EmojiMart.Picker({
            theme: 'light', onEmojiSelect: (res, _) => {
                //Insert into cover frame:
                updatusercover(res.native);
                $('.emoji_selector .show').removeClass('show');
            }
        });
        $(".emoji_i").append(picker_i);
        $(".emoji_e").append(picker_e);
        $('.emoji_selector').on('click', function (event) {
            //This prevents the emoji modal from closing when an emoji is selected
            event.stopPropagation();
            $(".dropdown-toggle.show").dropdown('toggle');
        });

        $('.text_adder').on('click', function (event) {
            //This prevents the emoji modal from closing when an emoji is selected
            var text_value = $(this).attr('text_value');
            insertText($(".save_postmessageraw"), "\n " + text_value);
            post_recommendations();
            setTimeout(function () {
                $(".save_postmessageraw").focus();
            }, 377);
        });

    </script>

    <script type="module">

        //Emoji selector:
        import insertText from 'https://cdn.jsdelivr.net/npm/insert-text-at-cursor@0.3.0/index.js'
        import {insert_text} from "./website.js";

        window.greetFromModule = insert_text;


    </script>

    <link href="https://unpkg.com/cloudinary-video-player@1.10.5/dist/cld-video-player.min.css" rel="stylesheet">
    <script src="https://unpkg.com/cloudinary-video-player@1.10.5/dist/cld-video-player.min.js"
            type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.37.0/autocomplete.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/algoliasearch/3.35.1/algoliasearch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.1/Sortable.min.js"></script>
    <script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/fbf7f3ae67.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/autosize@4.0.2/dist/autosize.min.js"></script>
    <script src="/application/views/website.js?cache_time=<?= $this->config->item('cache_time') ?>"></script>

    <?php

    //Load Fonts Dynamically
    echo '<style> ' . "\n" . "\n";


    //Hide superpower CSS thats missing:
    foreach ($this->config->item('users___10957') as $superpower_id => $superpower) {
        if (is_array($this->session->userdata('session_superpowers_unlocked')) && !in_array($superpower_id, $this->session->userdata('session_superpowers_unlocked'))) {
            echo ' body .hidden_superpower__' . $superpower_id . ' { display:none !important; } ' . "\n";
        }
    }


    //Header Fonts
    foreach ($this->config->item('users___14506') as $userid => $m) {
        if (isset($users___29711[$userid]) && isset($users___29763[$userid])) {
            array_push($google_fonts, $users___29711[$userid]['m__message']);
            echo '
            .custom_ui_14506_' . $userid . ' .itemsetting.active:not(.exclude_fonts),
            .custom_ui_14506_' . $userid . '.itemsetting.exclude_fonts,
            .custom_ui_14506_' . $userid . ' h1,
            .custom_ui_14506_' . $userid . ' h2,
            .custom_ui_14506_' . $userid . ' .main__title,
            .custom_ui_14506_' . $userid . ' .first_line,
            .custom_ui_14506_' . $userid . ' .headline,
            .custom_ui_14506_' . $userid . ' .btn,
            .custom_ui_14506_' . $userid . ' .mid-text-line span,
            .custom_ui_14506_' . $userid . ' .texttype_lg,
            .custom_ui_14506_' . $userid . ' .texttype_lg::placeholder,
            .custom_ui_14506_' . $userid . ' .alert a {
                font-family:' . $users___29763[$userid]['m__message'] . ' !important;
            }
            ';
        }
    }


    //Content Fonts
    foreach ($this->config->item('users___29700') as $userid => $m) {
        if (isset($users___29711[$userid]) && isset($users___29763[$userid])) {
            array_push($google_fonts, $users___29711[$userid]['m__message']);
            echo '
            .custom_ui_29700_' . $userid . '.itemsetting.exclude_fonts,
            .custom_ui_29700_' . $userid . ' div,
            .custom_ui_29700_' . $userid . ' p,
            .custom_ui_29700_' . $userid . ' .dropdown .btn,
            .custom_ui_29700_' . $userid . ' html,
            .custom_ui_29700_' . $userid . ' body,
            .custom_ui_29700_' . $userid . ' .doregular {
                font-family: ' . $users___29763[$userid]['m__message'] . ' !important;
            }
            ';
        }
    }


    if ($app_userid == 14565) {

        $domain_background = website_setting(28621);
        if (strlen($domain_background)) {

            $apply_css = 'body, .container, .chat-title span, div.dropdown-item, .mid-text-line span';

            //Make sure we have enough padding at the bottom:
            echo '.bottom_spacer {  padding-bottom:987px !important; } ';

            if (substr($domain_background, 0, 1) == '#') {

                echo 'body, .container, .chat-title span, div.dropdown-item, .mid-text-line span { ';
                echo 'background:' . $domain_background . ' !important; ';
                echo '}';

            } elseif (substr($domain_background, 0, 8) == 'https://' && filter_var($domain_background, FILTER_VALIDATE_URL)) {

                //Video of photo?
                if (substr($domain_background, -4) == '.mp4') {

                    //Is Video:
                    $bgVideo = '<video autoplay loop muted playsinline class="video_contain"><source src="' . $domain_background . '" type="video/mp4"></video>';

                } else {

                    //Is Photo:
                    echo 'body { 
    background: url("' . $domain_background . '") no-repeat center center fixed !important; 
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
      background: url("' . $domain_background . '") no-repeat center center !important;
      -webkit-background-size: cover !important;
      -moz-background-size: cover !important;
      -o-background-size: cover !important;
      background-size: cover !important;
}';

                }

                echo '.container, .chat-title span, div.dropdown-item, .mid-text-line span { ';
                echo 'background: transparent !important; ';
                echo '}';

                echo ' .halfbg { background: rgba(0, 0, 0, 0.69) !important;  } ';
                echo ' .fixed-top { background: rgba(21,21,21, 1) !important;  } ';
                echo ' .top-header-position.fixed-top { background: none !important; } ';
                echo ' .i_cache>span u, .i_cache>span a { line-height: 100% !important; padding:0 !important; } ';

            }
        }
    }

    //Left Sidebar Menu Styles
    echo '
    .left-sidebar-menu {
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        width: 64px;
        background-color: rgba(0, 0, 0, 0.69) !important;
        border-right: none;
        z-index: 1000;
        display: flex !important;
        visibility: visible !important;
        flex-direction: column;
        padding: 15px 8px;
        transition: width 0.3s ease;
        overflow-x: hidden;
        transform: none !important;
        justify-content: space-between;
    }
    
    .sidebar-logo {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px 10px;
        margin-bottom: 30px;
        min-height: 50px;
        position: sticky;
        top: 0;
        background-color: transparent;
        z-index: 10;
    }
    
    .sidebar-logo-frame {
        display: flex;
        align-items: center;
        width: 100%;
        position: relative;
        height: 34px;
    }
    
    .sidebar-logo-frame .logo_cover {
        position: relative;
        top: auto;
        left: auto;
        display: flex;
        align-items: center;
        width: auto !important;
        height: auto !important;
        margin-right: 8px;
    }
    
    .sidebar-logo-frame .logo_cover img {
        width: 34px !important;
        height: 34px !important;
        margin: 0 !important;
    }
    
    .sidebar-logo-title {
        position: relative !important;
        top: auto !important;
        left: auto !important;
        display: block !important;
        line-height: 151% !important;
        font-size: 1.21em !important;
        font-weight: bold !important;
        height: 34px !important;
        overflow: hidden !important;
        color: #ffffff !important;
        text-decoration: none;
        white-space: nowrap;
        flex: 1;
    }
    
    .sidebar-logo-title:hover {
        color: #ffffff !important;
        text-decoration: none;
    }
    
    /* Make top menu user image same size as sidebar logo */
    .menu-cover.e_cover_mini img,
    .menu-cover.e_cover_mini .e_cover img,
    .menu-cover.e_cover_mini div.img {
        width: 32px !important;
        height: 32px !important;
    }
    
    .menu-cover.e_cover_mini {
        width: 32px !important;
        height: 32px !important;
    }
    
    /* Compact spacing for top-right menu dropdown items */
    .block-menu .dropdown-menu .dropdown-item {
        padding: 6px 12px !important;
    }
    
    /* Hide footnote section in post modal */
    #modal31911 .save_postfootnote {
        display: none !important;
    }
    
    #modal31911 .dynamic_editing_input:has(.save_postfootnote) {
        display: none !important;
    }
    
    .sidebar-menu-items {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .sidebar-menu-item {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        text-decoration: none;
        color: #ffffff;
        border-radius: 25px;
        transition: all 0.2s ease;
        width: 100%;
        justify-content: center;
        background-color: transparent;
        position: relative;
    }
    
    .sidebar-menu-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    .sidebar-menu-icon {
        font-size: 1.5em;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        color: #ffffff;
        position: relative;
    }
    
    .sidebar-menu-icon i {
        color: #ffffff;
    }
    
    .sidebar-menu-text {
        display: none;
        margin-left: 20px;
        font-size: 1.1em;
        font-weight: 500;
        white-space: nowrap;
    }
    
    .sidebar-menu-badge {
        position: absolute;
        top: -6px;
        right: -10px;
        background-color: #ed4956;
        color: #ffffff;
        border-radius: 10px;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 600;
        padding: 0 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .sidebar-menu-item-primary {
        background-color: #000000 !important;
        color: #ffffff !important;
        font-weight: 600;
        border: 1px solid #ffffff !important;
    }
    
    .sidebar-menu-item-primary:hover {
        background-color: #1a1a1a !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }
    
    .sidebar-menu-item-primary .sidebar-menu-icon i,
    .sidebar-menu-item-primary .sidebar-menu-text {
        color: #ffffff !important;
    }
    
    .sidebar-search-input-wrapper {
        display: none;
        width: 100%;
        padding: 0 10px;
        margin-bottom: 8px;
        flex-direction: row;
        align-items: center;
        gap: 8px;
        position: relative;
        z-index: 10;
        pointer-events: auto;
    }
    
    .sidebar-search-input-wrapper.show {
        display: flex !important;
    }
    
    .sidebar-search-input-wrapper.hidden {
        display: none !important;
    }
    
    .sidebar-search-input-wrapper .search-back-btn {
        background: transparent;
        border: none;
        color: #ffffff;
        font-size: 1.2em;
        cursor: pointer;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        pointer-events: auto;
        z-index: 11;
    }
    
    .sidebar-search-input-wrapper .search-back-btn:hover {
        opacity: 0.7;
    }
    
    .sidebar-search-input-wrapper .search-back-btn.hidden {
        display: none !important;
    }
    
    .sidebar-search-input-wrapper form {
        flex: 1;
        display: flex;
        pointer-events: auto;
        position: relative;
        z-index: 10;
    }
    
    .sidebar-search-input-wrapper .search-input {
        flex: 1;
        padding: 8px 12px;
        background-color: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        color: #ffffff;
        font-size: 1em;
        width: 100%;
        pointer-events: auto;
        cursor: text;
        z-index: 11;
        position: relative;
    }
    
    .sidebar-search-input-wrapper .search-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .sidebar-search-input-wrapper .search-input:focus {
        outline: none;
        background-color: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .sidebar-menu-item.search-toggle-item.hidden {
        display: none !important;
    }
    
    /* Hide autocomplete dropdown and search results container for sidebar search */
    .sidebar-search-input-wrapper .algolia-autocomplete .aa-dropdown-menu,
    .sidebar-search-input-wrapper ~ #container_finder,
    .sidebar-search-input-wrapper + * #container_finder {
        display: none !important;
        visibility: hidden !important;
    }
    
    /* Also hide nav_finder when sidebar search is active */
    body:has(.sidebar-search-input-wrapper.show) .nav_finder,
    body:has(.sidebar-search-input-wrapper.show) #container_finder {
        display: none !important;
        visibility: hidden !important;
    }
    
    .sidebar-user-menu {
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-user-menu-items {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 8px;
        padding-left: 0;
    }
    
    .sidebar-user-menu-item {
        padding-left: 20px !important;
    }
    
    .sidebar-user-toggle .sidebar-menu-icon img,
    .sidebar-user-toggle .sidebar-menu-icon .e_cover img,
    .sidebar-user-toggle .sidebar-menu-icon div.img {
        width: 24px !important;
        height: 24px !important;
    }
    
    .sidebar-user-toggle .sidebar-menu-icon .e_cover,
    .sidebar-user-toggle .sidebar-menu-icon {
        width: 24px !important;
        height: 24px !important;
        min-width: 24px !important;
    }
    
    /* Larger screens - show full menu */
    @media (min-width: 1024px) {
        .left-sidebar-menu {
            width: 280px;
            align-items: center;
        }
        
        .sidebar-logo {
            display: flex;
            justify-content: center;
        }
        
        .sidebar-menu-item {
            justify-content: center;
        }
        
        .sidebar-menu-text {
            display: block;
        }
        
        .sidebar-user-toggle {
            justify-content: center;
        }
        
        .sidebar-user-menu-items {
            padding-left: 0 !important;
        }
        
        .sidebar-user-menu-item {
            padding-left: 20px !important;
        }
        
        /* Adjust body padding to account for sidebar while keeping containers centered */
        body {
            padding-left: 280px;
        }
    }
    
    /* Medium screens - collapsed menu */
    @media (min-width: 768px) and (max-width: 1023px) {
        .left-sidebar-menu {
            width: 64px;
        }
        
        .sidebar-logo-title {
            display: none !important;
        }
        
        .sidebar-logo {
            justify-content: center;
        }
        
        /* Adjust body padding to account for sidebar while keeping containers centered */
        body {
            padding-left: 64px;
        }
    }
    
    /* Small screens - fixed bottom menu */
    @media (max-width: 767px) {
        .left-sidebar-menu {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            top: auto;
            height: auto;
            width: 100%;
            display: flex !important;
            visibility: visible !important;
            transform: none !important;
            flex-direction: row;
            padding: 10px 5px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            border-right: none;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-logo {
            display: flex !important;
            margin-bottom: 0;
            padding: 0;
            min-height: auto;
            position: relative;
            flex: 1;
            justify-content: center;
            align-items: center;
            border-radius: 25px;
            transition: all 0.2s ease;
        }
        
        .sidebar-logo:hover {
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .sidebar-logo-frame {
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 5px;
        }
        
        .sidebar-logo-frame .logo_cover {
            margin-right: 0;
        }
        
        .sidebar-logo-frame .logo_cover img {
            width: 32px !important;
            height: 32px !important;
        }
        
        .sidebar-logo-title {
            display: none !important;
        }
        
        .sidebar-menu-items {
            flex-direction: row;
            flex: 1;
            justify-content: space-around;
            gap: 0;
            margin: 0 5px;
        }
        
        .sidebar-menu-item {
            flex-direction: column;
            padding: 8px 5px;
            flex: 1;
            justify-content: center;
            min-width: 0;
            max-width: none;
        }
        
        .sidebar-menu-item.sidebar-menu-item-primary {
            flex: 1.3;
        }
        
        .sidebar-menu-text {
            display: none;
        }
        
        .sidebar-menu-icon {
            font-size: 1.3em;
        }
        
        .sidebar-menu-item {
            position: relative;
        }
        
        .sidebar-menu-icon {
            position: relative;
        }
        
        .sidebar-menu-badge {
            top: -4px;
            right: -8px;
        }
        
        .sidebar-user-menu {
            margin-top: 0;
            padding-top: 0;
            border-top: none;
            flex: 1;
            justify-content: center;
            align-items: center;
        }
        
        .sidebar-user-toggle {
            padding: 8px 5px !important;
            flex: 1;
            justify-content: center;
            border-radius: 25px;
            transition: all 0.2s ease;
        }
        
        .sidebar-user-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        
        .sidebar-user-toggle .sidebar-menu-icon {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-user-toggle .sidebar-menu-icon img,
        .sidebar-user-toggle .sidebar-menu-icon .e_cover img,
        .sidebar-user-toggle .sidebar-menu-icon div.img {
            width: 32px !important;
            height: 32px !important;
        }
        
        .sidebar-user-toggle .sidebar-menu-icon .e_cover {
            width: 32px !important;
            height: 32px !important;
        }
        
        .sidebar-user-toggle .sidebar-menu-text {
            display: none;
        }
        
        .sidebar-user-menu-items {
            position: fixed !important;
            bottom: 70px !important;
            left: 0 !important;
            right: 0 !important;
            background-color: rgba(0, 0, 0, 0.95) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
            padding: 15px !important;
            max-height: calc(100vh - 70px) !important;
            overflow-y: auto !important;
            z-index: 99999 !important;
            flex-direction: column !important;
            gap: 8px !important;
            margin-top: 0 !important;
            padding-left: 15px !important;
            pointer-events: auto !important;
            isolation: isolate !important;
        }
        
        .sidebar-user-menu-items .sidebar-user-menu-item {
            padding-left: 0 !important;
            padding: 12px 15px !important;
            width: 100% !important;
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            justify-content: flex-start !important;
            position: relative !important;
            z-index: 100000 !important;
            pointer-events: auto !important;
            cursor: pointer !important;
            gap: 12px !important;
            -webkit-tap-highlight-color: transparent !important;
        }
        
        .sidebar-user-menu-items .sidebar-user-menu-item * {
            pointer-events: none !important;
        }
        
        .sidebar-user-menu-items .sidebar-user-menu-item a,
        .sidebar-user-menu-items .sidebar-user-menu-item {
            pointer-events: auto !important;
        }
        
        .sidebar-user-menu-items .sidebar-user-menu-item .sidebar-menu-text {
            display: block !important;
            margin-left: 0 !important;
            font-size: 1.1em !important;
            font-weight: 500 !important;
            white-space: nowrap !important;
            color: #ffffff !important;
            flex: 1;
        }
        
        .sidebar-user-menu-items .sidebar-user-menu-item .sidebar-menu-icon {
            font-size: 1.5em !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 24px !important;
            width: 24px !important;
            color: #ffffff !important;
            position: relative !important;
            flex-shrink: 0;
        }
        
        .sidebar-user-menu-items .sidebar-user-menu-item .sidebar-menu-icon img,
        .sidebar-user-menu-items .sidebar-user-menu-item .sidebar-menu-icon .e_cover img,
        .sidebar-user-menu-items .sidebar-user-menu-item .sidebar-menu-icon div.img {
            width: 24px !important;
            height: 24px !important;
        }
        
        /* Adjust body padding to account for bottom menu */
        body {
            padding-left: 0;
            padding-bottom: 70px;
        }
    }
    ';

    echo ' </style>';
    ?>
    
    <script>
    // Override toggle_finder to also toggle sidebar search input
    (function() {
        var originalToggleFinder = window.toggle_finder;
        window.toggle_finder = function() {
            if (originalToggleFinder) {
                originalToggleFinder();
            }
            
            var searchInput = document.getElementById('sidebarSearchInput');
            var searchButton = document.querySelector('.search-toggle-item');
            var backButton = document.querySelector('.sidebar-search-input-wrapper .search-back-btn');
            
            if (searchInput && searchButton) {
                var isCurrentlyHidden = searchInput.style.display === 'none' || searchInput.style.display === '';
                
                if (isCurrentlyHidden) {
                    // Show search input, hide button
                    searchInput.style.display = 'flex';
                    searchInput.classList.add('show');
                    searchButton.classList.add('hidden');
                    if (backButton) {
                        backButton.style.display = 'flex';
                    }
                    // Hide search results dropdown and container
                    var containerFinder = document.getElementById('container_finder');
                    var navFinder = document.querySelector('.nav_finder');
                    if (containerFinder) {
                        containerFinder.style.display = 'none';
                        containerFinder.classList.add('hidden');
                    }
                    if (navFinder) {
                        navFinder.style.display = 'none';
                        navFinder.classList.add('hidden');
                    }
                    // Ensure input is clickable
                    var inputField = document.getElementById('website_finder');
                    if (inputField) {
                        inputField.style.pointerEvents = 'auto';
                        inputField.style.cursor = 'text';
                        inputField.disabled = false;
                        inputField.readOnly = false;
                        // Hide autocomplete dropdown
                        setTimeout(function() {
                            var autocompleteWrapper = inputField.closest('.algolia-autocomplete');
                            if (autocompleteWrapper) {
                                var dropdown = autocompleteWrapper.querySelector('.aa-dropdown-menu');
                                if (dropdown) {
                                    dropdown.style.display = 'none';
                                    dropdown.style.visibility = 'hidden';
                                }
                            }
                        }, 100);
                    }
                    // Focus the input after a short delay
                    setTimeout(function() {
                        if (inputField) {
                            inputField.focus();
                            inputField.select();
                            // Hide dropdown again after focus (in case autocomplete tries to show it)
                            setTimeout(function() {
                                var autocompleteWrapper = inputField.closest('.algolia-autocomplete');
                                if (autocompleteWrapper) {
                                    var dropdown = autocompleteWrapper.querySelector('.aa-dropdown-menu');
                                    if (dropdown) {
                                        dropdown.style.display = 'none';
                                        dropdown.style.visibility = 'hidden';
                                    }
                                }
                                // Also hide container_finder
                                if (containerFinder) {
                                    containerFinder.style.display = 'none';
                                    containerFinder.classList.add('hidden');
                                }
                            }, 100);
                        }
                    }, 200);
                } else {
                    // Hide search input, show button
                    searchInput.style.display = 'none';
                    searchInput.classList.remove('show');
                    searchButton.classList.remove('hidden');
                    if (backButton) {
                        backButton.style.display = 'none';
                    }
                }
            }
        };
    })();
    
    // Hide autocomplete dropdown when typing in sidebar search
    (function() {
        function hideSearchDropdown() {
            var searchInputWrapper = document.querySelector('.sidebar-search-input-wrapper.show');
            if (searchInputWrapper) {
                var containerFinder = document.getElementById('container_finder');
                var navFinder = document.querySelector('.nav_finder');
                var inputField = document.getElementById('website_finder');
                
                if (containerFinder) {
                    containerFinder.style.display = 'none';
                    containerFinder.classList.add('hidden');
                }
                if (navFinder) {
                    navFinder.style.display = 'none';
                    navFinder.classList.add('hidden');
                }
                if (inputField) {
                    var autocompleteWrapper = inputField.closest('.algolia-autocomplete');
                    if (autocompleteWrapper) {
                        var dropdown = autocompleteWrapper.querySelector('.aa-dropdown-menu');
                        if (dropdown) {
                            dropdown.style.display = 'none';
                            dropdown.style.visibility = 'hidden';
                        }
                    }
                }
            }
        }
        
        // Monitor for dropdown appearance
        var observer = new MutationObserver(function(mutations) {
            hideSearchDropdown();
        });
        
        // Start observing when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                observer.observe(document.body, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                    attributeFilter: ['style', 'class']
                });
                
                // Also listen to input events
                setTimeout(function() {
                    var inputField = document.getElementById('website_finder');
                    if (inputField) {
                        inputField.addEventListener('input', hideSearchDropdown);
                        inputField.addEventListener('keyup', hideSearchDropdown);
                        inputField.addEventListener('focus', hideSearchDropdown);
                    }
                }, 500);
            });
        } else {
            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['style', 'class']
            });
            
            setTimeout(function() {
                var inputField = document.getElementById('website_finder');
                if (inputField) {
                    inputField.addEventListener('input', hideSearchDropdown);
                    inputField.addEventListener('keyup', hideSearchDropdown);
                    inputField.addEventListener('focus', hideSearchDropdown);
                }
            }, 500);
        }
    })();
    
    function toggleSidebarUserMenu() {
        var menuItems = document.getElementById('sidebarUserMenuItems');
        if (menuItems) {
            var isMobile = window.innerWidth <= 767;
            if (menuItems.style.display === 'none' || menuItems.style.display === '') {
                menuItems.style.display = 'flex';
                if (isMobile) {
                    // Add overlay for mobile - only covers area above menu
                    setTimeout(function() {
                        var overlay = document.createElement('div');
                        overlay.id = 'sidebarUserMenuOverlay';
                        var menuRect = menuItems.getBoundingClientRect();
                        var menuTop = menuRect.top;
                        overlay.style.cssText = 'position: fixed; top: 0; left: 0; right: 0; bottom: ' + (window.innerHeight - menuTop) + 'px; background-color: rgba(0, 0, 0, 0.5); z-index: 99998; pointer-events: auto;';
                        overlay.onclick = function() {
                            toggleSidebarUserMenu();
                        };
                        document.body.appendChild(overlay);
                    }, 10);
                }
            } else {
                menuItems.style.display = 'none';
                // Remove overlay if exists
                var overlay = document.getElementById('sidebarUserMenuOverlay');
                if (overlay) {
                    overlay.remove();
                }
            }
        }
    }
    </script>

    <link href="https://fonts.googleapis.com/css?family=<?= join('|', $google_fonts) ?>&display=swap" rel="stylesheet">

</head>

<?php

echo '<body class="' . $body_class . '" id="main_body">';
echo $bgVideo;

//Left Sidebar Menu
if (!$basic_header_footer) {
    echo '<nav class="left-sidebar-menu" id="leftSidebarMenu">';
    echo '<div class="sidebar-logo">';
    echo '<div class="sidebar-logo-frame">' . (strlen($domain_cover) ? '<a href="' . view_memory(42903, 14565) . '" class="icon-block logo_cover">' . view_cover($domain_logo) . '</a>' : '') . '<a href="' . view_memory(42903, 14565) . '" class="main__title logo_title sidebar-logo-title">' . get_domain('m__name') . '</a></div>';
    echo '</div>';
    echo '<div class="sidebar-menu-items">';
    // Search input wrapper (hidden by default, shown when search is active)
    if (isset($users___11035[7256])) {
        echo '<div class="sidebar-search-input-wrapper" id="sidebarSearchInput" style="display: none;">';
        echo '<button type="button" class="search-back-btn" onclick="toggle_finder()" title="Close Search"><i class="fas fa-arrow-left"></i></button>';
        echo '<form id="searchFrontForm" style="flex: 1; display: flex;">';
        echo '<input class="form-control algolia_finder search-input sidebar-search-input" type="search" id="website_finder" data-lpignore="true" placeholder="' . $users___11035[7256]['m__name'] . '" style="pointer-events: auto; cursor: text;">';
        echo '</form>';
        echo '</div>';
    }
    // Search button (shown by default, hidden when search is active)
    echo '<a href="javascript:void(0);" class="sidebar-menu-item search-toggle-item" onclick="toggle_finder()"><span class="sidebar-menu-icon"><i class="fas fa-search"></i></span><span class="sidebar-menu-text">Search</span></a>';
    echo '<a href="javascript:void(0);" class="sidebar-menu-item sidebar-menu-item-primary" onclick="post_edit_start()"><span class="sidebar-menu-icon"><i class="fas fa-plus"></i></span><span class="sidebar-menu-text">Prompt</span></a>';
    echo '<a href="/apps" class="sidebar-menu-item"><span class="sidebar-menu-icon"><i class="far fa-slash-forward fa-sharp"></i></span><span class="sidebar-menu-text">Apps</span></a>';
    echo '</div>';
    
    //User menu at bottom - expandable
    $menu_type = ($user_session ? 12500 : 14372);
    echo '<div class="sidebar-user-menu">';
    echo '<a href="javascript:void(0);" class="sidebar-menu-item sidebar-user-toggle" onclick="toggleSidebarUserMenu()">';
    echo '<span class="sidebar-menu-icon"><i class="fas fa-ellipsis"></i></span>';
    echo '<span class="sidebar-menu-text">More</span>';
    echo '</a>';
    echo '<div class="sidebar-user-menu-items" id="sidebarUserMenuItems" style="display: none;">';
                foreach ($this->config->item('users___' . $menu_type) as $chainusertype => $m) {

                    $superpowers_required = array_intersect($this->config->item('userids___10957'), $m['m__following']);
                    if (count($superpowers_required) && !user_session(end($superpowers_required))) {
                        continue;
                    }

                    $hosted_domains = array_intersect($this->config->item('userids___14870'), $m['m__following']);
                    if (count($hosted_domains) && !in_array($website_id, $hosted_domains)) {
                        continue;
                    }

                    $extra_class = null;
                    $text_class = null;

                    if ($chainusertype == 26105 && $user_session) {

                        //Profile View
                        $m['m__cover'] = view_cover($user_session['usercover'], 1);
            $m['m__name'] = $user_session['username'];
            $text_class = 'type_head main__title';
                        $href = 'href="' . view_memory(42903, 42902) . $user_session['userhandle'] . '" ';

                    } elseif ($chainusertype == 42246 && $user_session) {

                        //Profile Edit
                        $href = 'href="javascript:void(0);" onclick="user_editor(' . $user_session['userid'] . ',0)" ';

                    } elseif ($chainusertype == 28615) {

                        //Phone US
                        $value = website_setting($chainusertype);
                        if (!strlen($value)) {
                            continue;
                        }
                        $href = 'href="tel:' . preg_replace("/[^0-9]/", "", $value) . '"';

                    } elseif ($chainusertype == 28614) {

                        //Email US
                        $value = website_setting($chainusertype);
                        if (!strlen($value)) {
                            continue;
                        }
                        $href = 'href="mailto:' . $value . '"';

                    } elseif (in_array($chainusertype, $this->config->item('userids___6287'))) {

            //APP - Handle apps (logout will be included if it's in userids___6287)
            // Skip Apps menu item as it's now in the main sidebar menu
            // Only show logout and other non-app items
            if ($chainusertype == 4269) {
                // Logout - keep it in dropdown
                $href = 'href="' . view_app_chain($chainusertype) . (isset($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : '') . '"';
            } else {
                // Skip other apps - they're now in the main Apps menu
                continue;
            }

                    } else {

                        //Unknown
                        continue;

                    }

        //Navigation - styled like sidebar menu items
        echo '<a ' . $href . ' chainusertype="' . $chainusertype . '" class="sidebar-menu-item sidebar-user-menu-item ' . $extra_class . '">';
        echo '<span class="sidebar-menu-icon">' . $m['m__cover'] . '</span>';
        if ($chainusertype == 26105 && $user_session) {
            echo '<span class="sidebar-menu-text"><span class="type_head main__title">' . $user_session['username'] . '</span><span class="grey type_user">@' . $user_session['userhandle'] . '</span></span>';
        } else {
            echo '<span class="sidebar-menu-text ' . ($text_class ? $text_class : '') . '">' . strip_tags($m['m__name']) . '</span>';
        }
        echo '</a>';

    }
                echo '</div>';
                echo '</div>';
    echo '</nav>';
}

//JS Variables for this app on page
if ($focus_post) {
    echo '<input type="hidden" id="focus__node" value="12273" />
<input type="hidden" id="focus_handle" value="' . $focus_post['posthashtag'] . '" />
<input type="hidden" id="focus__id" value="' . $focus_post['postid'] . '" />';
    if ($target_post) {
        echo '<input type="hidden" id="target_posthashtag" value="' . $target_post['posthashtag'] . '" />
        <input type="hidden" id="target_postid" value="' . $target_post['postid'] . '" />';
    }
} elseif ($focus_e) {
    echo '<input type="hidden" id="focus__node" value="12274" />
<input type="hidden" id="focus_handle" value="' . $focus_e['userhandle'] . '" />
<input type="hidden" id="focus__id" value="' . $focus_e['userid'] . '" />';
}

//Do not show for /sign view
?>
<?php

echo '<div id="container_finder" class="container hidden hideIfEmpty"><div class="row justify-content hideIfEmpty"></div></div>';
echo '<div id="container_main" class="container container_content">';

//Any message we need to show here?
if (!isset($flash_message) || !strlen($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}

if (strlen($flash_message) > 0) {

    //Delete from Flash:
    $this->session->unmark_flash('flash_message');

    echo '<div class="' . ($basic_header_footer ? ' center-info ' : '') . ' center" id="flash_message">' . $flash_message . '</div>';

}


$user_session = user_session();

if ($user_session) {
    //For profile editing only:
    echo '<div class="hidden">';
    echo user_view(42287, $user_session, null);
    echo '</div>';
}

if ($user_session && (!isset($basic_header_footer) || !$basic_header_footer)) {

    $dynamic_edit = '';
    for ($p = 1; $p <= view_memory(6404, 42206); $p++) {
        $dynamic_edit .= '<div class="dynamic_item hidden dynamic_' . $p . '" d__id="" d_chainid="">';
        $dynamic_edit .= '<div class="inner_dynamic">';
        $dynamic_edit .= '<div class="text_content">';
        $dynamic_edit .= '<h3 class="mini-font"></h3>';
        $dynamic_edit .= '<input type="text" class="form-control unsaved_warning save_dynamic_' . $p . '" value="">';
        $dynamic_edit .= '</div>';
        $dynamic_edit .= '</div>';
        $dynamic_edit .= '</div>';
    }

    //Apply to All Users
    if (user_session(12700)) {
        ?>
        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal4997" tabindex="-1"
             role="dialog" aria-labelledby="modal4997Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content long_flat">
                    <form method="POST" action="<?= view_app_chain(27196) ?>?focus__id=12274">
                        <div class="modal-header">
                            <div class="initial_header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <button type="submit" class="btn btn-default">APPLY</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="s__id" value=""/>
                            <?php

                            //Mass Editor:
                            $dropdown_options = '';
                            $input_options = '';
                            $editor_counter = 0;

                            foreach ($this->config->item('users___4997') as $action_userid => $user_list_action) {


                                $editor_counter++;
                                $dropdown_options .= '<option value="' . $action_userid . '" title="' . $user_list_action['m__message'] . '">' . $user_list_action['m__name'] . '</option>';
                                $is_upper = (in_array($action_userid, $this->config->item('userids___12577') /* USER UPDATER UPPERCASE */) ? ' main__title ' : false);


                                //Start with the input wrapper:
                                $input_options .= '<span title="' . $user_list_action['m__message'] . '" class="mass_id_' . $action_userid . ' inline-block ' . ($editor_counter > 1 ? ' hidden ' : '') . ' mass_action_item">';


                                if (in_array($action_userid, array(5000, 5001, 10625))) {

                                    //String Find and Replace:

                                    //Find:
                                    $input_options .= '<input type="text" name="mass_value1_' . $action_userid . '" placeholder="Search" class="form-control border ' . $is_upper . '">';

                                    //Replace:
                                    $input_options .= '<input type="text" name="mass_value2_' . $action_userid . '" placeholder="Replace" class="form-control border ' . $is_upper . '">';


                                } elseif (in_array($action_userid, array(5981, 5982, 13441))) {

                                    //Member search box:

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_' . $action_userid . '"  placeholder="Search Users" class="form-control algolia_finder user_text_finder border ' . $is_upper . '">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="hidden" name="mass_value2_' . $action_userid . '" value="" placeholder="Search User" />';

                                } elseif ($action_userid == 11956) {

                                    //If Has THIS
                                    $input_options .= '<input type="text" name="mass_value1_' . $action_userid . '"  placeholder="IF THIS USER" class="form-control algolia_finder user_text_finder border ' . $is_upper . '">';

                                    //ADD THIS
                                    $input_options .= '<input type="text" name="mass_value2_' . $action_userid . '"  placeholder="ADD THIS USER" class="form-control algolia_finder user_text_finder border ' . $is_upper . '">';

                                } elseif ($action_userid == 42804) {

                                    //Chain Type update:

                                    //Find:
                                    $input_options .= '<select name="mass_value1_' . $action_userid . '" class="form-control border">';
                                    $input_options .= '<option value="*">Update All Interaction Types</option>';
                                    foreach ($this->config->item('users___32292') /* User Chains */ as $chainusertype3 => $m3) {
                                        $input_options .= '<option value="' . $chainusertype3 . '">Update Only If = ' . $m3['m__name'] . '</option>';
                                    }
                                    $input_options .= '</select>';

                                    //Replace:
                                    $input_options .= '<select name="mass_value2_' . $action_userid . '" class="form-control border">';
                                    $input_options .= '<option value="">Set New Status</option>';
                                    foreach ($this->config->item('users___32292') /* User Chains */ as $chainusertype3 => $m3) {
                                        $input_options .= '<option value="' . $chainusertype3 . '">Set to ' . $m3['m__name'] . '</option>';
                                    }
                                    $input_options .= '</select>';


                                } else {

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_' . $action_userid . '"  placeholder="String" class="form-control border ' . $is_upper . '">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="hidden" name="mass_value2_' . $action_userid . '" value="" />';

                                }

                                $input_options .= '</span>';

                            }

                            //Drop Down
                            echo '<select class="form-control border mass_action_toggle" name="mass_action_toggle">';
                            echo $dropdown_options;
                            echo '</select>';

                            echo $input_options;

                            ?>
                            <div class="chain_preview"></div>
                        </div>
                </div>
                </form>
            </div>
        </div>
        <?php
    }


    //Apply to All Posts
    if (user_session(12700)) {
        ?>
        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal12589" tabindex="-1"
             role="dialog" aria-labelledby="modal12589Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content long_flat">

                    <form method="POST" action="<?= view_app_chain(27196) ?>?focus__id=12273">

                        <div class="modal-header">
                            <div class="initial_header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <button type="submit" class="btn btn-default">APPLY</button>
                        </div>

                        <div class="modal-body">

                            <input type="hidden" name="s__id" value=""/>
                            <?php

                            //POST LIST EDITOR
                            $dropdown_options = '';
                            $input_options = '';
                            $this_counter = 0;

                            foreach ($this->config->item('users___12589') as $action_userid => $user_list_action) {

                                $this_counter++;
                                $dropdown_options .= '<option value="' . $action_userid . '">' . $user_list_action['m__name'] . '</option>';


                                //Start with the input wrapper:
                                $input_options .= '<span title="' . $user_list_action['m__message'] . '" class="mass_id_' . $action_userid . ' inline-block ' . ($this_counter > 1 ? ' hidden ' : '') . ' mass_action_item">';

                                if (in_array($action_userid, array(12591, 27080, 27985, 27082, 27084, 27086))) {

                                    //User search box:

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_' . $action_userid . '"  placeholder="Search Users" class="form-control algolia_finder user_text_finder border main__title">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="text" name="mass_value2_' . $action_userid . '" value="" />';

                                } elseif (in_array($action_userid, array(12592, 27081, 27986, 27083, 27085, 27087))) {

                                    //User search box:

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_' . $action_userid . '"  placeholder="Search Users" class="form-control algolia_finder user_text_finder border main__title">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="hidden" name="mass_value2_' . $action_userid . '" value="" />';

                                } elseif (in_array($action_userid, array(12611, 12612, 27240, 28801))) {

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_' . $action_userid . '"  placeholder="Search Posts" class="form-control algolia_finder i_text_finder border main__title">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="hidden" name="mass_value2_' . $action_userid . '" value="" />';

                                }

                                $input_options .= '</span>';

                            }

                            //Drop Down
                            echo '<select class="form-control border mass_action_toggle" name="mass_action_toggle">';
                            echo $dropdown_options;
                            echo '</select>';

                            echo $input_options;

                            ?>
                            <div class="chain_preview"></div>
                        </div>
                </div>
                </form>
            </div>
        </div>

        <?php
    }

    if ($user_session) {

        $users___12273 = $this->config->item('users___12273'); //POST Cache
        $users___12274 = $this->config->item('users___12274'); //User Cache

        ?>


        <!-- Post Modal -->
        <div class="i_footer_note hidden">Posts saved. <a href=""><b>View</b></a></div>
        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal31911" tabindex="-1"
             role="dialog" aria-labelledby="modal31911Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content long_flat">

                    <div class="modal-header">
                        <div class="initial_header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <button type="button" class="btn btn-default post_edit_save post_button" onclick="post_edit_save()">
                            POST
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="save_results hideIfEmpty alert alert-danger" style="margin:8px 0;"></div>

                        <input type="hidden" class="created_postid" value="0"/>
                        <input type="hidden" class="save_postid" value="0"/>
                        <input type="hidden" class="save_chainid" value="0"/>

                        <!-- Post Hashtag -->
                        <div class="dynamic_editing_input single_line hash_group hidden_superpower__10939">
                            <h3 class="mini-font"><span
                                        class="icon-block"><?= $users___12273[32337]['m__cover'] ?></span></h3>
                            <input type="text" class="form-control unsaved_warning save_posthashtag no-border"
                                   placeholder="<?= $users___12273[32337]['m__name'] ?>"
                                   maxlength="<?= view_memory(6404, 41985) ?>"
                                   title="<?= $users___12273[32337]['m__message'] ?>">
                        </div>

                        <!-- Post Creator -->
                        <div class="creator_box">
                            <?php
                            //Always append current user:
                            echo '<div class="creator_headline first_headline"><span class="icon-block">' . view_cover($user_session['usercover']) . '</span></div>';
                            ?>
                        </div>

                        <!-- Post Message -->
                        <div class="dynamic_editing_input" style="margin: 0 !important;">
                            <textarea
                                    class="form-control note-textarea algolia_finder new-note editing-mode unsaved_warning algolia__e algolia__i save_postmessageraw"
                                    placeholder="<?= (strlen($users___12273[4736]['m__message']) ? $users___12273[4736]['m__message'] : $users___12273[4736]['m__name']) ?>"
                                    style="margin:0; width:100%; background-color: #FFFFFF !important;"></textarea>
                        </div>

                        <!-- Post Footnote -->
                        <div class="dynamic_editing_input" style="margin: -8px 0 0 0 !important;">
                            <h3 class="mini-font" style="margin-bottom: -35px;"><span
                                        class="icon-block"><?= $users___11035[3449971]['m__cover'] ?></span></h3>
                            <textarea
                                    class="form-control note-textarea algolia_finder editing-mode algolia__e algolia__i save_postfootnote"
                                    placeholder="<?= (strlen($users___11035[3449971]['m__message']) ? $users___11035[3449971]['m__message'] : $users___11035[3449971]['m__name']) ?>"
                                    style="margin:0; width:100%; background-color: #FFFFFF !important;"></textarea>
                        </div>

                        <div class="inner_message left_padded">
                            <?php
                            foreach ($this->config->item('users___44168') as $userid => $m) {

                                if ($userid == 2125205) {

                                    //Suggested Posts
                                    echo '<div class="dynamic_editing_input no_padded compact_dropdown hidden_superpower__10939">';
                                    echo '<button class="btn btn-secondary dropdown-toggle icon-block" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="' . $m['m__name'] . '" id="suggest_' . $userid . '">' . $m['m__cover'] . '</button>';

                                    echo '<ul class="dropdown-menu left-padded-menu suggest_menu suggest__' . $userid . '" aria-labelledby="suggest_' . $userid . '">';
                                    foreach ($this->config->item('users___2125205') as $userid2 => $m2) {

                                        //Print Header
                                        echo '<li class="grey"><span class="dropdown-item" title="' . $m2['m__message'] . '"><span class="icon-block-sm">' . $m2['m__cover'] . '</span>' . $m2['m__name'] . ':</span></li>';

                                        if ($userid2 == 4486) {

                                            //Ideas
                                            foreach ($this->config->item('users___4486') as $userid3 => $m3) {
                                                echo '<li class="inline-block"><a class="dropdown-item inline-block text_adder ' . (strlen($m3['m__message']) ? 'underdot' : '') . '" href="javascript:void(0);" text_value="' . $m3['m__cover'] . '" title="' . $m3['m__message'] . '"><b>' . $m3['m__cover'] . '</b>' . str_replace(' ','', $m3['m__name']) . '</a></li>';
                                            }

                                        }

                                    }
                                    echo '</ul>';
                                    echo '</div>';

                                } elseif ($userid == 2125246) {

                                    //Suggested Users
                                    echo '<div class="dynamic_editing_input no_padded compact_dropdown hidden_superpower__10939">';
                                    echo '<button class="btn btn-secondary dropdown-toggle icon-block" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="' . $m['m__name'] . '" id="suggest_' . $userid . '">' . $m['m__cover'] . '</button>';

                                    echo '<ul class="dropdown-menu left-padded-menu suggest_menu suggest__' . $userid . '" aria-labelledby="suggest_' . $userid . '">';
                                    foreach ($this->config->item('users___2125246') as $userid2 => $m2) {

                                        //Print Header
                                        echo '<li class="grey item__'.$userid2.'"><span class="dropdown-item" title="' . $m2['m__message'] . '"><span class="icon-block-sm">' . $m2['m__cover'] . '</span>' . $m2['m__name'] . ':</span></li>';

                                        if ($userid2 == 13550) {

                                            //Mentions
                                            foreach ($this->config->item('users___13550') as $userid3 => $m3) {
                                                echo '<li class="inline-block item__'.$userid2.' item__'.$userid3.'"><a class="dropdown-item inline-block text_adder ' . (strlen($m3['m__message']) ? 'underdot' : '') . '" href="javascript:void(0);" text_value="' . $m3['m__cover'] . '" title="' . $m3['m__message'] . '"><b>' . $m3['m__cover'] . '</b>' . str_replace(' ','', $m3['m__name']) . '</a></li>';
                                            }

                                        } elseif ($userid2 == 4737) {

                                            //Form Inputs
                                            foreach ($this->config->item('users___4737') as $userid3 => $m3) {
                                                echo '<li class="inline-block item__'.$userid2.' item__'.$userid3.'"><a class="dropdown-item inline-block text_adder ' . (strlen($m3['m__message']) ? 'underdot' : '') . '" href="javascript:void(0);" text_value="@' . $m3['m__handle'] . data_type_example($userid3) . '" title="' . $m3['m__name'] . (strlen($m3['m__message']) ? ': ' . $m3['m__message'] : '') . '">@' . $m3['m__handle'] . '</a></li>';
                                            }

                                        } elseif ($userid2 == 42179) {

                                            //Form Settings
                                            foreach ($this->config->item('users___42179') as $userid3 => $m3) {
                                                echo '<li class="inline-block item__'.$userid2.' item__'.$userid3.'"><a class="dropdown-item inline-block text_adder" href="javascript:void(0);" text_value="@' . $m3['m__handle'] . data_type_example($userid3) . '" title="' . $m3['m__name'] . (strlen($m3['m__message']) ? ': ' . $m3['m__message'] : '') . '">@' . $m3['m__handle'] . '</a></li>';
                                            }

                                        } elseif ($userid2 == 6287) {

                                            //Apps
                                            foreach ($this->config->item('users___30841') as $userid3 => $m3) {
                                                echo '<li class="inline-block item__'.$userid2.' item__'.$userid3.'"><a class="dropdown-item inline-block text_adder ' . (strlen($m3['m__message']) ? 'underdot' : '') . '" href="javascript:void(0);" text_value="@' . $m3['m__handle'] . data_type_example($userid3) . '" title="' . $m3['m__name'] . (strlen($m3['m__message']) ? ': ' . $m3['m__message'] : '') . '">@' . $m3['m__handle'] . '</a></li>';
                                            }

                                        }
                                    }
                                    echo '</ul>';
                                    echo '</div>';

                                } elseif ($userid == 13572) { //Upload File

                                    echo '<div class="dynamic_editing_input no_padded">
                                        <a class="uploader_13572 icon-block" href="javascript:void(0)" title="' . $m['m__name'] . '">' . $m['m__cover'] . '</a>
                                    </div>';

                                } elseif ($userid == 44170) { //ADD EMOJI

                                    echo '<div class="dynamic_editing_input no_padded" style="margin: 0 !important;">
                                        <div class="dropdown emoji_selector">
                                            <button type="button" class="btn no-left-padding no-right-padding icon-block" id="emoji_i" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="' . $m['m__name'] . '">' . $m['m__cover'] . '</button>
                                            <div class="dropdown-menu emoji_i" aria-labelledby="emoji_i"></div>
                                        </div>
                                    </div>';

                                } elseif ($userid == 3449936) { //Post Editor Processing

                                    echo '<div class="frame_3449936 full_width_box inline-block"></div>';

                                }
                            }
                            ?>
                            <div class="doclear">&nbsp;</div>
                        </div>


                        <div style="margin-left: 40px;">
                            <div class="preview_postmessageview hideIfEmpty"></div>
                            <div class="doclear">&nbsp;</div>
                        </div>


                        <div class="hidden_superpower__10939 left_padded">
                            <!-- Chain Note -->
                            <div class="dynamic_editing_input save_frame hidden">
                                <h3 class="mini-font"><?= '<span class="icon-block-sm">' . $users___11035[4372]['m__cover'] . '</span>' . $users___11035[4372]['m__name'] . ': '; ?></h3>
                                <textarea class="form-control border unsaved_warning save_chainvalue"
                                          data-lpignore="true" placeholder="..."></textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!-- User Modal -->
        <div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal31912" tabindex="-1"
             role="dialog" aria-labelledby="modal31912Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content long_flat">

                    <div class="modal-header">
                        <div class="initial_header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <button type="button" class="user_save_edit btn btn-default post_button"
                                onclick="user_save_edit()">SAVE
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="save_results hideIfEmpty alert alert-danger" style="margin:8px 0;"></div>

                        <input type="hidden" class="save_userid" value="0"/>
                        <input type="hidden" class="save_chainid" value="0"/>


                        <!-- User Handle -->
                        <div class="dynamic_editing_input">
                            <h3 class="mini-font"><?= '<span class="icon-block">' . $users___12274[32338]['m__cover'] . '</span><input type="text" class="form-control unsaved_warning save_userhandle" style="margin-top: -20px;" placeholder="' . (strlen($users___12274[32338]['m__message']) ? $users___12274[32338]['m__message'] : $users___12274[32338]['m__name']) . '">'; ?></h3>
                        </div>

                        <!-- User Title -->
                        <div class="dynamic_editing_input">
                            <h3 class="mini-font"><?= '<span class="icon-block">' . $users___12274[6197]['m__cover'] . '</span><textarea class="form-control unsaved_warning save_username main__title" placeholder="' . (strlen($users___12274[6197]['m__message']) ? $users___12274[6197]['m__message'] : $users___12274[6197]['m__name']) . '" style="margin:0; width:100%; margin-top: -30px; background-color: #FFFFFF !important;"></textarea>'; ?></h3>
                        </div>

                        <!-- User Bio -->
                        <div class="dynamic_editing_input" style="margin: 0 !important;">
                            <h3 class="mini-font"><?= '<span class="icon-block">' . $users___12274[3423966]['m__cover'] . '</span>' ?>
                                <textarea
                                        class="form-control note-textarea algolia_finder editing-mode unsaved_warning algolia__e algolia__i save_userbio"
                                        placeholder="<?= (strlen($users___12274[3423966]['m__message']) ? $users___12274[3423966]['m__message'] : $users___12274[3423966]['m__name']) ?>"
                                        style="margin:-23px 0 0 0; width:100%; background-color: #FFFFFF !important;"></textarea>
                            </h3>
                        </div>


                        <div class="dynamic_editing_input">

                            <h3 class="mini-font"
                                style="margin-bottom: -38px;"><?= '<span class="icon-block">' . $users___12274[6198]['m__cover'] . '</span>'; ?></h3>

                            <!-- Cover HIDDEN Input -->
                            <input type="text"
                                   class="form-control unsaved_warning save_usercover hidden_superpower__13758"
                                   data-lpignore="true"
                                   placeholder="<?= (strlen($users___12274[6198]['m__message']) ? $users___12274[6198]['m__message'] : $users___12274[6198]['m__name']) ?>">


                            <!-- USER COVER -->
                            <div class="message_controllers">
                                <table class="emoji_table">
                                    <tr>
                                        <td>
                                            <!-- Upload Cover -->
                                            <a class="uploader_3467376" class="icon-block-sm" href="javascript:void(0);"
                                               title="<?= $users___11035[3467376]['m__name'] ?>"><?= $users___11035[3467376]['m__cover'] ?></a>
                                        </td>
                                        <td class="hidden_superpower__13758">
                                            <!-- EMOJI -->
                                            <div class="icon-block-sm">
                                                <div class="dropdown emoji_selector"
                                                     style="max-height: 21px; margin-top: -18px;">
                                                    <button type="button" class="btn no-left-padding no-right-padding"
                                                            id="emoji_e" data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false"><i class="far fa-face-smile"></i>
                                                    </button>
                                                    <div class="dropdown-menu emoji_e" aria-labelledby="emoji_e"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hidden_superpower__13758">
                                            <!-- Font Awesome Insert -->
                                            <a href="javascript:void(0);" class="icon-block-sm"
                                               onclick="updatusercover('far fa-icons')" title="Use Font Awesome"><i
                                                        class="far fa-icons"></i></a>
                                        </td>
                                        <td>
                                            <!-- Ramdom Animal -->
                                            <a href="javascript:void(0);" class="random_animal"
                                               onclick="updatusercover('hide '+random_animal())"
                                               title="Set a random animal"></a>
                                        </td>
                                    </tr>
                                </table>
                            </div>


                            <!-- Font Awesome Search -->
                            <div class="hidden_superpower__13758 fa_search hidden">
                                <a href="https://fontawesome.com/search" class="icon-block-sm" target="_blank"
                                   title="Open New Window to Search on Font Awesome"><i class="far fa-search-plus"></i></a>
                            </div>
                            <div class="doclear">&nbsp;</div>

                            <div>

                                <!-- Cover Preview -->
                                <div class="section_demo ">
                                    <div class="card_cover preview_cover">
                                        <div class="cover-wrapper uploader_3467376">
                                            <div class="black-background-obs cover-chain" style="">
                                                <div class="cover-btn"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div style="text-align: center;"><a class="uploader_3467376" class="btn btn-lrg"
                                                                    href="javascript:void(0);"><?= '<span class="icon-block">' . $users___11035[3467376]['m__cover'] . '</span>' . $users___11035[3467376]['m__name'] ?></a>
                                </div>

                            </div>
                        </div>


                        <!-- Chain Note -->
                        <div class="dynamic_editing_input save_frame hidden">
                            <h3 class="mini-font"><?= '<span class="icon-block">' . $users___11035[4372]['m__cover'] . '</span>' . $users___11035[4372]['m__name'] . ': '; ?></h3>
                            <textarea class="form-control border unsaved_warning save_chainvalue" data-lpignore="true"
                                      placeholder="..."></textarea>
                        </div>


                        <!-- Dynamic Loader -->
                        <div class="dynamic_editing_loading hidden"><span class="icon-block-sm"><i
                                        class="fas fa-yin-yang fa-spin"></i></span>Loading
                        </div>

                        <!-- Dynamic Inputs -->
                        <div class="dynamic_frame"><?= $dynamic_edit ?></div>

                    </div>
                    <div class="modal-footer hideIfEmpty"></div>
                </div>
            </div>
        </div>


        <?php

    }

}


}
?>
