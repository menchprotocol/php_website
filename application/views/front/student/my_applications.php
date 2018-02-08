<?php 
echo '<div id="application_status" style="text-align:left !important; padding-left:5px !important;">';
echo '<h3>'.$udata['u_fname'].' '.$udata['u_lname'].' Bootcamp Applications</h3>';

//Reverse order, newest Class at top:
$admissions = array_reverse($admissions);

//Show all Student admissions:
foreach($admissions as $admission){
    
    //Determine the steps:
    $applied = ( strlen($admission['ru_application_survey'])>0 );
    $paid = ( count($admission['ru__transactions'])>0 );
    $botactivated = ( $admission['u_fb_id']>0 );

    echo '<div style="border:2px solid #000; padding:7px; margin-top:25px; border-radius:5px; background-color:#EFEFEF;">';
    
    echo '<p><b>'.$admission['c_objective'].'</b> ('.time_format($admission['r_start_date'],4).' - '.time_format($admission['r__class_end_time'],4).') Application:</p>';

    
    //Account, always created at this point:
    echo '<div class="checkbox"><label style="text-decoration:line-through;"><input type="checkbox" disabled checked> Step 1: Initiate Application</label></div>';

    
    //Apply Form:
    $qa_title = 'Step 2: Submit Application Questionnaire';
    if($applied){
        echo '<div class="checkbox"><label style="text-decoration: line-through;"><input type="checkbox" disabled checked> '.$qa_title.'</label></div>';
    } else {
        echo '<div class="checkbox"><label><input type="checkbox" disabled> <a href="/my/class_application/'.$admission['ru_id'].'?u_key='.$u_key.'&u_id='.$u_id.'"> '.$qa_title.' <i class="fa fa-chevron-right" aria-hidden="true"></i></a></label></div>';
    }

    
    if($admission['r_usd_price']>0){
        //Payment
        echo '<div class="checkbox"><label '.( $paid ? 'style="text-decoration: line-through;"' : '' ).'><input type="checkbox" disabled '.( $paid ? 'checked' : '' ).'> <a href="javascript:void(0)" '.($paid ? '' : 'onclick="$(\'#paypal_'.$admission['ru_id'].'\').submit()"').'>Step 3: Pay $'.$admission['r_usd_price'].' Bootcamp Tuition using Debit Card, Credit Card or <i class="fa fa-paypal" aria-hidden="true"></i> Paypal <i class="fa fa-chevron-right" aria-hidden="true"></i></a></label></div>';
        if(!$paid){
            ?>
            
            <?php if(isset($_GET['pay_r_id']) && intval($_GET['pay_r_id']) && intval($_GET['pay_r_id'])==intval($admission['r_id'])){ ?>
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
                <input type="hidden" name="item_name" value="<?= $admission['c_objective'] ?>">
                <input type="hidden" name="item_number" value="<?= $admission['ru_id'] ?>">
                <input type="hidden" name="custom_r_id" value="<?= $admission['r_id'] ?>">
                <input type="hidden" name="custom_u_id" value="<?= $u_id ?>">
                <input type="hidden" name="custom_u_key" value="<?= $u_key ?>">
                <input type="hidden" name="amount" value="<?= $admission['r_usd_price'] ?>">
                <input type="hidden" name="currency_code" value="USD">
                <input type="hidden" name="button_subtype" value="services">
                <input type="hidden" name="no_note" value="1">
                <input type="hidden" name="no_shipping" value="1">
                <input type="hidden" name="rm" value="1">
                <input type="hidden" name="return" value="https://mench.co/my/applications?status=1&message=<?= urlencode('Payment received.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u_id ?>">
                <input type="hidden" name="cancel_return" value="https://mench.co/my/applications?status=0&message=<?= urlencode('Payment cancelled. You can manage your admission below.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u_id ?>">
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
            
            <?php
        }
    }

    $bot_title = 'Step '.( $admission['r_usd_price']>0 ? '4' : '3' ).': Activate Your MenchBot on Facebook Messenger';
    if($botactivated){
        echo '<div class="checkbox"><label style="text-decoration: line-through;"><input type="checkbox" disabled checked> '.$bot_title.'</label></div>';
    } else {
        echo '<div class="checkbox"><label><input type="checkbox" disabled> <a href="'.messenger_activation_url('381488558920384',$admission['u_id']).'"> '.$bot_title.' <i class="fa fa-chevron-right" aria-hidden="true"></i></a></label></div>';
    }
    
    //Let them know the status of their application:
    echo '<div style="font-size: 0.7em;">Current Status: '.status_bible('ru',$admission['ru_status'],0,'top').'</div>';

    echo '</div>';
}
echo '</div>';
?>