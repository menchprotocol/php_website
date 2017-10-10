<input type="hidden" id="b_id" value="<?= $bootcamp['b_id'] ?>" />


   	
<div id="acordeon">
    <div class="panel-group" id="accordion">
          
          
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
    
    			<div class="title" style="margin-top:0;"><h4>Status</h4></div>
    			<?php echo_status_dropdown('b','b_status',$bootcamp['b_status']); ?>
    			<div style="clear:both; margin:0; padding:0;"></div>
    			
                <div class="title" style="margin-top:30px;"><h4>Bootcamp URL Key <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Used as the URL of this bootcamp for students to view and register."></i></h4></div>
                <div class="form-group label-floating is-empty">
                    <input type="text" id="b_url_key" style="text-transform:lowercase;" value="<?= $bootcamp['b_url_key'] ?>" class="form-control">
                    <span class="material-input"></span>
                    <p class="extra-info"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Warning: URL changes break previously shared links.</p>
                </div>
                
                
                <div class="title"><h4>Featured Image URL (w/h ratio of 1.78) <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="The image for the marketplace."></i></h4></div>
                <div class="form-group label-floating is-empty">
                    <input type="url" id="b_image_url" value="<?= $bootcamp['b_image_url'] ?>" class="form-control">
                    <span class="material-input"></span>
                </div>
                
                
                
                <div class="title"><h4>Featured Video URL (2-3 minutes max) <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="The video that would be displayed on the landing page of the bootcamp that explains why students should join your bootcamp."></i></h4></div>
                <div class="form-group label-floating is-empty">
                    <input type="url" id="b_video_url" value="<?= $bootcamp['b_video_url'] ?>" class="form-control">
                    <span class="material-input"></span>
                </div>
                
                <table width="100%"><tr><td class="save-td" style="width:230px;">
                	<a href="/bootcamps/<?= $bootcamp['b_url_key'] ?>" target="_blank" class="btn btn-default">Open <i class="fa fa-external-link" style="font-size:1em;" aria-hidden="true"></i></a>
                	<a href="javascript:save_settings();" class="btn btn-primary">Save</a>
                </td><td><span id="save_c_results"></span></td></tr></table>
                
          </div>
        </div>
      </div>
      
      
      
    
          
          
          
          
      <div class="panel panel-border panel-default" name="collapseMentors">
        <div class="panel-heading" role="tab">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseMentors" aria-controls="collapseMentors">
                <h4 class="panel-title">
                Team Members
                <i class="material-icons">keyboard_arrow_down</i>
                </h4>
            </a>
        </div>
        <div id="collapseMentors" class="panel-collapse collapse">
          <div class="panel-body">
        	<p>Here are team members who would assist in running this bootcamp:</p>
        	
        	<?php
        	echo '<div id="list-outbound" class="list-group">';
        	foreach($bootcamp['b__admins'] as $admin){
        	    echo echo_br($admin);
        	}
    		echo '</div>';
    		?>
    		
    		<p>Contact us at support@mench.co to modify team members.</p>
    		
    		<!-- 
    		<div id="list-outbound" class="list-group">
        		<div class="list-group-item list_input">
    				<div class="input-group">
    					<div class="form-group is-empty" style="margin: 0; padding: 0;">
    						<input type="email" class="form-control autosearch" id="addAdmin" placeholder="johnsmith@gmail.com">
    					</div>
    					<span class="input-group-addon" style="padding-right:0;">
    						<span class="label label-primary pull-right" style="cursor:pointer;" onclick="ba_add();">
    							<div><i class="fa fa-plus"></i></div>
    						</span>
    					</span>
    				</div>
				</div>
			</div>
			-->
			
          </div>
        </div>
      </div>
  
  
  
	</div>
</div><!--  end acordeon -->
        
    

