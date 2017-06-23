Steps for App Creation:

1. First copy the sample App or the App you want to the server.
2. Go to your partners account and provide following details to the field:
	App url : Full server path of oauth.php
	Redirect Url :  Full server path of settings.php

	Proxy url : Full server path of proxy_frontend.php

3. Make changes to config.php:
    SHOPIFY_APP_API_KEY : Your App Key you get from partners account on app creation
    SHOPIFY_APP_SHARED_SECRET : Your App Secret Key you get from partners account
    SHOPIFY_SITE_URL : Full server path to your Apps main directory

4. Edit the admin_dashboard.php to prepare the App Backend.

5. Edit the appload.js and proxy_frontend.php (proxy file) to show something on the store front (Apps front-end)

6. Install the app by hitting the following url:
	Full Server path to Apps main directory/install.php?shop= YOUR SHOP NAME
  
