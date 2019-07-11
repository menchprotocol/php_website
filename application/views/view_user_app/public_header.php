<?php
//Attempt to fetch session variables:
$session_en = $this->session->userdata('user');
$url_part_1 = $this->uri->segment(1);
?><!doctype html>
<html lang="en">
<head>
    <!--

    WELCOME TO MENCH'S SOURCE CODE 😻​

    INTERESTED IN HELPING US BUILD THE FUTURE OF EDUCATION?

    YOU CAN WORK WITH US FROM ANYWHERE IN THE WORLD

    EMAIL YOUR RESUME TO SUPPORT@MENCH.COM

    -->
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" href="/img/bp_16.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title><?= (isset($title) ? $title . ' | ' : '') . 'Mench' ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <?php $this->load->view('view_shared/global_js_css'); ?>
</head>

<body class="landing-page">
<?php $this->load->view('view_shared/google_tag_manager'); ?>

<nav class="navbar navbar-warning navbar-fixed-top navbar-color-on-scroll no-adj">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand tag-manager-home-link" href="/"><img src="/img/bp_128.png" /><span
                        style="text-transform: lowercase; color: #2f2739;">Mench</span></a>
        </div>

        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (isset($session_en['en_id'])) {

                    $en_all_7372 = $this->config->item('en_all_7372');

                    //Is this user a Miner?
                    if(en_auth(array(1308))){
                        echo '<li><a href="/dashboard" class="parent-icon">'.$en_all_7372[7368]['m_icon'].' '.$en_all_7372[7368]['m_name'].'</a></li>';
                    }

                    //Student App:
                    echo '<li><a href="/actionplan">'.$en_all_7372[7369]['m_icon'].' '.$en_all_7372[7369]['m_name'].'</a></li>';

                    //Enable sign-out:
                    echo '<li><a href="/logout"><i class="fas fa-power-off"></i> Logout</a></li>';

                } else {

                    //Allow to sign-in:
                    echo '<li><a href="/login'. ( isset($in['in_id']) && $in['in_id'] != $this->config->item('in_focus_id') ? '?url=%2Fintents%2F'.$in['in_id'] : '' ) .'" class="tag-manager-sign-in"><i class="fas fa-sign-in"></i> Sign In</a></li>';

                }
                ?>
            </ul>
        </div>
    </div>
</nav>


<div class="main main-raised main-plain">
    <div class="container body-container">

<?php
//Show possible flash message:
$hm = $this->session->flashdata('flash_message');
if ($hm) {
    echo $hm;
}

if (isset($message)) {
    echo $message;
}
?>