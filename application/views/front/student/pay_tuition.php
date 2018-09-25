<?php

if(!isset($_GET['payment']) || doubleval($_GET['payment'])<1){

    //Show Header:


    //Show payment links

    echo '<span id="white_paypal"><img src="/img/paypal.png" /></span>';

} else {

    $application_status_salt = $this->config->item('application_status_salt');
    $u_key = md5($u['u_id'].$application_status_salt);

    ?>
    <script>
        $( document ).ready(function() {
            $('#paypal_<?= $enrollment['ru_id'] ?>').submit();
        });
    </script>

    <div style="text-align:center;"><img src="/img/round_load.gif" class="loader" /></div>

    <form action="https://www.paypal.com/cgi-bin/webscr" id="paypal_<?= $enrollment['ru_id'] ?>" method="post" target="_top" style="display:none;">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="EYKXCMCJHEBA8">
        <input type="hidden" name="lc" value="US">
        <input type="hidden" name="item_name" value="<?= $b['c_outcome'] ?>">
        <input type="hidden" name="item_number" value="<?= $enrollment['ru_id'] ?>">
        <input type="hidden" name="custom_b_id" value="<?= $enrollment['ru_b_id'] ?>">
        <input type="hidden" name="custom_u_id" value="<?= $u['u_id'] ?>">
        <input type="hidden" name="custom_u_key" value="<?=  $u_key ?>">
        <input type="hidden" name="amount" value="<?= doubleval($_GET['payment']) ?>">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="button_subtype" value="services">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="rm" value="1">
        <input type="hidden" name="return" value="https://mench.com/my/applications?status=1&purchase_value=<?= $enrollment['ru_upfront_pay'] ?>&message=<?= urlencode('Payment received.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u['u_id'] ?>">
        <input type="hidden" name="cancel_return" value="https://mench.com/my/applications?status=0&message=<?= urlencode('Payment cancelled. You can manage your enrollment below.'); ?>&u_key=<?= $u_key ?>&u_id=<?= $u['u_id'] ?>">
        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>

<?php } ?>