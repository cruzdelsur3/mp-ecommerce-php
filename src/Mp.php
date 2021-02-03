<?php

namespace src;

class Mp
{
    public static function getBotonPago(array $settings): string
    {
        return '<button type="submit" class="mercadopago-button" formmethod="post">Pagar</button>';
    }
}
