<h1>Online Bootcamps</h1>
<br /><br />
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

				<div class="card-content">
					<h6 class="category text-rose">'.$c['ct_name'].'</h6>
					<h4 class="card-title">
						<a href="/bootcamps/'.$c['c_url_key'].'">'.echo_title($c['c_objective']).'</a>
					</h4>
					<div class="card-description">';
    
    //Print admins:
    foreach($c['c__cohorts'][0]['r__admins'] as $admins){
        echo '<a href="/users/'.$admins['u_url_key'].'">'.$admins['u_fname'].' '.$admins['u_lname'].'</a>';
    }
                     echo '</div>
					<div class="footer">
                        <div class="price">
							<h4>'.($c['c__cohorts'][0]['r_usd_price']>0?'$'.number_format($c['c__cohorts'][0]['r_usd_price'],0):'FREE').'</h4>
						</div>
                    	<div class="stats">
							Starts <b>July 5th</b>
                    	</div>
                    </div>

				</div>

			</div>
		</div>';
}
?>
</div>