<h1>Add Intelligence</h1>

<h2>Step 1: Define Source</h2>
<p>New intelligence needs to be refereced to you, a new external source or an existing external source.</p>

<ul class="nav nav-tabs" style="margin-bottom:20px;">
  <li role="presentation" class="add_tab tab_add_me active"><a href="javascript:toggle_tab('add_me');">Me</a></li>
  <li role="presentation" class="add_tab tab_add_new"><a href="javascript:toggle_tab('add_new');">New</a></li>
  <li role="presentation" class="add_tab tab_add_existing"><a href="javascript:toggle_tab('add_existing');">Existing</a></li>
</ul>



<form>
	<input type="hidden" name="add_type" id="add_type" value="add_me" />
	
	
	<div class="add_content add_me">
		<p>The intelligence I am about to add is from my own thoughts.</p>
	</div>
	
	
	<div class="add_content add_new" style="display:none;">
		<p>I am about to reference a new source using an external URL.</p>
		
		  <div class="form-group">
		    <label for="exampleInputEmail1">Source URL</label>
		    <input type="url" class="form-control" name="newSourceURL" id="newSourceURL" placeholder="http://">
		  </div>
	</div>
	
	<div class="add_content add_existing" style="display:none;">
		<p>I am about to reference a existing source already indexed on US.</p>
		
		<div class="form-group">
		    <label for="exampleInputEmail1">Search Current Sources</label>
		    <div class="input-group">
		      <div class="input-group-addon">&</div>
		      <input type="text" class="form-control" id="searchCurrentSources" name="searchCurrentSources">
		    </div>
		</div>
		
		<div class="alert alert-warning" role="alert">No source selected yet. Type above to start search.</div>
		
		
	</div>

	<button type="submit" class="btn btn-primary">Continue</button>
	<a href="/add/step2">Step 2</a>
</form>

<script type="text/javascript">
	function toggle_tab(add_type){
		$('.add_content').hide();
		$('.'+add_type).show();
		$('#add_type').val(add_type);
		$('.add_tab').removeClass('active');
		$('.tab_'+add_type).addClass('active');
	}
</script>