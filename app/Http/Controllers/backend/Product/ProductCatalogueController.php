<?php
namespace App\Http\Controllers\backend\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\ProductCatalogueServiceInterface as ProductCatalogueService;
use App\Repositories\Interfaces\ProductCatalogueRepositoryInterface as ProductCatalogueRepository;
use App\Classes\Nestedsetbie;
use App\Http\Requests\Product\StoreProductCatalogueRequest;
use App\Http\Requests\Product\UpdateProductCatalogueRequest;
use App\Models\Language;

use App\Models\ProductCatalogue;
class ProductCatalogueController extends Controller
{
    protected $productCatalogueService;
    protected $productCatalogueRepository;
    protected $nestedset;
    protected $language;


    public function __construct(
        ProductCatalogueService $productCatalogueService,
        ProductCatalogueRepository $productCatalogueRepository
    ) {
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language =$language->id;
            $this->initialize();
            return $next($request);
        });
        $this->productCatalogueService = $productCatalogueService;
        $this->productCatalogueRepository = $productCatalogueRepository;
        $this->initialize();

       

    }
    private function initialize(){
        $this->nestedset = new NestedSetBie([
            'table'=>'product_catalogues',
            'foreignkey'=> 'product_catalogue_id',
            'language_id'=>  $this->language,
        ]);
    }

    public function index(Request $request)
    {   
        $this->authorize('module','product.catalogue.index');
        $productCatalogues = $this->productCatalogueService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $config['seo'] = __('messages.productCatalogue');
        $template = 'backend.product.catalogue.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'productCatalogues'
        ));
    }

    public function create()
    {
        $this->authorize('module','product.catalogue.create');

        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',

                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js'

            ],
        ];
        $template = 'backend.product.catalogue.form';
        $config['seo'] = __('messages.productCatalogue');
        $config['method'] = 'create';
        $dropdown =$this->nestedset->dropdown();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }

    public function store(StoreProductCatalogueRequest $request)
    {
        if ($this->productCatalogueService->create($request)) {
            return redirect()->route('product.catalogue.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('product.catalogue.index')->with('error', 'Thêm mới không thành công');
    }

    public function edit($id)
    {   
        $this->authorize('module','product.catalogue.update');

        $productCatalogue = $this->productCatalogueRepository->getProductCatalogueById($id, $this->language);
        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                  'backend/library/seo.js',

                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $dropdown =$this->nestedset->dropdown();

        $template = 'backend.product.catalogue.form';
        $config['seo'] = __('messages.productCatalogue');
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'productCatalogue',
            'dropdown'

        ));
    }

    public function update($id, UpdateProductCatalogueRequest $request)
    {   

        if ($this->productCatalogueService->update($id, $request)) {
            return redirect()->route('product.catalogue.index')->with('success', 'Cập nhật thành công');
        }

        return redirect()->route('product.catalogue.index')->with('error', 'Cập nhật không thành công');
    }

    public function destroy($id, Request $request)
    {
        $this->authorize('module','product.catalogue.destroy');

        if (!ProductCatalogue::isChildrenNode($id)) {
            return redirect()->route('product.catalogue.index')->with('error', 'Không thể xóa vì danh mục có con.');
        }
    
        if ($this->productCatalogueService->destroy($id, $request)) {
            return redirect()->route('product.catalogue.index')->with('success', 'Xoá thành công');
        }
    
        return redirect()->route('product.catalogue.index')->with('error', 'Xoá không thành công');
    }
    
    
}
