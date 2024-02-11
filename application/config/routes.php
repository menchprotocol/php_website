
MENCH
Routes
Copy/Paste the following code in routes.php
<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['translate_uri_dashes'] = FALSE;
$route['default_controller'] = "view/index"; //Redirects to default app
$route['404_override'] = 'view/app_load'; //Page not found

$route['CheatSheet'] = "view/app_load/13450";
$route['uploader'] = "view/app_load/42363";
$route['InteractionType'] = "view/app_load/4593";
$route['reminder'] = "view/app_load/42216";
$route['routes'] = "view/app_load/42006";
$route['ServerTime'] = "view/app_load/41286";
$route['contacts'] = "view/app_load/40947";
$route['TreeLister'] = "view/app_load/40355";
$route['signapp'] = "view/app_load/32603";
$route['EmailImporter'] = "view/app_load/35983";
$route['gameplay'] = "view/app_load/33292";
$route['tree'] = "view/app_load/13900";
$route['apps'] = "view/app_load/6287";
$route['RegularExpressionRemove'] = "view/app_load/32103";
$route['templates'] = "view/app_load/31077";
$route['checkin'] = "view/app_load/31076";
$route['terms'] = "view/app_load/14373";
$route['mytickets'] = "view/app_load/26560";
$route['RegularExpressionMatch'] = "view/app_load/26611";
$route['responses'] = "view/app_load/13980";
$route['SelectionTimeout'] = "view/app_load/28199";
$route['SnoozingSubscriber'] = "view/app_load/28917";
$route['notifications'] = "view/app_load/28904";
$route['MultiApply'] = "view/app_load/27196";
$route['ReceivedSMS'] = "view/app_load/27901";
$route['LoginAs'] = "view/app_load/27238";
$route['paypalapp'] = "view/app_load/27004";
$route['paypalpayment'] = "view/app_load/26595";
$route['messenger'] = "view/app_load/26582";
$route['guest'] = "view/app_load/14938";
$route['cache'] = "view/app_load/14599";
$route['CleanupDeletedIdeas'] = "view/app_load/14573";
$route['home'] = "view/app_load/14565";
$route['SocialLoginCallback'] = "view/app_load/14564";
$route['notFound'] = "view/app_load/14563";
$route['ledger'] = "view/app_load/4341";
$route['SocialLogin'] = "view/app_load/14436";
$route['communities'] = "view/app_load/13207";
$route['setupaccount'] = "view/app_load/14517";
$route['logout'] = "view/app_load/7291";
$route['login'] = "view/app_load/4269";
$route['sheet'] = "view/app_load/13790";
$route['tokens'] = "view/app_load/13602";
$route['InteractionID'] = "view/app_load/4367";
$route['cron'] = "view/app_load/7274";
$route['gephiSync'] = "view/app_load/7278";
$route['SyncSearchIndex'] = "view/app_load/7279";
$route['reports'] = "view/app_load/12114";
$route['weights'] = "view/app_load/12569";
$route['or'] = "view/app_load/7712";
$route['SourceRandomAvatars'] = "view/app_load/12738";
$route['discoveryInfo'] = "view/app_load/12733";
$route['SourceIdeaSyncPrivacy'] = "view/app_load/12732";
$route['SourceSearchReplace'] = "view/app_load/12730";
$route['InteractionMetadataView'] = "view/app_load/12722";
$route['memory'] = "view/app_load/4527";
$route['phpInfo'] = "view/app_load/12709";
$route['IdeaOrphaned'] = "view/app_load/7260";
$route['IdeaDuplicates'] = "view/app_load/7261";
$route['icons'] = "view/app_load/7267";
$route['SourceDuplicates'] = "view/app_load/7268";
$route['SourceOrphaned'] = "view/app_load/7269";


$route['@([a-zA-Z0-9]+)'] = "view/e_layout/$1"; //Source
$route['~([a-zA-Z0-9]+)'] = "view/i_layout/$1"; //Ideate
$route['([a-zA-Z0-9]+)/([a-zA-Z0-9]+)'] = "view/x_layout/$1/$2"; //Discovery Sequence
$route['([a-zA-Z0-9]+)'] = "view/x_layout/0/$1/0"; //Discovery Single

