<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
?>
	<div class="page-header header-filter" data-parallax="true" style="background-image: url('/img/bg.jpg');">
        <div class="container">
        	<?php 
        	$hm = $this->session->flashdata('hm');
            if($hm){
                echo '<div class="row"><div class="col-sm-12">'.$hm.'</div></div>';
        	}
        	?>
            <div class="row">
				<div class="col-sm-9">
					<!--
					<h1 class="home-p">Online Projects for the Ambitious.</h1>
                    <h4 class="home-p">Accomplish an outcome <u>faster</u> by working with expert instructors that hold you accountable in completing weekly or daily Tasks on-time.</h4>
                     -->
                    <h1 class="home-p">Empower Student Success</h1>
                    <h4 class="home-p">Infrastructure for experts to build & operate online Projects designed to maximize student engagements.</h4>

                    <a href="/experts" class="btn btn-danger btn-raised btn-lg bg-glow" style="margin-bottom:50px;">Run a 7-Day Project <i class="fa fa-rocket" style="font-size:1.2em;" aria-hidden="true"></i></a>
				</div>
				<div class="col-sm-3">&nbsp;</div>
            </div>
        </div>
    </div>
    
    <div class="main main-raised">
	<div class="container body-container">
