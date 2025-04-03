<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public $serviceBindings =[
        'App\Repositories\Interfaces\UserRepositoryInterface' => 
        'App\Repositories\UserRepository',

        'App\Repositories\Interfaces\UserCatalogueRepositoryInterface' => 
        'App\Repositories\UserCatalogueRepository',

        'App\Repositories\Interfaces\LanguageRepositoryInterface' => 
        'App\Repositories\LanguageRepository',

        'App\Repositories\Interfaces\PostCatalogueRepositoryInterface' => 
        'App\Repositories\PostCatalogueRepository',

        'App\Repositories\Interfaces\PostRepositoryInterface' => 
        'App\Repositories\PostRepository',

        'App\Repositories\Interfaces\PermissionRepositoryInterface' => 
        'App\Repositories\PermissionRepository',

        'App\Repositories\Interfaces\GenerateRepositoryInterface' => 
        'App\Repositories\GenerateRepository',

        'App\Repositories\Interfaces\ProvinceRepositoryInterface' => 
        'App\Repositories\ProvinceRepository',

        'App\Repositories\Interfaces\DistrictRepositoryInterface' => 
        'App\Repositories\DistrictRepository',

          
        'App\Repositories\Interfaces\RouterRepositoryInterface' => 
        'App\Repositories\RouterRepository',

    
        'App\Repositories\Interfaces\ProductCatalogueRepositoryInterface' => 'App\Repositories\ProductCatalogueRepository',
        'App\Repositories\Interfaces\ProductRepositoryInterface' => 'App\Repositories\ProductRepository',

        'App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface' => 'App\Repositories\AttributeCatalogueRepository',
        'App\Repositories\Interfaces\AttributeRepositoryInterface' => 'App\Repositories\AttributeRepository',

        'App\Repositories\Interfaces\ProductVariantRepositoryInterface' => 'App\Repositories\ProductVariantRepository',
        'App\Repositories\Interfaces\ProductVariantLanguageRepositoryInterface' => 'App\Repositories\ProductVariantLanguageRepository',
        'App\Repositories\Interfaces\ProductVariantAttributeRepositoryInterface' => 'App\Repositories\ProductVariantAttributeRepository',
        'App\Repositories\Interfaces\MenuRepositoryInterface' => 'App\Repositories\MenuRepository',
        'App\Repositories\Interfaces\MenuCatalogueRepositoryInterface' => 'App\Repositories\MenuCatalogueRepository',
        'App\Repositories\Interfaces\SlideRepositoryInterface' => 'App\Repositories\SlideRepository',
        'App\Repositories\Interfaces\WidgetRepositoryInterface' => 'App\Repositories\WidgetRepository',
        'App\Repositories\Interfaces\PromotionRepositoryInterface' => 'App\Repositories\PromotionRepository',
        'App\Repositories\Interfaces\SourceRepositoryInterface' => 'App\Repositories\SourceRepository',
        'App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface' => 'App\Repositories\CustomerCatalogueRepository',
        'App\Repositories\Interfaces\CustomerRepositoryInterface' => 'App\Repositories\CustomerRepository',
        'App\Repositories\Interfaces\OrderRepositoryInterface' => 'App\Repositories\OrderRepository',
        'App\Repositories\Interfaces\CartItemRepositoryInterface' => 'App\Repositories\CartItemRepository',
        'App\Repositories\Interfaces\ReviewRepositoryInterface' => 'App\Repositories\ReviewRepository',











];
    public function register(): void
    {
       
        foreach($this->serviceBindings as $key =>$val){
            $this->app->bind($key,$val);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
