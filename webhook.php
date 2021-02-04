<?php

include_once ('./vendor/autoload.php');

$request = print_r($_REQUEST, true);
$post = print_r($_POST, true);
$get = print_r($_GET, true);
$dateTime = date('Y-m-d H:i:s');
$body = file_get_contents('php //input');

$mp = new src\Mp();
$jsonPayment = $mp->getPaymentJson('13471893690', 'payment');

// echo var_dump(json_decode($jsonPayment)); die();

$log = "
<br>
<hr>
DATETIME: {$dateTime}<BR>
REQUEST<BR>
{$request}<br>
<br>
POST<BR>
{$post}<br>
<br>
GET<BR>
{$get}<br>
<br>
JSON<BR>
{$jsonPayment}<br>
<br>
BODY<BR>
{$body}<br>
<br>";



file_put_contents('webhook.html', $log, FILE_APPEND);
