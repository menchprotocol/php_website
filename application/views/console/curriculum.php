<?php
if(!isset($level)){
    die('$level not set.');
}

$level_names = $this->config->item('level_names');
?>

<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />
<input type="hidden" id="c_id" value="<?= $bootcamp['c_id'] ?>" />
<input type="hidden" id="pid" value="<?= $intent['c_id'] ?>" />
<input type="hidden" id="next_level" value="<?= $level+1 ?>" />


<div class="row">
    <div class="col-md-6">
    
    	
    	<div id="acordeon">
            <div class="panel-group" id="accordion">
            
          
              <div class="panel panel-border panel-default" name="collapsePrimaryObjective">
                <div class="panel-heading" role="tab">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePrimaryObjective" aria-expanded="false" aria-controls="collapsePrimaryObjective">
                        <h4 class="panel-title">
                        Objective
                        <i class="material-icons">keyboard_arrow_down</i>
                        </h4>
                    </a>
                </div>
                <div id="collapsePrimaryObjective" class="panel-collapse collapse"> <!-- collapse in -->
                  <div class="panel-body">
                  
                    	<p>The objective is also the title of this <?= $level_names[$level] ?> that must be defined as a S.M.A.R.T. goal: Specific, Measurable, Achievable, Relevant & Trackable.</p>
                        <div class="form-group label-floating is-empty">
                            <input type="text" id="c_objective" value="<?= $intent['c_objective'] ?>" class="form-control">
							
							<?php if($level==1){ ?>
							<div class="alert alert-warning" role="alert"><div><b>REMINDER:</b></div>The primary bootcamp objective sets the guideline for the Tuition Reimbursement Guarantee included in all Mench bootcamps. It basically means that if students execute the entire curriculum and fail to achieve this primary objective, they would get their tuition fully reimbursed.</div>                            
							<?php } ?>
							
                            <?php if($level>1 && 0){ ?>
                                <p class="extra-info"><span data-toggle="tooltip" title="First word replacements would replace matched words only if placed as the very first word to enhance & simplify the title."><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> First word replacements:
                                <?php
                                $title_replacements = $this->config->item('title_replacements');
                                $count = 0;
                                foreach($title_replacements['prepend'] as $key=>$value){
                                    $count++;
                                    if($count>1){
                                        echo ' , ';
                                    }
                                    echo '['.$key.'] = ['.$value.']';
                                }
                                ?></span></p>
                            <?php } ?>
                        </div>
                        
                  </div>
                </div>
              </div>
              
              
              
              
              <div class="panel panel-border panel-default" name="collapseOverview">
                <div class="panel-heading" role="tab">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOverview" aria-expanded="false" aria-controls="collapseOverview">
                        <h4 class="panel-title">
                        Overview <?= (strlen($intent['c_todo_overview'])>0 ? '<i class="fa fa-search title-sub" aria-hidden="true" data-toggle="tooltip" title="Has Overview"></i>' : '') ?>
                        <i class="material-icons">keyboard_arrow_down</i>
                        </h4>
                    </a>
                </div>
                <div id="collapseOverview" class="panel-collapse collapse"> <!-- collapse in -->
                  <div class="panel-body">
                    	<p>An overview of operations and what to expect. <a href="/console/help/showdown_markup" target="_blank">Markup Supported <i class="fa fa-info-circle"></i></a></p>
                        <div class="form-group label-floating is-empty">
                            <textarea class="form-control text-edit" rows="2" id="c_todo_overview"><?= $intent['c_todo_overview'] ?></textarea>
                            <span class="material-input"></span>
                        </div>
                  </div>
                </div>
              </div>
              
              
              
              
              
              
              <div class="panel panel-border panel-default" name="collapsePrerequisites">
                <div class="panel-heading" role="tab">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePrerequisites" aria-controls="collapsePrerequisites">
                        <h4 class="panel-title">
                        Requirements <?= (strlen($intent['c_prerequisites'])>0 ? '<i class="fa fa-exclamation-circle title-sub" aria-hidden="true" data-toggle="tooltip" title="Has Requirements"></i>' : '') ?>
                        <i class="material-icons">keyboard_arrow_down</i>
                        </h4>
                    </a>
                </div>
                <div id="collapsePrerequisites" class="panel-collapse collapse">
                  <div class="panel-body">
                    	<p>An optional list of requirements students must meet to achieve the primary objective. <a href="/console/help/showdown_markup" target="_blank">Markup Supported <i class="fa fa-info-circle"></i></a></p>
                        <div class="form-group label-floating is-empty">
                            <textarea class="form-control text-edit" rows="2" id="c_prerequisites"><?= $intent['c_prerequisites'] ?></textarea>
                            <span class="material-input"></span>
                        </div>
                        <?php if($level>1){ ?>
							<div class="alert alert-warning" role="alert"><div><b>WARNING:</b></div>Students cannot see <?= strtolower($level_names[$level]) ?> requirements until after they have enrolled. So if you are adding any critical requirements that need students attention, make sure to also specify them in the <a href="/console/<?= $bootcamp['b_id'] ?>/curriculum">curriculum requirements</a> section so students can make an informed decision when considering enrollment.</div>                            
						<?php } ?>
                  </div>
                </div>
              </div>
            
              
              
              
          
          <div class="panel panel-border panel-default" name="collapseOutline">
            <div class="panel-heading" role="tab">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseExecHandout" aria-controls="collapseExecHandout">
                    <h4 class="panel-title">
                    Action Plan with Time Estimate
                    <?= (strlen($intent['c_todo_bible'])>0 ? '<i class="fa fa-wrench title-sub" aria-hidden="true" data-toggle="tooltip" title="Has Action Plan"></i>' : '') ?>
              		<?= ($intent['c_time_estimate']>0 ? echo_time($intent['c_time_estimate']) : '') ?>
                    <i class="material-icons">keyboard_arrow_down</i>
                    </h4>
                </a>
            </div>
            <div id="collapseExecHandout" class="panel-collapse collapse">
              <div class="panel-body">
        				
        			<div class="title"><h4>Action Plan</h4></div>
                	<p>Unlike the overview, the action plan contains detailed instructions for execution. The action plan is kept private until the week of execution, when we share it with students so they know what exactly they need to do. <a href="/console/help/showdown_markup" target="_blank">Markup Supported <i class="fa fa-info-circle"></i></a></p>
                    <div class="form-group label-floating is-empty">
                        <textarea class="form-control text-edit" rows="2" placeholder="Execution tips, media references, examples, etc..." id="c_todo_bible"><?= $intent['c_todo_bible'] ?></textarea>
                        <span class="material-input"></span>
                    </div>
                    
                    <div class="title"><h4>Estimated Time</h4></div>
                    <p>An estimat of how long it takes to review and execute the action plan. If you estimate more than 13 hours of work, then break this down into smaller sprints/tasks.</p>
                    <select class="form-control input-mini" id="c_time_estimate">
                    	<?php 
                    	$times = $this->config->item('c_time_options');
                    	foreach($times as $time){
                    	    echo '<option value="'.$time.'" '.( $intent['c_time_estimate']==$time ? 'selected="selected"' : '' ).'>~'.echo_hours($time).'</option>';
                    	}
                    	?>
                    </select>
              </div>
            </div>
          </div>
          
          
       
          
          
        </div>
        </div><!--  end acordeon -->
        
        
        
        
        
        
        <table width="100%"><tr><td class="save-td"><a href="javascript:save_c();" class="btn btn-primary">Save</a></td><td><span id="save_c_results"></span></td></tr></table>
        
        
        
        
        
        
       
        
        
    </div>
    <div class="col-md-6">
    
    
    	<?php if($level<3){ ?>
    		<div class="panel panel-border panel-default" name="collapseWeeklySprints">
                <div class="panel-heading" role="tab">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseWeeklySprints" aria-expanded="true" aria-controls="collapseWeeklySprints">
                        <h4 class="panel-title">
                        <?= ($level==1 ? 'Weekly Sprints' : 'Week Tasks' ) ?>
                        <i class="material-icons">keyboard_arrow_down</i>
                        </h4>
                    </a>
                </div>
                <div id="collapseWeeklySprints" class="panel-collapse collapse in">
                  <div class="panel-body">
                  
                    	<?= '<p>'.($level==1 ? 'Add weekly sprint by defining a SMART objective for each week:' : 'Define tasks that contribute to accomplishing the primary objective of this '.$level_names[$level].':'); ?>
                    	<?php
                    	echo '<div id="list-outbound" class="list-group">';
                    	foreach($intent['c__child_intents'] as $sub_intent){
                    	    echo echo_cr($bootcamp['b_id'],$sub_intent,'outbound',($level+1));
                    	}
                		echo '</div>';
                		?>
                		
                		<div id="list-outbound" class="list-group">
                    		<div class="list-group-item list_input">
                				<div class="input-group">
                					<div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control autosearch" id="addnode" placeholder="+ Add <?= ($level==1 ? 'Sprint' : 'Objective') ?>"></div>
                					<span class="input-group-addon" style="padding-right:0;">
                						<span id="dir_handle" class="label label-primary pull-right" style="cursor:pointer;" onclick="new_intent($('#addnode').val());">
                							<div><span id="dir_name" class="dir-sign">OUTBOUND</span> <i class="fa fa-plus"></i></div>
                							<div class="togglebutton" style="margin-top:5px; display:none;">
                				            	<label>
                				                	<input type="checkbox" onclick="change_direction()" />
                				            	</label>
                		            		</div>
                						</span>
                					</span>
                				</div>
            				</div>
        				</div>
                  </div>
                </div>
           </div>
		<?php } ?>
        
        
        
    </div>
</div>



<?php
/*
 * i Messaging features / Disabled for now...
 * Note: Model calls are commented out form Controller as well
 * 
echo '<p>'.$this->lang->line('i_desc').'</p>';
echo '<div id="message-sorting" class="list-group list-messages" style="margin-bottom:0;">';
	foreach($i_messages as $i){
		echo_message($i);
	}
echo '</div>';


//TODO do_edits
echo '<div class="list-group list-messages">';
	echo '<div class="list-group-item">';
		echo '<div class="add-msg">';
		echo '<textarea id="i_message" placeholder="+ Add Media"></textarea>';
		echo '<ul class="msg-nav">';
			echo '<li><a href="javascript:msg_create();" data-toggle="tooltip" title="Ctrl + Enter ;)"><i class="fa fa-plus"></i> Add</a></li>';
			echo '<li class="pull-right"><a href="/console/help/showdown_markup" target="_blank"><i class="fa fa-info-circle"></i> Markup Support</a></li>';
		echo '</ul>';
		echo '</div>';
	echo '</div>';
echo '</div>';
*/
?>


