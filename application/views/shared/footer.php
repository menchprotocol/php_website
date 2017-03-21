<?php 
//Attempt to fetch session variables:
$user_data = $this->session->userdata('user');
?>
	</div> <!-- End #main_container -->
	
	<?php if(isset($user_data['id'])){ ?>
	<div class="container">
		<footer class="outsider">
	        <p><b><?= version_salt() ?></b> Made in Vancouver with Love</p>
		</footer>
	</div>
	<?php } ?>
	
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
	<script src="/js/jquery.easy-autocomplete.min.js"></script>
	<script src="/js/main.js?v=<?= version_salt() ?>"></script>
  </body>
</html>