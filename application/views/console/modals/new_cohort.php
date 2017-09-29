<!-- Modal Core -->
<div class="modal fade" id="newCohortModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3 class="modal-title">New Cohort</h3>
      </div>
      <div class="modal-body">
      		
      		<div class="alert alert-info" role="alert">It's best to define <a href="/console/<?= $bootcamp['c_id'] ?>/content">weekly sprints</a> before creating a cohort as this gives you a better idea of how much time students should commit to, and how much to charge.</div>
      		
      		<input type="hidden" id="r_c_id" value="<?= $bootcamp['c_id'] ?>" />
      		
      		<div class="title"><h4>Student Commitment Level</h4></div>
        	<select id="r_pace_id" style="margin-top:9px;">
            	<?php
            	$r_pace_options = $this->config->item('r_pace_options');
            	foreach($r_pace_options as $pace_id=>$pace){
            	    if($pace_id<1){
            	        continue;
            	    }
            	    echo '<option value="'.$pace_id.'">'.$pace['p_name'].': '.$pace['p_hours'].'</option>';
            	}
            	?>
            </select><br /><br />
            
            
        	<div class="title"><h4>Starting Week (Monday)</h4></div>
			<div class="form-group label-floating is-empty">
			    <input type="text" id="r_start_date" style="width:233px;" class="form-control" />
			    <span class="material-input"></span>
			</div>
			
			<div class="title"><h4>Ending Week (Sunday)</h4></div>
			<?php 
            $outbound = $this->Db_model->cr_outbound_fetch(array(
                'cr.cr_inbound_id' => $bootcamp['c_id'],
                'cr.cr_status >=' => 0,
            ));
            echo '<p style="padding-left:25px;"><b>'.count($outbound).' Weeks Later</b> based on <a href="/console/'.$bootcamp['c_id'].'/content">weekly sprints</a>.<br />You can modify sprints before publishing this cohort.</p>';
            ?>
            
            
			<div class="title"><h4>Enrollment Price</h4></div>
        	<div class="input-group">
              <span class="input-group-addon addon-lean">USD $</span>
              <input type="number" min="0" step="0.01" style="width:100px; margin-bottom:-5px;" id="r_usd_price" class="form-control" />
            </div>
            
            
			
			<div id="new_cohort_result"></div>
      </div>
      <div class="modal-footer">
        <a href="javascript:r_process_create()" type="button" class="btn btn-primary">Create</a>
      </div>
    </div>
  </div>
</div>

<script>
$('#r_start_date, #r_usd_price, #r_pace_id').bind("enterKey",function(e){
	r_process_create();
});
$('#r_start_date, #r_usd_price, #r_pace_id').keyup(function(e){
    if(e.keyCode == 13)
    {
    	r_process_create();
    }
});
</script>
