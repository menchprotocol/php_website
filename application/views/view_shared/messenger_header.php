<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <link rel="icon" type="image/png" href="/img/bp_16.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title><?= 'Mench' . (isset($title) ? ' | ' . $title : '') ?></title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'/>

    <?php $this->load->view('view_shared/global_js_css'); ?>

</head>


<body id="funnel">

<div class="main main-raised">
    <div class="container body-container">

<?php
if (isset($hm) && $hm) {
    echo $hm;
} else {
    $hm = $this->session->flashdata('hm');
    if ($hm) {
        echo $hm;
    }
}
?>