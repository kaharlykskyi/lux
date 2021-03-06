<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

Route::get('robots.txt', 'Admin\RobotsController');

Route::get('/', 'HomeController@index')->middleware(['cache'])->name('home');
Route::get('/get-brands', 'HomeController@getBrands')->middleware(['cache'])->name('gat_brands');
Route::get('/get-model', 'HomeController@getModel')->middleware(['cache'])->name('gat_model');
Route::get('/get-modifications', 'HomeController@getModifications')->middleware(['cache'])->name('get_modifications');
Route::post('/get-section-part', 'HomeController@getSectionParts')->name('get_section_part');
Route::get('/del-garage-car', 'HomeController@delGarageCar')->middleware(['cache'])->name('del_garage_car');
Route::get('/brands/{rootcategory?}', 'HomeController@allBrands')->middleware(['cache'])->name('all_brands');
Route::get('/modification-info', 'HomeController@modificationInfo')->middleware(['cache'])->name('modification_info');
Route::post('/call-order', 'HomeController@callOrder')->name('call_order');
Route::get('/default-car/{modification}', 'HomeController@defaultCar')->name('default_car');

Route::get('/track-order/{id}', 'TrackOrderController@index')->middleware(['cache'])->name('track_order');

/*-----LiqPay-------*/
Route::prefix('liqpay')->middleware(['auth'])->group(function () {
    Route::get('/', 'LiqPayController@index')->middleware(['cache'])->name('liqpay');
    Route::post('/pay', 'LiqPayController@sendPayRequest')->name('liqpay.pay');
    Route::get('/result-pay', 'LiqPayController@resultPay')->middleware(['cache'])->name('liqpay.result_pay');
    Route::get('/status-pay', 'LiqPayController@checkPayStatus')->middleware(['cache'])->name('liqpay.status_pay');
});
Route::post('/liqpay/response', 'LiqPayController@getLiqPayResponse')->name('liqpay.response');

/*--------PROFILE---------*/
Route::prefix('profile')->middleware(['auth'])->group(function () {
    Route::get('/', 'ProfileController@index')->middleware(['cache'])->name('profile');
    Route::post('/add-car', 'ProfileController@addCar')->name('add_car');
    Route::post('/change-password', 'ProfileController@changePassword')->name('change_password');
    Route::post('/change-user-info', 'ProfileController@changeUserInfo')->name('change_user_info');
    Route::post('/change-delivery-info', 'ProfileController@deliveryInfo')->name('change_delivery_info');
    Route::post('/delete-car', 'ProfileController@deleteCar')->name('delete_car');
    Route::get('/track-order', 'ProfileController@trackOrder')->middleware(['cache'])->name('profile.track_order');
    Route::match(['get', 'post'],'/dop-user-phone','ProfileController@dopUserPhone')->name('dop_user_phone');
});
/*-------CART--------*/
Route::prefix('cart')->group(function () {
    Route::get('/', 'CartController@index')->middleware(['cache'])->name('cart');
    Route::post('/product-count', 'CartController@productCount')->name('product_count');
    Route::post('/product-delete', 'CartController@productDelete')->name('product_delete');
});
/*------PRODUCT-------*/
Route::prefix('product')->group(function () {
    Route::get('/{alias}', 'ProductController@index')->middleware(['cache'])->name('product');
    Route::post('/fast-buy/{id}', 'ProductController@fastBuy')->name('fast_buy');
    Route::post('/add-cart/{id}', 'ProductController@addCart')->name('add_cart');
    Route::post('/comment', 'ProductController@writeComment')->middleware(['auth'])->name('product.comment');
    Route::post('/full-info', 'ProductController@fullInfoProduct')->name('product.full_info');
});
/*-----CHECKOUT------*/
Route::prefix('checkout')->group(function () {
    Route::get('/', 'CheckoutController@index')->middleware(['cache'])->name('checkout');
    Route::post('/new-user', 'CheckoutController@newUser')->name('checkout.new_user');
    Route::post('/old-user', 'CheckoutController@oldUser')->name('checkout.old_user');
    Route::post('/create-oder', 'CheckoutController@createOder')->middleware(['auth'])->name('checkout.create_oder');
});
/*------PAGES------*/
Route::get('/page/{alias}','PageController@index')->middleware(['cache'])->name('page');
/*------FEEDBACK------*/
Route::post('/feedback','FeedBackController@index')->name('feedback');
/*-------RUBRICS---------*/
Route::prefix('rubric')->group(function () {
    Route::get('/{category}','RubricaController@index')->middleware(['cache'])->name('rubric');
    Route::get('/choose-car/{category}','RubricaController@chooseCar')->middleware(['cache'])->name('rubric.choose_car');
});
/*-----VIN DECODE-----*/
Route::match(['get', 'post'],'/vin-decode','VinDecodeController@index')->name('vin_decode');
Route::get('/vin-decode/catalog','VinDecodeController@catalog')->name('vin_decode.catalog');
Route::get('/vin-decode/catalog/page','VinDecodeController@page')->name('vin_decode.catalog.page');
Route::get('/vin-decode/quick_group','VinDecodeController@quickGroup')->name('vin_decode.quick_group');
Route::get('/vin-decode/units','VinDecodeController@units')->name('vin_decode.units');
Route::get('/vin-decode/vin_car','VinDecodeController@vinCar')->name('vin_decode.vin_car');

