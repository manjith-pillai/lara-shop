<?php

/*
|--------------------------------------------------------------------------
| Application RoutesuserDetails
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
 ]);


Route::group(array('prefix' => '/app'), function()
{
    Route::get('/add_to_cart/{id}/{quantity}', 'AppController@addToCart');
    Route::get('/verify_mobile', 'AppController@verifyMobile');
    Route::get('/status/{id}', 'AppController@getOrderDetailsForId');
    // Route::get('/logout', 'AppController@logout');
    Route::get('/updateCart', 'AppController@addOrUpdateCart');
    Route::post('/updateCart', 'AppController@addOrUpdateCart');
    Route::get('/rest_migration/', 'WelcomeController@rest_migration');
    Route::post('/userLogin/', 'Auth\AuthController@userLogin');
    Route::post('/registerUser/', 'Auth\AuthController@registerUser');
    Route::get('/sort_by/{city}/{lat}/{lng}/{type}/{sortedorderval}','AppController@sortBy');
    Route::get('/{city}/search/{text}/{lat}/{lng}','AppController@omniSearch');
    Route::get('/{city}/{name}', 'AppController@restaurantPageByUrlName');
    Route::get('/', 'AppController@index');
    Route::get('/cities', 'AppController@service_cities');
    Route::post('/devices/', 'AppController@saveDeviceInfo');
    Route::post('/delivery_boy_referral/', 'AppController@saveDeliveryBoyReferral');
    //route for add, update and delete address
    Route::post('/add_address/', 'AppController@addAddress');
    Route::post('/update_address/', 'AppController@updateAddress');
    Route::post('/delete_address/', 'AppController@deleteAddress');
    Route::get('/logout', 'Auth\AuthController@getLogout');
    Route::put('/update_profile/{user_id}', 'AppController@updateProfile');
    Route::post('/request_otp', 'AppController@sendOTP');
    Route::post('/verify_otp', 'AppController@verify_otp');
    Route::post('/order_feedback', 'AppController@orders_feedback');
    Route::post('/order_list', 'AppController@orderList');
    Route::post('/order_detail', 'AppController@OrderDetail');
    Route::post('/reorder', 'AppController@reOrder'); 
    Route::post('/fb_login', 'Auth\AuthController@fbLogin');
    Route::post('/change_password/', 'Auth\AuthController@changePassword');
    Route::post('/forget_password/', 'Auth\AuthController@forgetPassword');
    Route::post('/order_history', 'AppController@orderHistory');
    Route::post('/reset_password', 'AppController@resetPassword');
    Route::post('/contact_us', 'AppController@contactUs');
    Route::post('/ongoing_orders', 'AppController@ongoingOrders');
    Route::post('/past_order', 'AppController@pastOrder');
    
    
});
Route::get('/home', 'ApiController@index');


Route::get('/get_cart_details/', 'ApiController@getCartDetails');
Route::get('/add_to_cart/{id}/{quantity}', 'ApiController@addToCart');
// Route::get('/your_details/', 'ApiController@confirmCheckout');
Route::get('/success/', 'ApiController@confirmCheckout');
Route::get('/confirm_checkout/', 'ApiController@userDetails');
Route::get('/verify_mobile/{mobile}/{verify_code}', 'ApiController@verify_otp');
Route::get('/status/', 'ApiController@status');
Route::get('/logout/', 'Auth\AuthController@logout');
Route::get('/make_payment/', 'ApiController@makePayment');
Route::post('payu_payment_success','ApiController@payuPaymentSuccess');
Route::post('payu_payment_failure','ApiController@payuPaymentFailure');
Route::get('/rest_migration/', 'WelcomeController@rest_migration');
Route::post('/make_payment/', 'ApiController@makePayment');
Route::get('/payment_redirect',function(){
    return view('payment_redirect');
});

//Route::get('/orderp/{order_id}','ApiController@sendOrderConfirmationEmail');

Route::get('/myaccount','Account\AccountController@myaccount');
Route::get('/sort_by/{city}/{lat}/{lng}/{type}/{sortedorderval}', 'ApiController@sortBy');

Route::get('/contact_us', 'FooterController@showContactUsPage');
Route::get('/about_us', 'FooterController@showAboutUsPage');
Route::get('/terms_and_conditions', 'FooterController@showTncPage');
Route::get('/privacy_policy', 'FooterController@showPrivacyPolicyPage');
Route::get('/cancellations_and_refund', 'FooterController@showCnrPage');
Route::get('/shipping_delivery', 'FooterController@showShippingPage');
Route::get('/faq', 'FooterController@showFaqPage');
Route::get('/careers','FooterController@careerPage');
Route::get('/career-list','FooterController@careerList');
Route::get('/500','FooterController@error500Page');
Route::get('/404','FooterController@error404Page');
Route::get('/our_social_responsibilites', 'FooterController@showOsrPage');
Route::post('location_update', 'DeliveryBoyController@updateLocation');
Route::post('login', 'DeliveryBoyController@login');
Route::post('/delivery_boy_devices/', 'DeliveryBoyController@deliveryBoyDeviceInfo');
Route::post('new_order', 'DeliveryBoyController@newOrder');
Route::post('delivery_successful', 'DeliveryBoyController@deliverySuccessful');
Route::post('getPast', 'DeliveryBoyController@getPast');
Route::post('getPending', 'DeliveryBoyController@getPending');
Route::post('billingActivity', 'DeliveryBoyController@billingActivity');
Route::post('is_accept', 'DeliveryBoyController@isAccept');
Route::post('updateOrder', 'DeliveryBoyController@updateOrder');


//restro
Route::post('add_push_key', 'RestroAppController@addPushKey');
Route::post('send_notification', 'RestroAppController@sendNotification');
Route::post('get_order_details', 'RestroAppController@getOrderDetails');
Route::post('book_delivery','RestroAppController@bookDelivery');
Route::post('order_rejected','RestroAppController@orderRejected');
Route::post('order_history','RestroAppController@getHistory');
Route::post('order_status_counts', 'RestroAppController@getStatusCount');
Route::post('apply_filter', 'RestroAppController@applyFilter');
Route::post('sign_up', 'RestroAppController@signUp');
Route::post('sign_in', 'RestroAppController@signIn');
Route::get('/', 'ApiController@index');

Route::get('/{city}/{name}', 'ApiController@restaurantPageByUrlName');
Route::get('/{city}/search/{text}/{lat}/{lng}/','ApiController@omniSearch');

// for admin console
Route::Controller('admin/order','Admin\OrderController');
Route::post('order_history_update/{order_id}','Admin\OrderController@orderHistoryUpdate');




//If there is any new url with get method with 2 continues parameters separated by slash (/) it will call (Route::get('/{city}/{name}', 'ApiController@restaurantPageByUrlName'));


Route::post('checkout/paytm/response','ApiController@paytmPaymentResponse');
Route::get('checkout/paytm/response','ApiController@paytmPaymentResponse');


Route::get('system/migrate/rest','Admin\OrderController@migrate_restaurants');
Route::get('system/migrate/rest_taxes','Admin\OrderController@migrate_restaurant_taxes');

Route::get('system/admin/checkLatestOrder', 'Admin\OrderController@checkLatestOrder');
Route::post('system/admin/checkLatestOrder', 'Admin\OrderController@checkLatestOrder');
Route::get('/sitemap', 'ApiController@sitemap');
Route::get('/zappmeal','ApiController@showZapdelHomely');

//Admin Add New Restaurant Panel
Route::group(array('prefix' => 'admin/'), function() {
        Route::get('restaurants/add','Admin\RestaurantController@addNewRestaurant');
        Route::get('restaurants/area','Admin\RestaurantController@getAreaList');
        Route::post('restaurants/add','Admin\RestaurantController@storeRestaurant');
        Route::get('restaurants/index','Admin\RestaurantController@showRestaurantList');
        Route::get('restaurants/edit/{id}','Admin\RestaurantController@editRestaurant');
        Route::get('restaurants/edit/{id}/area','Admin\RestaurantController@getAreaList');
        Route::patch('restaurants/update/{id}','Admin\RestaurantController@updateRestaurant');
        Route::patch('restaurants/update/open/{id}','Admin\RestaurantController@opeRestaurant');
        Route::patch('restaurants/update/closed/{id}','Admin\RestaurantController@closedRestaurant');
        Route::delete('restaurants/{id}','Admin\RestaurantController@deleteRestaurant');
        Route::get('restaurants/import','Admin\RestaurantController@importview');
        Route::post('restaurants/importfiles','Admin\RestaurantController@importfiles');
        Route::get('dashboard/home','Admin\DashboardController@index');

        Route::get('/{city_url_name}/track_boys', 'Admin\OrderController@deliveryBoysLocation');

        Route::get('restaurants/placeorder','Admin\RestaurantController@placeorder');
        Route::get('restaurants/placeorder/{city_url}','Admin\RestaurantController@placeorder');
        Route::get('restaurants/placeorder/{city_url}/{rest_url_name}','Admin\RestaurantController@placeorder');
        Route::get('/delivery_boys/reports/{city_url_name}','Admin\OrderController@deliveryBoysReports');
        Route::get('system/mark_order_as_not_responded','DeliveryBoyController@markOrdersAsNotResponded');
        Route::post('system/checkNewOrderAssignmentStatus','DeliveryBoyController@checkNewOrderAssignmentStatus');

        Route::get('category/index','Admin\RestaurantController@showCategoryList');
        Route::get('category/add','Admin\RestaurantController@addNewCategory');
        Route::post('category/add','Admin\RestaurantController@storeCategory');
        Route::get('category/edit/{id}','Admin\RestaurantController@editCategory');
        Route::patch('category/update/{id}','Admin\RestaurantController@updateCategory');

        Route::get('dish_details/index','Admin\RestaurantController@showDishDetails');
        Route::get('dish_details/add','Admin\RestaurantController@addNewDish');
        Route::post('dish_details/add','Admin\RestaurantController@storeDish');
        Route::get('category/categoryAutoComplete','Admin\RestaurantController@categoryAutoComplete');
        Route::get('dish_details/edit/{id}','Admin\RestaurantController@editDish');
        Route::patch('dish_details/update/{id}','Admin\RestaurantController@updateDish');
        Route::get('restaurant_dishes/{id}','Admin\RestaurantController@showRestaurantMenu');
        Route::get('dishes/dishAutoComplete','Admin\RestaurantController@dishAutoComplete');
        Route::post('restaurant_dishes/{id}','Admin\RestaurantController@storeRestaurantDish');
        Route::get('restaurant_dishes/{restaurant_id}/edit/{id}','Admin\RestaurantController@editRestaurantDish');
});

// SEO Pages
Route::get('food-delivery-restaurants-noida','ApiController@seoPagesFoodDeliveryRestaurants');
Route::get('home-delivery-food-surat','ApiController@seoPagesHomeDelivery');
Route::get('online-food-order-lucknow','ApiController@seoPagesOnlineOrder');
Route::get('pizza-order-ghaziabad','ApiController@seoPizzaOrder');
Route::get('food-delivery-indore','ApiController@seoFoodDelivery');
Route::get('food-order-vadodara','ApiController@seoFoodOrder');
Route::get('home-delivery-restaurants-ahmedabad','ApiController@seoHomeDeliveryRestaurant');
Route::get('food-delivery-agra','ApiController@seoFoodDeliveryagra');
Route::get('food-order-chandigarh','ApiController@seoFoodOrderchandigarh');

//Routes for delivery boy app
Route::group(array('prefix' => 'delivery/'), function() {
    Route::post('location_update', 'DeliveryBoyController@updateLocation');
    Route::post('login', 'DeliveryBoyController@login');
    Route::post('/delivery_boy_devices/', 'DeliveryBoyController@deliveryBoyDeviceInfo');
    Route::post('new_order', 'DeliveryBoyController@newOrder');
    Route::post('delivery_successful', 'DeliveryBoyController@deliverySuccessful');
    Route::post('getPast', 'DeliveryBoyController@getPast');
    Route::post('getPending', 'DeliveryBoyController@getPending');
    Route::post('billingActivity', 'DeliveryBoyController@billingActivity');
    Route::post('is_accept', 'DeliveryBoyController@isAccept');
    Route::post('updateOrder', 'DeliveryBoyController@updateOrder');
    Route::post('logout', 'DeliveryBoyController@logout');
    Route::post('duty_log', 'DeliveryBoyController@dutyLog');
    Route::post('distance_travelled', 'DeliveryBoyController@deliveryBoyDistanceTraveled');
    Route::post('change_password', 'DeliveryBoyController@changePassword');


});
