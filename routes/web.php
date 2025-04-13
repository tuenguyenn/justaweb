<?php
use App\Http\Controllers\MessagesController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\backend\AuthController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Controllers\Ajax\LocationController;
use App\Http\Controllers\Ajax\StatusController;
use App\Http\Controllers\Ajax\AjaxAttributeController;
use App\Http\Controllers\Ajax\AjaxMenuController;
use App\Http\Controllers\Ajax\AjaxProductController;
use App\Http\Controllers\Ajax\AjaxSourceController;
use App\Http\Controllers\Ajax\AjaxCartController;
use App\Http\Controllers\Ajax\AjaxOrderController;
use App\Http\Controllers\Ajax\AjaxCustomerController;
use App\Http\Controllers\Ajax\AjaxReviewController;

use App\Http\Controllers\backend\User\UserController;
use App\Http\Controllers\backend\User\UserCatalogueController;

use App\Http\Controllers\backend\Customer\CustomerController;
use App\Http\Controllers\backend\Customer\CustomerCatalogueController;

use App\Http\Controllers\backend\LanguageController;

use App\Http\Controllers\backend\Post\PostCatalogueController;
use App\Http\Controllers\backend\Post\PostController;
use App\Http\Controllers\backend\PermissionController;
use App\Http\Controllers\backend\GenerateController;
use App\Http\Controllers\backend\MenuController;
use App\Http\Controllers\backend\SlideController;
use App\Http\Controllers\backend\WidgetController;
use App\Http\Controllers\backend\PromotionController;
use App\Http\Controllers\backend\SourceController;
use App\Http\Controllers\backend\OrderController;

use App\Http\Controllers\backend\ReviewController;




use App\Http\Controllers\backend\Product\ProductCatalogueController;


use App\Http\Controllers\backend\Product\ProductController;

use App\Http\Controllers\backend\Attribute\AttributeCatalogueController;

use App\Http\Controllers\backend\Attribute\AttributeController;

use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\frontend\RouterController;
use App\Http\Controllers\frontend\CartController;
use App\Http\Controllers\frontend\VnpayController;
use App\Http\Controllers\frontend\MomoController;
use App\Http\Controllers\frontend\CustomerFEController;
use App\Http\Controllers\frontend\OrderCustomerController;
use App\Http\Controllers\frontend\ChatbotController;
use App\Http\Controllers\frontend\SearchController;


use App\Models\Customer;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


//@@userController@@










/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/admin', [AuthController::class, 'index'])->name('admin');

/* FRONTEND*/
Route::get('customer', [AuthController::class, 'customer'])->name('customer');

