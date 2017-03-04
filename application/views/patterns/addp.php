<?php 
$pattern_name = NULL;
if(isset($pattern['hashtag'])){
	$pattern_name = $pattern['hashtag'];
} elseif(isset($_GET['pattern_name'])){
	$pattern_name = $_GET['pattern_name'];
}

$parent_id = NULL;
if(isset($pattern['parent_id'])){
	$parent_id = $pattern['parent_id'];
} elseif(isset($_GET['parent_id'])){
	$parent_id = $_GET['parent_id'];
}

//Load more variables:
$metadata_types = metadata_types();
?>

<div class="row mborc" style="padding-bottom:5px;">
	<div class="col-md-12">
		<h2 style="padding-bottom:5px; text-align:center;">
			<?php
			if(isset($editing_mode)){
				echo '<a href="/patterns/v/'.$pattern['id'].'">'.$pattern_name.'</a> &raquo; Edit Pattern';
			} else {
				echo 'New Pattern';
			}
			?>
		</h2>
		
		<div id="nav_reports">
			<div class="alert alert-info alert-data" role="alert">
				<form id="inputForm" action="/patterns_api/pattern<?= (isset($pattern['id']) && intval($pattern['id'])>0 ? '/edit/'.intval($pattern['id']) : '/add') ?>" method="POST">
					
					<div class="row">
						<div class="col-sm-3"><b>Pattern <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span></b></div>
						<div class="col-sm-9"><input type="text" class="form-control" id="p_pattern_name" maxlength="500" <?= ( !isset($pattern_name) ? 'autofocus' : '' ) ?> value="<?= ( isset($pattern_name) ? text_cleanup_inverse(htmlspecialchars($pattern_name)) : NULL ) ?>" required="required" placeholder="" name="hashtag"/></div>
					</div>
					
					
					<?php 
					if(isset($pattern['id']) && $pattern['id']==332){
						?> <input type="hidden" name="parent_id" value="0" /> <?php
					} else {
						?>
						<div class="row">
							<div class="col-sm-3"><b>Parent ID <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span></b></div>
							<div class="col-sm-9"><input type="text" class="form-control autocomplete_pattern" value="<?= ( $parent_id ? $parent_id : NULL ) ?>" name="parent_id" required="required" placeholder="Type to search" /></div>
						</div>
						<?php
					}
					?>
					
					<div class="row">
						<div class="col-sm-3"><b>Description</b></div>
						<div class="col-sm-9"><textarea class="form-control" id="p_description" name="description" style="height:70px;" <?= ( isset($pattern_name) && !isset($pattern['description']) ? 'autofocus' : '' ) ?>><?= ( isset($pattern['description']) ? text_cleanup_inverse($pattern['description']) : '' ) ?></textarea></div>
					</div>
					
					<div class="row">
						<div class="col-sm-3"><b>Learn More URL</b></div>
						<div class="col-sm-9"><input type="text" class="form-control" id="p_url" maxlength="1500" value="<?= ( isset($pattern['url']) ? htmlspecialchars($pattern['url']) : NULL ) ?>" placeholder="" name="url" /></div>
					</div>
					
					<div class="row">
						<div class="col-sm-3"><b>Report Fields</b></div>
						<div class="col-sm-9">
						  <select name="report_template_id" id="report_template_id" class="form-control">
					        <option value="0">Select template...</option>
					        <?php print_select_pattern(298,0,( ( $pattern['template_pattern_id']>0 ? $pattern['template_pattern_id'] : 0 ) ) ); ?>
					      </select>
					      <?php
					      if(intval($pattern['template_pattern_id'])<1){ ?>
						      <span> OR </span>
							  <div class="btn-group" role="group">
							    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							      <span class="glyphicon glyphicon-plus" aria-hidden="true" style="color:#333;"></span> Add
							      <span class="caret"></span>
							    </button>
							    <ul class="dropdown-menu">
							    	<?php
									foreach ($metadata_types as $key => $value) {
										echo '<li><a href="javascript:add_meta_data('.$key.',\''.htmlentities($value['icon'].' '.$value['name']).'\',null)">'.$value['icon'].' '.$value['name'].'</a></li>';
									}
							    	?>
							    </ul>
							  </div>
							  <?php
							  if(isset($editing_mode)) {
							  	//See if there are any reporting fields at the moment:
							  	$current_fields = $this->Patterns_model->fetch_pattern_metadata($pattern['id'],ses_user(),true);
							  	foreach($current_fields as $md){
							  		echo '<script> 	$(document).ready(function() { add_meta_data('.$md['type_id'].',\''.$metadata_types[$md['type_id']]['icon'].' '.$metadata_types[$md['type_id']]['name'].'\' , '.json_encode($md).'); }); </script>';
							  	}
							  }
					      } else {
					      	//Give admin option to edit the parent template
					      	echo '<span><a href="/patterns/v/'.$pattern['template_pattern_id'].'/edit">Edit Template</a></span>';
					      }
						  ?>
						  <div id="meta"><ul id="meta_list"></ul></div>
						</div>
					</div>
					
					
					<div class="row">
						<div class="col-sm-3"><b>Callback URL <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Passon pattern report data as JSON variables for further automation."></span></b></div>
						<div class="col-sm-9"><input type="text" class="form-control" value="<?= ( isset($pattern['callback_url']) ? $pattern['callback_url'] : '' ) ?>" placeholder="" name="callback_url"/></div>
					</div>
					
					<div class="row">
						<div class="col-sm-3"><b>List sub patterns</b></div>
						<div class="col-sm-9">
							<div class="radio">
							  <label>
							    <input type="radio" name="show_child_descriptions" value="0" <?= ( $pattern['show_child_descriptions']=='f' || !$editing_mode ? 'checked="checked"' : '' ) ?> >
							    Name
							  </label>
							</div>
							<div class="radio">
							  <label>
							    <input type="radio" name="show_child_descriptions" value="1" <?= ( $pattern['show_child_descriptions']=='t' ? 'checked="checked"' : '' ) ?> >
							    Name and description
							  </label>
							</div>
						</div>
					</div>
					
					
					<div class="row">
						<div class="col-sm-3"><b>Sort sub patterns</b></div>
						<div class="col-sm-9">
							<div class="radio">
							  <label>
							    <input type="radio" name="rank_sort" value="0" <?= ( $pattern['rank_sort']=='f' || !$editing_mode ? 'checked="checked"' : '' ) ?> >
							    Report count <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Child patterns with most reports listed higher"></span>
							  </label>
							</div>
							
							<div class="radio">
							  <label>
							    <input type="radio" name="rank_sort" value="1" <?= ( $pattern['rank_sort']=='t' ? 'checked="checked"' : '' ) ?> >
							    Manual <span class="glyphicon glyphicon-info-sign" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Manual sorting for child patterns"></span>
							  </label>
							</div>
						</div>
					</div>
					
					<button type="submit" class="btn btn-lg btn-primary submit-process"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></span> Save</button>
					<?php
					if(isset($editing_mode)){
						echo '<div class="delete_div"><a href="javascript:delete_pattern('.$pattern['id'].')"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span> Delete</a></div>';
					}
					?>
				</form>
			</div>
		</div>
	</div>
</div>


<script src="/js/pattern/Sortable.js"></script>
<script type="text/javascript">
var list = document.getElementById("meta_list");
var sortable_active = 1;
Sortable.create(list , {
  animation: 300,
  handle: ".sort_handle",
});
</script>
