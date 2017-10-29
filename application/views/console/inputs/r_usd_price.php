<div class="title"><h4><i class="fa fa-usd" aria-hidden="true"></i> Admission Price</h4></div>
<ul>
	<li>Set based on bootcamp length and level of 1-on-1 support.</li>
	<li>We recommend charging $100-$200 per support hour.</li>
	<li>Enter "0" for free cohorts. Our commission is 15% for paid cohorts.</li>
</ul>
<div class="input-group">
	<span class="input-group-addon addon-lean">USD $</span>
	<input type="number" min="0" step="0.01" style="width:100px; margin-bottom:-5px;" id="r_usd_price" value="<?= isset($r_usd_price) && floatval($r_usd_price)>=0 ? $r_usd_price : null ?>" class="form-control border" />
</div>