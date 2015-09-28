<html>
<head>
    <script>
    var fbLoggedIn = false;
    var elleLoggedIn = true;

        window.fbAsyncInit = function() {

                FB.init({
                  appId      : '225127250995550',
                  status     : true,
                  xfbml      : true
                });

        FB.Event.subscribe('comment.create', function(response) { alert('create'); });
        FB.Event.subscribe('comment.remove', function(response) { alert('delete');});

/*
        FB.getLoginStatus(function(response) {
          if (response.status === 'connected') {
            var uid = response.authResponse.userID;
            var accessToken = response.authResponse.accessToken;
            fbLoggedIn = true;
          } else if (response.status === 'not_authorized') {
            fbLoggedIn = true;
          } else {
            fbLoggedIn = false;
          }
         });
*/

              };
        (function(d, s, id){
           var js, fjs = d.getElementsByTagName(s)[0];
           if (d.getElementById(id)) {return;}
           js = d.createElement(s); js.id = id;
           js.src = "//connect.facebook.net/ru_RU/all.js";
           fjs.parentNode.insertBefore(js, fjs);
         }(document, 'script', 'facebook-jssdk'));
        var noFacebook = false;
    </script>
</head>
<body>
<div class="fb-comments" data-href="http://www.oblivionmachine.com" data-numposts="10" data-order-by="reverse_time" data-colorscheme="light" data-notify="true" data-width="690">
                    Загрузка...</div>
</body>
</html>