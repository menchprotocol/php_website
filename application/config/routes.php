<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['translate_uri_dashes'] = FALSE;
$route['default_controller'] = "app/index"; //Redirects to default app
$route['404_override'] = 'app/load'; //Page not found


$route['(?i)GraphCleanup'] = "app/load/42912";
$route['(?i)Sourcing'] = "app/load/42902";
$route['(?i)Discovery'] = "app/load/30795";
$route['(?i)Ideation'] = "app/load/33286";
$route['(?i)paypalpayment'] = "app/load/42875";
$route['(?i)SignApp'] = "app/load/42816";
$route['(?i)uploader'] = "app/load/42363";
$route['(?i)InteractionType'] = "app/load/4593";
$route['(?i)EventReminder'] = "app/load/42216";
$route['(?i)Routes'] = "app/load/42006";
$route['(?i)ServerTime'] = "app/load/41286";
$route['(?i)Contacts'] = "app/load/40947";
$route['(?i)TreeLister'] = "app/load/40355";
$route['(?i)EmailImporter'] = "app/load/35983";
$route['(?i)Gameplay'] = "app/load/33292";
$route['(?i)FlattenTree'] = "app/load/13900";
$route['(?i)Apps'] = "app/load/6287";
$route['(?i)Sourc93'] = "app/load/32103";
$route['(?i)Templates'] = "app/load/31077";
$route['(?i)CheckinApp'] = "app/load/31076";
$route['(?i)TermsofUse'] = "app/load/14373";
$route['(?i)Ticket'] = "app/load/26560";
$route['(?i)Sour215'] = "app/load/26611";
$route['(?i)TimeLimit'] = "app/load/28199";
$route['(?i)SnoozingSubscriber'] = "app/load/28917";
$route['(?i)NotificationLevel'] = "app/load/28904";
$route['(?i)MultiApply'] = "app/load/27196";
$route['(?i)ReceivedSMS'] = "app/load/27901";
$route['(?i)LoginAs'] = "app/load/27238";
$route['(?i)PayPalApp'] = "app/load/27004";
$route['(?i)Messenger'] = "app/load/26582";
$route['(?i)GuestLogin'] = "app/load/14938";
$route['(?i)CacheAppSeconds'] = "app/load/14599";
$route['(?i)CleanupDeletedIdeas'] = "app/load/14573";
$route['(?i)Home'] = "app/load/14565";
$route['(?i)SocialLoginCallback'] = "app/load/14564";
$route['(?i)PageNotFound'] = "app/load/14563";
$route['(?i)Ledger'] = "app/load/4341";
$route['(?i)SocialLogin'] = "app/load/14436";
$route['(?i)Communities'] = "app/load/13207";
$route['(?i)AccountSetup'] = "app/load/14517";
$route['(?i)logout'] = "app/load/7291";
$route['(?i)login'] = "app/load/4269";
$route['(?i)SpreadSheet'] = "app/load/13790";
$route['(?i)MonthlyTokens'] = "app/load/13602";
$route['(?i)InteractiveID'] = "app/load/4367";
$route['(?i)CronJobs'] = "app/load/7274";
$route['(?i)SyncGephi'] = "app/load/7278";
$route['(?i)SyncSearchIndex'] = "app/load/7279";
$route['(?i)Reports'] = "app/load/12114";
$route['(?i)Weights'] = "app/load/12569";
$route['(?i)ORIdeas'] = "app/load/7712";
$route['(?i)SourceRandomAvatars'] = "app/load/12738";
$route['(?i)DiscoveryTreeInfo'] = "app/load/12733";
$route['(?i)SourceIdeaSyncPrivacy'] = "app/load/12732";
$route['(?i)SourceSearchReplace'] = "app/load/12730";
$route['(?i)InteractionMetadataView'] = "app/load/12722";
$route['(?i)Memory'] = "app/load/4527";
$route['(?i)PhpInfo'] = "app/load/12709";
$route['(?i)IdeaOrphaned'] = "app/load/7260";
$route['(?i)IdeaDuplicates'] = "app/load/7261";
$route['(?i)IconSearch'] = "app/load/7267";
$route['(?i)SourceDuplicates'] = "app/load/7268";
$route['(?i)SourceOrphaned'] = "app/load/7269";


$route['@([a-zA-Z0-9]+)']                 = "app/load/42902/$1"; //Source
$route['([a-zA-Z0-9]+)/([a-zA-Z0-9]+)']   = "app/load/30795/0/$2/$1"; //Target Idea / Discovery
$route['([a-zA-Z0-9]+)']                  = "app/load/33286/0/$1"; //Focus Idea / Ideation
