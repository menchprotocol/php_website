<?php

$can_modify = can_modify('c',$bootcamp['c_id']);

/*
echo '<div id="list-inbound" class="list-group" style="margin-top:10px;">';
if(isset($cr['inbound']) && count($cr['inbound'])>0){
	foreach($cr['inbound'] as $relation){
		echo echo_cr($this->uri->segment(2, 0),$relation,'inbound');
	}
}
echo '</div>';
*/

if($cr['c']['c_id']==$bootcamp['c_id'] || !isset($cr['inbound'])){
    $level = 1;
    echo '<ol class="breadcrumb"><li>'.$this->lang->line('cr_name').'</li></ol>';
} else {
    
    //print_r($cr['inbound']);
    
    //See if this is level 2, which means directly below main bootcamp:
    foreach($cr['inbound'] as $relation){
        if($relation['cr_outbound_id']==$cr['c']['c_id'] && ($relation['cr_inbound_id']==$bootcamp['c_id'])){
            //Found this as level 2:
            $level = 2;
            echo '<ol class="breadcrumb"><li><a href="/console/'.$bootcamp['c_id'].'/content">'.$this->lang->line('cr_name').'</a></li><li>'.echo_level(2, $relation['cr_outbound_rank']).': '.$cr['c']['c_objective'].'</li></ol>';
            break;
        }
    }    
    
    //Not level 2? Likely level 3:
    if(!isset($level)){
        foreach($cr['inbound'] as $relation){
            if($relation['cr_outbound_id']==$cr['c']['c_id'] && !($relation['cr_inbound_id']==$bootcamp['c_id'])){
                //This is level 3:
                $level = 3;
                
                //Fetch level 2:
                $level_2 = $this->Db_model->c_fetch(array(
                    'c.c_id >=' => $relation['cr_inbound_id'],
                ));
                
                $level_2_relation = $this->Db_model->cr_outbound_fetch(array(
                    'cr.cr_outbound_id' => $relation['cr_inbound_id'],
                    'cr.cr_inbound_id' => $bootcamp['c_id'],
                ));
                
                //Print breadcrumb:
                echo '<ol class="breadcrumb"><li><a href="/console/'.$bootcamp['c_id'].'/content">'.$this->lang->line('cr_name').'</a></li><li><a href="/console/'.$bootcamp['c_id'].'/content/'.$relation['cr_inbound_id'].'">'.echo_level(2, $level_2_relation[0]['cr_outbound_rank']).': '.$level_2[0]['c_objective'].'</a></li><li>'.echo_level(3, $relation['cr_outbound_rank']).': '.$cr['c']['c_objective'].'</li></ol>';
                
                break;
            }
        }
    }
}

echo '<h1>'.echo_title($cr['c']['c_objective']).'</h1>';
echo '<input type="hidden" id="next_level" value="'.($level+1).'" >';

?>

<ul class="nav nav-pills nav-pills-primary" style="margin-top:10px;">
  <?= ( $level<3 ? '<li class="active"><a href="#pill1" data-toggle="tab"><i class="fa fa-list-ol" aria-hidden="true"></i> '.( $level==1 ? 'Weekly Sprints' : 'Sprint Objectives' ).'</a></li>' : '' ) ?>
  <!-- <li><a href="#pill2" data-toggle="tab" onclick="load_message_sorting()"><?= $this->lang->line('i_icon') ?> <?= $this->lang->line('i_pname') ?></a></li> -->
  <li <?= ( $level==3 ? 'class="active"' : '' ) ?>><a href="#pill4" data-toggle="tab"><i class="fa fa-pencil" aria-hidden="true"></i> <?= $this->lang->line('edit').' '.str_replace('data-toggle="tooltip"','',status_bible('c',$cr['c']['c_status'],1)) ?></a></li>
</ul>




