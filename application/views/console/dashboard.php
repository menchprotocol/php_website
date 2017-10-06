<div class="dashboard">
    <div class="row">
      <div class="col-md-3"><a href="/console/<?= $bootcamp['c_id'] ?>/settings"><b>Bootcamp Status <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-md-9"><?= status_bible('c',$bootcamp['c_status']) ?></div>
    </div>
    <div class="row">
      <div class="col-md-3"><a href="/console/<?= $bootcamp['c_id'] ?>/curriculum"><b>Curriculum <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-md-9"><?= count($bootcamp['c__sprints']) ?> Weeks + <?= $bootcamp['c__task_count'] ?> Tasks<?= ( count($bootcamp['c__sprints'])>0 ? '<br />'.round($bootcamp['c__estimated_hours'],1).' Total Hours<br />'.round($bootcamp['c__estimated_hours']/count($bootcamp['c__sprints'])).' Hours/Week' : '' ) ?></div>
    </div>
    
    <div class="row">
      <div class="col-md-3"><a href="/console/<?= $bootcamp['c_id'] ?>/cohorts"><b>Cohorts <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-md-9"><?= ( isset($bootcamp['c__cohorts']) ? count($bootcamp['c__cohorts']).'<br />Next starts '.time_format($bootcamp['c__cohorts'][0]['r_start_date'],1) : 0 )  ?></div>
    </div>
    
    <div class="row">
      <div class="col-md-3"><a href="/console/<?= $bootcamp['c_id'] ?>/students"><b>Students <i class="fa fa-angle-right" aria-hidden="true"></i></b></a></div>
      <div class="col-md-9">0</div>
    </div>
</div>
