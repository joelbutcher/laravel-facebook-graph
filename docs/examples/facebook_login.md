# Facebook Login Example

This example demonstrates how to implement Facebook Login using the Laravel Facebook Graph SDK, based on the original example for [Facebook SDK V6 for PHP](https://github.com/joelbutcher/facebook-php-graph-sdk/blob/6.x/docs/examples/facebook_login.md).

## Example

In this example, we will be using two routes: one to generate the Facebook Login URL and one for the callback where Facebook will redirect the user after the login dialog is called.

> Before we start, make sure that you have added the  **Persistent Data Handlers** in the `AppServiceProvider`, You can find an example of how to do this [here](./docs/examples/persistent_data_storage.md)

## Route for generating Facebook Login URL

```php

use JoelButcher\Facebook\Facades\Facebook as FacebookFacade;

...

Route::get('facebook/login', function () {
    // set the permissions (scopes)
    // by default the email and public_profile permission are added
    // in the HandlesAuthentication trait

    $scopes = ['pages_manage_posts', 'pages_read_engagement', 'pages_show_list'];
    
    $loginUrl = FacebookFacade::getRedirect(route('facebook.callback'), $scopes);
    
    echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
});
```

## Route for Facebook callback

```php
use JoelButcher\Facebook\Facebook;
use JoelButcher\Facebook\Facades\Facebook as FacebookFacade;

...

Route::get('facebook/callback', function () {
    // this is how to get the token
    // make sur to save the token in the database
    // because you can't use this methods again unless 
    // you repeat the long proccess
    
    $token = FacebookFacade::getToken();


    // this is how to use the token
    
    $fb = app(Facebook::class);
    $fb->getFacebook()->setDefaultAccessToken($token);

    return $fb->getUser();
})->name('facebook.callback');

```

This will return a JSON object with the user's name and ID:


```json
{
    "name":"Facebook User",
    "id":"XXXXXXXXXXXXXXXX"
}
```