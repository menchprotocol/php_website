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


</body>
</html>