<div class="dashboard">
    <div class="row">
      <div class="col-md-3"><a href="/console/<?= $bootcamp['c_id'] ?>/settings"><b>Bootcamp Status <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-md-9"><?= status_bible('c',$bootcamp['c_status']) ?></div>
    </div>
    <div class="row">
      <div class="col-md-3"><a href="/console/<?= $bootcamp['c_id'] ?>/curriculum"><b>Curriculum <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-md-9">
      	<div><?= count($bootcamp['c__sprints']) ?> Weeks</div>
      	<div><?= $bootcamp['c__task_count'] ?> Tasks</div>
      	<?= ( count($bootcamp['c__sprints'])>0 ? '<div>'.round($bootcamp['c__estimated_hours'],1).' Hours</div><div>'.round($bootcamp['c__estimated_hours']/count($bootcamp['c__sprints'])).' Hours/Week</div>' : '' ) ?>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-3"><a href="/console/<?= $bootcamp['c_id'] ?>/cohorts"><b>Cohorts <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-md-9"><?= ( isset($bootcamp['c__cohorts']) ? count($bootcamp['c__cohorts']).'<br />'.time_format($bootcamp['c__cohorts'][0]['r_start_date'],1).' is next' : 0 )  ?></div>
    </div>
    
    <div class="row">
      <div class="col-md-3"><a href="/console/<?= $bootcamp['c_id'] ?>/students"><b>Students <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-md-9">0</div>
    </div>
</div>
