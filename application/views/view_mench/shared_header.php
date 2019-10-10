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
    <link href="https://fonts.googleapis.com/css?family=Montserrat:800|Nanum+Gothic+Coding&display=swap" rel="stylesheet">

    <!--
    <link href="/css/lib/material-dashboard.css" rel="stylesheet"/>
    <link href="/css/lib/material-kit.css" rel="stylesheet"/>
    <script src="/js/lib/material.min.js" type="text/javascript"></script>
    <script src="/js/lib/material-dashboard.js" type="text/javascript"></script>
    -->

    <link href="/css/custom/mench.css?v=v<?= $this->config->item('app_version') ?>" rel="stylesheet"/>

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
        //echo ' var algolia_filter = \'alg_obj_is_in=1 AND _tags:alg_for_users\'; ';
        echo ' var algolia_filter = \'\'; ';


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

        //Random Messages:
        echo ' var random_loading_message = ' . json_encode(echo_random_message('ying_yang', true)) . '; ';
        echo ' var random_saving_message = ' . json_encode(echo_random_message('saving_notify', true)) . '; ';
        ?>
    </script>
</head>

<body>

<?php
//Any message we need to show here?
if (!isset($flash_message)) {
    $flash_message = $this->session->flashdata('flash_message');
}
if(strlen($flash_message) > 0){
    echo '<div class="container" id="custom_message">'.$flash_message.'</div>';
}
?>

    <?php if(!isset($hide_header) || !$hide_header){ ?>

        <?php $en_all_7305 = $this->config->item('en_all_7305'); /* MENCH PLATFORM */ ?>

        <!-- HEADER -->
        <div class="container fixed-top">
            <div class="row">
                <table class="header-top">
                    <tr>
                        <?php
                        foreach($this->config->item('en_all_2738') as $en_id => $m){
                            $handle = strtolower($m['m_name']);
                            echo '<td valign="bottom"><a class="'.$handle.' border-'.$handle.( $this->uri->segment(1)==$handle ? ' background-'.$handle: null ).'" href="/'.$handle.'">' . $m['m_icon'] . '<span class="mn_name montserrat">' . $m['m_name'] . '</span> <span class="current_count"><i class="far fa-yin-yang fa-spin"></i></span></a></td>';
                        }
                        ?>
                    </tr>
                </table>
            </div>
        </div>


        <!-- FOOTER -->
        <div class="container fixed-bottom">
            <div class="row">
                <table class="footer-bottom">
                    <tr>
                        <td><img src="/img/mench-v2-128.png" class="search-toggle footer-logo mench-spin" /><span class="mench-logo montserrat search-toggle">MENCH</span><div class="search-toggle hidden"><form id="searchFrontForm"><input class="form-control algolia_search" type="search" id="mench_search" data-lpignore="true" placeholder="<?= $en_all_7305[7256]['m_name'] ?>"></form></div></td>
                        <td class="block-link search-toggle"><a href="javascript:void(0);" onclick="load_searchbar();" data-toggle="tooltip" data-placement="top" title="<?= $en_all_7305[7256]['m_name'] ?>"><?= $en_all_7305[7256]['m_icon'] ?></a></td>
                        <td class="block-link"><a href="/read/8263" data-toggle="tooltip" data-placement="top" title="<?= $en_all_7305[7540]['m_name'] ?>"><?= $en_all_7305[7540]['m_icon'] ?></a></td>
                        <td class="block-link">
                            <?php
                            if (isset($session_en['en_id'])) {
                                echo '<a href="/play/'.$session_en['en_id'].'" data-toggle="tooltip" data-placement="top" title="'.$session_en['en_name'].'">'.echo_en_icon($session_en).'</a>';
                            } else {
                                //Give option to signin
                                echo '<a href="/sign" data-toggle="tooltip" data-placement="top" title="'.$en_all_7305[4269]['m_name'].'">'.$en_all_7305[4269]['m_icon'].'</a>';
                            }
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>


        <!-- SEARCH -->
        <div class="container" id="searchresults"></div>

    <?php } ?>