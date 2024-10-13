<?php
$player_e = superpower_unlocked();
$first_segment = $this->uri->segment(1);
$e_segment = view_valid_handle_e($first_segment);
$second_segment = $this->uri->segment(2);
$e___11035 = $this->config->item('e___11035'); //Encyclopedia
$e___14870 = $this->config->item('e___14870'); //Website Partner
$handle___40904 = $this->config->item('handle___40904');
$website_id = website_setting(0);
$website_favicon = website_setting(31887);
$basic_header_footer = in_array($app_e__id, $this->config->item('n___14562'));
$domain_link = one_two_explode("\"","\"",get_domain('m__cover'));
$logo = ( $website_favicon ? $website_favicon : ( filter_var($domain_link, FILTER_VALIDATE_URL) ? $domain_link : 'https://s3foundation.s3.us-west-2.amazonaws.com/yin-yang-solid.svg' ));
$bgVideo = null;

//Transaction Website
$domain_cover = get_domain('m__cover');
$domain_logo = ( substr_count($domain_cover, '"')>0 ? one_two_explode('"','"', $domain_cover) : $domain_cover );
$is_emoji = ( !filter_var($domain_logo, FILTER_VALIDATE_URL) && !string_is_icon($domain_logo) );


//Generate Body Class String:
$body_class = ' app__'.$app_e__id.' '; //Always append current coin
foreach($this->config->item('e___13890') as $e__id => $m){
    if($player_e){
        //Look at their session:
        $body_class .= ' custom_ui_'.$e__id.'_'.$this->session->userdata('session_custom_ui_'.$e__id).' ';
    } else {

        $this_class = '';

        //Fetch Website Defaults:
        foreach(array_intersect($this->config->item('n___'.$e__id), $e___14870[$website_id]['m__following']) as $this_e_id) {
            $this_class = ' custom_ui_'.$e__id.'_'.$this_e_id.' ';
        }

        //If not found, fetch platform defaults:
        if(!strlen($this_class)){
            $e___4527 = $this->config->item('e___4527');
            foreach(array_intersect($this->config->item('n___'.$e__id), $e___4527[6404]['m__following']) as $this_e_id) {
                $this_class = ' custom_ui_'.$e__id.'_'.$this_e_id.' ';
            }
        }

        $body_class .= $this_class;
    }
}


if($website_id==39599){
    $body_class .= ' center-align dark-theme ';
}

