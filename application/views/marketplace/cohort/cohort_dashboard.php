<div class="mini-label">
	<div><a href="/marketplace/<?= $challenge['c_id'] ?>"><span class="label label-default"><?= $challenge['c_objective'] ?></span></a></div>
</div>
<h1><?= $this->lang->line('r_name') ?> #<?= $run['r_id'] ?> <?= $this->lang->line('r_d_name') ?></h1>

<h3 style="margin-top:30px;">Overview</h3>
<div class="row">
  <div class="col-md-3"><b>Status</b></div>
  <div class="col-md-9"><?= status_bible('r',$run['r_status']) ?></div>
</div>
<div class="row">
  <div class="col-md-3"><b>Start Time</b></div>
  <div class="col-md-9"><?= time_format($run['r_start_time']) ?></div>
</div>
<div class="row">
  <div class="col-md-3"><b>End Time</b></div>
  <div class="col-md-9"><?= time_format($run['r_end_time']) ?></div>
</div>
<div class="row">
  <div class="col-md-3"><b>Price</b></div>
  <div class="col-md-9">$<?= number_format($run['r_usd_price'],0) ?> USD</div>
</div>





<h3 style="margin-top:30px;">Enrollment</h3>
<div class="row">
  <div class="col-md-3"><b>Currently Enrolled</b></div>
  <div class="col-md-9">0</div>
</div>
<div class="row">
  <div class="col-md-3"><b>Enrollment Limits</b></div>
  <div class="col-md-9"><?= ( $run['r_min_students']>0 ? 'Min '.$run['r_min_students'] : 'No Min') ?> - <?= ( $run['r_max_students']>0 ? 'Max '.$run['r_max_students'] : 'No Max') ?></div>
</div>




<h3 style="margin-top:30px;">Rules</h3>
<div class="row">
  <div class="col-md-3"><b>Max Strikes</b></div>
  <div class="col-md-9"><?= $run['r_max_strikes'] ?></div>
</div>