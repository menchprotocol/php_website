
<script type="text/javascript">

    function update_icon(){
        var new_icon = $('#cover_input').val();
        $('#cover_frame').attr("class",new_icon);
    }

    $(document).ready(function () {

        update_icon();

        $('#cover_input').keyup(function() {
            update_icon();
        });
    });


</script>

<input id="cover_input" type="text" value="fas fa-circle idea" class="form-control white-border" />

<div style="    width: 300px;
    height: 450px;
    text-align: center;
    border: 3px solid #FC1B44;
    margin: 21px auto;
    background-color: #000;
    font-size: 8em;">
    <i id="cover_frame" style="padding-top: 135px;"></i>
</div>