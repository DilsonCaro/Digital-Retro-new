<?php 

require 'vendor/autoload.php';
MercadoPago\SDK::setAccessToken(TOKEN_MP);

$preference = new MercadoPago\Preference();



$item = new MercadoPago\Item();
$item->id = "0001";
$item->title = "producto CDP";
$item->quantity = 1;
$item->unit_price = 15000;
$item->currency_id = "CLP";

$preference->items = array($item);

$preference->back_urls = array(
    "success" =>"http://localhost/DigitalRetroNew/usuario/captura.php",
    "failure" =>"http://localhost/DigitalRetroNew/usuario/fallo.php"
);

$preference->auto_return = "approved";
$preference->binary_mode = true;

$preference->save();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>

    <h3>Mercado Pago</h3>

    <div class="checkout-btn"></div>

    <script>
        const mp = new MercadoPago('TEST-9e1d4fb3-89d1-43a2-9df2-50582a6f106f',{
            locale: 'es-CL'
        });

        mp.checkout({
            preference:{
                id: '<?php echo $preference->id;?>'
            },
            render: {
                container: '.checkout-btn',
                label: 'Pagar con Mercado Pago'
            }
        })
    </script>
    
</body>
</html>