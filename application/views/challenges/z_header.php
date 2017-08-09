<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="/img/bp_16.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title><?= ( isset($node[0]) ? strip_tags($node[0]['value']) : ( isset($title) ? $title: $website['name'] ) ) ?></title>
	<?= @$meta_data ?>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />

	<!--     Fonts and icons     -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons|Titillium+Web:700" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" />

	<!-- CSS Files -->
    <link href="/css/challenges/bootstrap.min.css" rel="stylesheet" />
    <link href="/css/challenges/material-kit.css?v=1.1.0" rel="stylesheet"/>
    <link href="/css/challenges/styles.css?v=1.1.0" rel="stylesheet"/>
</head>

<body class="landing-page">
    <nav class="navbar navbar-warning navbar-fixed-top navbar-color-on-scroll navbar-transparent">
    	<div class="container">
        	<!-- Brand and toggle get grouped for better mobile display -->
        	<div class="navbar-header">
        		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example">
            		<span class="sr-only">Toggle navigation</span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
		            <span class="icon-bar"></span>
        		</button>
        		<a class="navbar-brand" href="/"><img src="/img/bp_48.png" /><span>mench</span></a>
        	</div>

        	<div class="collapse navbar-collapse">
        		<ul class="nav navbar-nav navbar-right">
    				<li><a href="/challenges"><i class="fa fa-search"></i> Browse</a></li>
    				<li><a href="/challenges"><i class="fa fa-rocket"></i> Launch</a></li>
    				<li><a href="/signup">Sign Up</a></li>
    				<li><a href="/login">Login</a></li>
        		</ul>
        	</div>
    	</div>
    </nav>