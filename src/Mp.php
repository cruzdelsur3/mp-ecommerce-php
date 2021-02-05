<?php

namespace src;

use MercadoPago\Item;
use MercadoPago\Payer;
use MercadoPago\Preference;
use MercadoPago\SDK;

class Mp
{
    private $token = 'APP_USR-6317427424180639-042414-47e969706991d3a442922b0702a0da44-469485398';
    private $urlChekoutJs = 'https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js';
    private $orderId = '1234';




    public function getPaymentJson(string $id, string $topic): string
    {
        if ($id === '' ||
            $topic === 'payment ') {
            return "no es pago: {$id} {$topic}";
        }

        $url = "https://api.mercadopago.com/v1/payments/{$id}?access_token={$this->token}";

        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $jsonText = curl_exec($ch);
        curl_close($ch);

        return $jsonText;
    }


    public function getBotonPago(array $settings): string
    {
        // https://www.mercadopago.com.ar/developers/es/guides/payments/web-payment-checkout/integration/

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);



        SDK::setAccessToken($this->token);
        SDK::setIntegratorId('dev_24c65fb163bf11ea96500242ac130004');

        // Crea un objeto de preferencia
        $preference = new Preference();


        $dirName = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_DIRNAME);
        $baseUrl = "https://{$_SERVER['SERVER_NAME']}{$dirName}";

        $item = new Item();
        $item->id = $this->orderId;
        $item->title = $settings['title'];
        $item->description = 'Dispositivo móvil de Tienda e-commerce';
        $imgUrl = preg_replace('~\.\/~', '',$settings['img']);
        $item->picture_url = Format::cleanPath("{$baseUrl}/{$imgUrl}");
        $item->quantity = $settings['unit'];
        $item->unit_price = $settings['price'];
        $items[] = $item;

        $preference->items = $items;




        /*
         *  DATOS PAGADOR
         * Nombre y Apellido: Lalo Landa
Email: test_user_63274575@testuser.com
clave mp: qatest2417
tel 11  22223333
direccion false 123
codigo postal: 1111*/

        // $payer = new Payer();
        $payer = new Payer();
        $payer->name = "Lalo";
        $payer->surname = "Landa";
        $payer->email = "test_user_63274575@testuser.com";
        $payer->phone = [
            "area_code" => "11",
            "number" => "22223333"
        ];

        /*$payer->identification = array(
            "type" => "DNI",
            "number" => "12345678"
        );*/

        $payer->address = array(
            "street_name" => "False",
            "street_number" => 123,
            "zip_code" => "1111"
        );

        /*$payer->address = array(
            "street_name" => "Soler",
            "street_number" => 1554,
            "zip_code" => "3080"
        );*/

        // $payer->save();



        $preference->payer = $payer;

        $preference->external_reference = 'marcos.botta@gmail.com';
        $preference->notification_url = Format::cleanPath("{$baseUrl}/webhook.php");


        $backUrlSuccess = Format::cleanPath("{$baseUrl}/success.php");
        $backUrlFail = Format::cleanPath("{$baseUrl}/fail.php");
        $backUrlPending = Format::cleanPath("{$baseUrl}/pending.php");



        // echo $backUrl;
        $preference->back_urls = [
            'success' => $backUrlSuccess,
            'failure' => $backUrlFail,
            'pending' => $backUrlPending,
        ];
        $preference->auto_return = 'approved'; // redirige automaticamente a la pagina luego de un pago exitoso

        $preference->payment_methods = array(
            "excluded_payment_methods" => array(
                array("id" => "amex"),
            ),
            "excluded_payment_types" => array(
                array("id" => "atm"),
            ),
            "installments" => 6
        );


        $preference->save();




        $preferenceId = $preference->id;
        file_put_contents('id.html', "ID: {$preferenceId}<br>
", FILE_APPEND);

        // var_dump($preference);

        return '<a href="' . $preference->init_point . '">Pagar la compra</a>';

        return '
<div class="text-center">
<form action="' . $backUrlSuccess  . '" method="POST">
  <script
   src="' . $this->urlChekoutJs . '"
   data-button-label="Pagar la compra"
   data-preference-id="' . $preferenceId . '">
  </script>
</form>
</div><br>';
    }




}