?><!doctype html>
<html lang="en" >
<head>

    <meta charset="utf-8">

    <meta name="theme-color" content="#FFFFFF">
    <link rel="icon" id="favicon" href="<?= $logo ?>">
    <?php
    if($is_emoji){
        echo '<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>'.$domain_logo.'</text></svg>">';
    } else {
        echo '<link rel="mask-cover" href="'.$logo.'" color="#000000">';
    }

    if(isset($_SERVER['SERVER_NAME'])){
        echo '<link rel="canonical" href="https://'.$_SERVER['SERVER_NAME'].get_server('REQUEST_URI').'">';
    }
    ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <?php

    //Font Helps:
    $e___29763 = $this->config->item('e___29763'); //CSS Font Family
    $e___29711 = $this->config->item('e___29711'); //Google Font Family
    $e___14506 = $this->config->item('e___14506');
    $google_fonts = array();


    //Do we have Google Analytics?
    $google_analytics_code = website_setting(30033);
    if(strlen($google_analytics_code) > 0){
        echo view_google_tag($google_analytics_code);
    }


    //Do we have Google Tags or second google analytics?
    $google_tag_code = website_setting(38216);
    if(strlen($google_tag_code) > 0){
        echo view_google_tag($google_tag_code);
    }


    echo '<script> ';
    //JS VARIABLES

    echo ' var insert_text = \'' . (isset($_GET['insert']) ? $_GET['insert'] : '')  . '\'; ';
    echo ' var js_pl_id = ' . ( $player_e ? $player_e['e__id'] : '0' ) . '; ';
    echo ' var js_pl_handle = \'' . ( $player_e ? $player_e['e__handle'] : '' ) . '\'; ';
    echo ' var js_pl_name = \'' . ( $player_e ? str_replace('\'','\\\'',trim($player_e['e__title'])) : '' ) . '\'; ';
    echo ' var js_request_uri = \'' . $_SERVER['REQUEST_URI'] . '\'; ';
    echo ' var universal_search_enabled = ' . intval($this->config->item('universal_search_enabled')) . '; ';
    echo ' var website_id = "' . $website_id . '"; ';
    echo ' var js_session_superpowers_unlocked = ' . json_encode(($player_e ? $this->session->userdata('session_superpowers_unlocked') : array())) . ';';
    echo ' var search_and_filter = ( js_session_superpowers_unlocked.includes(12701) ? \'\' : \' AND ( _tags:public_index \' + ( js_pl_id > 0 ? \'OR _tags:z_\' + js_pl_id : \'\' ) + \') \' ); ';

    //JAVASCRIPT PLATFORM MEMORY
    foreach($this->config->item('e___11054') as $x__type => $m){
        if(is_array($this->config->item('e___'.$x__type))){
            echo ' var js_e___'.$x__type.' = ' . json_encode($this->config->item('e___'.$x__type)) . ';';
            echo ' var js_n___'.$x__type.' = ' . json_encode($this->config->item('n___'.$x__type)) . ';';
        }
    }
    echo '</script>';



    //Latest version of twitter bootstrap:
    echo view_memory(6404,4523);
    ?>

    <link href="/application/views/shared/global.css?cache_time=<?= $this->config->item('cache_time') ?>" rel="stylesheet">

    <script type="module">

        //Emoji selector:
        import insertText from 'https://cdn.jsdelivr.net/npm/insert-text-at-cursor@0.3.0/index.js'
        const picker_i = new EmojiMart.Picker({ theme: 'light', onEmojiSelect: (res, _) => {
            //Insert into idea text box:
            insertText($(".save_i__message"), res.native);
            //We keep it open!
        }});
        const picker_e = new EmojiMart.Picker({ theme: 'light', onEmojiSelect: (res, _) => {
            //Insert into cover frame:
            update__cover(res.native);
            $('.emoji_selector .show').removeClass('show');
        }});
        $(".emoji_i").append(picker_i);
        $(".emoji_e").append(picker_e);
        $('.emoji_selector').on('click', function(event){
            //This prevents the emoji modal from closing when an emoji is selected...
            event.stopPropagation();
        });

    </script>
    <link href="https://unpkg.com/cloudinary-video-player@1.10.5/dist/cld-video-player.min.css" rel="stylesheet">
    <script src="https://unpkg.com/cloudinary-video-player@1.10.5/dist/cld-video-player.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autocomplete.js/0.37.0/autocomplete.jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/algoliasearch/3.35.1/algoliasearch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.10.1/Sortable.min.js"></script>
    <script src="https://upload-widget.cloudinary.com/global/all.js" type="text/javascript"></script>
    <script src="https://kit.fontawesome.com/fbf7f3ae67.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/autosize@4.0.2/dist/autosize.min.js"></script>
    <script src="/application/views/shared/global.js?cache_time=<?= $this->config->item('cache_time') ?>"></script>

    <?php

    //Load Fonts Dynamically
    echo '<style> '."\n"."\n";


    //Hide superpower CSS thats missing:
    foreach($this->config->item('e___10957') as $superpower_id => $superpower){
        if(is_array($this->session->userdata('session_superpowers_unlocked')) && !in_array($superpower_id, $this->session->userdata('session_superpowers_unlocked'))){
            echo ' body .hidden_superpower__'.$superpower_id.' { display:none !important; } '."\n";
        }
    }


    //Header Fonts
    foreach($this->config->item('e___14506') as $e__id => $m){
        if(isset($e___29711[$e__id]) && isset($e___29763[$e__id])){
            array_push($google_fonts, $e___29711[$e__id]['m__message']);
            echo '
            .custom_ui_14506_'.$e__id.' .itemsetting.active:not(.exclude_fonts),
            .custom_ui_14506_'.$e__id.'.itemsetting.exclude_fonts,
            .custom_ui_14506_'.$e__id.' h1,
            .custom_ui_14506_'.$e__id.' h2,
            .custom_ui_14506_'.$e__id.' .main__title,
            .custom_ui_14506_'.$e__id.' .first_line,
            .custom_ui_14506_'.$e__id.' .headline,
            .custom_ui_14506_'.$e__id.' .btn,
            .custom_ui_14506_'.$e__id.' .mid-text-line span,
            .custom_ui_14506_'.$e__id.' .texttype__lg,
            .custom_ui_14506_'.$e__id.' .texttype__lg::placeholder,
            .custom_ui_14506_'.$e__id.' .alert a {
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
            .custom_ui_29700_'.$e__id.'.itemsetting.exclude_fonts,
            .custom_ui_29700_'.$e__id.' div,
            .custom_ui_29700_'.$e__id.' p,
            .custom_ui_29700_'.$e__id.' .dropdown .btn,
            .custom_ui_29700_'.$e__id.' html,
            .custom_ui_29700_'.$e__id.' body,
            .custom_ui_29700_'.$e__id.' .doregular {
                font-family: '.$e___29763[$e__id]['m__message'].' !important;
            }
            ';
        }
    }



    if(isset($app_e__id) && in_array($app_e__id, $this->config->item('n___28621'))){

        $domain_background = website_setting(28621);
        if(strlen($domain_background)){

            $apply_css = 'body, .container, .chat-title span, div.dropdown-item, .mid-text-line span';

            //Make sure we have enough padding at the bottom:
            echo '.bottom_spacer {  padding-bottom:987px !important; } ';

            if(substr($domain_background, 0, 1)=='#'){

                echo 'body, .container, .chat-title span, div.dropdown-item, .mid-text-line span { ';
                echo 'background:'.$domain_background.' !important; ';
                echo '}';

            } elseif(substr($domain_background, 0, 8)=='https://' && filter_var($domain_background, FILTER_VALIDATE_URL)){

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

                echo ' .halfbg { background: rgba(0, 0, 0, 0.69) !important;  } ';
                echo ' .fixed-top { background: rgba(21,21,21, 1) !important;  } ';
                echo ' .top-header-position.fixed-top { background: none !important; } ';
                echo ' .i_cache>span u, .i_cache>span a { line-height: 100% !important; padding:0 !important; } ';

            }
        }
    }


    echo ' </style>';
    ?>

    <link href="https://fonts.googleapis.com/css?family=<?= join('|',$google_fonts) ?>&display=swap" rel="stylesheet">

</head>

<?php


//Called when the paypal payment is complete:
if(isset($_POST['payment_status']) && isset($_POST['item_number'])){

    $item_numbers = array();
    $completion_status = array();
    $is_good = false;

    //Log New Payment:
    $item_parts = explode(' ', $_POST['item_number']);

    $item_numbers['i_target'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[0])) : false ));
    $item_numbers['i_destination'] = strtolower(( count($item_parts)==4 ? trim(str_replace('#','',$item_parts[1])) : trim($item_parts[0]) ));
    $item_numbers['e_wesbite'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 2 : 1 )])));
    $item_numbers['e_player'] = strtolower(trim(str_replace('@','',$item_parts[( count($item_parts)==4 ? 3 : 2 )])));

    //Fetch Objects based on handles:
    $player_es = $this->E_model->fetch(array(
        'LOWER(e__handle)' => $item_numbers['e_player'],
    ));
    $website_es = $this->E_model->fetch(array(
        'LOWER(e__handle)' => $item_numbers['e_wesbite'],
    ));
    $next_is = $this->I_model->fetch(array(
        'LOWER(i__hashtag)' => $item_numbers['i_destination'],
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    ));
    $target_is = ($item_numbers['i_target'] ? $this->I_model->fetch(array(
        'LOWER(i__hashtag)' => $item_numbers['i_target'],
        'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
    )) : false);


    if(count($player_es) && count($next_is)) {

        $is_pending = ($_POST['payment_status']=='Pending');
        $is_good = true;

        //Is the payment amount greater than zero?
        if(doubleval(( strlen($_POST['payment_gross']) ? $_POST['payment_gross'] : $_POST['mc_gross'])) > 0){

            //Paid:
            $x__type = ( $is_pending ? 35572 /* Pending Payment */ : 26595 );

            //Log Payment:
            $completion_status = $this->X_model->mark_complete($x__type, $player_es[0]['e__id'], ( isset($target_is[0]['i__id']) ? $target_is[0]['i__id'] : 0 ), $next_is[0], array(), array(
                'x__weight' => intval($_POST['quantity']),
                'x__metadata' => $_POST,
            ));

        } else {

            $x__type = ( $is_pending ? 39597 /* Pending Refund */ : 31967 );

            //Find issued tickets:
            $original_payment = $this->X_model->fetch(array(
                'x__type' => 26595,
                'x__player' => $player_es[0]['e__id'],
                'x__previous' => $next_is[0]['i__id'],
            ));

            //Log Refund:
            $completion_status = $this->X_model->mark_complete($x__type, $player_es[0]['e__id'], ( isset($target_is[0]['i__id']) ? $target_is[0]['i__id'] : 0 ), $next_is[0], array(), array(
                'x__weight' => (-1 * ( isset($original_payment[0]['x__weight']) ? $original_payment[0]['x__weight'] : 1 )),
                'x__metadata' => $_POST,
                'x__reference' => ( isset($original_payment[0]['x__id']) ? $original_payment[0]['x__id'] : 0 ),
                'x__website' => ( isset($original_payment[0]['x__website']) && $original_payment[0]['x__website']>0 ? $original_payment[0]['x__website'] : 0 ),
            ));

        }
    }
}



