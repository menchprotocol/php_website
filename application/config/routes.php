<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['translate_uri_dashes'] = FALSE;
$route['default_controller'] = "view/index"; //Redirects to default app
$route['404_override'] = 'view/app_load'; //Page not found

$route['(?i)Sourcing'] = "view/app_load/42902";
$route['(?i)Discovery'] = "view/app_load/30795";
$route['(?i)Ideation'] = "view/app_load/33286";
$route['(?i)paypalpayment'] = "view/app_load/42875";
$route['(?i)SignApp'] = "view/app_load/42816";
$route['(?i)uploader'] = "view/app_load/42363";
$route['(?i)InteractionType'] = "view/app_load/4593";
$route['(?i)EventReminder'] = "view/app_load/42216";
$route['(?i)Routes'] = "view/app_load/42006";
$route['(?i)ServerTime'] = "view/app_load/41286";
$route['(?i)Contacts'] = "view/app_load/40947";
$route['(?i)TreeLister'] = "view/app_load/40355";
$route['(?i)EmailImporter'] = "view/app_load/35983";
$route['(?i)Gameplay'] = "view/app_load/33292";
$route['(?i)FlattenTree'] = "view/app_load/13900";
$route['(?i)Apps'] = "view/app_load/6287";
$route['(?i)Sourc93'] = "view/app_load/32103";
$route['(?i)Templates'] = "view/app_load/31077";
$route['(?i)CheckinApp'] = "view/app_load/31076";
$route['(?i)TermsofUse'] = "view/app_load/14373";
$route['(?i)Ticket'] = "view/app_load/26560";
$route['(?i)Sour215'] = "view/app_load/26611";
$route['(?i)TimeLimit'] = "view/app_load/28199";
$route['(?i)SnoozingSubscriber'] = "view/app_load/28917";
$route['(?i)NotificationLevel'] = "view/app_load/28904";
$route['(?i)MultiApply'] = "view/app_load/27196";
$route['(?i)ReceivedSMS'] = "view/app_load/27901";
$route['(?i)LoginAs'] = "view/app_load/27238";
$route['(?i)PayPalApp'] = "view/app_load/27004";
$route['(?i)Messenger'] = "view/app_load/26582";
$route['(?i)GuestLogin'] = "view/app_load/14938";
$route['(?i)CacheAppSeconds'] = "view/app_load/14599";
$route['(?i)CleanupDeletedIdeas'] = "view/app_load/14573";
$route['(?i)Home'] = "view/app_load/14565";
$route['(?i)SocialLoginCallback'] = "view/app_load/14564";
$route['(?i)PageNotFound'] = "view/app_load/14563";
$route['(?i)Ledger'] = "view/app_load/4341";
$route['(?i)SocialLogin'] = "view/app_load/14436";
$route['(?i)Communities'] = "view/app_load/13207";
$route['(?i)AccountSetup'] = "view/app_load/14517";
$route['(?i)logout'] = "view/app_load/7291";
$route['(?i)login'] = "view/app_load/4269";
$route['(?i)SpreadSheet'] = "view/app_load/13790";
$route['(?i)MonthlyTokens'] = "view/app_load/13602";
$route['(?i)InteractiveID'] = "view/app_load/4367";
$route['(?i)CronJobs'] = "view/app_load/7274";
$route['(?i)SyncGephi'] = "view/app_load/7278";
$route['(?i)SyncSearchIndex'] = "view/app_load/7279";
$route['(?i)Reports'] = "view/app_load/12114";
$route['(?i)Weights'] = "view/app_load/12569";
$route['(?i)ORIdeas'] = "view/app_load/7712";
$route['(?i)SourceRandomAvatars'] = "view/app_load/12738";
$route['(?i)DiscoveryTreeInfo'] = "view/app_load/12733";
$route['(?i)SourceIdeaSyncPrivacy'] = "view/app_load/12732";
$route['(?i)SourceSearchReplace'] = "view/app_load/12730";
$route['(?i)InteractionMetadataView'] = "view/app_load/12722";
$route['(?i)Memory'] = "view/app_load/4527";
$route['(?i)PhpInfo'] = "view/app_load/12709";
$route['(?i)IdeaOrphaned'] = "view/app_load/7260";
$route['(?i)IdeaDuplicates'] = "view/app_load/7261";
$route['(?i)IconSearch'] = "view/app_load/7267";
$route['(?i)SourceDuplicates'] = "view/app_load/7268";
$route['(?i)SourceOrphaned'] = "view/app_load/7269";


$route['@([a-zA-Z0-9]+)']           = "view/e_layout/$1"; //Source
$route['([a-zA-Z0-9]+)/([a-zA-Z0-9]+)']   = "view/x_layout/$1/$2"; //Discovery
$route['([a-zA-Z0-9]+)']                  = "view/i_layout/$1"; //Ideation
$route['~([a-zA-Z0-9]+)']                  = "view/i_layout/$1"; //Ideation
