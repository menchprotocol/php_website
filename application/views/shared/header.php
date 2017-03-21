<?php 
//Attempt to fetch session variables:
$user_data = $this->session->userdata('user');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://trello-attachments.s3.amazonaws.com/56663c0b94f2f4d85376ee1a/80x80/2477359e76f4b66fa7023f18148cbbe7/US_Network_Space.png">

    <title><?= ( isset($title) ? $title : '#USNetwork' ) ?></title>

    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	
    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Inconsolata" rel="stylesheet">
	<link href='//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
	<link href="https://swisnl.github.io/jQuery-contextMenu/dist/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
	<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
	<link href="/css/easy-autocomplete.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://swisnl.github.io/jQuery-contextMenu/dist/jquery.contextMenu.js" type="text/javascript"></script>
    
    <link href="/css/main.css?v=<?= version_salt() ?>" rel="stylesheet">
  </head>
  <body>
    
    <?php 
    if(isset($user_data['id'])){
    ?>
    <div class="container" style="margin-top:15px;">
	    <div class="row">
		  <div class="col-xs-8">
		  	<p class="headercont">
				<a href="/us">US</a>
				<a href="/patterns">#Patterns</a>
			</p>
		  </div>
		  <div class="col-xs-4">
			  <div class="btn-group" style="float:right; margin-top:5px;">
				  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <?= $user_data['username'] ?> <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
				    <li><a href="/us/<?= $user_data['username'] ?>">Profile</a></li>
				    <li><a href="/us/logout">Logout</a></li>
				  </ul>
			  </div>
		  </div>
		</div>
	</div>
    <?php } else { ?>
    <style type="text/css">
		#main_container{ margin-top:50px; }
	</style>
    <?php } ?>
    <div class="container " role="main" id="main_container">
    
    <?php
	//Any Html Messages int he flash session to show?
	$hm = $this->session->flashdata('hm');
	if($hm){
		echo '<div class="row" style="margin-top:10px;"><div class="col-xs-12">'.$hm.'</div></div>';
	}
	?>