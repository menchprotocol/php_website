<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="/img/psquare_16.png">
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
    <nav class="navbar navbar-danger navbar-transparent navbar-absolute">
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
    				<li>
						<a href="../index.html">
							Components
						</a>
					</li>

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="material-icons">view_day</i> Sections
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu dropdown-with-icons">
							<li>
								<a href="../sections.html#headers">
									<i class="material-icons">dns</i> Headers
								</a>
							</li>
							<li>
								<a href="../sections.html#features">
									<i class="material-icons">build</i> Features
								</a>
							</li>
							<li>
								<a href="../sections.html#blogs">
									<i class="material-icons">list</i> Blogs
								</a>
							</li>
							<li>
								<a href="../sections.html#teams">
									<i class="material-icons">people</i> Teams
								</a>
							</li>
							<li>
								<a href="../sections.html#projects">
									<i class="material-icons">assignment</i> Projects
								</a>
							</li>
							<li>
								<a href="../sections.html#pricing">
									<i class="material-icons">monetization_on</i> Pricing
								</a>
							</li>
							<li>
								<a href="../sections.html#testimonials">
									<i class="material-icons">chat</i> Testimonials
								</a>
							</li>
							<li>
								<a href="../sections.html#contactus">
									<i class="material-icons">call</i> Contacts
								</a>
							</li>

						</ul>
					</li>

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="material-icons">view_carousel</i> Examples
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu dropdown-with-icons">
							<li>
								<a href="../examples/about-us.html">
									<i class="material-icons">account_balance</i> About Us
								</a>
							</li>
							<li>
								<a href="../examples/blog-post.html">
									<i class="material-icons">art_track</i> Blog Post
								</a>
							</li>
							<li>
								<a href="../examples/blog-posts.html">
									<i class="material-icons">view_quilt</i> Blog Posts
								</a>
							</li>
							<li>
								<a href="../examples/contact-us.html">
									<i class="material-icons">location_on</i> Contact Us
								</a>
							</li>
							<li>
								<a href="../examples/landing-page.html">
									<i class="material-icons">view_day</i> Landing Page
								</a>
							</li>
							<li>
								<a href="../examples/login-page.html">
									<i class="material-icons">fingerprint</i> Login Page
								</a>
							</li>
							<li>
								<a href="../examples/pricing.html">
									<i class="material-icons">attach_money</i> Pricing Page
								</a>
							</li>
							<li>
								<a href="../examples/ecommerce.html">
									<i class="material-icons">shop</i> Ecommerce Page
								</a>
							</li>
							<li>
								<a href="../examples/product-page.html">
									<i class="material-icons">beach_access</i> Product Page
								</a>
							</li>
							<li>
								<a href="../examples/profile-page.html">
									<i class="material-icons">account_circle</i> Profile Page
								</a>
							</li>
							<li>
								<a href="../examples/signup-page.html">
									<i class="material-icons">person_add</i> Signup Page
								</a>
							</li>
						</ul>
					</li>

					<li>
						<a href="http://www.creative-tim.com/buy/material-kit-pro?ref=presentation" target="_blank" class="btn btn-white btn-simple">
							<i class="material-icons">shopping_cart</i> Buy Now
						</a>
					</li>
        		</ul>
        	</div>
    	</div>
    </nav>