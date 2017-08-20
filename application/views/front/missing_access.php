<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
?>
<div class="alert alert-danger" role="alert" style="margin-bottom:0;"><b>ERROR:</b> You cannot access <?= ( isset($_GET['url']) ? $_GET['url'] : 'the page you requested' ) ?>.<?= ( isset($udata['u_id']) ? ' <a href="/contact">Contact us</a> to request access.' : ' <a href="#" data-toggle="modal" data-target="#loginModal">Login</a> to continue.' ) ?></div>