/*--------CATALOG--------*/
Route::match(['get', 'post'],'/catalog/{category?}','CatalogController@index')->middleware(['cache'])->name('catalog');
Route::get('/get-subcategory', 'HomeController@subcategory')->middleware(['cache'])->name('get_subcategory');
Route::get('/filter', 'CatalogController@filter')->middleware(['cache'])->name('filter');
Route::get('/alternate/{article}', 'CatalogController@replaceProducts')->middleware(['cache'])->name('alternate');

/*!!--------ADMIN--------!!*/
Route::group(['prefix' => 'admin','namespace' => 'Admin', 'middleware' => ['auth','permission']],function (){
    Route::get('/dashboard','DashboardController@index')->name('admin.dashboard');
    Route::get('/import-history','DashboardController@importHistory')->name('admin.import_history');
    Route::match(['get', 'post'], '/filter/{status}','DashboardController@setFilterSettings')->name('admin.filter');
    Route::match(['get', 'post'],'/call-orders','DashboardController@callOrder')->name('admin.call_orders');
    Route::get('/pay-mass','DashboardController@payMass')->name('admin.pay_mass');
    Route::match(['get', 'post'],'/company-settings','DashboardController@companySettings')->name('admin.company.settings');

    //USER
    Route::group(['prefix' => 'users'],function (){
        Route::match(['get', 'post'], '/','UserController@index')->name('admin.users');
        Route::match(['get', 'post'],'/{user}','UserController@show')->name('admin.user.show');
        Route::match(['get', 'post'],'/{user}/garage','UserController@garageShow')->name('admin.user.garage');
        Route::match(['get', 'post'],'/{user}/garage/add','UserController@garageAdd')->name('admin.user.garage.add');
        Route::post('/update-garage-car/{user}','UserController@updateCar')->name('admin.user.garage.update');
        Route::post('/change-balance/{user}','UserController@userBalance')->name('admin.user.change_balance');
    });
    Route::match(['get', 'post'],'/create-user','UserController@createUser')->name('admin.user.create');

    //CROSS NUMBER MANEGE
    Route::group(['prefix' => 'cross'],function (){
        Route::get('/','CrossController@index')->name('admin.cross.index');
        Route::match(['get', 'post'],'/edit','CrossController@edit')->name('admin.cross.edit');
        Route::match(['get', 'post'],'/create','CrossController@create')->name('admin.cross.create');
        Route::get('/delete','CrossController@delete')->name('admin.cross.delete');
    });

    //MANAGE ORDERS
    Route::group(['prefix' => 'orders'],function (){
        Route::match(['get', 'post'], '/','OrderController@index')->name('admin.orders');
        Route::get('/change-status/orders','OrderController@changeStatusOrder')->name('admin.product.change_status_order');
        Route::get('/stock-product','OrderController@stockProductDelivery')->name('admin.product.stock');
        Route::match(['get', 'post'], '/edit/{order}','OrderController@editOder')->name('admin.order_edit');
        Route::match(['get', 'post'],'/generate-order-pdf','OrderController@generatePdf')->name('admin.order.pdf');
        Route::get('/search-product','OrderController@searchProduct')->name('admin.order.search_product');
        Route::match(['get', 'post'],'/create_order','OrderController@createOrder')->name('admin.order.create');
    });

    Route::get('/full-order-info','OrderController@getOrderData')->name('admin.product.full_order_info');
    Route::get('/info-product-stock','OrderController@getInfoProductStock')->name('admin.product.info_product_stock');

    Route::post('/permission','UserController@permission')->name('permission');
    Route::post('/discount-user/{user?}','UserController@setDiscount')->name('discount_user');
    Route::get('/users-cart','UserController@userCart')->name('admin.users_cart');

    Route::match(['get', 'post'], '/fast-buy/{status}','FastBuyController@index')->name('admin.fast_buy');

    Route::resources([
        'category' => 'CategoryController',
        'page' => 'PageController',
        'product' => 'ProductController',
        'discount' => 'DiscountController',
        'banner' => 'BannerController',
        'provider' => 'ProviderController',
        'pro_file' => 'ProFileController',
        'sto_manager' => 'STOManagerController',
        'sto_check_manager' => 'STOCheackManagerController',
        'car_categories' => 'CategoresGroupForCarController'
    ],['as' => 'admin']);
    Route::get('/start-import','ProductController@startImport')->name('admin.start_import');
    Route::get('/child-tecdoc-category','CategoresGroupForCarController@getChildCategory')->name('admin.tecdoc.child_category');

    Route::group(['prefix' => 'feedback'],function (){
        Route::get('/','FeedBackController@index')->name('admin.feedback');
        Route::delete('/{id}','FeedBackController@delete')->name('admin.feedback.delete');
        Route::post('/ask-feedback','FeedBackController@sendFeedBack')->name('admin.feedback.ask');
    });
    Route::match(['get', 'post'], '/brands','ShowBrandController@index')->name('admin.show_brand');

    Route::match(['get', 'post'], '/comment','DashboardController@productComment')->name('admin.comment');
    Route::match(['get', 'post'], '/shipping-payment','DashboardController@shippingPayment')->name('admin.shipping_payment');
    Route::get('/popular-product','ProductController@popularProduct')->name('admin.product.popular');

    //Export
    Route::get('/export-start','ProductController@startExport')->name('admin.export.start');
    //Import
    Route::post('/import-ease-start','ProductController@startEaseImport')->name('admin.import_ease.start');
    //advertising
    Route::match(['get', 'post'], '/advertising','DashboardController@advertising')->name('admin.advertising');
    //get incognito file
    Route::get('/incognito-file/{file}','ProductController@incognitoFile')->name('admin.incognito');
    //STO pdf
    Route::get('/sto-pdf/{check}','STOCheackManagerController@pdfGenerator')->name('admin.sto_check_manager.pdf');

    Route::get('product/delete-file/{brand}/{article}/{name_file}', 'ProductController@deleteFile')->name('admin.product.destroy_file');

    //MANAGE ALL CATEGORY TREE
    Route::group(['prefix' => 'all-category'],function (){
        Route::get('/','AllCategoryTreeController@index')->name('admin.all_category.index');
        Route::match(['get', 'post'], '/edit','AllCategoryTreeController@edit')->name('admin.all_category.edit');
    });

    //MANAGE ALIAS PRODUCTS
    Route::group(['prefix' => 'no-brands'],function (){
        Route::get('/products','NoBrandProductController@index')->name('admin.no_brands.products');
        Route::post('/products','NoBrandProductController@createReplace')->name('admin.no_brands.create_replace');
        Route::post('/products/create-brand','NoBrandProductController@createBrand')->name('admin.no_brands.create_brand');
        Route::match(['get', 'post'], '/alias','NoBrandProductController@brandAlias')->name('admin.no_brands.alis');
        Route::get('/alias/{id}','NoBrandProductController@brandAliasDelete')->name('admin.no_brands.delete');
        Route::post('/products/delete-brand','NoBrandProductController@deleteNoBrandProduct')->name('admin.no_brands.delete_product');
    });

    Route::get('/cache/clear','DashboardController@cacheClear')->name('admin.cache.clear');

    Route::get('/sitemap/create', 'SiteMapController@index')->name('admin.sitemap');
});

Route::get('/sitemap/get-links', 'Admin\SiteMapController@getLinks')->name('sitemap.get_links');
