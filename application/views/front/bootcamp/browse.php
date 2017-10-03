<h1>Online Bootcamps</h1>
<br />

<div class="row">
<?php 
foreach($bootcamps as $count=>$c){
    if(fmod($count,4)==0){
        echo '</div><div class="row">';
    }
    echo '<div class="col-sm-6 col-md-4">
			<div class="card card-product">
				<div class="card-image">
					<img class="img" src="'.$c['c_image_url'].'">
				</div>

				<div class="card-content">';
                //echo '<h6 class="category text-muted">'.$c['ct_icon'].' '.$c['ct_name'].'</h6>';
                echo '<h4 class="card-title">
						<a href="/bootcamps/'.$c['c_url_key'].'">'.echo_title($c['c_objective']).'</a>
					</h4>


                    <div class="card-description">'.echo_pace($c).'</div>
					<div class="card-description">By ';
    
    //Print admins:
    $admin_count = 0;
    foreach($c['c__admins'] as $admin){
        if($admin['ba_team_display']!=='t'){
            continue;
        }
        if($admin_count>0){
            echo ' & ';
        }
        echo '<span style="display:inline-block;"><img src="'.$admin['u_image_url'].'" /> '.$admin['u_fname'].' '.$admin['u_lname'].'</span>';
        $admin_count++;
    }
                     echo '</div>
					<div class="footer">
                        <div class="price">
							<h4>'.echo_price($c['c__cohorts'][0]['r_usd_price']).'</h4>
						</div>
                    	<div class="stats"><span>Starts <b>'.time_format($c['c__cohorts'][0]['r_start_date'],1).'</b></span></div>
                    </div>

				</div>

			</div>
		</div>';
}
?>
</div>

</div>
</div>


<div>
<div class="container">

<?php $this->load->view('front/shared/bootcamps_inlcude'); ?>
<br /><br />
