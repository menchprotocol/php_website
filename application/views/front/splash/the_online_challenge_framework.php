<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
?>
	<div class="page-header header-filter" data-parallax="true" style="background-image: url('/img/bg.jpg');">
        <div class="container">
            <div class="row">
				<div class="col-sm-9">
					<h1>The<br />Online<br />Challenge<br />Framework.</h1>
                    <h4 class="home-p">Empower your audience to achieve their goals by taking action.</h4>
                    
                    <?php
    				if(isset($udata['id'])){
    					echo '<a href="/challenge" class="btn btn-danger btn-raised btn-lg bg-glow">My Challenges <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
    				} else {
    					echo '<a href="https://mench.typeform.com/to/nh4s2u" class="btn btn-danger btn-raised btn-lg bg-glow">Get Early Access <i class="fa fa-sign-in"></i></a>';
    					echo '<p class="sub-button">Or <a href="#" data-toggle="modal" data-target="#loginModal">Login as Partner</a></p>';
    				}
    				?>
				</div>
				<div class="col-sm-3">&nbsp;</div>
            </div>
        </div>
    </div>
    
    <script>
    $( document ).ready(function() {
    	 var glow = $('.bg-glow');
    	    setInterval(function(){
    	        glow.toggleClass('glow');
    	    }, 1333);
    });
    </script>