Route :: post('customer/login',[AuthController :: class, 'customerLogin'])-> name('customer.login');
Route::post('customer/logout', [AuthController::class, 'customerLogout'])->name('customer.logout');
Route::get('customerfe/create',[CustomerFEController::class,'create'])->name('customerfe.create');
Route :: post('customerfe/store',[CustomerFEController :: class, 'store'])-> name('customerfe.store');
Route :: get('{id}/profile',[CustomerFEController :: class, 'profile'])->where(['id'=>'[0-9]+'])-> name('customer.profile');
Route :: post('customerfe/{id}/update',[CustomerFEController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('customerfe.update');
Route :: get('customerfe/{id}/verify',[CustomerFEController :: class, 'verify'])->where(['id'=>'[0-9]+'])-> name('customerfe.verify');
Route :: post('customerfe/{id}/verifyEmail',[CustomerFEController :: class, 'verifyEmail'])->where(['id'=>'[0-9]+'])-> name('customerfe.verifyEmail');
Route:: post('ajax/customer/resendOtp',[AjaxCustomerController::class,'resendOtp'])->name('ajax.customer.resendOtp');
Route::get('/change-password', [CustomerFEController::class, 'showChangePasswordForm'])->name('password.change');
Route::post('/change-password', [CustomerFEController::class, 'updatePassword'])->name('password.update');
Route:: post('ajax/review/create',[AjaxReviewController::class,'create'])->name('ajax.customer.create');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetEmail'])->name('password.email');

Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.new');


Route :: get('{id}/order-history',action: [OrderCustomerController :: class, 'orderHistory'])-> name('order.history');

Route::get('/order/customer/detail/{id}', [OrderCustomerController::class, 'orderCustomerDetail'])
    ->name('order.customer.detail');
    Route :: post('{id}/cancel',[OrderCustomerController :: class, 'cancel'])->where(['id'=>'[0-9]+'])-> name('order.cancel');


Route::get('auth/google', function () { 
    return Socialite::driver('google')->redirect();
})->name('google.login');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route :: get('/otp/verify',[CustomerFEController :: class, 'otpVerifyForm'])-> name('otp.verify.form');
Route::post('/otp/verify', [CustomerFEController::class, 'verifyOtp'])->name('otp.verify');


Route :: get('returnvnpay',[VnpayController :: class, 'vnpay_return'])-> name('vnpay.vnpay_return');
Route :: get('return/vnpay_ipn',[VnpayController :: class, 'vnpay_ipn'])-> name('vnpay.vnpay_ipn');

Route :: get('returnmomo',[MomoController :: class, 'momo_return'])-> name('momo.momo_return');
Route :: post('return/ipn',[MomoController :: class, 'momo_ipn'])-> name('momo.momo_ipn');

Route::get('/checkout', [CartController::class, 'checkout'])
->middleware('locale')
->name('cart.checkout');

Route::post('/chatbot', [ChatbotController::class, 'sendMessage'])->name('sendMessage');



Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('ajax/location/getLocation',[LocationController::class,'getLocation'])->name('ajax.location.index');



Route::group(['middleware'=> ['locale']], function () {
    Route :: get('/',[HomeController :: class, 'index'])-> name('home.index');
    Route :: get('/{canonical}',[RouterController :: class, 'index'])-> name('router.index')->where('canonical','[a-zA-Z0-9-]+');
    Route ::get('ajax/product/loadVariant',[AjaxProductController::class,'loadVariant'])->name('ajax.product.loadVariant');
    Route ::get('ajax/product/filter',[AjaxProductController::class,'filter'])->name('ajax.product.filter');

    Route::post('ajax/cart/create',[AjaxCartController::class,'create'])->name('ajax.cart.index');
    Route::post('ajax/cart/update',[AjaxCartController::class,'update'])->name('ajax.cart.update');
    Route::post('ajax/cart/delete',[AjaxCartController::class,'delete'])->name('ajax.cart.delete');
    Route::post('cart/store',[CartController::class,'store'])->name('cart.store');
    Route::get('cart/success/{code}',[CartController::class,'success'])->name('cart.success');


   


});
/* BACKEND*/
Route::group(['middleware'=> ['auth','admin','locale']], function () {
    /* dashboard*/
    Route :: get('dashboard/index',[DashboardController :: class, 'index'])-> name('dashboard.index')->middleware(AuthenticateMiddleware::class);

    /*user*/

    Route :: group(['prefix'=> 'user'],function(){
        Route :: get('/index',[UserController :: class, 'index'])-> name('user.index');
        Route :: get('/create',[UserController :: class, 'create'])-> name('user.create');
        Route :: post('/store',[UserController :: class, 'store'])-> name('user.store');
        Route :: get('{id}/edit',[UserController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('user.edit');
        Route :: post('{id}/update',[UserController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('user.update');
        Route :: post('destroy/{id}',[UserController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('user.destroy');

    });
    Route :: group(['prefix'=> 'user/catalogue'],function(){
        Route :: get('/index',[UserCatalogueController :: class, 'index'])-> name('user.catalogue.index');
        Route :: get('/create',[UserCatalogueController :: class, 'create'])-> name('user.catalogue.create');
        Route :: post('/store',[UserCatalogueController :: class, 'store'])-> name('user.catalogue.store');
        Route :: get('{id}/edit',[UserCatalogueController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('user.catalogue.edit');
        Route :: post('{id}/update',[UserCatalogueController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('user.catalogue.update');
        Route :: post('destroy/{id}',[UserCatalogueController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('user.catalogue.destroy');
        Route :: get('/permission',[UserCatalogueController :: class, 'permission'])-> name('user.catalogue.permission');
        Route :: post('/updatePermission',[UserCatalogueController :: class, 'updatePermission'])-> name('user.catalogue.updatePermission');

    });
    Route :: group(['prefix'=> 'customer'],function(){
        Route :: get('/index',[CustomerController :: class, 'index'])-> name('customer.index');
        Route :: get('/create',[CustomerController :: class, 'create'])-> name('customer.create');
        Route :: post('/store',[CustomerController :: class, 'store'])-> name('customer.store');
        Route :: get('{id}/edit',[CustomerController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('customer.edit');
        Route :: post('{id}/update',[CustomerController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('customer.update');
        Route :: post('destroy/{id}',[CustomerController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('customer.destroy');

    });
    Route :: group(['prefix'=> 'customer/catalogue'],function(){
        Route :: get('/index',[CustomerCatalogueController :: class, 'index'])-> name('customer.catalogue.index');
        Route :: get('/create',[CustomerCatalogueController :: class, 'create'])-> name('customer.catalogue.create');
        Route :: post('/store',[CustomerCatalogueController :: class, 'store'])-> name('customer.catalogue.store');
        Route :: get('{id}/edit',[CustomerCatalogueController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('customer.catalogue.edit');
        Route :: post('{id}/update',[CustomerCatalogueController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('customer.catalogue.update');
        Route :: post('destroy/{id}',[CustomerCatalogueController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('customer.catalogue.destroy');
        Route :: get('/permission',[CustomerCatalogueController :: class, 'permission'])-> name('customer.catalogue.permission');
        Route :: post('/updatePermission',[CustomerCatalogueController :: class, 'updatePermission'])-> name('customer.catalogue.updatePermission');

    });
    Route :: group(['prefix'=> 'post/catalogue'],function(){
        Route :: get('/index',[PostCatalogueController :: class, 'index'])-> name('post.catalogue.index');
        Route :: get('/create',[PostCatalogueController :: class, 'create'])-> name('post.catalogue.create');
        Route :: post('/store',[PostCatalogueController :: class, 'store'])-> name('post.catalogue.store');
        Route :: get('{id}/edit',[PostCatalogueController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('post.catalogue.edit');
        Route :: post('{id}/update',[PostCatalogueController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('post.catalogue.update');
        Route :: post('destroy/{id}',[PostCatalogueController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('post.catalogue.destroy');

    });
    Route :: group(['prefix'=> 'post'],function(){
        Route :: get('/index',[PostController :: class, 'index'])-> name('post.index');
        Route :: get('/create',[PostController :: class, 'create'])-> name('post.create');
        Route :: post('/store',[PostController :: class, 'store'])-> name('post.store');
        Route :: get('{id}/edit',[PostController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('post.edit');
        Route :: post('{id}/update',[PostController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('post.update');
        Route :: post('destroy/{id}',[PostController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('post.destroy');

    });
    Route :: group(['prefix'=> 'permission'],function(){
        Route :: get('/index',[PermissionController :: class, 'index'])-> name('permission.index');
        Route :: get('/create',[PermissionController :: class, 'create'])-> name('permission.create');
        Route :: post('/store',[PermissionController :: class, 'store'])-> name('permission.store');
        Route :: get('{id}/edit',[PermissionController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('permission.edit');
        Route :: post('{id}/update',[PermissionController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('permission.update');
        Route :: post('destroy/{id}',[PermissionController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('permission.destroy');

    });




    Route :: group(['prefix'=> 'languages'],function(){
        Route :: get('/index',[LanguageController :: class, 'index'])-> name('language.index');
        Route :: get('/create',[LanguageController :: class, 'create'])-> name('language.create');
        Route :: post('/store',[LanguageController :: class, 'store'])-> name('language.store');
        Route :: get('{id}/edit',[LanguageController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('language.edit');
        Route :: post('{id}/update',[LanguageController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('language.update');
        Route :: post('destroy/{id}',[LanguageController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('language.destroy');
        Route :: get('{id}/switch',[LanguageController :: class, 'switchBackendLanguage'])->where(['id'=>'[0-9]+'])-> name('language.switch');
        Route :: get('translate/{model}/{id}/{languageId}',[LanguageController :: class, 'translate'])->where(['id'=>'[0-9]+'])->where(['languageId'=>'[0-9]+'])-> name('language.translate');
        Route :: post('/storeTranslate',[LanguageController :: class, 'storeTranslate'])-> name('language.storeTranslate');



    });

    Route :: group(['prefix'=> 'generate'],function(){
        Route :: get('/index',[GenerateController :: class, 'index'])-> name('generate.index');
        Route :: get('/create',[GenerateController :: class, 'create'])-> name('generate.create');
        Route :: post('/store',[GenerateController :: class, 'store'])-> name('generate.store');
        Route :: get('{id}/edit',[GenerateController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('generate.edit');
        Route :: post('{id}/update',[GenerateController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('generate.update');
        Route :: post('destroy/{id}',[GenerateController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('generate.destroy');
     

    });


 
 
  
   

  

    

    Route :: group(['prefix'=> 'product/catalogue'],function(){
        Route :: get('/index',[ProductCatalogueController :: class, 'index'])-> name('product.catalogue.index');
        Route :: get('/create',[ProductCatalogueController :: class, 'create'])-> name('product.catalogue.create');
        Route :: post('/store',[ProductCatalogueController :: class, 'store'])-> name('product.catalogue.store');
        Route :: get('{id}/edit',[ProductCatalogueController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('product.catalogue.edit');
        Route :: post('{id}/update',[ProductCatalogueController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('product.catalogue.update');
        Route :: post('destroy/{id}',[ProductCatalogueController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('product.catalogue.destroy');
});

    

    Route :: group(['prefix'=> 'product'],function(){
        Route :: get('/index',[ProductController :: class, 'index'])-> name('product.index');
        Route :: get('/create',[ProductController :: class, 'create'])-> name('product.create');
        Route :: post('/store',[ProductController :: class, 'store'])-> name('product.store');
        Route :: get('{id}/edit',[ProductController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('product.edit');
        Route :: post('{id}/update',[ProductController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('product.update');
        Route :: post('destroy/{id}',[ProductController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('product.destroy');
});

    Route :: group(['prefix'=> 'attribute/catalogue'],function(){
        Route :: get('/index',[AttributeCatalogueController :: class, 'index'])-> name('attribute.catalogue.index');
        Route :: get('/create',[AttributeCatalogueController :: class, 'create'])-> name('attribute.catalogue.create');
        Route :: post('/store',[AttributeCatalogueController :: class, 'store'])-> name('attribute.catalogue.store');
        Route :: get('{id}/edit',[AttributeCatalogueController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('attribute.catalogue.edit');
        Route :: post('{id}/update',[AttributeCatalogueController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('attribute.catalogue.update');
        Route :: post('destroy/{id}',[AttributeCatalogueController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('attribute.catalogue.destroy');
});

    Route :: group(['prefix'=> 'attribute'],function(){
        Route :: get('/index',[AttributeController :: class, 'index'])-> name('attribute.index');
        Route :: get('/create',[AttributeController :: class, 'create'])-> name('attribute.create');
        Route :: post('/store',[AttributeController :: class, 'store'])-> name('attribute.store');
        Route :: get('{id}/edit',[AttributeController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('attribute.edit');
        Route :: post('{id}/update',[AttributeController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('attribute.update');
        Route :: post('destroy/{id}',[AttributeController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('attribute.destroy');
});

Route :: group(['prefix'=> 'menu'],function(){
    Route :: get('/index',[MenuController :: class, 'index'])-> name('menu.index');
    Route :: get('/create',[MenuController :: class, 'create'])-> name('menu.create');
    Route :: post('/store',[MenuController :: class, 'store'])-> name('menu.store');
    Route :: get('{id}/edit',[MenuController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('menu.edit');
    Route :: get('{id}/editMenu',[MenuController :: class, 'editMenu'])->where(['id'=>'[0-9]+'])-> name('menu.editMenu');

    Route :: post('{id}/update',[MenuController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('menu.update');
    Route :: post('destroy/{id}',[MenuController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('menu.destroy');
    Route :: get('{id}/children',[MenuController :: class, 'children'])->where(['id'=>'[0-9]+'])-> name('menu.children');
    Route :: post('{id}/saveChildren',[MenuController :: class, 'saveChildren'])->where(['id'=>'[0-9]+'])-> name('menu.save.children');
    Route :: get('{languageId}/{id}/translate',[MenuController :: class, 'translate'])->where(['languageId'=>'[0-9]+','id'=>'[0-9]+'])-> name('menu.translate');
    Route :: post('{languageId}/saveTranslate',[MenuController :: class, 'saveTranslate'])->where(['languageId'=>'[0-9]+'])-> name('menu.translate.save');

});

Route :: group(['prefix'=> 'slide'],function(){
    Route :: get('/index',[SlideController :: class, 'index'])-> name('slide.index');
    Route :: get('/create',[SlideController :: class, 'create'])-> name('slide.create');
    Route :: post('/store',[SlideController :: class, 'store'])-> name('slide.store');
    Route :: get('{id}/edit',[SlideController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('slide.edit');
    Route :: post('{id}/update',[SlideController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('slide.update');
    Route :: post('destroy/{id}',[SlideController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('slide.destroy');
});
Route :: group(['prefix'=> 'widget'],function(){
    Route :: get('/index',[WidgetController :: class, 'index'])-> name('widget.index');
    Route :: get('/create',[WidgetController :: class, 'create'])-> name('widget.create');
    Route :: post('/store',[WidgetController :: class, 'store'])-> name('widget.store');
    Route :: get('{id}/edit',[WidgetController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('widget.edit');
    Route :: post('{id}/update',[WidgetController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('widget.update');
    Route :: post('destroy/{id}',[WidgetController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('widget.destroy');
    Route :: get('{languageId}/{id}/translate',[WidgetController :: class, 'translate'])->where(['languageId'=>'[0-9]+','id'=>'[0-9]+'])-> name('widget.translate');
    Route :: post('/saveTranslate',[WidgetController :: class, 'saveTranslate'])-> name('widget.saveTranslate');

    
});
Route :: group(['prefix'=> 'promotion'],function(){
    Route :: get('/index',[PromotionController :: class, 'index'])-> name('promotion.index');
    Route :: get('/create',[PromotionController :: class, 'create'])-> name('promotion.create');
    Route :: post('/store',[PromotionController :: class, 'store'])-> name('promotion.store');
    Route :: get('{id}/edit',[PromotionController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('promotion.edit');
    Route :: post('{id}/update',[PromotionController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('promotion.update');
    Route :: post('destroy/{id}',[PromotionController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('promotion.destroy');


    
});
Route :: group(['prefix'=> 'source'],function(){
    Route :: get('/index',[SourceController :: class, 'index'])-> name('source.index');
    Route :: get('/create',[SourceController :: class, 'create'])-> name('source.create');
    Route :: post('/store',[SourceController :: class, 'store'])-> name('source.store');
    Route :: get('{id}/edit',[SourceController :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('source.edit');
    Route :: post('{id}/update',[SourceController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('source.update');
    Route :: post('destroy/{id}',[SourceController :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('source.destroy');


    
});

Route :: group(['prefix'=> 'order'],function(){
    Route :: get('/index',[OrderController :: class, 'index'])-> name('order.index');
    Route :: get('{id}/detail',[OrderController :: class, 'detail'])->where(['id'=>'[0-9]+'])-> name('order.detail');
    Route :: post('{id}/update',[OrderController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('order.update');
    // routes/web.php
    


});
Route :: group(['prefix'=> 'review'],function(){
    Route :: get('/index',[ReviewController :: class, 'index'])-> name('review.index');
    Route :: post('{id}/update',[ReviewController :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('review.update');

});




//@@new-module@@


Route::post('ajax/status/changeStatus',[StatusController::class,'changeStatus'])->name('ajax.status.changeStatus');
Route::post('ajax/status/changeAllStatus',[StatusController::class,'changeAllStatus'])->name('ajax.status.changeAllStatus');

Route::post('ajax/menu/createCatalogue',[AjaxMenuController::class,'createCatalogue'])->name('ajax.menu.createCatalogue');
Route::post('ajax/menu/drag',[AjaxMenuController::class,'drag'])->name('ajax.menu.drag');







    //Ajax
   
   



});

Route::get('ajax/status/getMenu',[StatusController::class,'getMenu'])->name('ajax.status.getMenu');
Route::get('ajax/status/findModelObject',[StatusController::class,'findModelObject'])->name('ajax.status.findModelObject');
Route::get('ajax/status/findPromotionObject',[StatusController::class,'findPromotionObject'])->name('ajax.status.findPromotionObject');
Route::get('ajax/status/getPromotionContitionValue',[StatusController::class,'getPromotionContitionValue'])->name('ajax.status.getPromotionContitionValue');



Route::get('ajax/attribute/getAttribute',[AjaxAttributeController::class,'getAttribute'])->name('ajax.attribute.getAttribute');
Route::get('ajax/attribute/loadAttribute',[AjaxAttributeController::class,'loadAttribute'])->name('ajax.attribute.loadAttribute');


Route::get('ajax/product/loadProductPromotion',[AjaxProductController::class,'loadProductPromotion'])->name('ajax.product.loadProductPromotion');

Route::get('ajax/source/getAllSource',[AjaxSourceController::class,'getAllSource'])->name('ajax.source.getAllSource');

Route::post('ajax/order/updateField',[AjaxOrderController::class,'updateField'])->name('ajax.order.updateField');
Route::get('ajax/order/revenue-chart',[AjaxOrderController::class,'getRevenueChart'])->name('ajax.order.getRevenueChart');
Route :: get('admin',[AuthController :: class, 'index'])-> name('auth.admin');
Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

Route :: post('login',[AuthController :: class, 'login'])-> name('auth.login');


