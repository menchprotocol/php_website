				</div>
	        </div>
	    </div>
	</div>

<?php 
$udata = $this->session->userdata('user');
if(isset($load_view)){
    $data = array();
    if(isset($bootcamp)){
        $data = array(
            'bootcamp' => $bootcamp,
        );
    }
    $this->load->view($load_view , $data);
}
?>

<!-- Messenger Intro Model -->
<div class="modal fade" id="MenchBotModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">
        	<span class="navbar-brand dashboard-logo" style="margin-top:-7px;">
				<i class="fa fa-commenting" style="font-size:30px;" aria-hidden="true"></i>
				<span style="font-family: Lato; font-size: 26px; text-transform: uppercase;">MenchBot</span>
			</span>
		</h4>
      </div>
      <div class="modal-body" style="margin-top:-22px;">
      <p>MenchBot connects Students and Instructors using Facebook Messenger to increase engagement and empower on-demand communication.</p>
      <!--
      <p>Watch this video on how students would activate and use it:</p>
      <p style="text-align:center;"><video width="250" controls=""><source src="https://s3foundation.s3-us-west-2.amazonaws.com/6ab68e75f4858b981a6db2c737a52cdf.mp4" type="video/mp4"></video></p>
       -->
      </div>
      
      <div class="modal-footer">
      <?php if(strlen($udata['u_fb_id'])>4){ ?>
        <p style="text-align:left;">You are already connected to MenchBot.</p>
      <?php } else { ?>
        <a href="<?= messenger_activation_url($udata['u_id']) ?>" class="btn btn-primary">Connect to MenchBot</a>
      <?php } ?>
      </div>
      
    </div>
  </div>
</div>


</body>
</html>