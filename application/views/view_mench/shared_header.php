<?php
$session_en = $this->session->userdata('user');
?><!doctype html>
<html lang="en">
<head>

    <!--

    JOIN MENCH @ https://mench.com/12747

    -->

    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="/img/mench-v2-16.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= (isset($title) ? $title . ' | ' : '') . $this->config->item('system_name') ?></title>


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800&display=swap" rel="stylesheet">
    <link href="/css/styles.css?v=v<?= $this->config->item('app_version') ?>" rel="stylesheet"/>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/fbf7f3ae67.js" crossorigin="anonymous"></script>
    <script src="/js/lib/jquery.textcomplete.min.js"></script>
    <script src="/js/lib/autocomplete.jquery.min.js"></script>
    <script src="/js/lib/algoliasearch.min.js"></script>
    <script src="/js/lib/sortable.min.js" type="text/javascript"></script>
    <script src="/js/custom/global-js.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>
    <script src="/js/custom/platform-js.js?v=v<?= $this->config->item('app_version') ?>" type="text/javascript"></script>
    <script>
        <?php
        //Player Variables:
        echo ' var js_advance_view_enabled = ' . ( $this->session->userdata('advance_view_enabled') ? 1 : 0 ) . '; ';
        echo ' var js_pl_id = ' . ( isset($session_en['en_id']) ? $session_en['en_id'] : 0 ) . '; ';

        //Width-Dependant UI control:
        echo ' var is_compact = (is_mobile() || $(window).width() < 767); ';

        //Statuses:
        echo ' var js_en_all_4737 = ' . json_encode($this->config->item('en_all_4737')) . '; ';
        echo ' var js_en_all_6177 = ' . json_encode($this->config->item('en_all_6177')) . '; ';
        echo ' var js_en_all_6186 = ' . json_encode($this->config->item('en_all_6186')) . '; ';

        //Input Limits:
        echo ' var in_outcome_max_length = ' . $this->config->item('in_outcome_max_length') . '; ';
        echo ' var ln_content_max_length = ' . $this->config->item('ln_content_max_length') . '; ';
        echo ' var en_name_max_length = ' . $this->config->item('en_name_max_length') . '; ';

        //Randomg Messages:
        echo ' var random_loading_message = ' . json_encode(echo_random_message('ying_yang', true)) . '; ';
        echo ' var random_saving_message = ' . json_encode(echo_random_message('saving_notify', true)) . '; ';
        ?>
    </script>
</head>

<body>


    <table class="container navbar-top fixed-top">
        <tr>
            <?php
            foreach($this->config->item('en_all_2738') as $en_id => $m){
                $handle = strtolower($m['m_name']);
                echo '<td><a class="'.$handle.' border-'.$handle.( $this->uri->segment(1)==$handle || $handle=='read' ? ' background-'.$handle: null ).'" href="/'.$handle.'">' . $m['m_icon'] . '<span class="mn_name">' . $m['m_name'] . '</span> <span class="current_count"><i class="fas fa-yin-yang fa-spin"></i></span></a></td>';
            }
            ?>
        </tr>
    </table>


    <form id="searchFrontForm">
    <table class="container navbar-bottom fixed-bottom">
        <tr>
            <td><span class="mench-logo search-toggle">MENCH</span><div class="search-toggle hidden"><input class="form-control algolia_search" type="search" id="mench_search" data-lpignore="true" placeholder="SEARCH MENCH..."></div></td>
            <td class="single-a search-toggle"><a href="javascript:void(0);" onclick="$('.search-toggle').toggleClass('hidden');$('.algolia_search').focus();"><i class="far fa-search"></i></a></td>
            <td class="single-a"><a href="/signin"><i class="far fa-sign-in"></i></a></td>
        </tr>
    </table>
    </form>


    <?php
    //Any message we need to show here?
    if (!isset($flash_message)) {
        $flash_message = $this->session->flashdata('flash_message');
    }
    if(strlen($flash_message) > 0){
        echo '<div class="container container-body" id="custom_message">'.$flash_message.'</div>';
    }
    ?>

    <!-- Algolia Search Results -->
    <div class="container container-body" id="searchresults"></div>
