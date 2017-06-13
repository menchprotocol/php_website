<?php 
//Attempt to fetch session variables:
$user_data = $this->session->userdata('user');
//print_r($user_data);exit;
$grandparents = grandparents();
$controller = $this->uri->segment(1);
$function = $this->uri->segment(2);
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="/favicon.ico" />
    <title><?= ( isset($node[0]['title']) ? $grandparents[$node[0]['grandpa_id']]['sign'].' '.strip_tags($node[0]['value']) : ( isset($title) ? $title: 'Us') ) ?></title>
	<?= @$meta_data?>
	
	
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
  </head>
  <body>
    
    <div class="container topline <?= (isset($user_data['id']) ? '' : 'guestheader') ?>">
	  	<p class="headercont">
			<a href="/" style="display:inline-block">Us</a>
			<?php if(isset($user_data['id']) || isset($show_grandpas)){ ?>
				<form id="searchForm" class="search-block">
				  <input type="text" class="form-control autosearch" id="mainsearch" placeholder="Search...">
				</form>
			<?php } ?>		
		</p>
	</div>
	
		
	<div class="container main-header">
		
		<?php
		//Any Html Messages int he flash session to show?
		$hm = $this->session->flashdata('hm');
		if($hm){
			echo '<div class="row" style="margin-top:10px;"><div class="col-xs-12">'.$hm.'</div></div>';
		}
		?>
		
		<ul class="nav nav-tabs">
		  <?php if(isset($user_data['id']) || isset($show_grandpas)){ ?>
			  <li role="presentation" <?= ( isset($node) && $node[0]['grandpa_id']==1 ? 'class="active"' : '' ) ?>><a href="/1?from=header">@</a></li>
			  <li role="presentation" <?= ( isset($node) && $node[0]['grandpa_id']==3 ? 'class="active"' : '' ) ?>><a href="/3?from=header">#</a></li>
			  <?php if(isset($user_data['id'])){ ?>
			  <li role="presentation" <?= ( isset($node) && $node[0]['grandpa_id']==4 ? 'class="active"' : '' ) ?>><a href="/4?from=header">?</a></li>
			  <li role="presentation" <?= ( isset($node) && $node[0]['grandpa_id']==43 ? 'class="active"' : '' ) ?>><a href="/43?from=header">!</a></li>
			  <li role="presentation" style="float:right;"><a href="/<?= $user_data['node_id'] ?>?from=header" id="myGravatar" title="A link to your profile. Change your image on gravatar.com" data-toggle="tooltip" data-placement="bottom"><img src="https://www.gravatar.com/avatar/<?= md5(strtolower(trim($user_data['email']['value']))) ?>?d=identicon" /></a></li>
			  <?php } ?>
		  <?php } ?>
		  
		  <?php if(!isset($user_data['id'])) { ?>
			  <li role="presentation" <?= ( $controller=='login' ? 'class="active"' : '' ) ?> style="float:right;"><a href="/login<?= ( isset($node) ? '?from='.$node[0]['node_id']: '' ) ?>">Login</a></li>
			  <li role="presentation" <?= ( $controller=='signup' ? 'class="active"' : '' ) ?> style="float:right;"><a href="/signup">Signup</a></li>
		  <?php } ?>
		</ul>
	</div>
	
	 
	
    <div class="container <?= (isset($view) ? 'view_'.$view : '') ?>" role="main" id="main_container">   