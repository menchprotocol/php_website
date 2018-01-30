<?php
//Determine lock down status:
$current_applicants = count($this->Db_model->ru_fetch(array(
    'ru.ru_r_id'	    => $class['r_id'],
    'ru.ru_status >='	=> 0, //Anyone who has started an applications
)));
$disabled = ( $current_applicants>0 || $class['r_status']>=2 ? 'disabled' : null );
$website = $this->config->item('website');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato|Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons|Titillium+Web:700" />
    <link href="/css/lib/jquery.schedule.min.css" rel="stylesheet" />
    <script src="/js/lib/jquery.schedule.min.js"></script>
    <link href="/css/front/styles.css?v=<?= $website['version'] ?>" rel="stylesheet" />
    <style type="text/css">
    * {
        font-family: "Lato", "Helvetica", "Arial", sans-serif;
    }
    a {
        border: none;
        border-radius: 3px;
        position: relative;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0;
        will-change: box-shadow, transform;
        font-size: 15px;
        background-color: #fedd16;
        font-weight: bold;
        color: #000;
        box-shadow: 0 2px 2px 0 rgba(230, 224, 27, 0.14), 0 3px 1px -2px rgba(230, 224, 27, 0.14), 0 1px 5px 0 rgba(230, 224, 27, 0.14);
        padding: 6px 12px;
        text-decoration: none !important;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        display:inline-block;
    }
    </style>
</head>
<body style="margin:-15px -15px;">



<table width="100%" style="display:none;"><tr>
	<td style="text-align:right; width:120px; padding-right:20px;">
		<a href="javascript:void(0)" onclick="$('#schedule').jqs('reset')" style="background-color: #CCC;">Clear All</a>
	</td>
</tr></table>

<div id="schedule" class="jqs-demo <?= $disabled ?>"></div>



<script>
	function save_hours(){		
		$.post("/api_v1/update_schedule", { r_id:<?= $class['r_id'] ?> , hours : jQuery.parseJSON( $("#schedule").jqs('export') ) } , function(data) {
			//Update UI to confirm with user:
			$('.save_oa_update').html(data).hide().fadeIn();
	    });
	}
	
    $(function () {
        $("#schedule").jqs({
            mode: "<?= ( $disabled ? 'read' : 'edit' ) ?>",
            confirm: false,
            hour: 12,
            days: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            data: <?= json_encode(unserialize($class['r_live_office_hours'])) ?>
        });        
    });
</script>

</body>
</html>