<?php

namespace src;

use MercadoPago\Item;
use MercadoPago\Preference;
use MercadoPago\SDK;

class Mp
{
    private $token = 'APP_USR-6317427424180639-042414-47e969706991d3a442922b0702a0da44-469485398';
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


        $order_id = $this->orderId;
        $item = new Item();
        $item->title = $settings['title'];
        $item->quantity = $settings['unit'];
        $item->unit_price = $settings['price'];
        $items[] = $item;

        $preference->items = $items;
        $preference->external_reference = $order_id;
        $dirName = pathinfo($_SERVER['REQUEST_URI'], PATHINFO_DIRNAME);

        $preference->notification_url = "{$_SERVER['SERVER_NAME']}{$dirName}/webhook.php";


        $preference->payment_methods = array(
            "excluded_payment_types" => array(
                array("id" => "ticket")
            )
        );
        $preference->save();




        return '
<div class="text-center">
<form action="/mercado-pago-procesar-pago.php" method="POST">
  <script
   src="https://www.mercadopago.com.ar/integrations/v1/web-payment-checkout.js"
   data-preference-id="' . $preference->id . '">
  </script>
</form>
</div><br>';
        return '<button type="submit" class="mercadopago-button" formmethod="post">Pagar</button>';
    }




}
