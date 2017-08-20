<div style="display:block; margin-bottom:-20px;margin-top:10px;"><span class="label label-default" style="background-color:#2f2639;">THE &nbsp;CHALLENGE &nbsp;IS &nbsp;TO . . .</span></div>
<h1><?= $challenge['c_objective'] ?></h1>
<p class="showdown"><?= $challenge['c_description'] ?></p>

<br />
<hr />
<h2>Runs</h2>
<p>Challenges are operated under "runs", which represent an instance of each challenge.</p>
<?php
if(count($challenge['runs'])>0){
	
	//Print List:
	echo '<div class="list-group">';
		foreach($challenge['runs'] as $run){
			echo '<a href="/marketplace/'.$challenge['c_url_key'].'/'.$run['r_version'].'" class="list-group-item"><span class="label label-primary pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span><i class="fa fa-code-fork" style="margin:0 5px 0 0;"></i><span style="padding:0 30px 0 0;">RUN #'.$run['r_version'].'</span>'.status_bible('r',$run['r_status']).'</a>';
		}
		if(1){
			//TODO Has edit authority?
			echo '<a href="/marketplace/'.$challenge['c_url_key'].'/new" class="list-group-item"><span class="label label-primary pull-right"><i class="fa fa-plus" aria-hidden="true"></i></span><i class="fa fa-code-fork" style="margin:0 5px 0 0;"></i>Create RUN</a>';
		}
	echo '</div>';
	
} else {	
	//Notify that there are no runs!
	echo '<div class="alert alert-warning" role="alert"> No Runs created yet!</div>';
	if(1){
		//TODO Has edit authority?
		echo '<div class="list-group">';
			echo html_new_run();
		echo '</div>';
	}
}
?>