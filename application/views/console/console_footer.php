				</div>
	        </div>
	    </div>
	</div>


<script>
    function url_modal(url){
        $('#loadUrlModal .modal-body').html('<iframe src="'+url+'"></iframe>');
        $("#loadUrlModal").modal();
    }

    $(document).ready(function () {
        $('#loadUrlModal').on('hidden.bs.modal', function () {
            $('#loadUrlModal .modal-body').html('&nbsp;');
        });
    });

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