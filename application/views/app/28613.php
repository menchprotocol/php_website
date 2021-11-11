<?php

$from_email = get_domain_setting(28614);
$from_phone = get_domain_setting(28615);

echo 'You can call us at <a href="tel:'.$from_phone.'">'.$from_phone.'</a> or email <a href="mailto:'.$from_email.'">'.$from_email.'</a> to get in touch.';