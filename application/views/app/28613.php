<?php

$default_email = get_domain_setting(28614);
$default_phone = get_domain_setting(28615);
$default_map = get_domain_setting(28617);

echo '<p style="text-align: center; line-height:150%; display: block;">You can call us at <a href="tel:'.$default_phone.'"><u><b>'.$default_phone.'</b></u></a> or email <a href="mailto:'.$default_email.'"><u><b>'.$default_email.'</b></u></a> to get in touch.</p>';

if($default_map){
    echo '<iframe src="https://www.google.com/maps/d/embed?mid='.$default_map.'" width="100%" height="480"></iframe>';
}