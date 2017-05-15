<?php 
//Attempt to fetch session variables:
$user_data = $this->session->userdata('user');
?>
	</div> <!-- End #main_container -->
	
	<div class="container nonesearch">
		<footer class="outsider">
	        <p><?= ( auth_admin(1)? '<a href="/api/update_algolia">Update Search Cache</a>' : 'We love welcoming new contributors to <a href="/join">our community</a>.') ?></p>
	        <p><a href="https://github.com/USfoundation/us-indexer/commits/develop"><?= version_salt() ?></a> Built with &#10084; in Vancouver. <a href="/terms">Terms</a></p>
		</footer>
	</div>
	
	<script src="/js/main.js?v=<?= version_salt() ?>"></script>
	
  </body>
</html>