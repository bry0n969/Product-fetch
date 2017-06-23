<?php
header("Access-Control-Allow-Origin: *");
define('SHOPIFY_APP_API_KEY', 'b4d697f404daeb47ede952563cd6b51e');
define('SHOPIFY_APP_SHARED_SECRET', 'c342d563160899ac14d0e028d985bb57');

// SHOPIFY_SITE_URL = your App main directory Url
define('SHOPIFY_SITE_URL', 'https://sketchthemes.com/tech-shopify/sonali/product_App/');

// Create connection
$conn = mysqli_connect('localhost','sketcht_t-shop59','2L_$Tl+rm.*WN.d*v;','sketcht_tech-shopify5654');
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


?>