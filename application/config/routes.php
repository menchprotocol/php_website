<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['translate_uri_dashes'] = FALSE;
$route['default_controller'] = "view/index"; //Redirects to default app
$route['404_override'] = 'view/app_load'; //Page not found

$route['SignApp'] = "view/app_load/42816";
$route['uploader'] = "view/app_load/42363";
$route['InteractionType'] = "view/app_load/4593";
$route['EventReminder'] = "view/app_load/42216";
$route['Routes'] = "view/app_load/42006";
$route['ServerTime'] = "view/app_load/41286";
$route['Contacts'] = "view/app_load/40947";
$route['TreeLister'] = "view/app_load/40355";
$route['EmailImporter'] = "view/app_load/35983";
$route['Gameplay'] = "view/app_load/33292";
$route['FlattenTree'] = "view/app_load/13900";
$route['Apps'] = "view/app_load/6287";
$route['Sourc93'] = "view/app_load/32103";
$route['Templates'] = "view/app_load/31077";
$route['CheckinApp'] = "view/app_load/31076";
$route['TermsofUse'] = "view/app_load/14373";
$route['Ticket'] = "view/app_load/26560";
$route['Sour215'] = "view/app_load/26611";
$route['YourResponse'] = "view/app_load/13980";
$route['TimeLimit'] = "view/app_load/28199";
$route['SnoozingSubscriber'] = "view/app_load/28917";
$route['NotificationLevel'] = "view/app_load/28904";
$route['MultiApply'] = "view/app_load/27196";
$route['ReceivedSMS'] = "view/app_load/27901";
$route['LoginAs'] = "view/app_load/27238";
$route['PayPalApp'] = "view/app_load/27004";
$route['paypalpayment'] = "view/app_load/26595";
$route['Messenger'] = "view/app_load/26582";
$route['GuestLogin'] = "view/app_load/14938";
$route['CacheAppSeconds'] = "view/app_load/14599";
$route['CleanupDeletedIdeas'] = "view/app_load/14573";
$route['HomePage'] = "view/app_load/14565";
$route['SocialLoginCallback'] = "view/app_load/14564";
$route['PageNotFound'] = "view/app_load/14563";
$route['Ledger'] = "view/app_load/4341";
$route['SocialLogin'] = "view/app_load/14436";
$route['Communities'] = "view/app_load/13207";
$route['AccountSetup'] = "view/app_load/14517";
$route['logout'] = "view/app_load/7291";
$route['login'] = "view/app_load/4269";
$route['SpreadSheet'] = "view/app_load/13790";
$route['MonthlyTokens'] = "view/app_load/13602";
$route['InteractionID'] = "view/app_load/4367";
$route['CronJobs'] = "view/app_load/7274";
$route['SyncGephi'] = "view/app_load/7278";
$route['SyncSearchIndex'] = "view/app_load/7279";
$route['Reports'] = "view/app_load/12114";
$route['Weights'] = "view/app_load/12569";
$route['ORIdeas'] = "view/app_load/7712";
$route['SourceRandomAvatars'] = "view/app_load/12738";
$route['DiscoveryTreeInfo'] = "view/app_load/12733";
$route['SourceIdeaSyncPrivacy'] = "view/app_load/12732";
$route['SourceSearchReplace'] = "view/app_load/12730";
$route['InteractionMetadataView'] = "view/app_load/12722";
$route['Memory'] = "view/app_load/4527";
$route['PhpInfo'] = "view/app_load/12709";
$route['IdeaOrphaned'] = "view/app_load/7260";
$route['IdeaDuplicates'] = "view/app_load/7261";
$route['IconSearch'] = "view/app_load/7267";
$route['SourceDuplicates'] = "view/app_load/7268";
$route['SourceOrphaned'] = "view/app_load/7269";


$route['@([a-zA-Z0-9]+)'] = "view/e_layout/$1"; //Source
$route['~([a-zA-Z0-9]+)'] = "view/i_layout/$1"; //Ideate
$route['([a-zA-Z0-9]+)/start'] = "view/x_layout/0/$1"; //Discovery Sequence
$route['([a-zA-Z0-9]+)/([a-zA-Z0-9]+)'] = "view/x_layout/$1/$2"; //Discovery Sequence
$route['([a-zA-Z0-9]+)'] = "view/x_layout/0/$1/0"; //Discovery Single
