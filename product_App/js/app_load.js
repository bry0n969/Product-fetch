jQuery(document).ready(function(){

  // hide the  default  loader title  if it comes

  $(".ui-loader.ui-loader-default").hide(0);

  // Load the content of proxy file (proxy_frontend) inside the appfrontend-div to store front

            if(jQuery(".appfrontend-div").length == 0) {
              jQuery('body').append( "<div class='appfrontend-div'></div>" );
              jQuery('.appfrontend-div').load('/apps/proxy_frontend');

// "app/proxy_frontend" is the proxy path you given during the app configuration in partners account and proxy url must be given the fullpath of the file whose content you want to load on the store front end.


          }

});