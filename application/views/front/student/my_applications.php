<script>

    function withdraw_application(ru_id){

        //Confirm that they want to do this:
        var r = confirm("Are you sure you want to withdraw your application?");
        if (!(r == true)) {
            return false;
        }

        //Show loader:
        $('#process_withdrawal_'+ru_id).html('<img src="/img/round_load.gif" class="loader" style="width:24px !important; height:24px !important;" /> Processing...').hide().fadeIn();

        //Save the rest of the content:
        $.post("/api_v1/withdraw_application", {

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
            $('#application_status').html('<img src="/img/round_yellow_load.gif" class="loader" />');
            window.location.href = b_thankyou_url;
        }

    });

</script>

<?php
echo '<div id="application_status" style="text-align:left !important; padding-left:5px !important;">';
echo '<h3>'.$udata['u_fname'].' '.$udata['u_lname'].' Bootcamps</h3>';

if(count($admissions)>0 && is_array($admissions)){

    //Reverse order, newest Class at top:
    $admissions = array_reverse($admissions);

    //Show all Student admissions:
    foreach($admissions as $admission){

        //Fetch Admission Data:
        $bs = fetch_action_plan_copy($admission['r_b_id'],$admission['r_id']);
        $class = $bs[0]['this_class'];
        $price_to_pay = calculate_total($admission);

        //Fetch Live Bootcamp Data:
        $live_bs = $this->Db_model->b_fetch(array(
            'b_id' => $admission['r_b_id'],
        ));

        echo '<div style="border:2px solid #000; padding:7px; margin-top:25px; border-radius:5px; background-color:#EFEFEF;">';

        echo '<p><b>'.$bs[0]['c_objective'].'</b></p>';

        //Account, always created at this point:
        echo '<div class="checkbox"><label style="text-decoration:line-through;"><input type="checkbox" disabled checked> Step 1: Admission Initiated for Class of '.time_format($class['r_start_date'],2).' - '.trim(time_format($class['r__class_end_time'],2)).'</label></div>';

        //Free Class, Student just needs to submit their Application:
        echo '<div class="checkbox"><label '.( $admission['ru_status']>=4 ? 'style="text-decoration: line-through;"' : '' ).'><input type="checkbox" disabled '.( $admission['ru_status']>=4 ? 'checked' : '' ).'> <a '.( $admission['ru_status']>=4 ? 'href="javascript:void(0)"' : 'href="/my/class_application/'.$admission['ru_id'].'?u_key='.$u_key.'&u_id='.$u_id.'"' ).'>Step 2: Submit Your Application to Join Bootcamp <i class="fa fa-chevron-right" aria-hidden="true"></i></a></label></div>';

        if($price_to_pay>0){

            //Fetch Payment:
            $total_paid = 0;
            $ru__transactions = $this->Db_model->t_fetch(array(
                't.t_ru_id' => $admission['ru_id'],
            ));
            foreach($ru__transactions as $t){
                $total_paid += $t['t_total'];
            }
            $unpaid_amount = doubleval($price_to_pay - $total_paid);

            //Payment
            //echo '<div class="checkbox"><label '.( !$unpaid_amount ? 'style="text-decoration: line-through;"' : '' ).'><input type="checkbox" disabled '.( !$unpaid_amount ? 'checked' : '' ).'> <a href="javascript:void(0)" '.( !$unpaid_amount ? '' : 'onclick="$(\'#paypal_'.$admission['ru_id'].'\').submit()"').'>Step 2: Pay $'.$unpaid_amount.( $total_paid>0 ? ' (Already Paid $'.$total_paid.') remaining' :'').' using Debit Card, Credit Card or <i class="fa fa-paypal" aria-hidden="true"></i> Paypal <i class="fa fa-chevron-right" aria-hidden="true"></i></a></label></div>';

            if($unpaid_amount>0){

                if(isset($_GET['pay_r_id']) && intval($_GET['pay_r_id']) && intval($_GET['pay_r_id'])==intval($admission['r_id'])){ ?>
                    <!-- Immediate redirect to paypal -->
                    <script>
                        $( document ).ready(function() {
                            $('#paypal_<?= $admission['ru_id'] ?>').submit();
                            //Hide content from within the page:
                            $('#application_status').html('<div style="text-align:center;"><img src="/img/round_yellow_load.gif" class="loader" /></div>');
                        });
                    </script>
                <?php } ?>

                <form action="https://www.paypal.com/cgi-bin/webscr" id="paypal_<?= $admission['ru_id'] ?>" method="post" target="_top" style="display:none;">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="EYKXCMCJHEBA8">
                    <input type="hidden" name="lc" value="US">
                    <input type="hidden" name="item_name" value="<?= $bs[0]['c_objective'] . ' - Class of ' . time_format($class['r_start_date'],2) ?>">
                    <input type="hidden" name="item_number" value="<?= $admission['ru_id'] ?>">
                    <input type="hidden" name="custom_r_id" value="<?= $admission['r_id'] ?>">
                    <input type="hidden" name="custom_u_id" value="<?= $u_id ?>">
                    <input type="hidden" name="custom_u_key" value="<?= $u_key ?>">
                    <input type="hidden" name="amount" value="<?= $unpaid_amount ?>">
                    <input type="hidden" name="currency_code" value="USD">
                    <input type="hidden" name="button_subtype" value="services">
                    <input type="hidden" name="no_note" value="1">
                    <input type="hidden" name="no_shipping" value="1">
                    <input type="hidden" name="rm" value="1">
                    <input type="hidden" name="return" value="https://mench.com/my/applications?status=1&purchase_value=<?= $unpaid_amount ?>&message=<?= urlencode('Payment received.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u_id ?>">
                    <input type="hidden" name="cancel_return" value="https://mench.com/my/applications?status=0&message=<?= urlencode('Payment cancelled. You can manage your admission below.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u_id ?>">
                    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
                    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal">
                    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
                <?php
            }
        }

        //Are they in the Classroom? If so, show them chat activation:
        if($admission['ru_p2_price']>0){
            $bot_title = 'Step 3: Activate Messenger to Chat with your Instructor';
            if($admission['u_cache__fp_psid']>0){
                echo '<div class="checkbox"><label style="text-decoration: line-through;"><input type="checkbox" disabled checked> '.$bot_title.'</label></div>';
            } else {
                echo '<div class="checkbox"><label><input type="checkbox" disabled> <a href="'.$this->Comm_model->fb_activation_url($admission['u_id'],$live_bs[0]['b_fp_id']).'"> '.$bot_title.' <i class="fa fa-chevron-right" aria-hidden="true"></i></a></label></div>';
            }
        }

        //Let them know the status of their application:
        echo '<div style="font-size: 0.7em;">Current Status: <span id="withdraw_update_'.$admission['ru_id'].'">'.status_bible('ru',$admission['ru_status'],0,'top').'</span></div>';

        //More info like Bootcamp URL:
        echo '<div style="font-size: 0.7em; margin-top:5px; padding-top:5px; border-top:2px solid #333;">';
        echo '<a href="/'.$live_bs[0]['b_url_key'].'"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Visit Bootcamp Page</a>';
        if($admission['ru_status']==0){
            //They can still withdraw their application:
            echo '<span id="hide_post_withdrawal_'.$admission['ru_id'].'"> | <a href="javascript:void(0);" onclick="withdraw_application('.$admission['ru_id'].')"><i class="fa fa-minus-circle" aria-hidden="true"></i> Withdraw from Bootcamp</a> <span id="process_withdrawal_'.$admission['ru_id'].'"></span></span>';
        }
        echo '</div>';

        echo '</div>';
    }

} else {

    echo '<br /><div class="alert alert-info maxout" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>  No applications found</div>';

}

echo '</div>';
?>