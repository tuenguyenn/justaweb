<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\MenuComposer;
use App\Http\ViewComposers\CustomerComposer;

use App\Http\ViewComposers\LanguageComposer;


use App\Models\Language;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public $serviceBindings =[
        'App\Services\Interfaces\UserServiceInterface' => 'App\Services\UserService',
        'App\Services\Interfaces\UserCatalogueServiceInterface' => 'App\Services\UserCatalogueService',
        'App\Services\Interfaces\LanguageServiceInterface' => 'App\Services\LanguageService',
        'App\Services\Interfaces\PostCatalogueServiceInterface' => 'App\Services\PostCatalogueService',
        'App\Services\Interfaces\PostServiceInterface' => 'App\Services\PostService',
        'App\Services\Interfaces\PermissionServiceInterface' => 'App\Services\PermissionService',
        'App\Services\Interfaces\GenerateServiceInterface' => 'App\Services\GenerateService',
       
   
    'App\Services\Interfaces\ProductCatalogueServiceInterface' => 'App\Services\ProductCatalogueService',
    'App\Services\Interfaces\ProductServiceInterface' => 'App\Services\ProductService',
    'App\Services\Interfaces\AttributeCatalogueServiceInterface' => 'App\Services\AttributeCatalogueService',
    'App\Services\Interfaces\AttributeServiceInterface' => 'App\Services\AttributeService',

    'App\Services\Interfaces\MenuServiceInterface' => 'App\Services\MenuService',
    'App\Services\Interfaces\MenuCatalogueServiceInterface' => 'App\Services\MenuCatalogueService',

    'App\Services\Interfaces\SlideServiceInterface' => 'App\Services\SlideService',
    'App\Services\Interfaces\WidgetServiceInterface' => 'App\Services\WidgetService',
    'App\Services\Interfaces\PromotionServiceInterface' => 'App\Services\PromotionService',
    'App\Services\Interfaces\SourceServiceInterface' => 'App\Services\SourceService',
    'App\Services\Interfaces\CustomerCatalogueServiceInterface' => 'App\Services\CustomerCatalogueService',
    'App\Services\Interfaces\CustomerServiceInterface' => 'App\Services\CustomerService',
    'App\Services\Interfaces\CartServiceInterface' => 'App\Services\CartService',
    'App\Services\Interfaces\OrderServiceInterface' => 'App\Services\OrderService',
    'App\Services\Interfaces\ReviewServiceInterface' => 'App\Services\ReviewService',









];
    public function register(): void
    {
        foreach($this->serviceBindings as $key =>$val){
            $this->app->bind($key,$val);
        }
        $this->app->register(RepositoryServiceProvider::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {   
        $locale =app()->getLocale();
        $language = Language ::where('canonical', $locale)->first();
        $helperPath = app_path('Helpers/MyHelper.php');
        if (file_exists($helperPath)) {
            require_once $helperPath;
        }
        
        view()->composer('frontend.homepage.layout' , function($view) use ($language){
            $composerClasses =[
                MenuComposer::class,
                LanguageComposer::class,
                CustomerComposer::class,
            ];
            foreach($composerClasses as $key => $val){
                $composer = app()->make($val,['language'=> $language->id]);
                $composer->compose($view);
            }
        });
        
    }
    
    
}
