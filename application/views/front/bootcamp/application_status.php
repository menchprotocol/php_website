

<div>
	<p><b><?= $title ?>:</b></p>
    <br />
    <p><b>1.</b> Complete Application</p>
    <p><b>2.</b> Pay with CreditCard/PayPal</p>
    <p><b>3.</b> Connect to MenchBrain on Messenger</p>
    <a href="javascript:next_section()" class="btn btn-funnel">Let's Do It ></a><span class="enter">or press <b>ENTER</b></span>
</div>



<div class="checkbox"><label>
	<input type="checkbox" disabled checked>
	Account Created
</label></div>

<div class="checkbox"><label>
	<input type="checkbox" disabled>
	<a href="#">Submit Application Form ~5 Minutes</a>
</label></div>

<div class="checkbox"><label>
	<input type="checkbox" disabled>
	<a href="#">Submit Application Form ~5 Minutes</a>
</label></div>




<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="EYKXCMCJHEBA8">
    <input type="hidden" name="lc" value="US">
    <input type="hidden" name="item_name" value="Application Fee - Bootcamp Name">
    <input type="hidden" name="item_number" value="777">
    <input type="hidden" name="amount" value="80.00">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="button_subtype" value="services">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="rm" value="1">
    <input type="hidden" name="return" value="https://mench.co/success">
    <input type="hidden" name="cancel_return" value="https://mench.co/cancel">
    <input type="hidden" name="bn" value="PP-BuyNowBF:btn_paynowCC_LG.gif:NonHosted">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>