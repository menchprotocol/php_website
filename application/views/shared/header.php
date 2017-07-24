<?php 
//Attempt to fetch session variables:
$user_data = $this->session->userdata('user');
//print_r($user_data);exit;
$grandparents = $this->config->item('grand_parents');
$website = $this->config->item('website');
$controller = $this->uri->segment(1);
$function = $this->uri->segment(2);
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="https://s3-us-west-2.amazonaws.com/us-videos/Mench-logo-square.png" />
    <title><?= ( isset($node[0]) ? strip_tags($node[0]['value']) : ( isset($title) ? $title: $website['name'] ) ) ?></title>
	<?= @$meta_data ?>
	
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Exo" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/jquery-ui.min.css" rel="stylesheet"><!-- Click to see what it includes -->
	<link href="/css/main.css?v=<?= version_salt() ?>" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- JavaScript -->
	<script src="https://cdn.jsdelivr.net/jquery/2.1.4/jquery.min.js"></script>
	<script src="/js/jquery.textcomplete.js"></script>
	<script src="/js/jquery-ui.min.js"></script><!-- Click to see what it includes -->

    <script src="https://cdn.jsdelivr.net/autocomplete.js/0/autocomplete.jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/clipboard@1/dist/clipboard.min.js"></script><!-- https://clipboardjs.com -->
  </head>
  <body>
  
   
		
	<div class="container main-header">
		
		<?php
		//Any Html Messages int he flash session to show?
		$hm = $this->session->flashdata('hm');
		if($hm){
			echo '<div class="row" style="margin-top:10px;"><div class="col-xs-12">'.$hm.'</div></div>';
		}
		
		
		if(isset($user_data['id']) || isset($show_grandpas)){
			echo '<div>
			<form id="searchForm" class="search-block">
			  <input type="text" class="form-control autosearch" id="mainsearch" placeholder="Search...">
			</form>
		</div>';
		}
		
		$active_states = array(
				0 => ( isset($node) && $node[0]['node_id']==$user_data['node_id'] ? 'class="active"' : null ),
				1 => ( isset($node) && $node[0]['grandpa_id']==1 && $node[0]['node_id']!=$user_data['node_id'] ? 'class="active"' : null ),
				3 => ( isset($node) && $node[0]['grandpa_id']==3 ? 'class="active"' : null ),
				4 => ( isset($node) && $node[0]['grandpa_id']==4 ? 'class="active"' : null ),
				43 => ( isset($node) && $node[0]['grandpa_id']==43 ? 'class="active"' : null ),
		);
		?>
		
		<ul class="nav nav-tabs">
		  <?php if(isset($user_data['id']) || isset($show_grandpas)){ ?>
		  
			  <li role="presentation" <?= $active_states[3] ?>><a href="/3"><b class="blue">#</b></a></li>
			  <li role="presentation" <?= $active_states[1] ?>><a href="/1">@</a></li>
			  <li role="presentation" <?= $active_states[4] ?>><a href="/4">?</a></li>
			  <li role="presentation" <?= $active_states[43] ?>><a href="/43">!</a></li>
			  
			  <li role="presentation" <?= $active_states[0] ?>><a href="/<?= $user_data['node_id'] ?>" id="myGravatar" title="A shortcut link to your profile. Change your image on gravatar.com" data-toggle="tooltip" data-placement="bottom"><img src="https://www.gravatar.com/avatar/<?= md5(strtolower(trim($user_data['email']['value']))) ?>?d=identicon" /></a></li>
		  <?php } ?>
		  
		  <?php if(!isset($user_data['id'])) { 
		  	  /*
			  <li role="presentation" class="us_first rancol<?=rand(1,7)?>">Us</li>
			  <li role="presentation" <?= ( !$controller ? 'class="active"' : '' ) ?>><a href="/">Foundation</a></li>
			  */
			  ?>
			  <li role="presentation" <?= ( $controller=='login' ? 'class="active"' : '' ) ?> style="float:right;" title="Login to the publisher network." data-toggle="tooltip" data-placement="bottom"><a href="/login" style="margin-top: -5px; padding-bottom: 10px !important;"><span class="glyphicon glyphicon-log-in " aria-hidden="true"></span>&nbsp;</a></li>
		  
		  <?php } ?>
		</ul>
	</div>
	
	 
	
    <div class="container <?= (isset($view) ? 'view_'.$view : '') ?>" role="main" id="main_container">   