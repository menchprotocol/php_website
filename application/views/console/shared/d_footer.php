				</div>
	        </div>
	    </div>
	</div>

<?php 
$udata = $this->session->userdata('user');


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