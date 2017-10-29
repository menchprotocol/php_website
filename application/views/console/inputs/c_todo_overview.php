<div class="title"><h4><i class="fa fa-binoculars" aria-hidden="true"></i> Bootcamp Overview</h4></div>
<ul>
	<li>Give an overview of how you plan to help students accomplish the Primary Goal.</li>
    <li>Displayed at the top of the landing page right below the title.</li>
</ul>
<div id="c_todo_overview"><?= ( isset($c_todo_overview) ? $c_todo_overview : null ) ?></div>
<script> var c_todo_overview_quill = new Quill('#c_todo_overview', setting_full); </script>