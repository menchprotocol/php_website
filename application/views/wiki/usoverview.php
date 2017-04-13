<h1 class="boldi">Intelligence to Grow. Faster.</h1>
<p class="boldi">Us is a decentralized platform to store, organize and distribute knowledge that contributes to your most important goals. We're currently gathering intelligence on <b>growing a startup</b>.</p>
<h2 class="boldi">How Us works?</h2>

<div class="row featurelist">
  <div class="col-sm-4">
  	<!-- <div>&#128525;</div> -->
  	<div><img src="/img/icons/love.png" width="90" height="90" /></div>
	<b>Crowdsourcing</b>
	<p>We connect human intelligence to create AI.</p>
  </div>
  <div class="col-sm-4">
  	<div><img src="/img/icons/diamond.png" width="90" height="90" /></div>
	<b>Credibility Based</b>
	<p>We score data using author credentials & qualifications.</p>
  </div>
  <div class="col-sm-4">
    <div><img src="/img/icons/target.png" width="90" height="90" /></div>
  	<b>Goal-Driven</b>
  	<p>We correlate best-practices to specific, tangible goals.</p>
  </div>
</div>

<div class="row featurelist">
  <div class="col-sm-4">
    <div><img src="/img/icons/search.png" width="90" height="90" /></div>
  	<b>Personalized</b>
  	<p>2-Way interactions gives you data that matters.</p>
  </div>
  <div class="col-sm-4">
  	<div><img src="/img/icons/bolt.png" width="90" height="90" /></div>
  	<b>On-Demand</b>
  	<p>Instantly access actionable insights by searching goals.</p>
  </div>
  <div class="col-sm-4">
  	<div><img src="/img/icons/rainbow.png" width="90" height="90" /></div>
  	<b>For All of Us.</b>
  	<p>We are both <a href="https://github.com/usfoundation">open-source</a> & <a href="http://www2.gov.bc.ca/gov/content/employment-business/business/not-for-profit-organizations/societies">non-profit</a>.</p>
  </div>
</div>


<?php /*
<div class="row featurelist">
  <div class="col-sm-4">
  	<div><img src="/img/icons/mentor.png" width="90" height="90" /></div>
	<b>Mentorship</b>
	<p>Build relationships.<br />With people who care.</p>
  </div>
  <div class="col-sm-4">
    <div><img src="/img/icons/camera.png" width="90" height="90" /></div>
  	<b>Video Based</b>
  	<p>Live mentor video chats.<br />Short informative videos.</p>
  </div>
  <div class="col-sm-4">
  	<div><img src="/img/icons/wrench.png" width="90" height="90" /></div>
  	<b>Always Optimizing</b>
  	<p>We measure the A learning that is easier, faster and more effective.</p>
  </div>
</div>
*/?>



<p class="boldi" style="margin-top:75px;">Our mission is to <b>Extend Human Survival</b>.</p>
<p class="boldi">The question we're most concerned about is:
<br /><b>Would we humans be growing a million years from now?</b></p>
<p class="boldi">We believe leveraging our common intelligence would increase our chances.</p>

<?php if(!auth(1)){ ?>
<div class="list-group" style="margin-top:30px;">
	<?php /*
	<a href="/collectiveai" class="list-group-item"><span class="badge"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>Explore Collective AI</a>
	*/?>
	<a href="/join" class="list-group-item"><span class="badge"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>Join Us</a>
	<a href="/login" class="list-group-item"><span class="badge"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>Login</a>
</div>
<?php } else { user_nav(); } ?>
