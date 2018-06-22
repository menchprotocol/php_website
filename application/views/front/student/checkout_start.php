<script>

function start_application(){

    //Show loader:
    $('#submit_button').addClass('hidden');
    $('#start_result').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
    $(".form-el").prop('disabled', true).css('background-color','#EFEFEF');

    //Send data for processing:
    $.post("/api_v1/ru_checkout_initiate", {
        b_id:<?= $b['b_id'] ?>,
        u_full_name:$('#u_full_name').val(),
        u_email:$('#u_email').val(),
    } , function(data) {
        //Do we have a redirect URL?
        if(data.status){
            //Successful, move on:
            window.location = data.hard_redirect;
            return false;
        } else {
            //Some sort of an error, show it:
            $('#submit_button').removeClass('hidden');
            $(".form-el").prop('disabled', false).css('background-color','#FFFFFF');
            $('#start_result').html(data.error_message);
        }
    });
}

$('body').keyup(function(e){
    if(e.keyCode == 13) {
        start_application();
    }
});

$( document ).ready(function() {
	//Make focus:
	$('#u_full_name').focus(); //Focus on input

    //Auto submit only if user email is passed via URL:
    <?= ( count($u)>0 && isset($_GET['u_email']) ? 'start_application();' : '') ?>
});

</script>

<?= echo_b_header($b); ?>

<div class="section">
    <div class="row" style="max-width:330px; padding-left:10px;">
        <div class="col-xs-12">
            <p>Full Name:</p>
            <p><input type="text" id="u_full_name" value="<?= ( isset($u['u_full_name']) ? $u['u_full_name'] : '' ) ?>" class="form-el" /></p>
        </div>
        <div class="col-xs-12">
            <p>Email:</p>
            <p><input type="email" id="u_email" value="<?= ( isset($u['u_email']) ? $u['u_email'] : '' ) ?>" style="text-transform: lowercase;" class="form-el" /></p>
        </div>
    </div>
    <div class="row maxout" style="padding-left:10px;">
        <div class="col-xs-12" style="padding-left:14px;">
            <div id="start_result"></div>
            <a href="javascript:start_application()" id="submit_button" class="btn btn-funnel" style="color:#FFF;">Start &nbsp;<i class="fas fa-chevron-circle-right" style="font-size:1.1em;"></i></a>
        </div>
    </div>
</div>