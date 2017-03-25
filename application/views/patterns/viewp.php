<?php //print_r($pattern) ?>

<h1 style="margin:5px 0 15px 0;">#<?= $pattern['p_hashtag'] . ($pattern['p_id']>0 ? '<a style="padding: 5px 6px 0 12px; font-size:18px; line-height: 0;" href="javascript:edit_pattern('.$pattern['p_id'].')"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a>' : '') ?></h1>
<h4 style="margin:15px 0 15px 0;"><?= ( strlen($pattern['p_keywords'])>0 ? $pattern['p_keywords'] : 'No Keywords' ) ?> (<?= $pattern['p_id'] ?>)</h4>






<?php if(count($pattern['parents'])>0){ ?>
<h2>Parents</h2>
<div class="list-group">
<?php } ?>
<?php 
foreach ($pattern['parents'] as $row){
	//TODO: Reflect status of the other patterns
	echo '<li class="list-group-item"><span class="badge" style="float: left; margin-right: 10px;"><a href="/patterns/'.$row['p_hashtag'].'"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> #'.$row['p_hashtag'].'</span></a>&nbsp;'.$row['link_reference_notes'].'</li>';
}
?>
<?php if(count($pattern['parents'])>0){ ?>
</div>
<?php } ?>





<h2 style="margin-top:40px;">Children</h2>
<div class="list-group" id="sortableChild">
<?php 
if(count($pattern['children'])>0){
	foreach ($pattern['children'] as $row){
		//TODO: Reflect status of the other patterns
		//TODO: Implement sorting later: <span class="glyphicon glyphicon-sort sort_handle spott" aria-hidden="true"></span>
		echo '<a href="/patterns/'.$row['p_hashtag'].'" class="list-group-item context-menu-one"><span class="badge"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></span>#'.$row['p_hashtag'].' <span style="color:#EFEFEF;">'.$row['p_id'].' </span></a>';
	}
} else {
	echo '<div class="alert alert-info" role="alert">No children, yet.</div>';
	//echo '<div class="alert alert-warning" role="alert">Nothing found.</div>';
}
echo '<div class="list-group-item ui-state-disabled" style="padding:2px 3px;">
		<form action="us/invite" method="GET">
		<div class="easy-autocomplete" style="width:320px;"><input type="text" class="form-control autocomplete_pattern" select-action="create_mirror" parent-scope="0" parent-id="485" required="required" name="pattern_search" style="font-size: 16px; border:0; border-radius:0; box-shadow:none; -webkit-box-shadow:none; -webkit-transition:none; transition:none;" placeholder="Add Pattern" id="eac-4555" autocomplete="off"><div class="easy-autocomplete-container" id="eac-container-eac-4555"><ul></ul></div></div>
		</form>
	</div>';
?>

</div>

<script>

function edit_pattern(id){
	//Fetch pattern details:
	$.getJSON( "/api/fetch_pattern/"+id , function( data ) {
		console.log(data);
		$('#lastEditedStats').html('Last edited by <a href="/us/'+data.p_creator+'">@'+data.p_creator+'</a> on '+data.p_last_edited);
		$('#editPattern .modal-title').html('Edit #'+data.p_hashtag);
		$('#patterHashtagEdit').val(data.p_hashtag);
		$('#edit_p_id').val(data.p_id);
		$('#edit_redirect_id').val(<?= $pattern['p_id'] ?>);
		$('#patternKeywords').val(data.p_keywords);
	});
	$('#editPattern').modal('toggle');
}


  $( function() {
	  $( "#sortableChild" ).sortable({
	      items: "a:not(.ui-state-disabled)",
	      update: function( event, ui ) {
	          //Code to update sort
	          //alert('updated list');
	      },
	  }).disableSelection();
  } );


  $(function(){
	    $.contextMenu({
	        selector: '.context-menu-one', 
	        build: function($trigger, e) {
	            // this callback is executed every time the menu is to be shown
	            // its results are destroyed every time the menu is hidden
	            // e is the original contextmenu event, containing e.pageX and e.pageY (amongst other data)
	            return {
	                callback: function(key, options) {
	                    var m = "clicked: " + key;
	                    this.
	                    window.console && console.log(m) || alert(m);
	                },
	                items: {
	                    "edit": {name: "Edit", icon: "edit"},
	                    "cut": {name: "Cut", icon: "cut"},
	                    "copy": {name: "Copy", icon: "copy"},
	                    "paste": {name: "Paste", icon: "paste"},
	                    "delete": {name: "Delete", icon: "delete"},
	                    "sep1": "---------",
	                    "quit": {name: "Quit", icon: function($element, key, item){ return 'context-menu-icon context-menu-icon-quit'; }}
	                }
	            };
	        }
	    });
	});

</script>



<div class="modal fade" tabindex="-1" role="dialog" id="editPattern">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Loading...</h4>
      </div>
      <div class="modal-body">
        
        
        
        
        
        <p id="lastEditedStats"></p>
        <form action="/api/edit_pattern" method="POST" id="patternEditForm">
          <input type="hidden" name="redirect_pattern_id" id="edit_redirect_id" value="" />
          <input type="hidden" name="p_id" id="edit_p_id" value="" />
		  <div class="form-group">
		    <label for="patterHashtagEdit">Hashtag</label>
		    <div class="input-group">
		      <div class="input-group-addon">#</div>
		      <input type="text" class="form-control" autocomplete="off" id="patterHashtagEdit" name="p_hashtag" value="">
		    </div>
		  </div>
		  
		  <div class="form-group">
		    <label for="patternKeywords">Keywords</label>
		    <textarea class="form-control" rows="3" id="patternKeywords" name="p_keywords"></textarea>
		  </div>
		  
		  
		  <!-- 
		  <div class="form-group">
		    <label for="mergeWith">Merge With</label>
		    <div class="input-group">
		      <div class="input-group-addon">#</div>
		      <input type="text" class="form-control" autocomplete="off" id="mergeWith" value="">
		    </div>
		  </div>
		   -->
		</form>


        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="$('#patternEditForm').submit();">Save</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
