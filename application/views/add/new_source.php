<h1>Add Intelligence</h1>

<h2>Step 2: New Source Details</h2>
<p>Give US more insights on the new source you're adding.</p>


<form>
	<input type="hidden" name="add_type" id="add_type" value="add_me" />
	
	
	  <div class="form-group">
	    <label for="exampleInputEmail1">Source URL</label>
	    <input type="url" readonly="readonly" class="form-control" id="sourceURL" value="http://yahoo.com/article">
	  </div>
	  
	  <div class="form-group">
	    <label for="exampleInputEmail1">Title</label>
	    <input type="text" class="form-control" id="sourceURL" value="http://yahoo.com/article">
	  </div>
	  
	  <div class="form-group">
	    <label for="exampleInputEmail1">Description</label>
	    <input type="url" readonly="readonly" class="form-control" id="sourceURL" value="http://yahoo.com/article">
	  </div>
	  
	  <div class="form-group">
	    <label for="exampleInputEmail1">Publish Date</label>
	    <input type="url" readonly="readonly" class="form-control" id="sourceURL" value="http://yahoo.com/article">
	  </div>

	<button type="submit" class="btn btn-primary">Continue</button>
	<a href="/add/step3">Step 3</a>
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