<script>
var current_section = 1;
var default_text = 'or press <b>ENTER</b>';
var r_id = <?= $focus_class['r_id'] ?>;

function ui_show(section_overide=0){
	if(section_overide>0){
		current_section = section_overide;
	}
	$('.section').hide(); //Hide all boxes
	$('.s'+current_section).fadeIn();
	$('.s'+current_section+' input').focus();
	$('.s'+current_section+' .enter').html(default_text);
}

function next_section(){
	if(current_section>=4){
		//This is the final step, do nothing:
		return false;
	} else {
		$('.s'+current_section+' .enter').html('<img src="/img/round_yellow_load.gif" class="loader" />');
    	setTimeout(function() {
        	
    		//Send data for processing:
    		$.post("/api_v1/funnel_progress", {
    		r_id:r_id,
    		current_section:current_section,
    		u_email:$('#u_email').val(),
    		u_fname:$('#u_fname').val(),
    		u_lname:$('#u_lname').val(),
    		
        	} , function(data) {
        		//Do we have a redirect URL?
        		if(typeof data.hard_redirect !== 'undefined'){
            		//Time to go:
        			window.location = data.hard_redirect;
        			return false;
            	} else if(data.goto_section>0){
					ui_show(data.goto_section);
				} else {
					//Stop and show:
					$('.s'+current_section+' .enter').html(default_text);
					$('.s'+current_section+' .result').css('color',data.color).html(data.message).hide().fadeIn();
					$('.s'+current_section+' input').focus(); //Focus on input
				}
    	    });
    	    
    	}, 300);
	}
}

$('body').keyup(function(e){
    if(e.keyCode == 13) {
    	next_section();
    }
});

$( document ).ready(function() {
	//Make focus:
	$('.s1 input').focus(); //Focus on input
});

</script>



<p style="border-bottom:3px solid #000; font-weight:bold; padding-bottom:10px; display:block;"><?= $title ?></p>



<div class="section s1">
	<p>Email Address:</p>
    <p><input type="email" id="u_email" style="text-transform: lowercase;" class="form-control" /></p>
    <p class="result">&nbsp;</p>
    <a href="javascript:next_section()" class="btn btn-funnel">Next</a><span class="enter">or press <b>ENTER</b></span>
</div>

<div class="section s2" style="display:none;">
	<p>First Name:</p>
    <p><input type="text" id="u_fname" class="form-control" /></p>
    <p class="result">&nbsp;</p>
    <a href="javascript:next_section()" class="btn btn-funnel">Next</a><span class="enter">or press <b>ENTER</b></span>
</div>

<div class="section s3" style="display:none;">
	<p>Last Name:</p>
    <p><input type="text" class="form-control" id="u_lname" /></p>
    <p class="result">&nbsp;</p>
    <a href="javascript:next_section()" class="btn btn-funnel">Next</a><span class="enter">or press <b>ENTER</b></span>
</div>

<div class="section s4" style="display:none;">
	<p><i class="fa fa-envelope-open-o" aria-hidden="true"></i> Check Your Email</p>
	<br />
	<p>We emailed you a unique bootcamp application link to continue.</p>
	<p>You can close this window.</p>
</div>
