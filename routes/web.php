<?php
use Illuminate\Support\Facades\Route;

/*Route::group([], function () {
    Route::get('login/facebook', 'Front\SocialAuthController@redirectToFacebook');
    Route::any('login/facebook/callback', 'Front\SocialAuthController@handleFacebookCallback');
    Route::get('login/google', 'Front\SocialAuthController@redirectToGoogle');
    Route::any('login/google/callback', 'Front\SocialAuthController@handleGoogleCallback');
});*/
Route::group([], function () {
    Route::get('login/facebook', 'Front\SocialAuthController@redirectToFacebook');
    Route::any('login/facebook/callback', 'Front\SocialAuthController@handleFacebookCallback');
    Route::get('login/google', 'Front\SocialAuthController@redirectToGoogle');
    Route::any('login/google/callback', 'Front\SocialAuthController@handleGoogleCallback');
//    Route::get('/newpassword', 'Front\FrontUsersController@newPasswordIndex');
//    Route::post('/saveNewPass', 'Front\FrontUsersController@newPassword');
//    Route::post('/ajaxSortPage', 'Front\CatalogController@ajaxSortPage');
//    Route::any('/api/{parent?}/{children?}', 'Front\DefaultControllerApi@index');
});

Route::group([
    'prefix' => '{lang?}'
], function () {
    Route::group([
        'prefix' => 'back',
    ], function () {
        Route::any('/upload', 'FileController@upload');
        Route::any('/uploadPdf', 'FileController@uploadPdf');
        Route::any('/deletePdf', 'FileController@deletePdf');
        Route::post('/destroyFile', 'FileController@destroyOneSingleImg');
        Route::post('/destroyFiles', 'FileController@destroyOneMultipleImg');
        Route::post('/activateFile', 'FileController@activateOneImg');
//        Route::any('/upload-1c-file', 'OneCFileController@upload');
        Route::any('/uploadGalleryPhoto', 'FileController@uploadGalleryPhoto');

        Route::post('/new-feedback', 'Admin\DefaultController@ajaxCountFeedback');

//      Route::get('/', 'Admin\DefaultController@index');

        Route::get('/auth/login', 'Auth\CustomAuthController@login')->name('login');
        Route::post('/auth/login', 'Auth\CustomAuthController@checkLogin');

        Route::get('/auth/register', 'Auth\CustomAuthController@register');
        Route::post('/auth/register', 'Auth\CustomAuthController@checkRegister');

        Route::get('/auth/logout', 'Auth\CustomAuthController@logout');

        Route::any('/{module?}/{submenu?}/{action?}/{id?}/{lang_id?}',['uses' => 'RoleManager@routeResponder'] );
    });

    Route::group([], function() {
        Route::get('/', 'Front\DefaultController@index');



//        Route::get('/register', 'Front\FrontUsersController@registerPage');
//        Route::get('/login', 'Front\FrontUsersController@loginPage');
//        Route::post('/regin', 'Front\FrontUsersController@registerNewFrontUser');
//        Route::any('/signup', 'Front\FrontUsersController@frontUserLogin');
//        Route::get('/user/{id?}', 'Front\FrontUsersController@userPage');
//        Route::get('/table/{id?}', 'Front\TableController@pageTable');
//
           Route::get('/favorite', 'Front\WishController@wish');
           Route::post('/favAdd', 'Front\DefaultController@ajaxFav');
//        Route::post('/destroyItemWish', 'Front\WishController@destroyItemWish');
//
//        Route::get('/compare-list', 'Front\CompareController@compare');
//        Route::post('/compareElements/goods', 'Front\CompareController@ajaxCompare');
//        Route::post('/destroyCompareElements/goods', 'Front\CompareController@destroyCompareItem');
//        Route::post('/destroyAllCompareItems/goods', 'Front\CompareController@destroyAllCompareItems');
//
//        Route::get('/cart', 'Front\CartController@index');
//        Route::post('/cartElements/goods', 'Front\CartController@ajaxAddToCart');
//        Route::post('/diffSumItems/goods', 'Front\CartController@diffSumItemCart');
//        Route::post('/destroyCartElements/goods', 'Front\CartController@destroyItemCart');
//        Route::post('/newOrder', 'Front\OrderController@newOrder');
//
//
        Route::group(['middleware' => 'check-auth-front'], function(){


            Route::get('/logout', 'Front\FrontUsersController@frontUserLogout');
            Route::get('/cabinet', 'Front\CabinetController@userCabinet');
//            Route::get('/create', 'Front\TableController@index');
//            Route::post('/createTable', 'Front\TableController@createTable');
//            Route::get('/orders/{order?}', 'Front\CabinetController@showUserOrders');
//            Route::get('/change-pass', 'Front\CabinetController@changePass');
//            Route::post('/change-pass-ajax', 'Front\CabinetController@changePassAjax');
//            Route::post('/saveData', 'Front\CabinetController@saveUserData');
//            Route::any('/newReview', 'Front\CabinetController@newReview');

        });
//
//
//        Route::post('/catalog/filter', 'Front\CatalogController@filterResults');
//        Route::any('/projects/{project?}', 'Front\ProjectController@index');
//        Route::any('/services/{servs?}', 'Front\ServicesController@index');
//        Route::get('/search', 'Front\CatalogController@goodsSearch');
//        Route::get('/register-success', 'Front\FrontUsersController@registerSuccess');
//        Route::get('/checkout-success', 'Front\OrderController@orderSuccess');
//
//        Route::post('/createPdf', 'Front\TableController@createPdfdoc');
//
//        Route::post('/restore-pass', 'Front\FrontUsersController@userRestorePassword');

        Route::get('/calculator/{parent?}/{children?}', 'Front\DefaultController@calcWork');
//        Route::get('/trans', 'Front\DefaultController@trans');
        //Route::get('/archive/{parent?}/{children?}', 'Front\CatalogController@index');

        Route::post('/calcit/{children?}', 'Front\DefaultController@calcit');
        Route::post('/smartSearchFun', 'Front\SearchController@index');
        Route::get('/search', 'Front\SearchController@searchPage');
        Route::post('/simpleFeedback/feedback', 'Front\DefaultController@simpleFeedbackAjax');

        Route::get('/{parent}/{children?}', 'Front\DefaultController@menuElements');
    });
});
