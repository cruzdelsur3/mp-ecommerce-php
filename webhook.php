<?php

include_once ('./vendor/autoload.php');

date_default_timezone_set('America/Argentina/Buenos_Aires');

$request = print_r($_REQUEST, true);
$post = print_r($_POST, true);
$get = print_r($_GET, true);
$dateTime = date('Y-m-d H:i:s');

$bodyText = file_get_contents('php://input');


//  [id] => 2294363858 [topic] => merchant_order
//  [data_id] => 13494585540 [type] => payment
//  [id] => 13494585540 [topic] => payment
//  [id] => 2294363858 [topic] => merchant_order

$mp = new src\Mp();
$jsonPayment = 'no llama';
if (isset($_REQUEST['id']) &&
    isset($_REQUEST['topic'])) {
    $jsonPayment = $mp->getPaymentJson($_REQUEST['id'], $_REQUEST['topic']);
}



// echo var_dump(json_decode($jsonPayment)); die();

$log = "
<br>
<hr>
DATETIME: {$dateTime}<BR>
https://api.mercadopago.com/v1/payments/{id}?access_token={token}
REQUEST<BR>
{$request}<br>
<br>
POST<BR>
{$post}<br>
<br>
GET<BR>
{$get}<br>
<br>
BODY TEXT<BR>
{$bodyText}<br>
<br>
JSON<BR>
{$jsonPayment}<br>
<br>";

file_put_contents('webhook.html', $log, FILE_APPEND);
