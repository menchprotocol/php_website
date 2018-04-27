<script>

function start_application(){

    //Show loader:
    $('#submit_button').addClass('hidden');
    $('#start_result').html('<span><img src="/img/round_load.gif" class="loader" /></span>').hide().fadeIn();
    $(".form-el").prop('disabled', true).css('background-color','#EFEFEF');

    //Send data for processing:
    $.post("/api_v1/ru_checkout_initiate", {
        b_id:<?= $b['b_id'] ?>,
        u_fname:$('#u_fname').val(),
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
	$('#u_fname').focus(); //Focus on input
});

</script>

<p style="border-bottom:4px solid #3C4858; font-weight:bold; padding-bottom:10px; margin-bottom:20px; display:block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?= $b['c_outcome'] ?><span style="font-weight: 500; display: block; padding-top:5px; font-size:0.8em;"><i class="fa fa-calendar" aria-hidden="true"></i> <?= format_hours($b['c__estimated_hours']).' in '.$b['b__week_count'].' Week'.show_s($b['b__week_count']) ?> [<?= format_hours($b['c__estimated_hours']/($b['b__week_count']*7)) ?> per Day]</span></p>


<div class="section">
    <div class="row" style="max-width:330px; padding-left:10px;">
        <div class="col-xs-12">
            <p>Full Name:</p>
            <p><input type="text" id="u_fname" value="<?= ( isset($udata['u_fname']) ? $udata['u_fname'] : '' ) ?>" class="form-el" /></p>
        </div>
        <div class="col-xs-12">
            <p>Email:</p>
            <p><input type="email" id="u_email" value="<?= ( isset($udata['u_email']) ? $udata['u_email'] : '' ) ?>" style="text-transform: lowercase;" class="form-el" /></p>
        </div>
    </div>
    <div class="row maxout" style="padding-left:10px;">
        <div class="col-xs-12" style="padding-left:14px;">
            <div id="start_result"></div>
            <a href="javascript:start_application()" id="submit_button" class="btn btn-funnel" style="color:#FFF;">Start &nbsp;<i class="fa fa-chevron-circle-right" style="font-size:1.1em;" aria-hidden="true"></i></a>
        </div>
    </div>
</div>