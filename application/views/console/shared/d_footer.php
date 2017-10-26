				</div>
	        </div>
	    </div>
	</div>

<?php 
$website = $this->config->item('website');
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
  <div class="modal-dialog" style="max-width:400px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">
        	<span class="navbar-brand dashboard-logo">
				<a href="/console">
				<img src="/img/bp_48.png" style="margin-top:-11px !important;">
				<span>MenchBot</span>
			</span>
		</h4>
      </div>
      <div class="modal-body">
      <p>MenchBot is how Students and Instructors connect. It's how we increase engagement, and how we empower live on-demand communication.</p>
      </div>
      <div class="modal-footer" style="text-align:center;">
        <a href="<?= $website['bot_ref_url'].'?ref='.$udata['u_id'] ?>" class="btn btn-primary">Connect to MenchBot</a>
      </div>
    </div>
  </div>
</div>


</body>
</html>