<?php
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$uri_segment_1 = $this->uri->segment(1);
$uri_segment_2 = $this->uri->segment(2);
?><!doctype html>
<html lang="en">
<head>
    <!--

    WELCOME TO MENCH SOURCE CODE!

    INTERESTED IN HELPING US BUILD THE FUTURE OF EDUCATION?

    SEND US YOUR RESUME TO SUPPORT@MENCH.COM

    -->
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" href="/img/bp_16.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>
    <meta name="viewport" content="width=device-width"/>
    <title><?= (isset($title) ? $title . ' | ' : '') ?>Mench</title>

    <link href="/css/lib/devices.min.css" rel="stylesheet"/>
    <link href="/css/lib/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
    <?php $this->load->view('shared/global_js_css'); ?>

    <script src="/js/lib/jquery.textcomplete.min.js"></script>
    <script src="/js/lib/autocomplete.jquery.min.js"></script>
    <script src="/js/lib/algoliasearch.min.js"></script>

    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
    <script src="/js/custom/console-js.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>


    <script>
        <?php
        //Create flat list of JS variables:
        $js_parent_u_ids = array();
        if ($udata) {
            foreach ($udata['en__parents'] as $privilege) {
                array_push($js_parent_u_ids, intval($privilege['u_id']));
            }
        }

        //Translate intent/entity icons to make them available in JS functions:
        echo ' var in_statuses = ' . json_encode(echo_status('in')) . '; ';
        echo ' var u_statuses = ' . json_encode(echo_status('en')) . '; ';
        ?>

        //Define global js variables:
        var js_u_id = <?= $udata['u_id'] ?>;
        var js_parent_u_ids = [<?= join(',', $js_parent_u_ids) ?>];

    </script>

</head>


<body id="console_body" class="<?= (isset($_GET['skip_header']) ? 'grey-bg' : '') ?>">

<?php
if (!isset($_GET['skip_header'])) {
    //Include the chat plugin:
    $this->load->view('shared/messenger_web_chat');
}
?>

<div class="wrapper" id="console">

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
                        <table style="width: 100%; border:0; padding:0; margin:0;">
                            <tr>
                                <td style="width:40px;"><img
                                            src="//theme.zdassets.com/theme_assets/2085893/cabe5e69ca093a3e91eadfb22bc5bee28d66cdeb.png"/></td>
                                <td><input type="text" class="algolia_search" id="console_search" data-lpignore="true"
                                           placeholder="Search Entities/Intents"></td>
                            </tr>
                        </table>
					</span>
                </div>

                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-main navbar-right">

                        <li <?= ($uri_segment_1 == 'entities' ? 'class="entity-active"' : '') ?>><a
                                    href="/entities/<?= $this->config->item('primary_en_id') ?>"><i
                                        class="fas fa-at"></i> Entities</a></li>
                        <li <?= ($uri_segment_1 == 'ledger' ? 'class="entity-active"' : '') ?>><a href="/ledger"><i
                                        class="fas fa-atlas"></i> Ledger</a></li>
                        <li <?= ($uri_segment_1 == 'intents' ? 'class="intent-active"' : '') ?>><a
                                    href="/intents/<?= $this->config->item('primary_in_id') ?>"><i
                                        class="fas fa-hashtag"></i> Intents</a></li>


                        <li class="extra-toggle"><a href="javascript:void(0);" onclick="$('.extra-toggle').toggle();">&nbsp;
                                <i class="fas fa-ellipsis-h"></i> &nbsp;</a></li>

                        <li class="extra-toggle" style="display: none;"><a href="/my/actionplan"><i
                                        class="fas fa-flag"></i> Action Plans</a></li>
                        <li class="extra-toggle" style="display: none;"><a href="/adminpanel/statuslegend"><span
                                        class="icon-left"><i class="fas fa-shield"></i></span> Admin</a></li>
                        <li class="extra-toggle" style="display: none;"><a href="/entities/<?= $udata['u_id'] ?>"><span
                                        class="icon-left"><i class="fas fa-user-circle"></i></span> Me</a></li>
                        <li class="extra-toggle" style="display: none;"><a href="/logout"><span class="icon-left"><i
                                            class="fas fa-power-off"></i></span> Logout</a></li>
                    </ul>
                </div>

            </div>
        </nav>
    <?php } ?>




    <?php if ($uri_segment_1 == 'adminpanel'){ ?>
    <div class="sidebar" id="mainsidebar">
        <div class="sidebar-wrapper">

            <?php
            //Side menu header:
            echo '<div class="left-li-title">';
            echo '<i class="fas fa-user-shield" style="margin-right:3px;"></i> Admin Panel';
            echo '</div>';


            echo '<ul class="nav navbar-main" style="margin-top:7px;">';


            //The the Admin Panel Menu for the Mench team:
            echo '<li class="li-sep ' . ($uri_segment_2 == 'engagements' ? 'active' : '') . '"><a href="/adminpanel/engagements"><i class="fas fa-atlas"></i><p>Engagements</p></a></li>';

            echo '<li class="li-sep ' . ($uri_segment_2 == 'subscriptions' ? 'active' : '') . '"><a href="/adminpanel/subscriptions"><i class="fas fa-comment-plus"></i><p>Subscriptions</p></a></li>';

            echo '<li class="li-sep ' . ($uri_segment_2 == 'statuslegend' ? 'active' : '') . '"><a href="/adminpanel/statuslegend"><i class="fas fa-shapes"></i><p>Status Legend</p></a></li>';


            echo '</ul>';
            ?>

        </div>
    </div>

    <div class="main-panel">
        <?php } else { ?>
        <div class="main-panel no-side">
            <?php } ?>


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