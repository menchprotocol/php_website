
* * * * * /usr/bin/php /var/www/platform/index.php note cron__sync_common_base
0,30 * * * * /usr/bin/php /var/www/platform/index.php note cron__sync_extra_insights

10,30,50 * * * * /usr/bin/php /var/www/platform/index.php source cron__inherit_icons

45 1 19 * * /usr/bin/php /var/www/platform/index.php read cron__sync_algolia
45 3 * * * /usr/bin/php /var/www/platform/index.php read cron__sync_gephi
45 6 * * * /usr/bin/php /var/www/platform/index.php read cron__clean_metadatas
45 9 * * * /usr/bin/php /var/www/platform/index.php read cron__weights
10 8 * * 1 /usr/bin/php /var/www/platform/index.php read cron__weekly_coins