$i_view = 0;
$quick_id = 0;
$discovery_i__hashtag = ( strlen($first_segment) ? ( strlen($second_segment) ? $second_segment : $first_segment ) : 0 );
if(strlen($discovery_i__hashtag) && superpower_unlocked(12703)) {

    //Ideation Mode:
    $_GET['i__hashtag'] = $discovery_i__hashtag;
    $i_view = 30795;
    $quick_href = view_memory(42903, 33286);

} elseif(!strlen($first_segment) && superpower_unlocked(12703)) {

    //Edit Website Home Page:
    $quick_href = view_memory(42903, 42902);
    $quick_id = 33287;

} elseif($e_segment && $e_segment==$e___14870[$website_id]['m__handle']) {

    //Edit Website Home Page:
    $quick_href = '/?reset_cache=1';
    $quick_id = 6287;

} elseif(substr($first_segment, 0, 1)=='~') {

    //Ideation Mode:
    $_GET['i__hashtag'] = substr($first_segment, 1);
    $i_view = 33286;
    $quick_href = view_memory(42903, 33286);

} elseif(array_key_exists(strtolower($first_segment), $this->config->item('handle___6287'))) {

    //Source Mode:
    if(array_key_exists(strtolower($first_segment), $handle___40904) && isset($_GET['i__hashtag'])){
        $i_view = $handle___40904[strtolower($first_segment)];
    } else {
        $quick_id = 33287;
    }
    $quick_href = view_memory(42903, 42902);

} elseif($e_segment && array_key_exists(strtolower($e_segment), $this->config->item('handle___6287'))) {

    //App Store:
    if(array_key_exists(strtolower($e_segment), $handle___40904) && isset($_GET['i__hashtag'])){
        $i_view = $handle___40904[strtolower($e_segment)];
    } else {
        $quick_id = 6287;
    }
    $quick_href = view_memory(42903, 6287);

} elseif(isset($_GET['e__handle']) && strlen($_GET['e__handle'])) {

    //Source Mode:
    $quick_href = view_memory(42903, 42902);
    $quick_id = 33287;

} elseif(isset($_GET['i__hashtag']) && strlen($_GET['i__hashtag'])) {

    //Ideation Mode:
    $quick_href = view_memory(42903, 33286);
    $quick_id = 33286;

}

echo '<body class="'.$body_class.'" id="main_body">';
echo $bgVideo;



