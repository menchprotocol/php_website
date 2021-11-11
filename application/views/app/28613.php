<?php

$from_email = get_domain_setting(28614);
$from_phone = get_domain_setting(28615);

echo '<p style="text-align: center; line-height:150%; display: block;">You can call us at <a href="tel:'.$from_phone.'"><u><b>'.$from_phone.'</b></u></a> or email <a href="mailto:'.$from_email.'"><u><b>'.$from_email.'</b></u></a> to get in touch.</p>';