<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
?>
	    <div class="section text-center">
    		<!-- How? -->
			<div class="features">
				<div class="row">
					<div class="col-md-12">
						<h2 class="title"><?= $this->lang->line('how_heading') ?></h2>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-4">
						<div class="info">
							<div class="icon icon-primary mtweak"><?= $this->lang->line('how_1_icon') ?></div>
							<h4 class="info-title"><?= $this->lang->line('how_1_title') ?></h4>
							<p><?= $this->lang->line('how_1_desc') ?></p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="info">
							<div class="icon icon-primary mtweak"><?= $this->lang->line('how_2_icon') ?></div>
							<h4 class="info-title"><?= $this->lang->line('how_2_title') ?></h4>
							<p><?= $this->lang->line('how_2_desc') ?></p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="info">
							<div class="icon icon-primary mtweak"><?= $this->lang->line('how_3_icon') ?></div>
							<h4 class="info-title"><?= $this->lang->line('how_3_title') ?></h4>
							<p><?= $this->lang->line('how_3_desc') ?></p>
						</div>
					</div>
				</div>
			</div>
				
			<div class="features">
				<div class="row">
					<div class="col-md-12">
						<h2 class="title" style="margin-top:70px;"><?= $this->lang->line('why_heading') ?></h2>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="info">
							<div class="icon icon-primary mtweak"><?= $this->lang->line('why_1_icon') ?></div>
							<h4 class="info-title"><?= $this->lang->line('why_1_title') ?></h4>
							<p><?= $this->lang->line('why_1_desc') ?></p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="info">
							<div class="icon icon-primary mtweak"><?= $this->lang->line('why_2_icon') ?></div>
							<h4 class="info-title"><?= $this->lang->line('why_2_title') ?></h4>
							<p><?= $this->lang->line('why_2_desc') ?></p>
						</div>
					</div>
					<div class="col-md-4">
						<div class="info">
							<div class="icon icon-primary mtweak"><?= $this->lang->line('why_3_icon') ?></div>
							<h4 class="info-title"><?= $this->lang->line('why_3_title') ?></h4>
							<p><?= $this->lang->line('why_3_desc') ?></p>
						</div>
					</div>
				</div>
			</div>
			
			
			<!-- Get Started -->
			<div class="section section-contacts" style="padding-top:20px;">
				<div class="row">
					<div class="col-md-8 col-md-offset-2" style="text-align:center;">
						<?php
			    		if(isset($udata['u_id'])){
			    			echo '<a href="/console" class="btn btn-danger btn-raised btn-lg bg-glow">'.$this->lang->line('m_name').' <i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a>';
			    		} else {
			    			echo '<a href="https://mench.typeform.com/to/nh4s2u" class="btn btn-danger btn-raised btn-lg bg-glow glow">'.$this->lang->line('signup').' <i class="fa fa-sign-in"></i><div class="ripple-container"></div></a>';
			    			echo '<p class="sub-button">'.$this->lang->line('or').' <a href="/login">'.$this->lang->line('login').'</a></p>';
			    		}
			    		?>
					</div>
				</div>
			</div>

			<?php /*
        	<div class="section section-contacts">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2" style="text-align:center;">
                        <a href="/features" class="btn btn-danger btn-raised btn-lg bg-glow">See Features</a>
                    </div>
                </div>
            </div>
            */ ?>
             
          </div>