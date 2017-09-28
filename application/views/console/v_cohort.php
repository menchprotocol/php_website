<div class="mini-label">
	<div><a href="/console/<?= $challenge['c_id'] ?>"><span class="label label-default"><?= $challenge['c_objective'] ?></span></a></div>
</div>

<?php
//This page creates new challenges and edits existing ones!
$is_new = (!isset($run));

if($is_new){
	?>
	<h1><?= $this->lang->line('new') ?> <?= $this->lang->line('r_name') ?></h1>
	<?php
} else {
	?>
	<h1><?= $this->lang->line('r_name') ?> #<?= $run['r_id'] ?> <?= $this->lang->line('r_s_name') ?></h1>
	<?php
}
?>


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




<div class="title"><h4>Enrollment Price <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="The amount the student should pay to enroll in this bootcamp."></i></h4></div>
<div class="col-sm-4">
	<div class="input-group input-mini">
		<span class="input-group-addon">
			USD $
		</span>
		<input type="number" min="0" step="1" class="form-control" placeholder="0.00" value="<?= $run['r_usd_price'] ?>" />
	</div>
</div>
            
            


<form>
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  <div class="form-group">
    <label for="exampleInputFile">File input</label>
    <input type="file" id="exampleInputFile">
    <p class="help-block">Example block-level help text here.</p>
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox"> Check me out
    </label>
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>



<div class="input-group">
  <span class="input-group-addon" id="basic-addon1">@</span>
  <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
</div>

<div class="input-group">
  <input type="text" class="form-control" placeholder="Recipient's username" aria-describedby="basic-addon2">
  <span class="input-group-addon" id="basic-addon2">@example.com</span>
</div>

<div class="input-group">
  <span class="input-group-addon">$</span>
  <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
  <span class="input-group-addon">.00</span>
</div>

<label for="basic-url">Your vanity URL</label>
<div class="input-group">
  <span class="input-group-addon" id="basic-addon3">https://example.com/users/</span>
  <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">
</div>