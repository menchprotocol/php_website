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
                    <h1 class="home-p" style="margin-top:80px;">Train Mench. Earn Cash.</h1>
                    <p style="font-size: 1.6em; color:#3C4858; font-weight: 300; line-height: 110%;">Join a movement towards a smarter education system.</p>
                    <p style="font-size: 1.6em; color:#3C4858; font-weight: 300; line-height: 110%;">Earn recurring revenues from home for your indexed content.</p>
				</div>
				<div class="col-sm-3">&nbsp;</div>
            </div>
        </div>
    </div>
    
    <div class="main main-raised">
	<div class="container body-container">
