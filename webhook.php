<?php

$request = print_r($_REQUEST, true);
$post = print_r($_POST, true);
$get = print_r($_GET, true);


$log = "
<br>
<hr>
REQUEST<BR>
{$request}<br>
<br>
POST<BR>
{$post}<br>
<br>
GET<BR>
{$get}<br>
<br>";

file_put_contents('webhook.html', $log, FILE_APPEND);
