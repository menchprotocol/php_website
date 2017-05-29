<?php 
//Attempt to fetch session variables:
$user_data = $this->session->userdata('user');
?>
	</div> <!-- End #main_container -->
	
	<div class="container nonesearch">
		<footer class="outsider">
	        <p><b><?= version_salt() ?></b> / <a href="/terms">Terms</a></p>
	        <?= ( auth_admin(1) ? '<div class="btn-group dropup">
  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Admin <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="https://us.foundation/openapi/update_algolia" target="_blank"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Search Index</a></li>
    <li><a href="https://us.foundation/openapi/health_check" target="_blank"><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> Health Check</a></li>
  </ul>
</div>' : '' ) ?>
		</footer>
	</div>
	
	<script src="/js/main.js?v=<?= version_salt() ?>"></script>
	
  </body>
</html>