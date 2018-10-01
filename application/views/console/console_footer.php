				</div>
	        </div>
	    </div>
	</div>

<?php 
$udata = $this->session->userdata('user');

//For JS functions such as search and in account page
echo '<input type="hidden" id="u_id" value="'.$udata['u_id'].'" />';
?>



<script>
    function url_modal(url){
        $('#loadUrlModal .modal-body').html('<iframe src="'+url+'"></iframe>');
        $("#loadUrlModal").modal();
    }
</script>
<div class="modal fade" id="loadUrlModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>





</body>
</html>