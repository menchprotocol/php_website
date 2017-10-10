<h1>Online Bootcamps</h1>
<br />

<div class="row">
<?php 
foreach($bootcamps as $count=>$bootcamp){
    if(fmod($count,4)==0){
        echo '</div><div class="row">';
    }
    echo '<div class="col-sm-6 col-md-4">
			<div class="card card-product">
				<div class="card-image" goto="/bootcamps/'.$bootcamp['b_url_key'].'">
					<img class="img" src="'.$bootcamp['b_image_url'].'">
				</div>

				<div class="card-content">';
                //echo '<h6 class="category text-muted">'.$bootcamp['ct_icon'].' '.$bootcamp['ct_name'].'</h6>';
                echo '<h4 class="card-title">
						<a href="/bootcamps/'.$bootcamp['b_url_key'].'">'.$bootcamp['c_objective'].'</a>
					</h4>


                    <div class="card-description"><i class="fa fa-calendar" aria-hidden="true"></i> '.count($bootcamp['c__child_intents']).' Weeks @ '.round($bootcamp['c__estimated_hours']/count($bootcamp['c__child_intents'])).' Hours/Week</div>
					<div class="card-description">By ';
    
    //Print admins:
    $admin_count = 0;
    foreach($bootcamp['b__admins'] as $admin){
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
							<h4>'.echo_price($bootcamp['c__cohorts'][0]['r_usd_price']).'</h4>
						</div>
                    	<div class="stats"><span>Starts <b>'.time_format($bootcamp['c__cohorts'][0]['r_start_date'],1).'</b></span></div>
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


<script>
$( ".card-image" ).click(function() {
	window.location = $(this).attr('goto');
});
</script>