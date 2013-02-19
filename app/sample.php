<?php

$header_info = get_headers('https://soundcloud.com/emilios1310/sleaze-the-worst-remix-ever',1);

echo "<pre>";
print_r($header_info);
echo "</pre>";

?>