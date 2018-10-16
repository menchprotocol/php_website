<script>

    function withdraw_enrollment(ru_id){

        //Confirm that they want to do this:
        var r = confirm("Are you sure you want to withdraw your application?");
        if (!(r == true)) {
            return false;
        }

        //Show loader:
        $('#process_withdrawal_'+ru_id).html('<img src="/img/round_load.gif" class="loader" style="width:24px !important; height:24px !important;" /> Processing...').hide().fadeIn();

        //Save the rest of the content:
        $.post("/my/withdraw_enrollment", {

            u_id:<?= $_GET['u_id'] ?>,
            u_key:'<?= $_GET['u_key'] ?>',
            ru_id:ru_id,

        } , function(data) {

            //OK, what happened?
            if(data.status){

                //Withdrawal was successful

                //Update UI to confirm with user:
                $('#hide_post_withdrawal_'+ru_id).fadeOut();
                $('#withdraw_update_'+ru_id).html(data.message).hide().fadeIn();

                //Reload tooldip:
                $('[data-toggle="tooltip"]').tooltip();

            } else {
                //There was an error, show to user:
                $('#process_withdrawal_'+ru_id).html('<b style="color:#FF0000;">'+data.message+'</b>');
            }

        });

    }

    $(document).ready(function() {

        var b_enrollment_redirect_url = '<?= $b_enrollment_redirect_url ?>';
        if(b_enrollment_redirect_url.length>0){
            //We have a URL to redirect to as requested by Coach:
            $('#application_status').html('<img src="/img/round_load.gif" class="loader" />');
            window.location.href = b_enrollment_redirect_url;
        }

    });

</script>

<?php
echo '<div id="application_status" style="text-align:left !important; padding-left:5px !important;">';
echo '<h3>'.$udata['u_full_name'].' Bootcamps</h3>';


if(count($enrollments)>0 && is_array($enrollments)){

    //Show all Student enrollments:
    foreach($enrollments as $enrollment){

        //Fetch Subscription Data:
        $bs = fetch_action_plan_copy($enrollment['ru_b_id'],$enrollment['r_id']);
        $enrollment_active = ( $enrollment['ru_status']>=0 && $bs[0]['b_status']>=2 );

        echo '<div class="enrollment_block">';

            echo '<div class="enrollment_checklist">';

                echo '<p><b title="Subscription ID '.$enrollment['ru_id'].'"><i class="fas fa-cube"></i> '.$bs[0]['c_outcome'].'</b></p>';

                //Show date:
                echo '<p style="font-size: 0.9em;"><i class="fas fa-calendar" style="margin: 0 2px 0 3px;"></i> ';

                    echo 'Not Selected';

                echo '</p>';

                //Account, always created at this point:
                echo '<div class="checkbox" style="margin-top:20px;"><label style="text-decoration:line-through;"><input type="checkbox" disabled checked> Step 1: Initiate Subscription</label></div>';

                //Do they need to take the assessment?
                $step = 2;
                if($bs[0]['b_requires_assessment']){
                    //We have a assessment:
                    echo '<div class="checkbox"><label><input type="checkbox" disabled '.( $enrollment['ru_assessment_result']>0 ? 'checked' : '' ).'> '.( $enrollment['ru_assessment_result']>0 ? '<span style="text-decoration: line-through;">' : '<a href="/'.$bs[0]['b_url_key'].'/assessment?u_email='.$enrollment['u_email'].'">' ).'Step '.$step.': Pass Instant Assessment'.( $enrollment['ru_assessment_result']>0 ? '</span>' : ' <i class="fas fa-chevron-right"></i></a>' ).'</label></div>';
                    $step++;
                }


                //Student need to complete the Checkout process:
                echo '<div class="checkbox"><label><input type="checkbox" disabled '.( $enrollment['ru_status']>=4 ? 'checked' : '' ).'> '.( $enrollment['ru_status']>=4 || !$enrollment_active ? '<span style="text-decoration: line-through;">' : '<a href="/'.$bs[0]['b_url_key'].'/enroll?u_email='.$enrollment['u_email'].'">' ).'Step '.$step.': Choose Support Package'.( $enrollment['ru_status']>=4 || !$enrollment_active ? '</span>' : ' <i class="fas fa-chevron-right"></i></a>' ).'</label></div>';
        $step++;

            echo '</div>';



            //More info like Bootcamp URL:
            echo '<div class="enrollment_footer">';
                echo '<span id="withdraw_update_'.$enrollment['ru_id'].'">'.echo_status('ru',$enrollment['ru_status'],0,'top').'</span>';
                echo '<a href="/toupdate"> | <i class="fas fa-cube"></i> Bootcamp Overview</a>';
                if(in_array($enrollment['ru_status'],array(0,4)) && (!$start_unix || $start_unix>time())){
                    //They can still withdraw their application:
                    echo '<span id="hide_post_withdrawal_'.$enrollment['ru_id'].'"> | <a href="javascript:void(0);" title="'.$start_unix.'" onclick="withdraw_enrollment('.$enrollment['ru_id'].')"><i class="fas fa-times-hexagon"></i> Withdraw</a> <span id="process_withdrawal_'.$enrollment['ru_id'].'"></span></span>';
                }
            echo '</div>';


        echo '</div>';




        if($enrollment['ru_upfront_pay']>0 && $enrollment['ru_status']<4 && $enrollment['ru_status']>=0 && isset($_GET['pay_ru_id']) && intval($_GET['pay_ru_id'])==intval($enrollment['ru_id'])){
            //TODO Remove logic
        }
    }

} else {

    echo '<br /><div class="alert alert-info maxout" role="alert"><i class="fas fa-exclamation-triangle"></i> No applications found</div>';

}

echo '</div>';
?>