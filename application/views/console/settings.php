<input type="hidden" id="c_id" value="<?= $bootcamp['c_id'] ?>" />


<div class="alert alert-info" role="alert"><span>For Your Information:</span>Some bootcamp settings including its name, objectives, overview and prerequisites are managed using the <a href="/console/79/curriculum">Bootcamp Curriculum</a>.</div>
    	
<div id="acordeon">
    <div class="panel-group" id="accordion">
     
  
  
      <div class="panel panel-border panel-default" name="collapseGeneral">
        <div class="panel-heading" role="tab">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseGeneral" aria-controls="collapseGeneral">
                <h4 class="panel-title">
                Status
                <i class="material-icons">keyboard_arrow_down</i>
                </h4>
            </a>
        </div>
        <div id="collapseGeneral" class="panel-collapse collapse">
          <div class="panel-body">
    			<?php echo_status_dropdown('c','c_status',$bootcamp['c_status']); ?>
          </div>
        </div>
      </div>
      
      
      
      
          
          
      <div class="panel panel-border panel-default" name="collapseLandingPage">
        <div class="panel-heading" role="tab">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseLandingPage" aria-controls="collapseLandingPage">
                <h4 class="panel-title">
                Landing Page
                <i class="material-icons">keyboard_arrow_down</i>
                </h4>
            </a>
        </div>
        <div id="collapseLandingPage" class="panel-collapse collapse">
          <div class="panel-body">
    
    
                <div class="title" style="margin-top:30px;"><h4>Bootcamp URL Key <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Used as the URL of this bootcamp for students to view and register."></i></h4></div>
                <div class="form-group label-floating is-empty">
                    <input type="text" id="c_url_key" style="text-transform:lowercase;" value="<?= $bootcamp['c_url_key'] ?>" class="form-control">
                    <span class="material-input"></span>
                    <p class="extra-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning: URL changes break previously shared links.</p>
                </div>
                
                
                <div class="title"><h4>Featured Image URL (w/h ratio of 1.78) <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="The image for the marketplace."></i></h4></div>
                <div class="form-group label-floating is-empty">
                    <input type="url" id="c_image_url" value="<?= $bootcamp['c_image_url'] ?>" class="form-control">
                    <span class="material-input"></span>
                </div>
                
                
                
                <div class="title"><h4>Featured Video URL (2-3 minutes max) <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="The video that would be displayed on the landing page of the bootcamp that explains why students should join your bootcamp."></i></h4></div>
                <div class="form-group label-floating is-empty">
                    <input type="url" id="c_video_url" value="<?= $bootcamp['c_video_url'] ?>" class="form-control">
                    <span class="material-input"></span>
                </div>
                
                <p><a href="/bootcamps/<?= $bootcamp['c_url_key'] ?>" target="_blank" class="btn btn-default">Open Landing Page <i class="fa fa-external-link" style="font-size:1em;" aria-hidden="true"></i></a></p>
                
          </div>
        </div>
      </div>
      
      
      
    
          
          
          
          
      <div class="panel panel-border panel-default" name="collapseMentors">
        <div class="panel-heading" role="tab">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseMentors" aria-controls="collapseMentors">
                <h4 class="panel-title">
                Admins & TAs
                <i class="material-icons">keyboard_arrow_down</i>
                </h4>
            </a>
        </div>
        <div id="collapseMentors" class="panel-collapse collapse">
          <div class="panel-body">
            	<p>Define who can manage or contribute to this bootcamp:</p>
                <table class="table">
                	<thead>
                		<tr>
                			<th>Person</th>
                			<th>Role</th>
                			<th>Team Display <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Whether this person is shown in the bootcamp landing page as a team member."></i></th>
                			<th>Actions</th>
                		</tr>
                	</thead>
                	<tbody>
                	<?php 
                	$admins = $this->Db_model->c_admins($bootcamp['c_id']);
                	foreach($admins as $admin){
                	    echo '<tr> <td>'.$admin['u_fname'].' '.$admin['u_lname'].'</td> <td>'.status_bible('ba',$admin['ba_status']).'</td> <td>'.( $admin['ba_team_display']=='t' ? 'Yes' : 'No' ).'</td> <td><a href="javascript:ba_open_modify('.$admin['ba_id'].')" data-toggle="tooltip" title="Modify admin role and team display status"><i class="fa fa-pencil-square" aria-hidden="true"></i></a> &nbsp; &nbsp; <a href="javascript:ba_initiate_revoke('.$admin['ba_id'].')" data-toggle="tooltip" title="Revoke admin status"><i class="fa fa-ban" aria-hidden="true"></i></a></td> </tr>';
                	}
                	
                	echo '<tr style="background-color:#EFEFEF;"> <td><input type="email" id="new_admin_email" class="form-control" placeholder="New admin email" /></td> <td>';
                	//echo_status_dropdown('ba','new_admin_role',2);
                	echo '</td> <td><div class="checkbox"><label><input type="checkbox" id="new_admin_team_display"></label></div></td> <td><a href="javascript:ba_add()"><i class="fa fa-plus" aria-hidden="true"></i> Add Admin</a></td> </tr>';
                	?>
                	</tbody>
                </table>
          </div>
        </div>
      </div>
  
  
  
	</div>
</div><!--  end acordeon -->
        
    


<table width="100%"><tr><td class="save-td"><a href="javascript:save_settings();" class="btn btn-primary">Save</a></td><td><span id="save_c_results"></span></td></tr></table>

