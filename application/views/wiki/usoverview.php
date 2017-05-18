<h1 class="boldi">Your Personal Intelligence Assistant</h1>

<p class="boldi">We're building a bot assistant called "Us" that streamlines the learning of select topics. Our name is inspired by the human contribution that powers our bots' intelligence.</p>

<p class="boldi"><a href=""></a></p>

<?php if(!auth(1)){ ?>
<div class="list-group boldi">
	<a href="/signup" class="list-group-item"><span class="badge"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>Signup for Updates</a>
	<a href="/login" class="list-group-item"><span class="badge"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>Admin Login</a>
	<a href="mailto:support@us.foundation" class="list-group-item"><span class="badge"><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span></span>Email support@us.foundation</a>
</div>
<?php } ?>