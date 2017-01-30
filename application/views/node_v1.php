<?php
$header = '#USNetwork';
foreach($nds as $row){
	if($row['node_id']==$node_id && $row['parent']==2){
		$header = '#'.$row['value_string'];
	}
}
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://trello-attachments.s3.amazonaws.com/56663c0b94f2f4d85376ee1a/80x80/2477359e76f4b66fa7023f18148cbbe7/US_Network_Space.png">

    <title><?= $header ?></title>

    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Inconsolata" rel="stylesheet">
	<link href='//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
	<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>	
	<link href="/css/easy-autocomplete.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link href="/css/main.css" rel="stylesheet">
    <?php //TODO: Wire in based on session ?>
    <style> .modOnly{ display:block; } </style>
  </head>

  <body>
	<!-- Fixed navbar -->
	<?php /*
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/US"><img src="/img/US.png" width="50" height="50" /><!--  <span class="ai-logo">AI</span> --></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
          <!-- class="active" 
            <li><a href="/28">#Stories</a></li>
            <li><a href="/2">#Patterns</a></li>
            <li><a href="/30">#JoinUS</a></li>
            <li><a href="/31">#Login</a></li>-->
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    */?>
    
    
    
    
    
    <div class="container" style="padding-left:5px;">
		<footer class="outsider">
			<p>
	        <?php
	        $turnedon = false;
			foreach($nds as $row){
				if($row['node_id']==$node_id && $row['parent']==4){
					if($turnedon){
						echo '</p><p>';
						$turnedon = false;
					}
					echo '<a href="/'.$row['hashtag'].'">#'.$row['hashtag'].'</a>';
					if(strlen($row['value_string'])>0){
						echo $row['value_string'];
						$turnedon = true;
					}
				}
			}
			?>
	        </p>
		</footer>
	</div>
    
    
    
    
    <div class="container " role="main" id="main_container">
    <input type="hidden" id="current-node-id" value="<?= $node_id ?>" />

	<?php
	//Any messages to show?
	$pr = $this->session->flashdata('html_message');
	if($pr){
		echo '<div class="row mborc" style="padding-bottom:5px;"><div class="col-xs-12">'.$pr.'</div></div>';
	}
	?>






<?php
foreach($nds as $row){
	if($row['node_id']==$node_id && $row['parent']!=4){
		echo sprintf( $nds[$row['parent']]['meta_data'].''
			, $row['id'].''
			, $row['status'].''
			, $row['node_id'].''
			, $row['node_depth'].''
			, $row['parent'].''
			, date("D j M Y G:i A",strtotime(substr($row['time'],0,19))).''
			, $row['value_string'].''
			, $row['value_int'].''
			, $row['rank'].''
			, $row['meta_data'].''
		);
	}
}





echo '<div id="childHashtags" class="hashtagGroup">';
foreach($nds as $row){
	if($row['node_id']!==$node_id && $row['parent']==4){
		echo '<div><a href="/'.$row['hashtag'].'" node-id="'.$row['node_id'].'" class="hashtagLink">#'.$row['hashtag'].'</a>'.$row['value_string'].'</div>';
	}
}
echo '<div class="inputtext modOnly"><form action="/api/quick_link/" method="GET" _lpchecked="1" class="hashtagNewForm">
      		<input type="hidden" name="performaction" value="add_child">
      		<input type="hidden" name="currentnode" value="'.$node_id.'">
    		<div class="easy-autocomplete"><input type="text" class="form-control hashtagAddChild" required="required" name="hashtagName" placeholder="#..." autocomplete="off" parentscope="0" currentnode="'.$node_id.'" performaction="add_child"><div class="easy-autocomplete-container"><ul style="display: none;"></ul></div></div></form></div>';
echo '</div>';
?>





		
	</div> <!-- End #main_container -->
	
	
	<div class="container" style="padding-left:5px;">
		<footer class="outsider">
	        <p><a href="/US">#US</a><span>Open source micro education content management.</span></p>
	        <p><a href="/Signup">#JoinToGrow</a> or <a href="/Login">#Login</a>
	        <p>Hi <a href="/Shervin">#Shervin</a> Wanna <a href="/Logout">#Logout</a>?</p>
	        <?php /* if(is_pattern_admin()){ ?>
	        <a href="/USNetwork">#USNetwork</a><span>Unleash Your Potential.</span>
	       	<a href="/patterns/update_cache"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>Cache</a>
	        <?php } ?>
	        <?php if(ses_app_ver()){ ?>
	        <a href="/patterns/v/295"><span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>v<?= ses_app_ver() ?></a>
	        <?php } ?>
	        <?php if(!is_lazymeal_admin() || @$ses['pattern_user']['id']==2){ ?>
	        <a href="/patterns/logout"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>Logout</a>
	        <?php }*/ ?>
		</footer>
	</div>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
	<script src="/js/jquery.easy-autocomplete.min.js"></script>
	<script src="/js/Sortable.js"></script>
	
