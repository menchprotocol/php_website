<script>

    function ru_withdraw(ru_id){

        //Confirm that they want to do this:
        var r = confirm("Are you sure you want to withdraw your application?");
        if (!(r == true)) {
            return false;
        }

        //Show loader:
        $('#process_withdrawal_'+ru_id).html('<img src="/img/round_load.gif" class="loader" style="width:24px !important; height:24px !important;" /> Processing...').hide().fadeIn();

        //Save the rest of the content:
        $.post("/api_v1/ru_withdraw", {

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

        var b_thankyou_url = '<?= $b_thankyou_url ?>';
        if(b_thankyou_url.length>0){
            //We have a URL to redirect to as requested by Instructor:
            $('#application_status').html('<img src="/img/round_load.gif" class="loader" />');
            window.location.href = b_thankyou_url;
        }

    });

</script>

<?php
echo '<div id="application_status" style="text-align:left !important; padding-left:5px !important;">';
echo '<h3>'.$udata['u_fname'].' '.$udata['u_lname'].' Bootcamps</h3>';


if(count($admissions)>0 && is_array($admissions)){

    //Show all Student admissions:
    foreach($admissions as $admission){

        //Fetch Admission Data:
        $bs = fetch_action_plan_copy($admission['ru_b_id'],$admission['r_id']);
        $admission_active = ( $admission['ru_status']>=0 && $bs[0]['b_status']>=2 );

        //Fetch Live Bootcamp Data:
        $live_bs = $this->Db_model->b_fetch(array(
            'b_id' => $admission['ru_b_id'],
        ));

        echo '<div class="admission_block">';

            echo '<div class="admission_checklist">';

                echo '<p><b title="Admission ID '.$admission['ru_id'].'"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$bs[0]['c_objective'].'</b></p>';
                //Show date:
                echo '<p style="font-size: 0.9em;"><i class="fa fa-calendar" aria-hidden="true"></i> ';
                if(isset($bs[0]['b_is_parent']) && $bs[0]['b_is_parent']){

                    //Should have some child Bootcamps:
                    $child_intents = $this->Db_model->cr_outbound_fetch(array(
                        'cr.cr_inbound_c_id' => $bs[0]['b_outbound_c_id'],
                        'cr.cr_status >=' => 0,
                        'c.c_status >=' => 0,
                        'ru.ru_u_id' => $admission['ru_u_id'],
                    ), array('ru'));

                    if(count($child_intents)>0){

                        //Fetch start date for first Class:
                        $classes = $this->Db_model->r_fetch(array(
                            'r.r_id' => $child_intents[0]['ru_r_id'],
                        ));

                        echo time_format($classes[0]['r_start_date'],2).' - '.trim(time_format($classes[0]['r_start_date'],2, ((count($child_intents)*7*24*3600)-(12*3600)))).' ('.count($child_intents).' Weeks)';
                    } else {
                        echo 'Dates not yet selected';
                    }

                } else {
                    echo time_format($bs[0]['this_class']['r_start_date'],2).' - '.trim(time_format($bs[0]['this_class']['r__class_end_time'],2)).' (1 Week)';
                }
                echo '</p>';

                //Account, always created at this point:
                echo '<div class="checkbox" style="margin-top:20px;"><label style="text-decoration:line-through;"><input type="checkbox" disabled checked> Step 1: Admission Initiated</label></div>';


                //Student need to complete the Checkout process:
                echo '<div class="checkbox"><label><input type="checkbox" disabled '.( $admission['ru_status']>=4 ? 'checked' : '' ).'> '.( $admission['ru_status']>=4 || !$admission_active ? '<span style="text-decoration: line-through;">' : '<a href="/my/checkout_complete/'.$admission['ru_id'].'?u_key='.$u_key.'&u_id='.$u_id.'">' ).'Step 2: Submit Your Application to Join Bootcamp'.( $admission['ru_status']>=4 || !$admission_active ? '</span>' : ' <i class="fa fa-chevron-right" aria-hidden="true"></i></a>' ).'</label></div>';


                //Messenger activation for Active Bootcamps only
                if($bs[0]['b_status']>=2){
                    $bot_title = 'Step 3: Activate Your Facebook Messenger';
                    if($admission['u_cache__fp_psid']>0){
                        echo '<div class="checkbox"><label style="text-decoration: line-through;"><input type="checkbox" disabled checked> '.$bot_title.'</label></div>';
                    } else {
                        echo '<div class="checkbox"><label><input type="checkbox" disabled> <a href="'.$this->Comm_model->fb_activation_url($admission['u_id'],$live_bs[0]['b_fp_id']).'"> '.$bot_title.' <i class="fa fa-chevron-right" aria-hidden="true"></i></a></label></div>';
                    }
                }

            echo '</div>';



            if(isset($bs[0]['b_is_parent']) &&$bs[0]['b_is_parent']){

                //Fetch the Child Bootcamp ID:
                echo '<ul class="child_admissions">';
                foreach($child_intents as $child_admission){
                    echo '<li>';
                    echo status_bible('ru',$child_admission['ru_status'],1,'right');
                    echo ' Week '.$child_admission['cr_outbound_rank'].': '.$child_admission['c_objective'];
                    echo '</li>';
                }
                echo '</ul>';

            }



            //More info like Bootcamp URL:
            echo '<div class="admission_footer">';
                echo '<span id="withdraw_update_'.$admission['ru_id'].'">'.status_bible('ru',$admission['ru_status'],0,'top').'</span>';
                echo '<a href="/'.$live_bs[0]['b_url_key'].'"> | <i class="fa fa-dot-circle-o" aria-hidden="true"></i> Bootcamp Overview</a>';
                if($admission['ru_status']==0){
                    //They can still withdraw their application:
                    echo '<span id="hide_post_withdrawal_'.$admission['ru_id'].'"> | <a href="javascript:void(0);" onclick="ru_withdraw('.$admission['ru_id'].')"><i class="fa fa-minus-circle" aria-hidden="true"></i> Withdraw</a> <span id="process_withdrawal_'.$admission['ru_id'].'"></span></span>';
                }
            echo '</div>';



        echo '</div>';




        if($admission['ru_final_price']>0 && $admission['ru_status']<4 && $admission['ru_status']>=0 && isset($_GET['pay_ru_id']) && intval($_GET['pay_ru_id'])==intval($admission['ru_id'])){
            ?>
            <script>
                $( document ).ready(function() {
                    $('#paypal_<?= $admission['ru_id'] ?>').submit();
                    //Hide content from within the page:
                    $('#application_status').html('<div style="text-align:center;"><img src="/img/round_load.gif" class="loader" /></div>');
                });
            </script>
            <form action="https://www.paypal.com/cgi-bin/webscr" id="paypal_<?= $admission['ru_id'] ?>" method="post" target="_top" style="display:none;">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="EYKXCMCJHEBA8">
                <input type="hidden" name="lc" value="US">
                <input type="hidden" name="item_name" value="<?= $bs[0]['c_objective'] ?>">
                <input type="hidden" name="item_number" value="<?= $admission['ru_id'] ?>">
                <input type="hidden" name="custom_b_id" value="<?= $admission['ru_b_id'] ?>">
                <input type="hidden" name="custom_u_id" value="<?= $u_id ?>">
                <input type="hidden" name="custom_u_key" value="<?= $u_key ?>">
                <input type="hidden" name="amount" value="<?= $admission['ru_final_price'] ?>">
                <input type="hidden" name="currency_code" value="USD">
                <input type="hidden" name="button_subtype" value="services">
                <input type="hidden" name="no_note" value="1">
                <input type="hidden" name="no_shipping" value="1">
                <input type="hidden" name="rm" value="1">
                <input type="hidden" name="return" value="https://mench.com/my/applications?status=1&purchase_value=<?= $admission['ru_final_price'] ?>&message=<?= urlencode('Payment received.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u_id ?>">
                <input type="hidden" name="cancel_return" value="https://mench.com/my/applications?status=0&message=<?= urlencode('Payment cancelled. You can manage your admission below.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u_id ?>">
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
            <?php
        }
    }

} else {

    echo '<br /><div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>  No applications found</div>';

}

echo '</div>';
?>