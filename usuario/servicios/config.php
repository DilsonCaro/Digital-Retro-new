<?php 

define("SITE_URL","http://localhost/DigitalRetroNew/usuario/");
define("CLIENT_ID","ATe8IdikWEG_1Y0CQ1hy_wJxTJzeVJI_VUtme9OQJjg3LU1REAZnhgveqCr5coxci_zchsEiCb6iHnWA");
define("TOKEN_MP","TEST-2883535294675377-061414-b70e132545d85297b4d1dbd94bfea979-180568168");
define("TOKEN_TEST","TEST-9e1d4fb3-89d1-43a2-9df2-50582a6f106f");
define("CURRENCY","USD");
define("KEY_TOKEN","APR.wqc-354*");
define("MONEDA","CLP$");
define("MONEDA2","USD$");

//Datos para enviar el correo

define("MAIL_HOST","smtp.gmail.com");
define("MAIL_USER","digitalretrocompany@gmail.com");
define("MAIL_PASS","woghdrwjzlhhowpq");
define("MAIL_PORT","465");

session_start();
$num_cart = 0;
if(isset($_SESSION['carrito']['productos'])){
    $num_cart = count($_SESSION['carrito']['productos']);
}
?>