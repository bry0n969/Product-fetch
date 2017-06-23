<?php 
  session_start();
  error_reporting(1);
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
require __DIR__.'/conf.php';
  $instagram_table = "instagram_feed";
?>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
<script type="text/javascript">
    ShopifyApp.init({
      apiKey: '<?php echo SHOPIFY_APP_API_KEY; ?>',
        shopOrigin: 'https://<?php echo $_SESSION["shop"]; ?>',
    });
    var path = window.location.href;
    var new_path = (path.split("&tab"));
    var support_url = new_path[0]+"&tab=1";
    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }
        
    var selected = getParameterByName('tab',path);
    var show_select_two = '';
    var show_select_one = '';
    var show_select_three = '';
    if(selected==2){
      show_select_two = 'danger';
    }else{
      show_select_one = 'danger';
    }

// Note : This are the top buttons in your app

    ShopifyApp.ready(function(){
      ShopifyApp.Bar.initialize({
        title: 'By TechInfini',
        icon: '<?php  echo SHOPIFY_SITE_URL."App_admin_icon.png" ?>',
        buttons: { 
        // primary: {
          // Link Button that opens in a new window
        //     label: "About", 
        //     target: "new" 
        // },         
          secondary: [
            { 
              // Link Button that opens in a same window
              label: "Support", 
              href: support_url,
              style: show_select_one  
              
            },
            {
              label: "More Apps", 
              href: 'https://apps.shopify.com/', 
              target: "new"
            }
          ]
        }
      });
       ShopifyApp.Bar.loadingOff();
    });
    </script>
    
<?php 


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

//  Note : This is js file which will load the proxy file to your store front end
$app_load = SHOPIFY_SITE_URL."js/app_load.js";
$js_scripts = $shopify('POST /admin/script_tags.json', array(), 
    array
    (
    'script_tag' => 
      array
        (
        "event" => "onload",
        "src" => $app_load,
        )
    )
  );



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
file_put_contents($file, json_encode($all_array));
header("Content-type: application/json");
header('Content-Disposition: attachment; filename="'.basename($file).'"'); 
header('Content-Length: ' . filesize($file));

$shop_name = $shop;
$file_name = $file;
$file_path = SHOPIFY_SITE_URL.''.$file;

//Check for file in database

$check_file_query = "SELECT * FROM product_details_file WHERE shop ='$shop_name' ";
$check_file_exe = mysqli_query($conn, $check_file_query);

if (!(mysqli_num_rows($check_file_exe) > 0)) {
  $insert_details_query = "INSERT INTO product_details_file(shop_name,file_name,file_path) VALUES ('$shop_name','$file_name','$file_path')";
  $insert_details = mysqli_query($conn, $insert_details_query);
}

$add_web_hook = $shopify('POST /admin/webhooks.json', array('webhook' => 
array(
'topic'   => 'products/update',
'address' =>  'https://sketchthemes.com/tech-shopify/sonali/product_App/webhook.php?storeshopname='.$shop.'&k='.time(),
'format'  => 'json' 
)));


?>

<!-- Main Html (Write your code here) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- You can write css here on in the css file (css->app.css) and link it here -->
    <style type="text/css">
      div.adminbar , div.support {
       margin: 30px 10px 10px;
       padding: 20px 15px;
       background: #fff;
     }
    </style>

</head>
<?php $tab_to_display=$_GET['tab'];
?>
<body>
   <?php if($tab_to_display==1){?>
  <!-- You Have selected second tab in admin bar -->
  <!-- write html or php you want to show in Support tab -->

    <div class="support"> Support!... </div>

  <?php } else { ?>  
  <!-- no no tab is selected(Default is App Admin)  -->
  <!-- Write your App Admin panel code here (Main Code) -->

    <div class="adminbar">Sample App!...</div>


</body>

<!-- You can Write js code here or made a js file inside js folder and include it here -->
<script type="text/javascript">
  
  console.log("Wellcome to Sample App");

</script>
</html>

<?php } ?>