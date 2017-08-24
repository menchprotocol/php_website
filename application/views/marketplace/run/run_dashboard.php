<div class="mini-label">
	<div><a href="/marketplace/<?= $challenge['c_id'] ?>"><span class="label label-default"><?= $challenge['c_objective'] ?></span></a></div>
</div>
<h1><?= $this->lang->line('r_name') ?> #<?= $run['r_version'] ?> <?= $this->lang->line('r_d_name') ?></h1>

<h3 style="margin-top:30px;">Overview</h3>
<div class="row">
  <div class="col-md-3"><b>Status</b></div>
  <div class="col-md-9"><?= status_bible('r',$run['r_status']) ?></div>
</div>
<div class="row">
  <div class="col-md-3"><b>Kickoff Time</b></div>
  <div class="col-md-9"><?= time_format($run['r_kickoff_time']) ?></div>
</div>
<div class="row">
  <div class="col-md-3"><b>Price</b></div>
  <div class="col-md-9">$<?= number_format($run['r_cost'],0) ?> USD</div>
</div>
<div class="row">
  <div class="col-md-3"><b>Duration</b></div>
  <div class="col-md-9"><?= ( fmod($run['r_duration_days'],7)==0 ? ($run['r_duration_days']/7).' Weeks' : $run['r_duration_days'].' Days' ) ?></div>
</div>




<h3 style="margin-top:30px;">Enrollment</h3>
<div class="row">
  <div class="col-md-3"><b>Currently Enrolled</b></div>
  <div class="col-md-9">0</div>
</div>
<div class="row">
  <div class="col-md-3"><b>Limits</b></div>
  <div class="col-md-9"><?= ( $run['r_min_participants']>0 ? 'Min '.$run['r_min_participants'] : 'No Min') ?> - <?= ( $run['r_max_participants']>0 ? 'Max '.$run['r_max_participants'] : 'No Max') ?></div>
</div>




<h3 style="margin-top:30px;">Rules</h3>
<div class="row">
  <div class="col-md-3"><b>Max Strikes</b></div>
  <div class="col-md-9"><?= $run['r_max_strikes'] ?></div>
</div>