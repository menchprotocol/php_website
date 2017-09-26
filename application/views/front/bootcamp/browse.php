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
    foreach($c['c__cohorts'][0]['r__admins'] as $count2=>$admins){
        if($count2>0){
            echo ' & ';
        }
        echo '<img src="'.$admins['u_image_url'].'" /> '.$admins['u_fname'].' '.$admins['u_lname'];
    }
                     echo '</div>
					<div class="footer">
                        <div class="price">
							<h4>'.echo_price($c['c__cohorts'][0]['r_usd_price']).'</h4>
						</div>
                    	<div class="stats"><span '.( $c['c__cohorts'][0]['r_end_time'] ? 'data-toggle="tooltip" class="underdot" title="Ends '.time_format($c['c__cohorts'][0]['r_end_time'],1).(strlen($c['c__cohorts'][0]['r_closed_dates'])>0?' excluding '.$c['c__cohorts'][0]['r_closed_dates']:'').'"' : '' ).'>Starts <b>'.time_format($c['c__cohorts'][0]['r_start_time'],1).'</b></span></div>
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

<?php $this->load->view('front/shared/all_bootcamps'); ?>
<br /><br />
