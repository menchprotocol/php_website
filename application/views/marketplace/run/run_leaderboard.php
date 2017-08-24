<div class="mini-label">
	<div><a href="/marketplace/<?= $challenge['c_id'] ?>"><span class="label label-default"><?= $challenge['c_objective'] ?></span></a></div>
</div>

<h1>Run #<?= $run['r_version'] ?> <?= $this->lang->line('r_l_name') ?> <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="<?= $this->lang->line('r_l_desc') ?>"></i></h1>


<table class="table" style="margin-top:50px;">
	<thead>
		<tr>
			<th>Member</th>
			<th>Status</th>
			<th>Milestones</th>
			<th>Points</th>
		</tr>
	</thead>
	<?php /*
	<tbody>
		<?php 
		//Attempt to fetch session variables:
		$leaderboard = $this->Db_model->u_leaderboard(array(
			'r.r_id' => $run['r_version'],
		));
		foreach($leaderboard as $user){
			?>
			<tr>
			    <td><a href="/user/<?= $user['u_url_key']?>"><i class="fa fa-rocket"></i> <?= $c['c_objective']?></a></td>
			    <td class="span-activity">
			    	<span data-toggle="tooltip" title="Users"><i class="fa fa-user"></i> <?= $c['count_users']?></span>
			    	<span data-toggle="tooltip" title="Runs"><i class="fa fa-code-fork"></i> <?= $c['count_runs']?></span>
			    </td>
			    <td class="td-actions">
			        <a href="/marketplace/<?= $c['c_id']?>/library/<?= $c['c_id']?>" class="btn btn-primary" data-toggle="tooltip" title="Library"><i class="fa fa-book" aria-hidden="true"></i></a>
			        <a href="/marketplace/<?= $c['c_id']?>/editcopy" class="btn btn-primary"><i class="material-icons" data-toggle="tooltip" title="Settings">settings</i></a>
			    </td>
			</tr>
			<?php
		}
		?>
	</tbody>
	*/ ?>
</table>
<div class="alert alert-info" role="alert"><?= $this->lang->line('r_l_none') ?></div>