<script>
$.fn.editable.defaults.mode = 'inline';
$(document).ready(function() {
	//TODO: load editable if the user is admin:
    $('.editable').editable({
        escape: true,
        showbuttons: false,
        inputclass: 'edit_text_area',
        url: '/api/edit_value_string'
    });

    $('.editable').on('shown', function(e, editable) {
        //Set proper height for this box:
        $('.edit_text_area').css({"height":$(this).height()+"px"});
    });

    
    $('.editable').on('save', function(e, params) {
        //This would update the main ID to the newly inserted row
        if(params.response.parent==2){
            //This is a hashtag change, requires a page reload:
        	window.location = "/"+params.response.value_string;
        } else {
        	$(this).editable('option', 'pk', params.response.new_id);
        }
    });

    
    $( ".hashtagAddChild" ).focus(function() {
    	if($(this).val().length==0){
    		$(this).val('#');
    	}
    });

    $( ".hashtagAddChild" ).focusout(function() {
    	if($(this).val()=='#'){
    		$(this).val('');
    	}
    });
    

	//Give the screen a nice Fade in effect:
	//$('#main_container').hide().fadeIn();
	
	//Render auto complete inputs, if any:
	initiate_nodesearch_autocomplete(".hashtagAddChild");
});


/*
var list = document.getElementById("child-nodes");
var sortable_active = 1;
Sortable.create( list , {
  animation: 300,
  handle: ".sort_handle",
  onUpdate: function (evt){
	  //Loop through the current child patterns to determine current list order:
	  var patterns_sort = [];
	  $('#child-patterns a').each(function( index ) {
		  patterns_sort.push( $(this).attr('node-id') );
	  });
	  //Send the data for database updating:
	  $.post( "/api/update_sort/"+$('#current-node-id').val() , { sort: patterns_sort }).done(function( data ) {
		  console.log( data );
	  });
	}
});
*/


/*
 * 
Editable text image creator: http://www.text2image.com/html5_canvas.html 
*/

//A function for getCursorPosition()
(function ($, undefined) {
    $.fn.getCursorPosition = function() {
        var el = $(this).get(0);
        var pos = 0;
        if('selectionStart' in el) {
            pos = el.selectionStart;
        } else if('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
})(jQuery);







function extract_hashtag(inputstring,handler){
	var hshtgs = (inputstring.match(/#/g) || []).length; //Total number of hashtags found in the string
	var arr = inputstring.split('#');
	var pos = handler.getCursorPosition();
	
	return inputstring;
}



function initiate_nodesearch_autocomplete(object_selector){
	//Loop through all the auto complete fields on the page:
	$(object_selector).each(function() {
		var handler = $(object_selector);
		handler.easyAutocomplete({
			url: function(phrase) {
				return "/api/autocomplete/?parentscope="+handler.attr('parentscope')+"keyword="+extract_hashtag(phrase,handler);
			},
			getValue: function(element) {
				return element.value_string.replace(/(<([^>]+)>)/ig,"").trim();
			},
			requestDelay: 0, //Milliseconds
			highlightPhrase: true,
			/*placeholder: "Search for pattern ID",*/
			template: {
				type: "custom",
				method: function(value, item) {
					if(item.parent==2){
						return '<span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> #'+item.value_string;
					} else if(item.parent==7){
						return item.value_string;
					}
				}
			},
			list: {
				onChooseEvent: function() {
					var object_id = handler.getSelectedItemData().id;
					var object_id = handler.getSelectedItemData().id;
					if(object_id>0){
						switch (handler.attr('performaction')) {
						 case "add_parent":
						 case "add_child":
							window.location = "/api/quick_link/?performaction="+handler.attr('performaction')+"&currentnode="+handler.attr('currentnode')+"&hashtagId="+object_id;
							break;
						  default:
							//For now, default is to insert ID in the input field
							//alert( '"' + handler.getSelectedItemData().hashtag.replace(/(<([^>]+)>)/ig,"").trim() + '" selected with ID ' + object_id);
							//TODO: Later update to a fancier UI that shows name, and with click enables ID change
							handler.val(object_id);
						}
					}
				},
				match: {
					enabled: true
				},
				sort: {
					enabled: true
				}
			}
		});
	});
}

/*
 * Not sure what this does, disabling for now to see if anything breaks: Dec 29 2016 
 *
function textAreaAdjust(o) {
  o.style.height = "1px";
  o.style.height = (25+o.scrollHeight)+"px";
}
*/

//Google Analytics:
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-88365693-1', 'auto');
ga('send', 'pageview');
</script>


  </body>
</html>
