<?php
session_start();
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
require __DIR__.'/conf.php';

if(!empty($_GET['shop']) && !empty($_GET['code'])){
  //Get app directory name
   $dir = split("/",getcwd());

  $shop = $_GET['shop']; //shop name

  //get permanent access token
  $access_token = shopify\access_token($_GET['shop'], SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_GET['code']);
  //save the signature and shop name to the current session
  $shopify_signature     = $_SESSION['shopify_signature'] = $_GET['signature'];
  $_SESSION['shop']        = $shop;
  $_SESSION['oauth_token'] = $access_token;

  //Create config table
  $config_table_name = "shopify_App_".end($dir);
  $create_table_sql ="CREATE TABLE IF NOT EXISTS ".$config_table_name." (
        id INT NOT NULL AUTO_INCREMENT,
        PRIMARY KEY(id),
        shop varchar(255),
        oauth_token varchar(255),
        status INT(122) NOT NULL DEFAULT 0
    )";

    if (mysqli_query($conn, $create_table_sql)) {
    // echo "Table MyGuests created successfully";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}

 $sql = "SELECT * FROM $config_table_name WHERE shop ='$shop'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) <= 0) {
     $sql = "INSERT INTO $config_table_name VALUES ('','$shop','$access_token','')";
} else {
     $sql = "UPDATE  $config_table_name SET oauth_token = '$access_token' WHERE shop ='$shop' ";
}
mysqli_query($conn, $sql);






  //app path 
   $current_script_name = split("/",$_SERVER['SCRIPT_NAME']);
    if (($key = array_search(end($current_script_name), $current_script_name)) !== false) {
        unset($current_script_name[$key]);
    }
    $app_path = implode("/", $current_script_name);
    
  $confirmation_url = "https://$shop/admin/apps/".SHOPIFY_APP_API_KEY.$app_path."/admin_dashboard.php";
  die("<script> top.location.href='$confirmation_url'</script>");

 
}