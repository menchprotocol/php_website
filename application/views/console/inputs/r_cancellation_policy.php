<div class="title"><h4><i class="fa fa-shield" aria-hidden="true"></i> Refund Policy (for Paid Cohorts)</h4></div>
<?php 
$refund_policies = $this->config->item('refund_policies');
foreach($refund_policies as $type=>$terms){
    echo '<div class="radio">
	<label>
		<input type="radio" name="r_cancellation_policy" value="'.$type.'" '.( isset($r_cancellation_policy) && $r_cancellation_policy==$type ? 'checked="true"' : '' ).' />
		'.ucwords($type).'
	</label>
	<ul style="margin-left:15px;">';
    echo '<li>Full Refund: '.( $terms['full']>0 ? '<b>Before '.($terms['full']*100).'%</b> of the cohort\'s elapsed time' : ( $terms['prorated']>0 ? '<b>Before Start Date</b> of the cohort' : '<b>None</b> After Admission' ) ).'.</li>';
      echo '<li>Pro-rated Refund: '.( $terms['prorated']>0 ? '<b>Before '.($terms['prorated']*100).'%</b> of the cohort\'s elapsed time' : '<b>None</b> After Admission' ).'.</li>';
	echo '</ul></div>';
}
?>
<p>Students will always receive a full refund if you reject their application during the admission screeing process. Learn more about <a href="https://support.mench.co/hc/en-us/articles/115002095952" target="_blank" style="display:inline-block;">Refund Policies <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a>.</p>