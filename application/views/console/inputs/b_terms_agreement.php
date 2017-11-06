<div class="title" style="margin-top:15px;"><h4><i class="fa fa-thumbs-o-up" aria-hidden="true"></i> Lead Instructor Agreement</h4></div>
<ul>
	<li>I have read and understood how <a href="https://support.mench.co/hc/en-us/articles/115002473111" target="_blank"><u>Instructor Earning & Payouts <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a> work.</li>
	<li>I have read and understood my bootcamp's <a href="https://support.mench.co/hc/en-us/articles/115002080031" target="_blank"><u>Tuition Guarantee <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
	<li>I have read and understood the <a href="https://support.mench.co/hc/en-us/articles/115002096752" target="_blank"><u>Mench Code of Conduct <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
	<li>I have read and understood the <a href="https://support.mench.co/hc/en-us/articles/115002096732" target="_blank"><u>Mench Honor Code <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
	<li>I have read and agreed to Mench's <a href="/terms" target="_blank"><u>Terms of Service & Privacy Policy <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></u></a>.</li>
</ul>
<div class="form-group label-floating is-empty">
	<div class="checkbox">
	<label>
		<?php if(isset($b_terms_agreement_time) && strlen($b_terms_agreement_time)>0){ ?>
		<input type="checkbox" id="b_terms_agreement_time" disabled checked /> Agreed on <b><?= time_format($b_terms_agreement_time,0) ?> PST</b>
		<?php } else { ?>
		<input type="checkbox" id="b_terms_agreement_time" /> As the lead bootcamp instructor I certify that all above statements are true
		<?php } ?>
	</label>
</div>
</div>

