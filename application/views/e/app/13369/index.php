
<input type="text" value="fas fa-circle idea" class="form-control white-border">
<script type="text/javascript">
    function update_icon(){
        $('i').attr("class",$('input').val());
    }
    $(document).ready(function () {
        update_icon();
        $("input").change(function () {
            update_icon();
        });
    });
</script>

<div style="background-color: #000000; width: 400px; height: 600px; text-align: center; border:3px solid #FC1B44;">
    <i class="" style="font-size: 21em; padding-top: 126px;"></i>
</div>