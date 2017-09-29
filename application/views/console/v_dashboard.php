<h1>Dashboard</h1>
<br />

<div class="dashboard">
    <div class="row">
      <div class="col-md-3"><b>Bootcamp Status</b></div>
      <div class="col-md-9"><?= status_bible('c',$bootcamp['c_status']) ?></div>
    </div>
    
    <?php 
    $outbound = $this->Db_model->cr_outbound_fetch(array(
        'cr.cr_inbound_id' => $bootcamp['c_id'],
        'cr.cr_status >=' => 0,
    ));
    ?>
    <div class="row">
      <div class="col-md-3"><b>Weekly Sprints</b></div>
      <div class="col-md-9"><a href="/console/<?= $bootcamp['c_id'] ?>/content"><?= count($outbound) ?></a></div>
    </div>
    
    <div class="row">
      <div class="col-md-3"><b>Total Cohorts</b></div>
      <div class="col-md-9"><a href="/console/<?= $bootcamp['c_id'] ?>/cohorts"><?= ( isset($bootcamp['runs']) ? count($bootcamp['runs']) : 0 )  ?></a></div>
    </div>
    
    <div class="row">
      <div class="col-md-3"><b>Next Cohort Start</b></div>
      <div class="col-md-9"><?= ( isset($bootcamp['runs'][0]['r_start_date']) ? time_format($bootcamp['runs'][0]['r_start_date'],1) : '---'  )  ?></div>
    </div>
    
    <div class="row">
      <div class="col-md-3"><b>Total Students</b></div>
      <div class="col-md-9"><a href="/console/<?= $bootcamp['c_id'] ?>/community">0</a></div>
    </div>
</div>
