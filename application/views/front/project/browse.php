<h1>Browse Online Projects</h1>
<br />

<div class="row">
<?php 
foreach($projects as $count=>$project){
    
    //Fetch class:
    $focus_class = filter_class($project['c__classes'],null);
    
    if(!$focus_class){
        continue;
    }

    echo '<div class="col-sm-6 col-md-4">
			<div class="card card-product">
				<!-- <div class="card-image"></div> -->

				<div class="card-content">';
    
                //echo '<h6 class="category text-muted">'.$project['ct_icon'].' '.$project['ct_name'].'</h6>';
                echo '<h4 class="card-title" style="font-size: 1.4em; line-height: 110%; margin:15px 0 12px 0;"><a href="/'.$project['b_url_key'].'">'.$project['c_objective'].'</a></h4>';
                echo '<div class="card-description"><b>'.$project['c__milestone_units'].' '.ucwords($project['b_sprint_unit']).( $project['c__milestone_units']==1 ? '' : 's').': '.echo_hours(round($project['c__estimated_hours']/$project['c__milestone_units'])).'/'.ucwords($project['b_sprint_unit']).'</b></div>';
                
                
                echo '<div class="card-description">By ';
                //Print lead admin:
                foreach($project['b__admins'] as $admin){
                    if($admin['ba_status']==3){
                        echo '<span style="display:inline-block;"><img src="'.$admin['u_image_url'].'" /> '.$admin['u_fname'].' '.$admin['u_lname'].'</span>';
                    }
                }
                echo '</div>';
                
                echo '<div class="footer">
                        <div class="price">
							<h4>'.echo_price($focus_class['r_usd_price']).'</h4>
						</div>
                    	<div class="stats"><span>Starts <b>'.time_format($focus_class['r_start_date'],1).'</b></span></div>
                    </div>

				</div>
			</div>
		</div>';

    if(fmod($count,3)==0){
        echo '</div><div class="row">';
    }
}
?>
</div>

</div>
</div>


<div>
<div class="container">

<?php $this->load->view('front/shared/projects_include'); ?>
<br /><br />