//JS Variables for this app on page...
if ($focus_i){
    echo '<input type="hidden" id="focus__node" value="12273" />
<input type="hidden" id="focus_handle" value="'.$focus_i['i__hashtag'].'" />
<input type="hidden" id="focus__id" value="'.$focus_i['i__id'].'" />';
    if($target_i){
        echo '<input type="hidden" id="target_i__hashtag" value="'.$target_i['i__hashtag'].'" />
        <input type="hidden" id="target_i__id" value="'.$target_i['i__id'].'" />';
    }
} elseif($focus_e){
    echo '<input type="hidden" id="focus__node" value="12274" />
<input type="hidden" id="focus_handle" value="'.$focus_e['e__handle'].'" />
<input type="hidden" id="focus__id" value="'.$focus_e['e__id'].'" />';
}




//Load live chat?
$live_chat_page_id = website_setting(12899);
if(strlen($live_chat_page_id)>10){
    ?>
    <!-- Messenger Chat Plugin Code -->
    <div id="fb-root"></div>
    <!-- Your Chat Plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat" ref="<?= ( $player_e ? $player_e['e__id'] : '' ) ?>">
    </div>
    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "<?= $live_chat_page_id ?>");
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

    //Do not show for /sign view
    ?>
    <div class="container fixed-top top-header-position slim_flat no-print">
        <div class="row justify-content">
            <table class="platform-navigation">
                <tr>
                    <?php

                    echo '<td>';

                    echo '<div class="logo_frame">'.( strlen($domain_cover) ? '<a href="'.view_memory(42903,14565).'" class="icon-block logo_cover">'.view_cover($domain_logo).'</a>' : '') . '<a href="'.view_memory(42903,14565).'" class="main__title logo_title">'.get_domain('m__title').'</a>'.'</div>';


                    //SEARCH
                    echo '<div class="left_nav nav_finder hidden"><form id="searchFrontForm"><span class="icon-block-sm">'.$e___11035[7256]['m__cover'].'</span><input class="form-control algolia_finder" type="search" id="website_finder" data-lpignore="true" placeholder="'.$e___11035[7256]['m__title'].'"></form></div>';



                    echo '</div>';
                    echo '</td>';


                    /*
                    if($i_view > 0){
                        $e___40904 = $this->config->item('e___40904'); //Idea Views
                        echo '<td class="block-menu">';
                        echo '<div class="dropdown inline-block">';
                        echo '<button type="button" class="btn no-side-padding dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
                        echo '<span class="e_cover e_cover_mini menu-cover">' . $e___40904[$i_view]['m__cover'] .'</span>';
                        echo '</button>';
                        echo '<div class="dropdown-menu">';
                        foreach($e___40904 as $x__type => $m) {

                            $superpowers_required = array_intersect($this->config->item('n___10957'), $m['m__following']);
                            if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                                continue;
                            }

                            $hosted_domains = array_intersect($this->config->item('n___14870'), $m['m__following']);
                            if(count($hosted_domains) && !in_array($website_id, $hosted_domains)){
                                continue;
                            }

                            echo '<a href="'.$m['m__message'].$_GET['i__hashtag'].'" class="dropdown-item main__title"><span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'].'</a>';

                        }
                        echo '</div>';
                        echo '</div>';
                        echo '</td>';
                    }

                    if($quick_id > 0){
                        echo '<td class="block-x icon_finder"><a href="'.$quick_href.'" title="'.$e___11035[$quick_id]['m__title'].'">'.$e___11035[$quick_id]['m__cover'].'</a></td>';
                    }

                    */


                    if(search_enabled() && $player_e){
                        echo '<td class="block-x icon_finder enlarge '.( intval(website_setting(32450)) ? ' hidden ' : '' ).'"><a href="javascript:void(0);" onclick="toggle_finder()">'.$e___11035[7256]['m__cover'].'</a></td>';
                        echo '<td class="block-x icon_finder enlarge hidden"><a href="javascript:void(0);" onclick="toggle_finder()">'.$e___11035[13401]['m__cover'].'</a></td>';
                    }




                    //Add Idea:
                    if($player_e){
                        echo '<td class="block-x enlarge add_idea"><a href="javascript:void(0);" onclick="i_editor_load()" title="'.$e___11035[33532]['m__title'].'">'.$e___11035[33532]['m__cover'].'</a></td>';
                    }


                    //MENU
                    $menu_type = ( $player_e ? 12500 : 14372 );
                    echo '<td class="block-menu">';

                    echo '<div class="dropdown inline-block">';
                    echo '<button type="button" class="btn no-side-padding dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">';
                    echo '<span class="e_cover e_cover_mini menu-cover">' . ( $player_e && strlen($player_e['e__cover']) ? view_cover($player_e['e__cover'], 1) : $e___11035[$menu_type]['m__cover'] ) .'</span>';
                    echo '</button>';
                    echo '<div class="dropdown-menu">';
                    foreach($this->config->item('e___'.$menu_type) as $x__type => $m) {

                        $superpowers_required = array_intersect($this->config->item('n___10957'), $m['m__following']);
                        if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                            continue;
                        }

                        $hosted_domains = array_intersect($this->config->item('n___14870'), $m['m__following']);
                        if(count($hosted_domains) && !in_array($website_id, $hosted_domains)){
                            continue;
                        }

                        $extra_class = null;
                        $text_class = null;

                        if($x__type==26105 && $player_e) {

                            //Profile View
                            $m['m__cover'] = view_cover($player_e['e__cover'], 1);
                            $m['m__title'] = '<div class="type_head main__title">'.$player_e['e__title'].'</div><div class="grey type_handle">@'.$player_e['e__handle'].'</div>';
                            $href = 'href="'.view_memory(42903,42902).$player_e['e__handle'].'" ';

                        } elseif($x__type==42246 && $player_e) {

                            //Profile Edit
                            $href = 'href="javascript:void(0);" onclick="e_editor_load('.$player_e['e__id'].',0)" ';

                        } elseif($x__type==28615){

                            //Phone US
                            $value = website_setting($x__type);
                            if(!strlen($value)){
                                continue;
                            }
                            $href = 'href="tel:'.preg_replace("/[^0-9]/", "", $value).'"';

                        } elseif($x__type==28614){

                            //Email US
                            $value = website_setting($x__type);
                            if(!strlen($value)){
                                continue;
                            }
                            $href = 'href="mailto:'.$value.'"';

                        } elseif(in_array($x__type, $this->config->item('n___6287'))){

                            //APP
                            $href = 'href="'.view_app_link($x__type).( $x__type==4269 ? ( isset($_SERVER['REQUEST_URI']) ? '?url='.urlencode($_SERVER['REQUEST_URI']) /* Append current URL for redirects */ : '' ) : '' ).'"';

                        } else {

                            //Unknown
                            continue;

                        }

                        //Navigation
                        echo '<a '.$href.' x__type="'.$x__type.'" class="dropdown-item dropdown_type_'.$x__type.' main__title '.$extra_class.'"><span class="icon-block">'.$m['m__cover'].'</span><span class="'.$text_class.'">'.$m['m__title'].'</span></a>';

                    }

                    echo '</div>';
                    echo '</div>';
                    echo '</td>';


                    //Add Source
                    if(superpower_unlocked(10939)){
                        //echo '<td class="block-x"><a href="javascript:void(0);" onclick="e_editor_load()" title="'.$e___11035[42819]['m__title'].'">'.$e___11035[42819]['m__cover'].'</a></td>';
                    }

                    ?>
                </tr>
            </table>
        </div>
    </div>

