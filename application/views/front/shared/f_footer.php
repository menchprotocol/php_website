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
                    <li class="pull-left"><a href="/terms">Terms</a></li>
                    <li class="pull-left"><a href="/contact">Contact</a></li>
                    <?= (!isset($udata['u_id']) ? '<li class="pull-left"><a href="#" data-toggle="modal" data-target="#loginModal">Partner Login</a></li>' : ''); ?>
                    
                    <li class="pull-right"><i> Mench Media Inc.</i></li>
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
						<h4 class="card-title">Partner Login</h4>
					</div>
				</div>
				<div class="modal-body">
					<div class="card-content" style="padding-bottom:30px;">
						<p>Already a Mench Partner?</p>
						<div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" onlogin="checkLoginState();"></div>
					</div>
				</div>

				<div class="modal-footer text-center">
					<p style="padding-bottom:0;">Don't have one? <a href="https://mench.typeform.com/to/nh4s2u">Get Early Access <i class="fa fa-sign-in"></i></a></p>
					<p style="padding-bottom:20px;">Anything we can help? <a href="/contact">Contact Us <i class="fa fa-comment" aria-hidden="true"></i></a></p>
				</div>

			</div>
		</div>
	</div>
</div>
<!--  End Modal -->

</body>
</html>
