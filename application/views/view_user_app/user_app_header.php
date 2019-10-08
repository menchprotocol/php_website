<?php
//Attempt to fetch session variables:
$session_en = $this->session->userdata('user');
$is_logged = isset($session_en['en_id']);
$en_all_7369 = $this->config->item('en_all_7369');
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" href="/img/bp_16.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title><?= (isset($title) ? $title . ' | ' : '') . $this->config->item('system_name') ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <?php $this->load->view('view_shared/global_js_css'); ?>
</head>

<body class="landing-page">

<?php if(!isset($hide_header) || !$hide_header){ ?>
<nav class="navbar navbar-warning navbar-fixed-top navbar-color-on-scroll no-adj">
    <div class="container">

        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header navbar-front-header">
             <span class="navbar-brand dashboard-logo">
                <table style="width: 100%; border:0; padding:0; margin:-5px 0 0 0;">
                    <tr>
                        <td style="width:40px;">
                            <a class="navbar-brand" href="/"><img src="/img/bp_128.png" /><span
                                        style="text-transform: lowercase; color: #070707;"></span></a>
                        </td>
                        <td>
                            <form id="searchFrontForm">
                            <input type="text" class="algolia_search" id="mench_search" data-lpignore="true"
                                   placeholder="Search Mench...">
                            </form>
                        </td>
                    </tr>
                </table>
            </span>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php
                if ($is_logged) {


                    $navigation = array(
                        array(
                            'uri_segment_1' => 'actionplan',
                            'anchor' => $en_all_7369[6138]['m_icon'].' '.$en_all_7369[6138]['m_name'],
                        ),
                        array(
                            'uri_segment_1' => 'myaccount',
                            'anchor' => $en_all_7369[6137]['m_icon'].' '.$en_all_7369[6137]['m_name'],
                        )
                    );

                    //If trainer give access back to platform:
                    if(en_auth($this->config->item('en_ids_10691') /* Mench Trainers */)){
                        $en_all_7372 = $this->config->item('en_all_7372');
                        array_push($navigation, array(
                            'uri_segment_1' => 'intents',
                            'anchor' => '<span class="micro-image">'.$en_all_7372[7368]['m_icon'].'</span> '.$en_all_7372[7368]['m_name'].' &nbsp;<i class="fas fa-long-arrow-right"></i>',
                        ));
                    }

                    //Add signout:
                    array_push($navigation, array(
                        'uri_segment_1' => 'signout',
                        'anchor' => $en_all_7369[7291]['m_icon'].' '.$en_all_7369[7291]['m_name'],
                    ));

                    //Display all:
                    foreach ($navigation as $nav_item) {
                        echo '<li><a href="/' . $nav_item['uri_segment_1'] . '" ' . ( $this->uri->segment(1) == $nav_item['uri_segment_1'] ? ' style="color:#FFF; background-color:#070707 !important;"' : '') . '>' . $nav_item['anchor'] . '</a></li>';
                    }

                } else {

                    //Give option to sign-in:
                    echo '<li><a href="/sign">'.$en_all_7369[4269]['m_name'].' '.$en_all_7369[4269]['m_icon'].'</a></li>';

                    //Give option to Signup if Intent is passed:
                    echo '<li class="featured-nav"><a href="/'.( isset($in['in_id']) && $in['in_id']!=$this->config->item('in_focus_id') ? $in['in_id'] : $this->config->item('in_join_id') ).'/sign">SIGN UP <i class="fas fa-user-plus"></i></a></li>';

                }
                ?>
            </ul>
        </div>
    </div>
</nav>
<?php } ?>

<div class="main main-raised main-plain">
    <div class="container <?= ( in_array($this->uri->segment(1), array('links', 'stats') /* Need 100% Width */) ? 'links-container' : 'body-container' ) ?>">

<?php
//Show possible flash message:
echo '<span id="custom_message">';
$hm = $this->session->flashdata('flash_message');
if ($hm) {
    echo $hm;
}

if (isset($message)) {
    echo $message;
}
echo '</span>';
?>