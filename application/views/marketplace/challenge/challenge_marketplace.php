<h1>Challenge Marketplace</h1>
<p>List of all challenges being developed by our community of leader entrepenuers.</p>
<a href="/marketplace/new" type="submit" class="btn btn-primary btn-raised btn-round"><i class="fa fa-plus"></i> NEW CHALLENGE</a>


<table class="table" style="margin-top:75px;">
	<thead>
		<tr>
			<th>Challenge</th>
			<th>Stage</th>
			<th>Activity</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		//Attempt to fetch session variables:
		$all_c = $this->Db_model->c_fetch(array(
				'c.c_status >=' => 1,
				/*
				'r.r_status >=' => 1,
				'ru.ru_status >=' => 0,
				*/
		));
		foreach($all_c as $c){
			?>
			<tr>
			    <td><a href="/marketplace/<?= $c['c_url_key']?>"><i class="fa fa-rocket"></i> <?= $c['c_objective']?></a></td>
			    <td><?= status_bible('c',$c['c_status']); ?></td>
			    <td class="span-activity">
			    	<span data-toggle="tooltip" title="Users"><i class="fa fa-user"></i> <?= $c['count_users']?></span>
			    	<span data-toggle="tooltip" title="Runs"><i class="fa fa-code-fork"></i> <?= $c['count_runs']?></span>
			    </td>
			    <td class="td-actions">
			        <a href="/marketplace/<?= $c['c_url_key']?>/library/<?= $c['c_id']?>" class="btn btn-primary" data-toggle="tooltip" title="Library"><i class="fa fa-book" aria-hidden="true"></i></a>
			        <a href="/marketplace/<?= $c['c_url_key']?>/editcopy" class="btn btn-primary"><i class="material-icons" data-toggle="tooltip" title="Settings">settings</i></a>
			    </td>
			</tr>
			<?php
		}
		
		?>
	</tbody>
</table>


