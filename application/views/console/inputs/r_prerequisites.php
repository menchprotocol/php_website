<div class="title"><h4><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Prerequisites</h4></div>
<ul>
	<li>An optional list of requirements students must meet to join this cohort.</li>
	<li>We ask students to confirm all prerequisites during their application.</li>
</ul>
<div id="r_prerequisites"><?= (isset($r_prerequisites)?$r_prerequisites:null) ?></div>
<script> var r_prerequisites_quill = new Quill('#r_prerequisites', setting_listo); </script>