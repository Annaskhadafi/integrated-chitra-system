<?php
$str = file_get_contents('https://www.scmcpdelivery.com/wp-json/gravityview/v1/views/778/entries.json');
$json = json_decode($str, TRUE);

print_r($json);
// echo $json[0]['name'];

?>