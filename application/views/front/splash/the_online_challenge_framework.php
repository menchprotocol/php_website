<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
?>
	<div class="page-header header-filter" data-parallax="true" style="background-image: url('/img/bg.jpg');">
        <div class="container">
            <div class="row">
				<div class="col-sm-9">
					<h1 class="home-p">Online Bootcamps for the Ambitious.</h1>
                    <h4 class="home-p">We help students accomplish their goals faster by working with expert instructors that hold them accountable in executing a weekly action plan.</h4>
                    
                    <a href="/bootcamps" class="btn btn-danger btn-raised btn-lg bg-glow" style="margin-bottom:50px;">Browse Bootcamps <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>
				</div>
				<div class="col-sm-3">&nbsp;</div>
            </div>
        </div>
    </div>
    
    <div class="main main-raised">
	<div class="container body-container">
    
    <script>
    $( document ).ready(function() {
    	 var glow = $('.bg-glow');
    	    setInterval(function(){
    	        glow.toggleClass('glow');
    	    }, 1333);
    });
    </script>
