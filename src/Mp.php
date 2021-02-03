<?php

namespace src;

use MercadoPago\Item;
use MercadoPago\Preference;
use MercadoPago\SDK;

class Mp
{
    private $token = 'APP_USR-6317427424180639-042414-47e969706991d3a442922b0702a0da44-469485398';
    private $urlChekoutJs = 'https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js';
    private $orderId = '1234';





    public function getBotonPago(array $settings): string
    {
        // https://www.mercadopago.com.ar/developers/es/guides/payments/web-payment-checkout/integration/

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);



        SDK::setAccessToken($this->token);


        // Crea un objeto de preferencia
        $preference = new Preference();


        $dirName = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_DIRNAME);
        $baseUrl = "{$_SERVER['REQUEST_SCHEME']}://{$_SERVER['SERVER_NAME']}{$dirName}";

        $item = new Item();
        $item->id = $this->orderId;
        $item->title = $settings['title'];
        $item->description = 'Dispositivo móvil de Tienda e-commerce';
        $img = preg_replace('~\.\/~', '',$settings['img']);
        $item->picture_url = "{$baseUrl}/{$img}";
        $item->quantity = $settings['unit'];
        $item->unit_price = $settings['price'];
        $items[] = $item;

        $preference->items = $items;
        $preference->external_reference = 'marcos.botta@gmail.com';
        $preference->notification_url = "{$baseUrl}/webhook.php";


        $preference->payment_methods = array(
            "excluded_payment_types" => array(
                array("id" => "ticket")
            )
        );
        $preference->save();

        $backUrl = "{$baseUrl}/success.php";

        return '
<div class="text-center">
<form action="' . $backUrl  . '" method="POST">
  <script
   src="' . $this->urlChekoutJs . '"
   data-preference-id="' . $preference->id . '">
  </script>
</form>
</div><br>';
        return '<button type="submit" class="mercadopago-button" formmethod="post">Pagar</button>';
    }




}