<?php

}



echo '<div id="container_finder" class="container hidden hideIfEmpty"><div class="row justify-content hideIfEmpty"></div></div>';
echo '<div id="container_main" class="container container_content">';

//Any message we need to show here?
if (!isset($flash_message) || !strlen($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}

if(strlen($flash_message) > 0) {

    //Delete from Flash:
    $this->session->unmark_flash('flash_message');

    echo '<div class="'.( $basic_header_footer ? ' center-info ' : '' ).' center" id="flash_message">'.$flash_message.'</div>';

}












$player_e = superpower_unlocked();

if($player_e){
    //For profile editing only:
    echo '<div class="hidden">';
    echo view_card_e(42287, $player_e, null);
    echo '</div>';
}

if($player_e && ( !isset($basic_header_footer) || !$basic_header_footer )){

    $dynamic_edit = '';
    for ($p = 1; $p <= view_memory(6404,42206); $p++) {
        $dynamic_edit .= '<div class="dynamic_item hidden dynamic_' . $p . '" d__id="" d_x__id="">';
        $dynamic_edit .= '<div class="inner_dynamic">';
        $dynamic_edit .= '<div class="text_content">';
        $dynamic_edit .= '<h3 class="mini-font"></h3>';
        $dynamic_edit .= '<input type="text" class="form-control unsaved_warning save_dynamic_'.$p.'" value="">';
        $dynamic_edit .= '</div>';
        $dynamic_edit .= '</div>';
        $dynamic_edit .= '</div>';
    }

    //Apply to All Sources
    if(superpower_unlocked(12703)){
        ?>
        <div class="modal fade" id="modal4997" tabindex="-1" role="dialog" aria-labelledby="modal4997Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content long_flat">
                    <form method="POST" action="<?= view_app_link(27196) ?>?focus__id=12274">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <button type="submit" class="btn btn-default">APPLY</button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="s__id" value="" />
                            <?php

                            //Mass Editor:
                            $dropdown_options = '';
                            $input_options = '';
                            $editor_counter = 0;

                            foreach($this->config->item('e___4997') as $action_e__id => $e_list_action) {


                                $editor_counter++;
                                $dropdown_options .= '<option value="' . $action_e__id . '" title="'.$e_list_action['m__message'].'">' .$e_list_action['m__title'] . '</option>';
                                $is_upper = ( in_array($action_e__id, $this->config->item('n___12577') /* SOURCE UPDATER UPPERCASE */) ? ' main__title ' : false );


                                //Start with the input wrapper:
                                $input_options .= '<span title="'.$e_list_action['m__message'].'" class="mass_id_'.$action_e__id.' inline-block '. ( $editor_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';




                                if(in_array($action_e__id, array(5000, 5001, 10625))){

                                    //String Find and Replace:

                                    //Find:
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'" placeholder="Search" class="form-control border '.$is_upper.'">';

                                    //Replace:
                                    $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'" placeholder="Replace" class="form-control border '.$is_upper.'">';


                                } elseif(in_array($action_e__id, array(5981, 5982, 13441))){

                                    //Member search box:

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search sources" class="form-control algolia_finder e_text_finder border '.$is_upper.'">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" placeholder="Search Source" />';


                                } elseif($action_e__id==11956){

                                    //If Has THIS
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="IF THIS SOURCE" class="form-control algolia_finder e_text_finder border '.$is_upper.'">';

                                    //ADD THIS
                                    $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'"  placeholder="ADD THIS SOURCE" class="form-control algolia_finder e_text_finder border '.$is_upper.'">';


                                } elseif($action_e__id==5003){

                                    //Member Status update:

                                    //Find:
                                    $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                                    $input_options .= '<option value="*">Update All Statuses</option>';
                                    foreach($this->config->item('e___6177') /* Source Privacy */ as $x__type3 => $m3){
                                        $input_options .= '<option value="'.$x__type3.'">Update Only If = '.$m3['m__title'].'</option>';
                                    }
                                    $input_options .= '</select>';

                                    //Replace:
                                    $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                                    $input_options .= '<option value="">Set New Status</option>';
                                    foreach($this->config->item('e___6177') /* Source Privacy */ as $x__type3 => $m3){
                                        $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m__title'].'</option>';
                                    }
                                    $input_options .= '</select>';


                                } elseif($action_e__id==5865){

                                    //Transaction Privacy update:

                                    //Find:
                                    $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                                    $input_options .= '<option value="*">Update All</option>';
                                    foreach($this->config->item('e___6186') /* Interaction Privacy */ as $x__type3 => $m3){
                                        $input_options .= '<option value="'.$x__type3.'">Update Only If = '.$m3['m__title'].'</option>';
                                    }
                                    $input_options .= '</select>';

                                    //Replace:
                                    $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                                    $input_options .= '<option value="">Set New</option>';
                                    foreach($this->config->item('e___6186') /* Interaction Privacy */ as $x__type3 => $m3){
                                        $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m__title'].'</option>';
                                    }
                                    $input_options .= '</select>';

                                } elseif($action_e__id==42804){

                                    //Transaction Type update:

                                    //Find:
                                    $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                                    $input_options .= '<option value="*">Update All Interaction Types</option>';
                                    foreach($this->config->item('e___32292') /* Source Links */ as $x__type3 => $m3){
                                        $input_options .= '<option value="'.$x__type3.'">Update Only If = '.$m3['m__title'].'</option>';
                                    }
                                    $input_options .= '</select>';

                                    //Replace:
                                    $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                                    $input_options .= '<option value="">Set New Status</option>';
                                    foreach($this->config->item('e___32292') /* Source Links */ as $x__type3 => $m3){
                                        $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m__title'].'</option>';
                                    }
                                    $input_options .= '</select>';


                                } else {

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="String" class="form-control border '.$is_upper.'">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                                }

                                $input_options .= '</span>';

                            }

                            //Drop Down
                            echo '<select class="form-control border mass_action_toggle" name="mass_action_toggle">';
                            echo $dropdown_options;
                            echo '</select>';

                            echo $input_options;

                            ?>
                            <div class="x_mass_apply_preview"></div>
                        </div>
                </div>
                </form>
            </div>
        </div>
        <?php
    }


    //Apply to All Ideas
    if(superpower_unlocked(12700)){
        ?>
        <div class="modal fade" id="modal12589" tabindex="-1" role="dialog" aria-labelledby="modal12589Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content long_flat">

                    <form method="POST" action="<?= view_app_link(27196) ?>?focus__id=12273">

                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <button type="submit" class="btn btn-default">APPLY</button>
                        </div>

                        <div class="modal-body">

                            <input type="hidden" name="s__id" value="" />
                            <?php

                            //IDEA LIST EDITOR
                            $dropdown_options = '';
                            $input_options = '';
                            $this_counter = 0;

                            foreach($this->config->item('e___12589') as $action_e__id => $e_list_action) {

                                $this_counter++;
                                $dropdown_options .= '<option value="' . $action_e__id . '">' .$e_list_action['m__title'] . '</option>';


                                //Start with the input wrapper:
                                $input_options .= '<span title="'.$e_list_action['m__message'].'" class="mass_id_'.$action_e__id.' inline-block '. ( $this_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';

                                if(in_array($action_e__id, array(12591,27080,27985,27082,27084,27086))){

                                    //Source search box:

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Sources" class="form-control algolia_finder e_text_finder border main__title">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'" value="" />';

                                } elseif(in_array($action_e__id, array(12592,27081,27986,27083,27085,27087))){

                                    //Source search box:

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Sources" class="form-control algolia_finder e_text_finder border main__title">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                                } elseif(in_array($action_e__id, array(12611,12612,27240,28801))){

                                    //String command:
                                    $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search Ideas" class="form-control algolia_finder i_text_finder border main__title">';

                                    //We don't need the second value field here:
                                    $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                                }

                                $input_options .= '</span>';

                            }

                            //Drop Down
                            echo '<select class="form-control border mass_action_toggle" name="mass_action_toggle">';
                            echo $dropdown_options;
                            echo '</select>';

                            echo $input_options;

                            ?>
                            <div class="x_mass_apply_preview"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php
    }

    if($player_e){

        $e___6201 = $this->config->item('e___6201'); //IDEA Cache
        $e___6206 = $this->config->item('e___6206'); //Source Cache

        ?>


        <!-- Edit Idea Modal -->
        <div class="i_footer_note hidden">Idea saved. <a href=""><b>View</b></a></div>
        <div class="modal fade" id="modal31911" tabindex="-1" role="dialog" aria-labelledby="modal31911Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content long_flat">

                    <div class="modal-header">
                        <div class="initial_header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            <!-- Idea Links -->
                            <div class="dynamic_editing_input idea_link_type hidden hidden_superpower__10939" style="margin: 0 !important;">
                                <div class="dynamic_selector"><?= view_single_select_form(4486, 4228, false, false); ?></div>
                            </div>

                            <!-- Unlink -->
                            <div class="dynamic_editing_input no_padded idea_link_unlink hidden">
                                <a class="icon-block" href="javascript:void(0);" onclick="i_editor_switch()" title="Unlink Idea / Publish a Standalone idea"><i class="far fa-unlink"></i></a>
                            </div>

                            <!-- Toggle Direction -->
                            <div class="dynamic_editing_input no_padde idea_link_direction hidden hidden_superpower__42817">
                                <a class="icon-block" href="javascript:void(0);" onclick="" title="Switch Direction"><i class="far fa-arrow-up-arrow-down"></i></a>
                            </div>


                        </div>
                        <button type="button" class="btn btn-default i_editor_save post_button" onclick="i_editor_save()">SAVE</button>
                    </div>

                    <div class="modal-body">

                        <div class="save_results hideIfEmpty alert alert-danger" style="margin:8px 0;"></div>

                        <input type="hidden" class="created_i__id" value="0" />
                        <input type="hidden" class="save_i__id" value="0" />
                        <input type="hidden" class="save_x__id" value="0" />
                        <input type="hidden" class="next_i__id" value="0" />
                        <input type="hidden" class="previous_i__id" value="0" />

                        <div class="idea_list_next cover-text hideIfEmpty"></div>
                        <div class="doclear">&nbsp;</div>

                        <!-- Idea Creator(s) -->
                        <div class="creator_box">
                            <?php
                            foreach($this->X_model->fetch(array(
                                'x__following' => $player_e['e__id'],
                                'x__type' => 41011, //PINNED FOLLOWER
                                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            ), array('x__follower'), 0, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC')) as $x_pinned) {
                                echo '<div class="creator_headline"><span class="icon-block">'.view_cover($x_pinned['e__cover']).'</span><b>'.$x_pinned['e__title'].'</b><span class="grey mini-font mini-padded mini-frame">@'.$x_pinned['e__handle'].'</span></div>';
                                //TODO maybe give the option to remove?
                            }

                            //Always append current user:
                            echo '<div class="creator_headline first_headline"><span class="icon-block">'.view_cover($player_e['e__cover']).'</span></div>';
                            ?>
                        </div>

                        <!-- Idea Message -->
                        <div class="dynamic_editing_input" style="margin: 0 !important;">
                            <textarea class="form-control nodte-textarea algolia_finder new-note editing-mode unsaved_warning algolia__e algolia__i save_i__message" placeholder="<?= ( strlen($e___6201[4736]['m__message']) ? $e___6201[4736]['m__message'] : $e___6201[4736]['m__title'].'...' ) ?>" style="margin:0; width:100%; background-color: #FFFFFF !important;"></textarea>
                            <div class="media_outer_frame hideIfEmpty" style="margin-left: 40px;">
                                <div id="media_editor_frame" class="media_frame hideIfEmpty"></div>
                                <div class="doclear">&nbsp;</div>
                            </div>
                        </div>

                        <div class="inner_message left_padded">
                            <div class="idea_list_previous hideIfEmpty"></div>
                        </div>

                        <div class="inner_message left_padded">

                            <!-- EMOJI -->
                            <div class="dynamic_editing_input no_padded float_right">
                                <div class="dropdown emoji_selector">
                                    <button type="button" class="btn no-left-padding no-right-padding icon-block" id="emoji_i" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-face-smile"></i></button>
                                    <div class="dropdown-menu emoji_i" aria-labelledby="emoji_i"></div>
                                </div>
                            </div>

                            <!-- Upload -->
                            <div class="dynamic_editing_input no_padded float_right">
                                <a class="uploader_13572 icon-block" href="javascript:void(0);" title="<?= $e___11035[13572]['m__title'] ?>"><?= $e___11035[13572]['m__cover'] ?></a>
                            </div>


                            <!-- Idea Privacy -->
                            <div class="dynamic_editing_input" style="margin: 0 !important;">
                                <div class="dynamic_selector"><?= view_single_select_form(31004, 31005, false, true); ?></div>
                            </div>

                            <!-- Idea Type -->
                            <div class="dynamic_editing_input hidden_superpower__10939" style="margin: 0 !important;">
                                <div class="dynamic_selector"><?= view_single_select_form(4737, 6677, false, true); ?></div>
                            </div>

                            <div class="doclear">&nbsp;</div>

                        </div>

                        <div class="hidden_superpower__10939 left_padded">

                            <!-- Dynamic Loader -->
                            <div class="dynamic_editing_loading hidden"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Loading</div>

                            <!-- Dynamic Inputs -->
                            <div class="dynamic_frame"><?= $dynamic_edit ?></div>

                            <!-- Idea Hashtag -->
                            <div class="dynamic_editing_input single_line hash_group" title="<?= $e___6201[32337]['m__title'] ?>">
                                <h3 class="mini-font"><span class="icon-block-sm"><?= $e___6201[32337]['m__cover']  ?></span></h3>
                                <input type="text" class="form-control unsaved_warning save_i__hashtag" placeholder="<?= $e___6201[32337]['m__title'] ?>" maxlength="<?= view_memory(6404,41985) ?>">
                            </div>

                            <!-- Link Note -->
                            <div class="dynamic_editing_input save_x__frame hidden">
                                <h3 class="mini-font"><?= '<span class="icon-block-sm">'.$e___11035[4372]['m__cover'].'</span>'.$e___11035[4372]['m__title'].': ';  ?></h3>
                                <textarea class="form-control border unsaved_warning save_x__message" data-lpignore="true" placeholder="..."></textarea>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>






        <!-- Edit Source Modal -->
        <div class="modal fade" id="modal31912" tabindex="-1" role="dialog" aria-labelledby="modal31912Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content long_flat">

                    <div class="modal-header">
                        <div class="initial_header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <button type="button" class="e_editor_save btn btn-default post_button" onclick="e_editor_save()">SAVE</button>
                    </div>

                    <div class="modal-body">

                        <div class="save_results hideIfEmpty alert alert-danger" style="margin:8px 0;"></div>

                        <input type="hidden" class="save_e__id" value="0" />
                        <input type="hidden" class="save_x__id" value="0" />


                        <!-- Source Title -->
                        <div class="dynamic_editing_input">
                            <h3 class="mini-font"><?= '<span class="icon-block">'.$e___6206[6197]['m__cover'].'</span>'.$e___6206[6197]['m__title'].': ';  ?></h3>
                            <textarea class="form-control unsaved_warning save_e__title main__title" placeholder="..." style="margin:0; width:100%; background-color: #FFFFFF !important;"></textarea>
                        </div>


                        <!-- Source Handle -->
                        <div class="dynamic_editing_input">
                            <h3 class="mini-font"><?= '<span class="icon-block">'.$e___6206[32338]['m__cover'].'</span>'.$e___6206[32338]['m__title'].': ';  ?></h3>
                            <input type="text" class="form-control unsaved_warning save_e__handle" placeholder="...">
                        </div>

                        <!-- Source Privacy -->
                        <div class="dynamic_editing_input">
                            <h3 class="mini-font"><?= '<span class="icon-block">'.$e___6206[6177]['m__cover'].'</span>'.$e___6206[6177]['m__title'].': ';  ?></h3>
                            <div class="dynamic_selector"><?= view_single_select_form(6177, 6181, true, true); ?></div>
                        </div>

                        <!-- SOURCE COVER -->
                        <div class="message_controllers">
                            <table class="emoji_table">
                                <tr>
                                    <td>
                                        <!-- Upload Cover -->
                                        <a class="uploader_42359" class="icon-block-sm" href="javascript:void(0);" title="<?= $e___11035[42359]['m__title'] ?>"><?= $e___11035[42359]['m__cover'] ?></a>
                                    </td>
                                    <td class="hidden_superpower__13758">
                                        <!-- EMOJI -->
                                        <div class="icon-block-sm">
                                            <div class="dropdown emoji_selector" style="max-height: 21px; margin-top: -18px;">
                                                <button type="button" class="btn no-left-padding no-right-padding" id="emoji_e" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="far fa-face-smile"></i></button>
                                                <div class="dropdown-menu emoji_e" aria-labelledby="emoji_e"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden_superpower__13758">
                                        <!-- Font Awesome Insert -->
                                        <a href="javascript:void(0);" class="icon-block-sm" onclick="update__cover('far fa-icons')" title="Use Font Awesome"><i class="far fa-icons"></i></a>
                                    </td>
                                    <td class="hidden_superpower__13758 cover_history_button">
                                        <!-- History -->
                                        <a href="javascript:void(0);" class="icon-block-sm" onclick="$('.cover_history_content').toggleClass('hidden');" title="Toggle Previously Used Covers"><i class="far fa-clock-rotate-left"></i></a>
                                    </td>
                                    <td>
                                        <!-- Ramdom Animal -->
                                        <a href="javascript:void(0);" class="random_animal" onclick="update__cover('hide '+random_animal())" title="Set a random animal"></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="dynamic_editing_input">
                            <h3 class="mini-font"><?= '<span class="icon-block">'.$e___6206[6198]['m__cover'].'</span>'.$e___6206[6198]['m__title'].': ';  ?></h3>

                            <!-- Cover HIDDEN Input (Editable for font awesome icons only) -->
                            <input type="text" class="form-control unsaved_warning save_e__cover hidden_superpower__13758" data-lpignore="true" placeholder="Emoji, Image URL or Cover Code">

                            <!-- Font Awesome Search -->
                            <div class="hidden_superpower__13758 fa_search hidden">
                                <a href="https://fontawesome.com/search" class="icon-block-sm" target="_blank" title="Open New Window to Search on Font Awesome"><i class="far fa-search-plus"></i></a>
                            </div>
                            <div class="doclear">&nbsp;</div>

                            <div>

                                <!-- Cover Settings/Selectors -->
                                <div class="icons_small cover_history_content hidden section_subframe"></div>

                                <!-- Cover Demo -->
                                <div class="section_demo ">
                                    <div class="card_cover demo_cover">
                                        <div class="cover-wrapper uploader_42359"><div class="black-background-obs cover-link" style=""><div class="cover-btn"></div></div></div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <!-- Link Note -->
                        <div class="dynamic_editing_input save_x__frame hidden">
                            <h3 class="mini-font"><?= '<span class="icon-block">'.$e___11035[4372]['m__cover'].'</span>'.$e___11035[4372]['m__title'].': ';  ?></h3>
                            <textarea class="form-control border unsaved_warning save_x__message" data-lpignore="true" placeholder="..."></textarea>
                        </div>


                        <!-- Dynamic Loader -->
                        <div class="dynamic_editing_loading hidden"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Loading</div>

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

?>
