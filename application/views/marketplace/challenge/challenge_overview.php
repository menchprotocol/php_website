<div style="display:block; margin-bottom:-20px;margin-top:10px;"><span class="label label-default" style="background-color:#2f2639;">THE &nbsp;CHALLENGE</span></div>
<h1><?= $challenge['c_objective'] ?></h1>


<h2 style="margin-top:55px;"><span data-toggle="tooltip" title="An overview of the challenge.">Overview</span> <?= ( 1 ? '<a href="/marketplace/'.$challenge['c_url_key'].'/edit" type="submit" class="btn btn-primary btn-raised btn-round"><i class="fa fa-pencil"></i> EDIT</a>' : '' ) ?></h2>
<p class="showdown"><?= $challenge['c_description'] ?></p>



<h2 style="margin-top:55px;"><span data-toggle="tooltip" title="What will be conducted.">Syllabus</span> <?= ( 1 ? '<a href="/marketplace/'.$challenge['c_url_key'].'/new" type="submit" class="btn btn-primary btn-raised btn-round"><i class="fa fa-plus"></i> NEW</a>' : '' ) ?></h2>
<?php
echo '<div class="list-group">';
	echo '<a href="/" class="list-group-item"><span class="label label-primary pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span><i class="fa fa-code-fork" style="margin:0 5px 0 0;"></i><span style="padding:0 30px 0 0;">RECORD Online Course</span></a>';
	echo '<a href="/" class="list-group-item"><span class="label label-primary pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span><i class="fa fa-code-fork" style="margin:0 5px 0 0;"></i><span style="padding:0 30px 0 0;">LAUNCH Online Course</span></a>';
	echo '<a href="/" class="list-group-item"><span class="label label-primary pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span><i class="fa fa-code-fork" style="margin:0 5px 0 0;"></i><span style="padding:0 30px 0 0;">Create SALES FUNNEL</span></a>';
echo '</div>';
?>


<h2 style="margin-top:55px;"><span data-toggle="tooltip" title="What will be conducted.">Runs</span> <?= ( 1 ? '<a href="/marketplace/'.$challenge['c_url_key'].'/new" type="submit" class="btn btn-primary btn-raised btn-round"><i class="fa fa-plus"></i> NEW</a>' : '' ) ?></h2>
<?php
if(count($challenge['runs'])>0){
	
	//Print List:
	echo '<div class="list-group">';
		foreach($challenge['runs'] as $run){
			echo '<a href="/marketplace/'.$challenge['c_url_key'].'/'.$run['r_version'].'" class="list-group-item"><span class="label label-primary pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span><i class="fa fa-code-fork" style="margin:0 5px 0 0;"></i><span style="padding:0 30px 0 0;">RUN #'.$run['r_version'].'</span>'.status_bible('r',$run['r_status']).'</a>';
		}
	echo '</div>';
	
} else {	
	//Notify that there are no runs!
	echo '<div class="alert alert-warning" role="alert"> No Runs created yet!</div>';
}
?>