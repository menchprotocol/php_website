<div class="title"><h4><i class="fa fa-facebook-official" aria-hidden="true"></i> Facebook Group</h4></div>
<ul>
	<li>Increase engagement by empowering students to interact with each other.</li>
	<li>Create a Private Facebook Group for each cohort and add group ID below.</li>
	<li>MenchBot invites students to join group at the start of the cohort.</li>
</ul>
<div class="input-group border">
	<span class="input-group-addon addon-lean">https://www.facebook.com/groups/</span><input type="number" min="0" step="1" placeholder="123456789012345" class="form-control social-input" id="r_facebook_group_id" maxlength="22" value="<?= (isset($r_facebook_group_id) && bigintval($r_facebook_group_id)>0 ? $r_facebook_group_id : '') ?>" />
</div>