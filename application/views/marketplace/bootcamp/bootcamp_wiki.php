<?php

$can_modify = can_modify('c',$bootcamp['c_id']);

/* if($pid!=$bootcamp['c_id']){ ?>
<div class="mini-label">
	<div><a href="/marketplace/<?= $bootcamp['c_id'] ?>"><span class="label label-default"><?= $bootcamp['c_objective'] ?></span></a></div>
</div>
<?php } */ ?>
<?php 
echo '<div id="list-inbound" class="list-group" style="margin-top:10px;">';
if(isset($cr['inbound']) && count($cr['inbound'])>0){
	foreach($cr['inbound'] as $relation){
		echo echo_cr($this->uri->segment(2, 0),$relation,'inbound');
	}
}
echo '</div>';

if(!($cr['c']['c_id']==$bootcamp['c_id'])){
    echo '<h1 class="c_objective_body">'.echo_title($cr['c']['c_objective']).'</h1>';
}
?>

<ul class="nav nav-pills nav-pills-primary" style="margin-top:10px;">
  <li class="active"><a href="#pill1" data-toggle="tab"><i class="fa fa-link" aria-hidden="true"></i> Links</a></li>
  <li><a href="#pill2" data-toggle="tab" onclick="load_message_sorting()"><?= $this->lang->line('i_icon') ?> <?= $this->lang->line('i_pname') ?></a></li>
  <li><a href="#pill4" data-toggle="tab"><i class="fa fa-pencil" aria-hidden="true"></i> <?= $this->lang->line('edit').' '.str_replace('data-toggle="tooltip"','',status_bible('c',$cr['c']['c_status'],1)) ?></a></li>
</ul>




