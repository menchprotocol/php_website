<?php 
//Attempt to fetch session variables:
$user_data = $this->session->userdata('user');
//print_r($user_data);exit;
$parents = parents();
$controller = $this->uri->segment(1);
$function = $this->uri->segment(2);
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="shortcut icon" href="/favicon.ico" />
    <title><?= ( isset($node[0]['title']) ? $parents[$node[0]['grandpa_id']]['sign'].' '.strip_tags($node[0]['value']) : 'Us' ) ?></title>

    <!-- CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Exo" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<link href="/css/easy-autocomplete.min.css" rel="stylesheet">
	<link href="/css/main.css?v=<?= version_salt() ?>" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
    
  </head>
  <body>
    
    <div class="container topline <?= (isset($user_data['id']) ? '' : 'guestheader') ?>">
	  	<p class="headercont">
			<a href="/" style="display:inline-block">Us</a>
			<?php if(isset($user_data['id'])){ ?>
			<form class="search-block">
			     <div class="input-group">
			     
			      <!-- 
			      <div class="input-group-addon search-type">
			      	<div class="btn-group">
					  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">*</button>
					  <ul class="dropdown-menu">
					    <li><a href="#"><b>*</b>Everything</a></li>
					    <li><a href="#"><b>@</b>Us</a></li>
					    <li><a href="#"><b>&</b>Sources</a></li>
					    <li><a href="#"><b>#</b>Pattern</a></li>
					    <li><a href="#"><b>?</b>Questions</a></li>
					  </ul>
					</div>
			      </div>
			       -->
			       
				  <input type="text" class="form-control" id="MainSearch" placeholder="Search">
			    </div>
			</form>
			<?php } ?>				
		</p>
	</div>
	
	
	<div class="container searchresults"></div>
	
	<div class="container main-header nonesearch">
		<ul class="nav nav-tabs">
		  <?php if(isset($user_data['id'])){ ?>	
			  <li role="presentation" <?= ( isset($node) && $node[0]['grandpa_id']==1 && $node[0]['node_id']!=$user_data['node_id']? 'class="active"' : '' ) ?>><a href="/1">@</a></li>
			  <li role="presentation" <?= ( isset($node) && $node[0]['grandpa_id']==2 ? 'class="active"' : '' ) ?>><a href="/2">&</a></li>
			  <li role="presentation" <?= ( isset($node) && $node[0]['grandpa_id']==3 ? 'class="active"' : '' ) ?>><a href="/3">#</a></li>
			  <li role="presentation" <?= ( isset($node) && $node[0]['grandpa_id']==4 ? 'class="active"' : '' ) ?>><a href="/4">?</a></li>
			  <li role="presentation" <?= ( isset($node) && $node[0]['grandpa_id']==43 ? 'class="active"' : '' ) ?>><a href="/43">!</a></li>
			  <li role="presentation" <?= ( isset($node) && $node[0]['node_id']==$user_data['node_id'] ? 'class="active"' : '' ) ?> style="float:right;"><a href="/<?= $user_data['node_id'] ?>">@me</a></li>
		  <?php } else { ?>
			  <li role="presentation" <?= ( $controller=='login' ? 'class="active"' : '' ) ?> style="float:right;"><a href="/login">Login</a></li>
			  <li role="presentation" <?= ( $controller=='join' ? 'class="active"' : '' ) ?> style="float:right;"><a href="/join">Join Us</a></li>
		  <?php } ?>
		</ul>
	</div>
    <div class="container nonesearch" role="main" id="main_container">
    
    <?php
	//Any Html Messages int he flash session to show?
	$hm = $this->session->flashdata('hm');
	if($hm){
		echo '<div class="row" style="margin-top:10px;"><div class="col-xs-12">'.$hm.'</div></div>';
	}
	?>