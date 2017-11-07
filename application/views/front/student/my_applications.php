<?php 
echo '<div id="application_status" style="text-align:left !important; padding-left:5px !important;">';
echo '<h3>'.$udata['u_fname'].' '.$udata['u_lname'].' Bootcamp Applications</h3>';

foreach($admissions as $admission){
    
    //Determine the steps:
    $applied = (strlen($admission['ru_application_survey'])>0);
    //$paid = ( $admission['ru_paid_sofar']>=$admission['r_usd_price']); //The real deal
    $paid = ( $admission['ru_paid_sofar']>0);
    
    echo '<hr />';
    echo '<h4>'.$admission['c_objective'].'</h4>';
    echo '<p>Complete the following steps to apply to this bootcamp:</h4>';
    
    
    //Account, always created at this point:
    echo '<div class="checkbox"><label style="text-decoration:line-through;"><input type="checkbox" disabled checked> Step 1: Initiate Application</label></div>';
    
    
    //Typeform Application:
    echo '<div class="checkbox"><label '.( $applied ? 'style="text-decoration: line-through;"' : '' ).'><input type="checkbox" disabled '.( $applied ? 'checked' : '' ).'> <a href="'.( $applied ? 'javasript:void(0);' : typeform_url($admission['r_typeform_id'],$admission['r_id'],$admission) ).'"> Step 2: Submit Application <i class="fa fa-chevron-right" aria-hidden="true"></i></a></label></div>';

    
    if($admission['r_usd_price']>0){
        //Payment
        echo '<div class="checkbox"><label '.( $paid ? 'style="text-decoration: line-through;"' : '' ).'><input type="checkbox" disabled '.( $paid ? 'checked' : '' ).'> <a href="'.($paid ? 'javascript:void(0)' : 'javascript:$(\'#paypal_'.$admission['ru_id'].'\').submit()').';">Step 3: Initiate Payment for $'.$admission['r_usd_price'].' Tuition on <i class="fa fa-paypal" aria-hidden="true"></i> Paypal <i class="fa fa-chevron-right" aria-hidden="true"></i></a></label></div>';
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
                <input type="hidden" name="item_name" value="Bootcamp Tuition: <?= $admission['c_objective'] ?>">
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
                <input type="hidden" name="cancel_return" value="https://mench.co/my/applications?status=0&message=<?= urlencode('Payment cancelled.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u_id ?>">
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
            
            <?php
        }
    }
        
    
    
    //Instructor Approval:
    if($admission['ru_status']<4 && $applied && $paid){
        //Now let them know the status of their application:
        echo status_bible('ru',$admission['ru_status'],0,'top');
    } elseif($admission['ru_status']>=4 && isset($_GET['show_action_plan'])) {
        //The bootcamp has started, show the the link to it:
        //TODO This has issue because maybe they access it via their email URL and no Milestones psid is available
        //echo '<a href="/my/milestones/'.$admission['b_id'].'/'.$admission['c_id'].'" class="btn btn-black" style="font-size:0.8em;">Go to Milestones</a>';
    }
}
echo '</div>';
?>