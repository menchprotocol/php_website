<div class="title"><h4><i class="fa fa-binoculars" aria-hidden="true"></i> Bootcamp Overview</h4></div>
<ul>
	<li>Give more context on your <b style="display:inline-block;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> primary goal</b>.</li>
	<li>Give an overview of how you would help players to win.</li>
    <li>Will be displayed on landing page right below the title.</li>
</ul>
<div id="c_todo_overview"><?= ( isset($c_todo_overview) ? $c_todo_overview : null ) ?></div>
<script> var c_todo_overview_quill = new Quill('#c_todo_overview', setting_full); </script>