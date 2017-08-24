<div class="mini-label">
	<?= status_bible('u',$user['u_status']) ?>
</div>
<h1><?= $user['u_fname'].' '.$user['u_lname'] ?></h1>


<h3 style="margin-top:30px;">Overview</h3>
<div class="row">
  <div class="col-md-3"><b>Username</b></div>
  <div class="col-md-9">@<?= $user['u_url_key'] ?></div>
</div>
<div class="row">
  <div class="col-md-3"><b>Access</b></div>
  <div class="col-md-9"><?= print_r($user['access']); ?></div>
</div>