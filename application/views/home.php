<?php



//TITLE
$website_id = website_setting(0);
$expanded_space = in_array($website_id , $this->config->item('playerids___31025'));

if(in_array($website_id, $this->config->item('playerids___30984'))){
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'home_black_font\'); }); </script> ';
} else {
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'home_white_font\'); }); </script> ';
}



$primary_i = array();
$secondary_idea_list = array();
foreach($this->Menchledger->fetch(array(
    'linkplayertype' => 34513, //Pinned
    'linkplayerup' => $website_id,
), array('linkidearight'), 0, 0, array('linknumber' => 'ASC', 'linkid' => 'DESC')) as $this_i){
    if(!count($primary_i)){
        $primary_i = $this_i;
    } else {
        //Add to secondary ideas:
        array_push($secondary_idea_list, $this_i);
    }
}

if(count($primary_i)){
    echo ' <script> $(document).ready(function () { show_more('.$primary_i['ideaid'].'); $(document).prop(\'title\', \''.get_domain('m__title').' | '.str_replace('\'','\\\'',view_idea_title($primary_i, true)).'\'); }); </script> ';
}

echo '<h1 class="maxwidth" style="margin: '.( $expanded_space ? '144px auto 377px' : '89px auto 233px' ).' !important;">' . view_idea_title($primary_i, true) . '</h1>';


//Did we find any?
$messages = '<div class="center-frame hide-subline maxwidth hideIfEmpty remove_first_line">' . view_idea_links($primary_i) . '</div>';




//SOCIAL FOOTER
$domain_phone =  website_setting(28615);
$email_domain =  website_setting(28614);
$players___11035 = $this->config->item('players___11035');

$contact_us = '';
if($domain_phone || $email_domain) {

    $contact_us .= '<ul class="social-footer">';
    if($domain_phone){
        $contact_us .= '<li><a href="tel:'.preg_replace("/[^0-9]/", "", $domain_phone).'" data-toggle="tooltip" data-placement="top" title="'.$players___11035[28615]['m__title'].'">'.$players___11035[28615]['m__cover'].' '.$domain_phone.'</a></li>';
    }

    if($email_domain){
        $contact_us .= '<li><a href="mailto:'.$email_domain.'" title="'.$players___11035[28614]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$players___11035[28614]['m__cover'].' '.$email_domain.'</a></li>';
    }
    $contact_us .= '</ul>';

}





//Any Info Boxes?
foreach($this->Nodeplayers->scissor_player($website_id, 14903) as $player_item) {
    //Any Followers?
    $info_item = null;
    foreach($this->Menchledger->fetch(array(
        'linkplayerup' => $player_item['playerid'],
        'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array('linkplayerdown'), 0, 0, array('linknumber' => 'ASC')) as $info_element) {
        $info_item .= '<div class="col-12 col-md-4">';
        $info_item .= '<div class="info_box">';
        if(filter_var($info_element['playercover'], FILTER_VALIDATE_URL)){
            $info_item .= '<div class="info_box_cover">'.'<div class="center-cropped" style="background-image: url(\''.$info_element['playercover'].'\');"></div>'.'</div>';
            $info_item .= '<div class="info_box_title main__title">'.$info_element['playertext'].'</div>';
        } else {
            $info_item .= '<div class="info_box_cover">'.view_cover($info_element['playercover']).'</div>';
            $info_item .= '<div class="info_box_title main__title">'.$info_element['playertext'].'</div>';
        }
        if(strlen($info_element['linktext'])){
            $info_item .= '<div class="info_box_message">'.$info_element['linktext'].'</div>';
        }
        $info_item .= '</div>';
        $info_item .= '</div>';
    }

    if($info_item){
        $messages .= '<h2 class="info-head">'.$player_item['playertext'].'</h2>';
        if(strlen($player_item['linktext'])){
            $messages .= '<div class="row justify-content center" style="margin-bottom: 89px; padding: 0 34px;">'.$player_item['linktext'].'</div>';
        }
        $messages .= '<div class="row justify-content" style="margin-bottom: 89px; padding: 34px 0;">'.$info_item.'</div>';
    }

}


//Start darker background:
if($messages){
    echo '<div class="halfbg narrow-bar slim_flat">';
    echo $messages;
    echo '</div>';
}





//List Relevant Ideas in order:
$secondary_i = '';
foreach($secondary_idea_list as $ref_i){
    $secondary_i .= view_card_idea(14565,  $ref_i);
}
if(strlen($secondary_i)){
    echo '<div class="row justify-content flip-content">';
    echo $secondary_i;
    echo '</div>';
    echo '<div class="doclear" style="padding-bottom: 55px;">&nbsp;</div>';
}





$social_ui = null;
$players___14870 = $this->config->item('players___14870'); //Website Partner
foreach($this->config->item('players___14036') as $playerid => $m){
    foreach($this->Menchledger->fetch(array(
        'linkplayerup' => $playerid,
        'linkplayerdown' => $website_id,
        'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array(), 0, 0) as $social_link){

        //Determine link type:
        if(filter_var($social_link['linktext'], FILTER_VALIDATE_URL) && view_url_clean($social_link['linktext'])!=view_url_clean($players___14870[$website_id]['m__message'])){
            //We made sure not the current website:
            $social_url = $social_link['linktext'];
        } elseif(filter_var($social_link['linktext'], FILTER_VALIDATE_EMAIL)){
            $social_url = 'mailto:'.$social_link['linktext'];
        } elseif(strlen(preg_replace("/[^0-9]/", "", $social_link['linktext'])) > 5){
            //Phone
            $social_url = phone_href($playerid, $social_link['linktext']);
        } else {
            //Unknown!
            continue;
        }

        //Append to link:
        $social_ui .= '<li><a href="'.$social_url.'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'">'.$m['m__cover'].'</a></li>';

    }
}
if($social_ui){
    echo '<div class="narrow-bar slim_flat">';
    echo '<div class="social-footer">';
    echo '<ul class="social-ul">';
    echo $social_ui;
    echo '</ul>';
    echo '</div>';
    echo '</div>';
}

echo '<div class="bottom_spacer">&nbsp;</div>';

?>

<style>
    .creator_frame,
    .mini_time {
        display: none !important;
    }
    .container_content .line {
        color: #FFFFFF !important;
    }
    .cover-wrapper{
        background-color: #FFFFFF !important;
    }
</style>

<script>
    $(window).scroll(function() {
        if($(window).scrollTop()  > 110) {
            $('.fixed-top').removeClass('top-header-position');
        } else {
            $('.fixed-top').addClass('top-header-position');
        }
    });
</script>
