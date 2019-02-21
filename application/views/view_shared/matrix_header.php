<?php
//Attempt to fetch session variables:
$session_en = $this->session->userdata('user');
$uri_segment_1 = $this->uri->segment(1);
$uri_segment_2 = $this->uri->segment(2);
?><!doctype html>
<html lang="en">
<head>
    <!--

    WELCOME TO MENCH SOURCE CODE!

    INTERESTED IN BUILDING THE FUTURE OF EDUCATION?

    CHECKOUT OUR GITHUB PROJECT PAGE FOR MORE INFO:

    https://github.com/askmench

    -->
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" href="/img/bp_16.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
    <title><?= (isset($title) ? $title . ' | ' : '') ?>Mench</title>


    <link href="/css/lib/devices.min.css" rel="stylesheet"/>
    <link href="/css/lib/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
    <?php $this->load->view('view_shared/global_js_css'); ?>

    <script src="/js/lib/jquery.textcomplete.min.js"></script>
    <script src="/js/lib/autocomplete.jquery.min.js"></script>
    <script src="/js/lib/algoliasearch.min.js"></script>
    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
    <script src="/js/custom/matrix-js.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>

</head>


<body id="matrix_body" class="<?= (isset($_GET['skip_header']) ? 'grey-bg' : '') ?>">

    <!-- Managed by JS to edit various fields -->
    <div class="edit-box hidden"></div>

    <?php
    if (!isset($_GET['skip_header'])) {
        //Include the chat plugin:
        $this->load->view('view_shared/messenger_web_chat');

        //Include the App version:
        echo '<div class="app-version">v'.$this->config->item('app_version').'</div>';
    }
    ?>

    <div class="wrapper" id="matrix">

        <?php if (!isset($_GET['skip_header'])) { ?>
            <nav class="navbar navbar-transparent navbar-absolute">
                <div class="container-fluid">

                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <span class="navbar-brand dashboard-logo">
                            <table style="width: 100%; border:0; padding:0; margin:0 0 0 0;">
                                <tr>
                                    <td style="width:40px;">
                                        <img src="/img/mench_white.png"/>
                                    </td>
                                    <td>
                                        <input type="text" class="algolia_search" id="matrix_search" data-lpignore="true"
                                               placeholder="Search Intents & Entities">
                                    </td>
                                </tr>
                            </table>
                        </span>
                    </div>

                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-main navbar-right">

                            <li class="<?= ($uri_segment_1 == 'ledger' ? 'ledger-active' : 'ledger-inactive') ?>">
                                <a href="/ledger">
                                    <i class="fas fa-atlas"></i> Ledger
                                </a>
                            </li>

                            <li class="<?= ($uri_segment_1 == 'intents' ? 'intent-active' : 'intent-inactive') ?>">
                                <a href="/intents/<?= $this->config->item('in_tactic_id') ?>">
                                    <i class="fas fa-hashtag"></i> Intents
                                </a>
                            </li>

                            <li class="<?= ($uri_segment_1 == 'entities' ? 'entity-active' : 'entity-inactive') ?>">
                                <a href="/entities/<?= $this->config->item('en_start_here_id') ?>">
                                    <i class="fas fa-at"></i> Entities
                                </a>
                            </li>

                            <li class="<?= ($uri_segment_1 == 'entities' && $uri_segment_2 == $session_en['en_id'] ? 'entity-active' : 'entity-inactive') ?>" data-toggle="tooltip" data-placement="left" title="My Entity">
                                <a href="/entities/<?= $session_en['en_id'] ?>">
                                    <i class="fas fa-user-circle"></i>
                                </a>
                            </li>

                            <li class="<?= ($uri_segment_1 == 'add_source' ? 'entity-active' : 'entity-inactive' ) ?>" data-toggle="tooltip" data-placement="left" title="Add Source Wizard">
                                <a href="/entities/fn___add_source_wizard"><i class="fas fa-plus"></i></a>
                            </li>

                            <li class="<?= ($uri_segment_1 == 'stats' ? 'ledger-active' : 'ledger-inactive') ?>" data-toggle="tooltip" data-placement="left" title="Platform Stats">
                                <a href="/stats">
                                    <i class="fas fa-chart-bar"></i>
                                </a>
                            </li>

                            <li data-toggle="tooltip" data-placement="left" title="Logout">
                                <a href="/logout">
                                    <i class="fas fa-power-off"></i>
                                </a>
                            </li>

                        </ul>
                    </div>

                </div>
            </nav>
        <?php } ?>


        <div class="main-panel no-side">
            <div class="content <?= (isset($_GET['skip_header']) ? 'no-frame' : 'dash') ?>">
                <div class="container-fluid">
                    <?php
                    if (isset($message)) {
                        echo $message;
                    }
                    $hm = $this->session->flashdata('hm');
                    if ($hm) {
                        echo $hm;
                    }
                    ?>