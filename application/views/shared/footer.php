<?php 
//Attempt to fetch session variables:
$user_data = $this->session->userdata('user');
?>
	</div> <!-- End #main_container -->
	
	<div class="container nonesearch">
		<footer class="outsider">
	        <p><a href="https://github.com/USfoundation/us-indexer/commits/develop"><?= version_salt() ?></a>Built with &#10084; in Vancouver</p>
		</footer>
	</div>

	<?php /* 	<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
*/ ?>	
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="/js/jquery.easy-autocomplete.min.js"></script>
	<script src="/js/main.js?v=<?= version_salt() ?>"></script>
  </body>
</html>