<div class="tab-content tab-space">
    <div class="tab-pane active" id="pill1">
	    <p><?= $this->lang->line('cr_desc') ?></p>
		<?php
		//OUT
		echo '<div id="list-outbound" class="list-group">';
			if(isset($cr['outbound']) && count($cr['outbound'])>0){
				foreach($cr['outbound'] as $relation){
					echo echo_cr($this->uri->segment(2, 0),$relation,'outbound');
				}
			}
		echo '</div>';
		
		//Can they modify?
		if($can_modify){
			echo '<div class="list-group">';
			echo '<div class="list-group-item list_input">';
			?>
				<div class="input-group">
					<div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control autosearch" id="addnode" placeholder="+ Bootcamp"></div>
					<span class="input-group-addon">
						<span id="dir_handle" class="label label-primary pull-right" style="cursor:pointer;" onclick="new_challenge($('#addnode').val());">
							<div><span id="dir_name" class="dir-sign">OUTBOUND</span> <i class="fa fa-plus"></i></div>
							<div class="togglebutton" style="margin-top:5px; display:none;">
				            	<label>
				                	<input type="checkbox" onclick="change_direction()" />
				            	</label>
		            		</div>
						</span>
					</span>
				</div>
				<?php 
			echo '</div>';
			echo '</div>';
		}
		
		?>
    </div>
    <div class="tab-pane" id="pill2">
    	<p><?= $this->lang->line('i_desc') ?></p>
    	<?php
    	echo '<div id="message-sorting" class="list-group list-messages" style="margin-bottom:0;">';
	    	foreach($i_messages as $i){
	    		echo_message($i);
	    	}
    	echo '</div>';
    	
    	
    	if($can_modify){
    		echo '<div class="list-group list-messages">';
	    		echo '<div class="list-group-item">';
		    		echo '<div class="add-msg">';
		    		echo '<textarea id="i_message" placeholder="+ Add Media"></textarea>';
		    		echo '<ul class="msg-nav">';
		    			echo '<li><a href="javascript:msg_create();" data-toggle="tooltip" title="Ctrl + Enter ;)"><i class="fa fa-plus"></i> Add</a></li>';
		    			echo '<li class="pull-right"><a href="/guides/showdown_markup" target="_blank"><i class="fa fa-info-circle"></i> Markup Support</a></li>';
		    		echo '</ul>';
		    		echo '</div>';
	    		echo '</div>';
    		echo '</div>';
    	}
		?>
    </div>
	<div class="tab-pane" id="pill3">
		<?php
		if(count($bootcamp['runs'])>0){
			echo '<div class="list-group">';
				foreach($bootcamp['runs'] as $run){
					echo '<a href="/marketplace/'.$bootcamp['c_id'].'/cohorts/'.$run['r_id'].'" class="list-group-item"><span class="label label-primary pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span><i class="fa fa-calendar" aria-hidden="true"></i> '.( time_ispast($run['r_start_time']) ? $this->lang->line('started') : $this->lang->line('starts')).' '.time_format($run['r_start_time']).' '.status_bible('r',$run['r_status']).'</a>';
				}
			echo '</div>';
			
		} else {
			//Notify that there are no runs!
			echo '<div class="alert alert-warning" role="alert">'.$this->lang->line('r_none_message').'</div>';
		}
		?>
		<?= ( $can_modify ? '<a href="/marketplace/'.$bootcamp['c_id'].'/cohort/new" type="submit" class="btn btn-primary btn-raised btn-round"><i class="fa fa-plus"></i> '.$this->lang->line('new').'</a>' : '' ) ?>
    </div>
    
    
    <div class="tab-pane" id="pill4">
    
    		<input type="hidden" id="save_c_id" value="<?= $cr['c']['c_id'] ?>" />
    		
    		<div class="title"><h4>Bootcamp's Primary Goal <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="The primary objective to be accomplished by executing this bootcamp. This should be measurable, attainable, relevant, rewarding & trackable."></i></h4></div>
			<div class="form-group label-floating is-empty">
			    <input type="text" id="save_c_objective" value="<?= $cr['c']['c_objective'] ?>" class="form-control">
			    <span class="material-input"></span>
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
			</div>
			
			
			<div class="title"><h4>Additional Goals <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="A list of what the student should expect to accomplish on top of the primary goal if they execute on this bootcamp. Listed goals should be measurable, attainable, relevant, rewarding & trackable."></i> <span style="font-size:0.6em; color:#AAA;">(<a href="/guides/showdown_markup" target="_blank">Markup Support <i class="fa fa-info-circle"></i></a>)</span></h4></div>
			<div class="form-group label-floating is-empty">
			    <textarea class="form-control text-edit" rows="2" id="save_c_additional_goals"><?= $cr['c']['c_additional_goals'] ?></textarea>
			    <span class="material-input"></span>
			</div>
			
			
			<div class="title"><h4>Prerequisites <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="A list of what is required from the students *before* starting to execute on this bootcamp."></i> <span style="font-size:0.6em; color:#AAA;">(<a href="/guides/showdown_markup" target="_blank">Markup Support <i class="fa fa-info-circle"></i></a>)</span></h4></div>
			<div class="form-group label-floating is-empty">
			    <textarea class="form-control text-edit" rows="2" id="save_c_prerequisites"><?= $cr['c']['c_prerequisites'] ?></textarea>
			    <span class="material-input"></span>
			</div>
			
			
			<div class="title"><h4>Todo Overview <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="An overview (Not detail) of what the students should do to accomplish the guaranteed outcome. Meetings, projects, etc..."></i> <span style="font-size:0.6em; color:#AAA;">(<a href="/guides/showdown_markup" target="_blank">Markup Support <i class="fa fa-info-circle"></i></a>)</span></h4></div>
			<div class="form-group label-floating is-empty">
			    <textarea class="form-control text-edit" rows="2" id="save_c_todo_overview"><?= $cr['c']['c_todo_overview'] ?></textarea>
			    <span class="material-input"></span>
			</div>
			
			
			<div class="title"><h4>Todo Detailed Instructions <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="A list of detailed instructions on what the student should exactly do to accomplish this bootcamp. It would be shared with them on the week of this bootcamp"></i> <span style="font-size:0.6em; color:#AAA;">(<a href="/guides/showdown_markup" target="_blank">Markup Support <i class="fa fa-info-circle"></i></a>)</span></h4></div>
			<div class="form-group label-floating is-empty">
			    <textarea class="form-control text-edit" rows="2" id="save_c_todo_bible"><?= $cr['c']['c_todo_bible'] ?></textarea>
			    <span class="material-input"></span>
			</div>
			
			
			
			<div class="title"><h4>Chat Trigger Statements <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Enter 1 statement per line, that represent what the user might say via chat to trigger this bootcamp. We use natural language processing (NLP) to extrapolate more statements so we can automatically detect when the user is requesting more information on this bootcamp."></i></h4></div>
			<div class="form-group label-floating is-empty">
			    <textarea class="form-control text-edit" rows="2" id="save_c_user_says_statements"><?= $cr['c']['c_user_says_statements'] ?></textarea>
			    <span class="material-input"></span>
			</div>
            
            
            
             <div class="title"><h4><i class="fa fa-clock-o" aria-hidden="true"></i> Estimated Execution Time <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="The estimated time spent executing this bootcamp. Do not consider the execution time for other linked bootcamps via the Bootcamp Wiki as each bootcamp should have its own time estimate. If you estimate this bootcamp to take more than 21 hours, then break it down into smaller bootcamps."></i></h4></div>
            <select class="form-control input-mini" id="save_c_time_estimate">
        		<?php 
        		$times = $this->config->item('c_time_options');
            	foreach($times as $time){
            	    echo '<option value="'.$time.'" '.( $cr['c']['c_time_estimate']==$time ? 'selected="selected"' : '' ).'>'.echo_hours($time).'</option>';
            	}
            	?>
            </select>
            
            
            <div class="title"><h4>Status</h4></div>
            <input type="hidden" id="save_c_status" value="<?= $cr['c']['c_status'] ?>" /> 
            <div class="col-md-3 dropdown">
            	<a href="#" class="btn btn-simple dropdown-toggle" id="ui_save_c_status" data-toggle="dropdown">
                	<?= status_bible('c',$cr['c']['c_status']) ?>
                	<b class="caret"></b>
            	</a>
            	<ul class="dropdown-menu">
            		<?php 
            		$statuses = status_bible('c');
            		$count = 0;
            		foreach($statuses as $intval=>$status){
            		    $count++;
            		    echo '<li><a href="javascript:update_dropdown(\'save_c_status\','.$intval.','.$count.');">'.$status.'</a></li>';
            		    echo '<li style="display:none;" id="save_c_status_'.$count.'">'.$status.'</li>'; //For UI replacement
            		}
            		?>
            	</ul>
            </div>
            
            
            
            
           
            
            
            
            
            
            <div class="title"><h4>Marketplace Listing</h4></div>
            
            <div class="checkbox">
            	<label>
            		<input type="checkbox" id="c_is_grandpa" <?= ($cr['c']['c_is_grandpa']=='t' ? 'disabled checked' : '') ?> />
            		List on Marketplace <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Listing on the marketplace would enhance this bootcamp with the cohorts module which enables student registration, reporting & more."></i>
            	</label>
            </div>
            
            <div class="title req_c_is_grandpa" style="margin-top:30px; <?= ($cr['c']['c_is_grandpa']=='t' ? 'display:block;' : '') ?>"><h4>Marketplace URL <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Used as the URL of this bootcamp for students to view and register."></i></h4></div>
			<div class="form-group label-floating is-empty req_c_is_grandpa" style="<?= ($cr['c']['c_is_grandpa']=='t' ? 'display:block;' : '') ?>">
			    <input type="text" id="save_c_url_key" style="text-transform: lowercase;" value="<?= $cr['c']['c_url_key'] ?>" class="form-control">
			    <span class="material-input"></span>
			    <p class="extra-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning: URL changes break previously shared links.</p>
			</div>
            
		
		  
		    <div class="row" style="clear:both;">
			  <div class="col-xs-6"><a href="javascript:save_c();" class="btn btn-primary">Save</a> <span id="save_c_results"></span></div>
			  <div class="col-xs-6 action-right">
			  </div>
			</div>

    </div>
</div>