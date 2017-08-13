</div>
</div>

 	<footer class="footer">
        <div class="container">
            <nav class="pull-left">
                <ul>
                    <li><a href="/terms">Terms</a></li>
                    <li><a href="/contact">Contact</a></li>
                    <li><i><?= version_salt() ?></i></li>
                </ul>
            </nav>
            <?php /*
            <div class="pull-right">
                &copy; <script>document.write(new Date().getFullYear())</script> Mench Media Inc.
            </div>
            */ ?>
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
						<h4 class="card-title">Sign In / Sign Up</h4>
					</div>
				</div>
				<div class="modal-body">
					<div class="card-content">
						<div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="continue_with" data-show-faces="false" data-auto-logout-link="false" data-use-continue-as="false" onlogin="checkLoginState();"></div>
					</div>
				</div>

				<div class="modal-footer text-center">
					<p class="reasona" style="padding-bottom:20px;">We would never post without your concent. <a href="javascript:$('.reasona').hide();$('.reasonp').fadeIn();">Why Facebook?</a></p>
					<p class="reasonp" style="display:none; padding-bottom:20px;">We require Facebook login because we use Facebook Messenger for communication.</p>
				</div>

			</div>
		</div>
	</div>
</div>
<!--  End Modal -->

</body>
</html>
