<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
?>
	<div class="page-header header-filter" data-parallax="true" style="background-image: url('/img/bg.jpg');">
        <div class="container">
            <div class="row">
				<div class="col-sm-9">
					<h1 class="home-p"><?= $this->lang->line('headline_primary') ?></h1>
                    <h4 class="home-p"><?= $this->lang->line('headline_secondary') ?></h4>
                    
                    <?php
    				if(isset($udata['u_id'])){
    					echo '<a href="/marketplace" class="btn btn-danger btn-raised btn-lg bg-glow">'.$this->lang->line('m_name').' <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
    				} else {
    					echo '<a href="https://mench.typeform.com/to/nh4s2u" class="btn btn-danger btn-raised btn-lg bg-glow">'.$this->lang->line('signup').' <i class="fa fa-sign-in"></i></a>';
    					echo '<p class="sub-button">'.$this->lang->line('or').' <a href="#" data-toggle="modal" data-target="#loginModal">'.$this->lang->line('login').'</a></p>';
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