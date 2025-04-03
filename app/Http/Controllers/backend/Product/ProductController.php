<?php

namespace App\Http\Controllers\backend\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\ProductServiceInterface as ProductService;
use App\Repositories\Interfaces\ProductRepositoryInterface as ProductRepository;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;

use App\Classes\Nestedsetbie;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Language;
use Attribute;

class ProductController extends Controller
{
    protected $productService;
    protected $productRepository;
    protected $nestedset;
    protected $language;
    protected $attributeCatalogueRepository;

    public function __construct(
        ProductService $productService,
        ProductRepository $productRepository,
        AttributeCatalogueRepository $attributeCatalogueRepository,
    ) {
        $this->middleware(function ($request, $next) {
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language = $language->id;
            $this->initialize();
            return $next($request);
        });
        $this->productService = $productService;
        $this->productRepository = $productRepository;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;

        $this->initialize();
    }

    private function initialize()
    {
        $this->nestedset = new NestedSetBie([
            'table' => 'product_catalogues',
            'foreignkey' => 'product_catalogue_id',
            'language_id' => $this->language,
        ]);
    }

    public function index(Request $request)
    {
        // $this->authorize('module', 'product.index');
        $products = $this->productService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $config['seo'] = __('messages.product');
        $template = 'backend.product.product.index';
        $currentLanguage = $this->language;

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'products',
            'currentLanguage'
        ));
    }

    public function create()
    {
        // $this->authorize('module', 'product.create');
        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/plugins/nice_select/js/jquery.nice-select.min.js',
                'backend/js/plugins/switchery/switchery.js',

                'backend/library/finder.js',
                'backend/library/seo.js',
                'backend/library/variant.js',

            ],
            'css'=>[
                'backend/plugins/nice_select/css/nice-select.css',
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $attributeCatalogue= $this->attributeCatalogueRepository->getAll($this->language);
        $template = 'backend.product.product.form';
        $config['seo'] = __('messages.product');
        $config['method'] = 'create';
        $dropdown = $this->nestedset->dropdown();

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown',
            'attributeCatalogue'
        ));
    }

    public function store(StoreProductRequest $request)
    {
        if ($this->productService->create($request, $this->language)) {
            return redirect()->route('product.index')->with('success', __('messages.create_success'));
        }
        return redirect()->route('product.index')->with('error', __('messages.create_error'));
    }

    public function edit($id)
    {
        // $this->authorize('module', 'product.update');
        $product = $this->productRepository->getProductById($id, $this->language);
        $attributeCatalogue= $this->attributeCatalogueRepository->getAll($this->language);
        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/plugins/nice_select/js/jquery.nice-select.min.js',
                'backend/library/finder.js',
                'backend/library/seo.js',
                'backend/library/variant.js',

            ],
            'css'=>[
                'backend/plugins/nice_select/css/nice-select.css',

            ]
        ];
        $album =json_decode($product->album);
        $dropdown = $this->nestedset->dropdown();
        $template = 'backend.product.product.form';
        $config['seo'] = __('messages.product');
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'product',
            'dropdown',
            'attributeCatalogue',
            'album'
        ));
    }

    public function update($id, UpdateProductRequest $request)
    {
        if ($this->productService->update($id, $request,$this->language)) {
            return redirect()->route('product.index')->with('success', __('messages.update_success'));
        }
        return redirect()->route('product.index')->with('error', __('messages.update_error'));
    }

    public function destroy($id)
    {
        if ($this->productService->destroy($id)) {
            return redirect()->route('product.index')->with('success', __('messages.delete_success'));
        }
        return redirect()->route('product.index')->with('error', __('messages.delete_error'));
    }
}
