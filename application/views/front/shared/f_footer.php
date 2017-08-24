<?php 
//Attempt to fetch session variables:
$udata = $this->session->userdata('user');
$website = $this->config->item('website');
?></div>
</div>

 	<footer class="footer">
        <div class="container">
            <nav>
                <ul>
                    <li class="pull-left"><a href="/terms"><?= $this->lang->line('terms') ?></a></li>
                    <li class="pull-left"><a href="/contact"><?= $this->lang->line('contact_us') ?></a></li>
                    <?= (!isset($udata['u_id']) ? '<li class="pull-left"><a href="#" data-toggle="modal" data-target="#loginModal">'.$this->lang->line('login').'</a></li>' : ''); ?>
                    
                    <li class="pull-right"><i><?= $website['legaL_name'] ?></i></li>
                    <li class="pull-right"><i>v<?= $website['version'] ?></i></li>
                </ul>
            </nav>
        </div>
    </footer>

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-login">
		<div class="modal-content">
			<div class="card card-signup card-plain">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>

					<div class="header header-primary text-center">
						<h4 class="card-title"><?= $this->lang->line('login') ?></h4>
					</div>
				</div>
				<div class="modal-body">
					<div class="card-content" style="padding-bottom:30px;">
						<div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" onlogin="checkLoginState();"></div>
					</div>
				</div>

				<div class="modal-footer text-center">
					<p style="padding-bottom:0;"><?= $this->lang->line('no_account') ?> <a href="https://mench.typeform.com/to/nh4s2u"><?= $this->lang->line('signup') ?> <i class="fa fa-sign-in"></i></a></p>
					<p style="padding-bottom:20px;"><?= $this->lang->line('no_clue') ?> <a href="/contact"><?= $this->lang->line('contact_us') ?> <i class="fa fa-comment" aria-hidden="true"></i></a></p>
				</div>

			</div>
		</div>
	</div>
</div>
<!--  End Modal -->

</body>
</html>
