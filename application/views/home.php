<?php



//TITLE
$website_id = website_setting(0);
$expanded_space = in_array($website_id , $this->config->item('handleids___31025'));

if(in_array($website_id, $this->config->item('handleids___30984'))){
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'homecss_black_font\'); }); </script> ';
} else {
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'homecss_white_font\'); }); </script> ';
}


$secondary_i = '';
$primary_i = array();
foreach($this->Chains->read(array(
    'chainhandletype' => 34513, //Pinned
    'chainhandleinput' => $website_id,
), array('chainhashtagoutput'), 1, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC')) as $primary_i){

    echo ' <script> $(document).ready(function () { show_more('.$primary_i['hashtagid'].'); $(document).prop(\'title\', \''.get_domain('m__title').' | '.str_replace('\'','\\\'',view_hashtag_title($primary_i, true)).'\'); }); </script> ';

    echo '<h1 class="maxwidth" style="margin: '.( $expanded_space ? '144px auto 377px' : '89px auto 233px' ).' !important;">' . view_hashtag_title($primary_i, true) . '</h1>';

    $messages = '<div class="center-frame hide-subline maxwidth hideIfEmpty remove_first_line">' . view_hashtag_value($primary_i) . '</div>';

    foreach ($this->Chains->read(array(
        'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
        'chainhashtaginput' => $primary_i['hashtagid'],
    ), array('chainhashtagoutput'), 0, 0) as $next_i) {
        $secondary_i .= hashtag_view(14565,  $next_i);
    }

}





//SOCIAL FOOTER
$domain_phone =  website_setting(28615);
$email_domain =  website_setting(28614);
$handles___11035 = $this->config->item('handles___11035');

$contact_us = '';
if($domain_phone || $email_domain) {

    $contact_us .= '<ul class="social-footer">';
    if($domain_phone){
        $contact_us .= '<li><a href="tel:'.preg_replace("/[^0-9]/", "", $domain_phone).'" data-toggle="tooltip" data-placement="top" title="'.$handles___11035[28615]['m__title'].'">'.$handles___11035[28615]['m__cover'].' '.$domain_phone.'</a></li>';
    }

    if($email_domain){
        $contact_us .= '<li><a href="mailto:'.$email_domain.'" title="'.$handles___11035[28614]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$handles___11035[28614]['m__cover'].' '.$email_domain.'</a></li>';
    }
    $contact_us .= '</ul>';

}





//Any Info Boxes?
foreach($this->Handles->scissor($website_id, 14903) as $handle_item) {
    //Any Followers?
    $info_item = null;
    foreach($this->Chains->read(array(
        'chainhandleinput' => $handle_item['handleid'],
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array('chainhandleoutput'), 0, 0, array('chainkey' => 'ASC')) as $info_element) {
        $info_item .= '<div class="col-12 col-md-4">';
        $info_item .= '<div class="info_box">';
        if(filter_var($info_element['handlecover'], FILTER_VALIDATE_URL)){
            $info_item .= '<div class="info_box_cover">'.'<div class="center-cropped" style="background-image: url(\''.$info_element['handlecover'].'\');"></div>'.'</div>';
            $info_item .= '<div class="info_box_title main__title">'.$info_element['handlename'].'</div>';
        } else {
            $info_item .= '<div class="info_box_cover">'.view_cover($info_element['handlecover']).'</div>';
            $info_item .= '<div class="info_box_title main__title">'.$info_element['handlename'].'</div>';
        }
        if(strlen($info_element['chainvalue'])){
            $info_item .= '<div class="info_box_message">'.$info_element['chainvalue'].'</div>';
        }
        $info_item .= '</div>';
        $info_item .= '</div>';
    }

    if($info_item){
        $messages .= '<h2 class="info-head">'.$handle_item['handlename'].'</h2>';
        if(strlen($handle_item['chainvalue'])){
            $messages .= '<div class="row justify-content center" style="margin-bottom: 89px; padding: 0 34px;">'.$handle_item['chainvalue'].'</div>';
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



//List Relevant Hashtags in order:
if(strlen($secondary_i)){
    echo '<div class="row justify-content flip-content">';
    echo $secondary_i;
    echo '</div>';
    echo '<div class="doclear" style="padding-bottom: 55px;">&nbsp;</div>';
}




//Footer links
$social_ui = null;
$handles___14870 = $this->config->item('handles___14870'); //Website Partner
foreach($this->config->item('handles___14036') as $handleid => $m){
    foreach($this->Chains->read(array(
        'chainhandleinput' => $handleid,
        'chainhandleoutput' => $website_id,
        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array(), 0, 0) as $social_chain){

        //Determine chain type:
        if(filter_var($social_chain['chainvalue'], FILTER_VALIDATE_URL) && view_url_clean($social_chain['chainvalue'])!=view_url_clean($handles___14870[$website_id]['m__message'])){
            //We made sure not the current website:
            $social_url = $social_chain['chainvalue'];
        } elseif(filter_var($social_chain['chainvalue'], FILTER_VALIDATE_EMAIL)){
            $social_url = 'mailto:'.$social_chain['chainvalue'];
        } elseif(strlen(preg_replace("/[^0-9]/", "", $social_chain['chainvalue'])) > 5){
            //Phone
            $social_url = phone_href($handleid, $social_chain['chainvalue']);
        } else {
            //Unknown!
            continue;
        }

        //Append to chain:
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
    .app__14565 .creator_frame,
    .app__14565 .mini_time {
        display: none !important;
    }
    .app__14565 .container_content .line {
        color: #FFFFFF !important;
    }
    .app__14565 .cover-wrapper{
        background-color: #FFFFFF !important;
    }
</style>

<script>
    $(window).scroll(function() {
        if($(window).scrollTop()  > 110) {
            $('.app__14565 .fixed-top').removeClass('top-header-position');
        } else {
            $('.app__14565 .fixed-top').addClass('top-header-position');
        }
    });
</script>
