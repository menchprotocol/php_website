
<script type="text/javascript">
    function update_icon(){
        $('#cover_frame').attr("class",$('#cover_input').val());
    }
    update_icon();
    $("#cover_input").change(function () {
        update_icon();
    });
</script>

<input id="cover_input" type="text" value="fas fa-circle idea" class="form-control white-border">

<div style="background-color: #000000; width: 400px; height: 600px; text-align: center; border:3px solid #FC1B44; margin:21px 0;">
    <i id="cover_frame" class="" style="font-size: 21em; padding-top: 126px;"></i>
</div>