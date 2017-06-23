<?php 
header('Access-Control-Allow-Origin: *');
session_start();
error_reporting(0);
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
require __DIR__.'/conf.php';
$shop = isset($_SESSION['shop']) ? $_SESSION['shop'] : $_GET['shop'];
if(!$shop){
	$shop = $_REQUEST['storeshopname'];
}

http_response_code(200);
header('HTTP/1.0 200 OK');
header("Status: 200 OK");
define('SHOPIFY_APP_SECRET', SHOPIFY_APP_SHARED_SECRET);

if(!empty($_SESSION['shop'])){
        $shop = $_SESSION['shop'];
      }
      else{
        $shop = $_SESSION['shop'] = $_GET['storeshopname'];
      }
      // $shop = $_SESSION['shop']?:$_GET['shop'];

    if(!empty($_SESSION['oauth_token'])){
        $oauth_token = $_SESSION['oauth_token'];
      }
      else{
        $dir = split("/",getcwd());
        $config_table_name = "shopify_App_".end($dir);
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


$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$productData = file_get_contents('php://input');
$verified = verify_webhook($productData, $hmac_header);


function verify_webhook($data, $hmac_header){
    $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
    return ($hmac_header == $calculated_hmac);
}
	if($verified){
			$str = '';
			$product_arr = array();
			$current_page = ($_GET["page"])?($_GET["page"]):1;
			$product_count = $shopify('GET /admin/products/count.json');
			$totalpage = ceil($product_count/250);
			for($i=1; $i<=$totalpage; $i++){
			  $select_product[] = $shopify('GET /admin/products.json?limit=250=&page='.$i.'');
			}
			foreach ($select_product as $key => $value) {
			  foreach ($value as $index => $value1) {
			    $all_array[] = $value1 ;
			  }
			}
				$file = 'upload/'.$shop.'.json';
				exec('rm -r -f '.$file);
				file_put_contents($file,json_encode($all_array));
				header("Content-type: application/json");
				header('Content-Disposition: attachment; filename="'.basename($file).'"'); 
				header('Content-Length: ' . filesize($file));
				readfile($file);
			}
	

?>




