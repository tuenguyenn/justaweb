<?php
namespace App\Http\Controllers\backend\Attribute;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\AttributeCatalogueServiceInterface as AttributeCatalogueService;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface as AttributeCatalogueRepository;
use App\Classes\Nestedsetbie;
use App\Http\Requests\Attribute\StoreAttributeCatalogueRequest;
use App\Http\Requests\Attribute\UpdateAttributeCatalogueRequest;
use App\Models\Language;

use App\Models\AttributeCatalogue;
class AttributeCatalogueController extends Controller
{
    protected $attributeCatalogueService;
    protected $attributeCatalogueRepository;
    protected $nestedset;
    protected $language;


    public function __construct(
        AttributeCatalogueService $attributeCatalogueService,
        AttributeCatalogueRepository $attributeCatalogueRepository
    ) {
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language =$language->id;
            $this->initialize();
            return $next($request);
        });
        $this->attributeCatalogueService = $attributeCatalogueService;
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->initialize();

       

    }
    private function initialize(){
        $this->nestedset = new NestedSetBie([
            'table'=>'attribute_catalogues',
            'foreignkey'=> 'attribute_catalogue_id',
            'language_id'=>  $this->language,
        ]);
    }

    public function index(Request $request)
    {   
        // $this->authorize('module','attribute.catalogue.index');
        $attributeCatalogues = $this->attributeCatalogueService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $config['seo'] = __('messages.attributeCatalogue');
        $template = 'backend.attribute.catalogue.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'attributeCatalogues'
        ));
    }

    public function create()
    {
        // $this->authorize('module','attribute.catalogue.create');

        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',

                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js'

            ],
        ];
        $template = 'backend.attribute.catalogue.form';
        $config['seo'] = __('messages.attributeCatalogue');
        $config['method'] = 'create';
        $dropdown =$this->nestedset->dropdown();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }

    public function store(StoreAttributeCatalogueRequest $request)
    {
        if ($this->attributeCatalogueService->create($request)) {
            return redirect()->route('attribute.catalogue.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('attribute.catalogue.index')->with('error', 'Thêm mới không thành công');
    }

    public function edit($id)
    {   
        // $this->authorize('module','attribute.catalogue.update');

        $attributeCatalogue = $this->attributeCatalogueRepository->getAttributeCatalogueById($id, $this->language);
        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                  'backend/library/seo.js',

                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $dropdown =$this->nestedset->dropdown();

        $template = 'backend.attribute.catalogue.form';
        $config['seo'] = __('messages.attributeCatalogue');
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'attributeCatalogue',
            'dropdown'

        ));
    }

    public function update($id, UpdateAttributeCatalogueRequest $request)
    {   

        if ($this->attributeCatalogueService->update($id, $request)) {
            return redirect()->route('attribute.catalogue.index')->with('success', 'Cập nhật thành công');
        }

        return redirect()->route('attribute.catalogue.index')->with('error', 'Cập nhật không thành công');
    }

    public function destroy($id, Request $request)
    {
        // $this->authorize('module','attribute.catalogue.destroy');

        if (!AttributeCatalogue::isChildrenNode($id)) {
            return redirect()->route('attribute.catalogue.index')->with('error', 'Không thể xóa vì danh mục có con.');
        }
    
        if ($this->attributeCatalogueService->destroy($id, $request)) {
            return redirect()->route('attribute.catalogue.index')->with('success', 'Xoá thành công');
        }
    
        return redirect()->route('attribute.catalogue.index')->with('error', 'Xoá không thành công');
    }
    
    
}