<div class="tab-content tab-space">

	<?php if($level<3){ ?>
    <div class="tab-pane active" id="pill1">
		<?php
		//OUT
		if($level==1){
		    echo '<p>Breakdown your bootcamp into weekly sprints by defining a clear objective that is measurable, attainable, relevant, rewarding & trackable for each week. This is what students would be handing in at the end of the week. Each sprint starts on Monday early morning and ends Sunday midnight.</p>';
		} elseif($level==2){
		    echo '<p>Define 2-7 objectives that contribute to the students understanding on how to <b>'.$cr['c']['c_objective'].'</b>. Also make sure to edit this sprint and set its overview, estimate time and other details.</p>';
		}
		echo '<div id="list-outbound" class="list-group">';
			if(isset($cr['outbound']) && count($cr['outbound'])>0){
				foreach($cr['outbound'] as $relation){
				    echo echo_cr($this->uri->segment(2, 0),$relation,'outbound',($level+1));
				}
			}
		echo '</div>';
		//Can they modify?
		if($can_modify){
		    echo '<div id="list-outbound" class="list-group">';
		    echo '<div class="list-group-item list_input">';
		        ?>
				<div class="input-group">
					<div class="form-group is-empty" style="margin: 0; padding: 0;"><input type="text" class="form-control autosearch" id="addnode" placeholder="+ Add <?= ($level==1 ? 'Sprint' : 'Objective') ?>"></div>
					<span class="input-group-addon" style="padding-right:0;">
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
    <?php } ?>
    
    
    
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
		    			echo '<li class="pull-right"><a href="/console/help/showdown_markup" target="_blank"><i class="fa fa-info-circle"></i> Markup Support</a></li>';
		    		echo '</ul>';
		    		echo '</div>';
	    		echo '</div>';
    		echo '</div>';
    	}
		?>
    </div>
    
    
    <div class="tab-pane <?= ( $level==3 ? 'active' : '' ) ?>" id="pill4">
    
    		<input type="hidden" id="save_c_id" value="<?= $cr['c']['c_id'] ?>" />
    		
    		<div class="title"><h4>Primary Objective <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="The primary objective to be accomplished by executing this bootcamp. This should be measurable, attainable, relevant, rewarding & trackable."></i></h4></div>
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
			
			
			<div class="title"><h4>Additional Objectives <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="A list of what the student should expect to accomplish on top of the primary goal if they execute on this bootcamp. Listed goals should be measurable, attainable, relevant, rewarding & trackable."></i> <span style="font-size:0.6em; color:#AAA;">(<a href="/console/help/showdown_markup" target="_blank">Markup Support <i class="fa fa-info-circle"></i></a>)</span></h4></div>
			<div class="form-group label-floating is-empty">
			    <textarea class="form-control text-edit" rows="2" id="save_c_additional_goals"><?= $cr['c']['c_additional_goals'] ?></textarea>
			    <span class="material-input"></span>
			</div>
			
			
			<div class="title"><h4>Prerequisites <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="A list of what is required from the students *before* starting to execute on this bootcamp."></i> <span style="font-size:0.6em; color:#AAA;">(<a href="/console/help/showdown_markup" target="_blank">Markup Support <i class="fa fa-info-circle"></i></a>)</span></h4></div>
			<div class="form-group label-floating is-empty">
			    <textarea class="form-control text-edit" rows="2" id="save_c_prerequisites"><?= $cr['c']['c_prerequisites'] ?></textarea>
			    <span class="material-input"></span>
			</div>
			
			
			<div class="title"><h4>Todo Overview <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="An overview (Not detail) of what the students should do to accomplish the guaranteed outcome. Meetings, projects, etc..."></i> <span style="font-size:0.6em; color:#AAA;">(<a href="/console/help/showdown_markup" target="_blank">Markup Support <i class="fa fa-info-circle"></i></a>)</span></h4></div>
			<div class="form-group label-floating is-empty">
			    <textarea class="form-control text-edit" rows="2" id="save_c_todo_overview"><?= $cr['c']['c_todo_overview'] ?></textarea>
			    <span class="material-input"></span>
			</div>
			
			
			<div class="title"><h4>Todo Detailed Instructions <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="A list of detailed instructions on what the student should exactly do to accomplish this bootcamp. It would be shared with them on the week of this bootcamp"></i> <span style="font-size:0.6em; color:#AAA;">(<a href="/console/help/showdown_markup" target="_blank">Markup Support <i class="fa fa-info-circle"></i></a>)</span></h4></div>
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
            
            
            
            
           
            
            
            
            
            
            <div class="title"><h4>Mench Marketplace</h4></div>
            
            <div class="checkbox">
            	<label>
            		<input type="checkbox" id="c_is_grandpa" <?= ($cr['c']['c_is_grandpa']=='t' ? 'disabled checked' : '') ?> />
            		Publish to Marketplace <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Listing on the marketplace would enhance this bootcamp with the cohorts module which enables student registration, reporting & more."></i>
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