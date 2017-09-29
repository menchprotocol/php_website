				</div>
	        </div>
	    </div>
	</div>

<?php 
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