<script>

    $(document).ready(function() {

        $( "#ru_review_score" ).change(function() {
            if($( this ).val()>0){
                $('#hide_before_score').fadeIn();
            } else {
                $('#hide_before_score').hide();
            }
        });

    });

    function update_review(){
        if($( "#ru_review_score" ).val()<1){
            alert('Select a score from 1-10 to save your review');
            return false;
        }

        //Update the review:
        $('#save_review').html('<div style="padding-top:20px; padding-bottom:20px;"><img src="/img/round_load.gif" class="loader" /></div>');

        //Load the frame:
        $.post("/api_v1/update_review", {

            ru_id:<?= $ru_id ?>,
            ru_key:'<?= $ru_key ?>',
            ru_review_score:$("#ru_review_score").val(),
            ru_review_private_note:$("#ru_review_private_note").val(),
            ru_review_public_note:$("#ru_review_public_note").val(),

        }, function(data) {

            //Empty Inputs Fields if success:
            $('#save_review').html(data);

        });

    }

</script>

<?php

//See if they have already placed a review, and if so, load that data into view:
$has_reviewed = ( intval($admission['ru_review_score'])>0 );
$review_score_options = array(
    1 => '1 Not Likely At All :(',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10 Extremely Likely :)',
);


//Show Overview:
echo '<div class="maxout"><b>Lead Instructor</b>: '.$lead_instructor.'</div>';
if(count($admission['b__admins'])>1){
    echo '<div class="maxout"><b>Co-Instructor'.show_s((count($admission['b__admins'])-1)).'</b>: ';
    //We have assistant instructors, list them here:
    foreach($admission['b__admins'] as $key=>$assistant){
        if($key==0){
            //Skip this lead instructor:
            continue;
        } elseif($key>1){
            //Skip this lead instructor:
            echo ', ';
        }
        echo $assistant['u_fname'].' '.$assistant['u_lname'];
    }
    echo '</div>';
}
echo '<div class="maxout"><b>Bootcamp</b>: '.$admission['c_objective'].'</div>';
echo '<div class="maxout"><b>Class</b>: '.time_format($admission['r_start_date'],2).' - '.time_format($admission['r_cache__end_time'],2).'</div>';
echo '<div style="border-bottom:2px solid #000; margin:0 0 25px;">&nbsp;</div>';




//Show review inputs:
echo '<div id="save_review">';

    echo '<div class="maxout"><b><i class="fa fa-star" aria-hidden="true"></i> Review Score</b><br />From a scale of 1-10, how likely are you to recommend your Bootcamp experience to friends/family?</div>';
    echo '<select class="form-control input-mini border" id="ru_review_score">';
    if(!$has_reviewed){
        echo '<option value="0">Choose Score...</option>';
    }
    foreach($review_score_options as $val=>$name){
        echo '<option value="'.$val.'" '.( $admission['ru_review_score']==$val ? 'selected="selected"' : '' ).'>'.$name.'</option>';
    }
    echo '</select>';


    echo '<div id="hide_before_score" style="display:'.( !$has_reviewed ? 'none' : 'block' ).';">';

        echo '<div style="margin-top:25px;" class="maxout"><b><i class="fa fa-eye-slash" aria-hidden="true"></i> Private & Anonymous Feedback (Optional)</b><br />Share your thoughts/suggestions on how '.$lead_instructor.' can improve future Classes:</div>';
        echo '<textarea id="ru_review_private_note" class="form-textarea maxout">'.$admission['ru_review_private_note'].'</textarea>';


        echo '<div style="margin-top:25px;" class="maxout"><b><i class="fa fa-eye" aria-hidden="true"></i> Public Review (Optional)</b><br />Write a review for '.$lead_instructor.' to let his future/potential students know about your experience and what to expect:</div>';
        echo '<textarea id="ru_review_public_note" class="form-textarea maxout">'.$admission['ru_review_public_note'].'</textarea>';

    echo '</div>';

    echo '<br /><a href="javascript:void(0);" onclick="update_review()" class="btn btn-black">Submit Review</a>';

echo '</div>';


?>