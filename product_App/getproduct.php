<?php
error_reporting(0);
session_start();
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
require __DIR__.'/conf.php';



//  Note :  This is mendatory code to make the $shopify object work currectly

    if(!empty($_SESSION['shop'])){
        $shop = $_SESSION['shop'];
      }
      else{
        $shop = $_SESSION['shop'] = $_GET['shop'];
      }
      // $shop = $_SESSION['shop']?:$_GET['shop'];

    if(!empty($_SESSION['oauth_token'])){
        $oauth_token = $_SESSION['oauth_token'];
      }
      else{
        $dir = split("/",getcwd());
        $config_table_name = "shopify_App_".end($dir);
       //  $select_sql = "SELECT * FROM $config_table_name WHERE shop ='$shop' ";

       //    $select_rs  =  mysql_query($select_sql);
       //    $no_rec     =  mysql_num_rows($select_rs);  
       //   if($no_rec <= 0){
       //   }
       // else{
       //  while ($row = mysql_fetch_assoc($select_rs)) {   
       //   $oauth_token = $row['oauth_token'];
       //        }
       //    }


            $sql = "SELECT * FROM $config_table_name WHERE shop ='$shop' ";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      $oauth_token = $row['oauth_token'];
    }
} else {
    // echo "0 results";
}


      }
    

$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY , $oauth_token);
?>

<!-- Your Html Which You Want to show on front end.You can write php code also -->

<div class="proxy-file" style="text-align: center; font-size:20px; color:red;padding: 10px"> Wellcome to App frontent  </div>
