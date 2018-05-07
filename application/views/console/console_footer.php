				</div>
	        </div>
	    </div>
	</div>

<?php 
$udata = $this->session->userdata('user');

//For JS functions such as search and in account page
echo '<input type="hidden" id="u_id" value="'.$udata['u_id'].'" />';
echo '<input type="hidden" id="u_inbound_u_id" value="'.$udata['u_inbound_u_id'].'" />';


if(isset($load_view)){
    $data = array();
    if(isset($b)){
        $data = array(
            'b' => $b,
        );
    }
    $this->load->view($load_view , $data);
}
?>


</body>
</html>