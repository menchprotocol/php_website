<?php 
foreach($enrollments as $enrollment){
    echo '<p><b>'.$enrollment['bootcamp']['c_objective'].':</b></p>';
    
    //Account, always created at this point:
    echo '<div class="checkbox"><label style="text-decoration:line-through;"><input type="checkbox" disabled checked> Account Created</label></div>';
    
    //Typeform Application:
    echo '<div class="checkbox"><label '.( strlen($enrollment['ru_application_survey'])>0 ? 'style="text-decoration: line-through;"' : '' ).'><input type="checkbox" disabled '.( strlen($enrollment['ru_application_survey'])>0 ? 'checked' : '' ).'> '.( strlen($enrollment['ru_application_survey'])>0 ? 'Application Submitted' : '<a href="https://mench.typeform.com/to/'.$enrollment['cohort']['r_typeform_id'].'?u_key='.$u_key.'&u_id='.$enrollment['u_id'].'&u_email='.$enrollment['u_email'].'&u_fname='.urlencode($enrollment['u_fname']).'">Pending Application <i class="fa fa-chevron-right" aria-hidden="true"></i></a>' ).'</label></div>';
    
    //Payment
    echo '<div class="checkbox"><label '.( $enrollment['ru_is_fully_paid']=='t' ? 'style="text-decoration: line-through;"' : '' ).'><input type="checkbox" disabled '.( $enrollment['ru_is_fully_paid']=='t' ? 'checked' : '' ).'> ';
        if($enrollment['ru_is_fully_paid']=='t'){
            echo 'Paid in Full';
        } else {
            echo '<a href="javascript:$(\'#paypal_'.$enrollment['ru_id'].'\').submit();">Pay $'.$enrollment['cohort']['r_usd_price'].' using CreditCard/Paypal <i class="fa fa-chevron-right" aria-hidden="true"></i></a>';
            ?>
            <form action="https://www.paypal.com/cgi-bin/webscr" id="paypal_<?= $enrollment['ru_id'] ?>" method="post" target="_top" style="display:none;">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="EYKXCMCJHEBA8">
                <input type="hidden" name="lc" value="US">
                <input type="hidden" name="item_name" value="Bootcamp Tuition Fee: Create and Launch an Online Course">
                <input type="hidden" name="item_number" value="15">
                <input type="hidden" name="amount" value="<?= ( $enrollment['cohort']['r_usd_price'] ? 1 : 1 ) ?>">
                <input type="hidden" name="currency_code" value="USD">
                <input type="hidden" name="button_subtype" value="services">
                <input type="hidden" name="no_note" value="1">
                <input type="hidden" name="no_shipping" value="1">
                <input type="hidden" name="rm" value="1">
                <input type="hidden" name="return" value="https://mench.co/application_status?status=1&message=<?= urlencode('Payment received.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u_id ?>">
                <input type="hidden" name="cancel_return" value="https://mench.co/application_status?status=0&message=<?= urlencode('Payment cancelled.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u_id ?>">
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
            <?php 
        }
    echo '</label></div>';
    
    $enrollment['u_fb_id'] = 0;
    //Facebook Messenger:
    echo '<div class="checkbox"><label '.( intval($enrollment['u_fb_id'])>0 ? 'style="text-decoration: line-through;"' : '' ).'><input type="checkbox" disabled '.( intval($enrollment['u_fb_id'])>0 ? 'checked' : '' ).'> '.( intval($enrollment['u_fb_id'])>0 ? 'Facebook Messenger Mench Assistant is Connected' : '<a href="https://www.messenger.com/t/askmench">Connect to Mench Assistant on Facebook Messenger <i class="fa fa-chevron-right" aria-hidden="true"></i></a>' ).'</label></div>';
    
    //Instructor Approval:
    echo '<div class="checkbox"><label '.( $enrollment['ru_status']==4 ? 'style="text-decoration: line-through;"' : '' ).'><input type="checkbox" disabled '.( $enrollment['ru_status']==4 ? 'checked' : '' ).'> '.( $enrollment['ru_status']==4 ? 'Approved By Instructor: You Are Enrolled!' : 'Pending Interview & Instructor Approval' ).'</label></div>';
    
    
    echo '<hr />';
}
?>