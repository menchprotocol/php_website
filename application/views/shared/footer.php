<?php 
//Attempt to fetch session variables:
$user_data = $this->session->userdata('user');
?>
	</div> <!-- End #main_container -->
	
	<div class="container nonesearch">
		<footer class="outsider">
	        <p>Built with &#10084; in Vancouver</p>
	        <p>Us Humans Foundation</p>
	        <p><b><?= version_salt() ?></b> / <a href="/terms">Terms</a></p>
		</footer>
	</div>
	
	<script src="/js/main.js?v=<?= version_salt() ?>"></script>
	
  </body>
</html>