<?php
namespace App\Http\Controllers\backend\Post;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Interfaces\PostCatalogueServiceInterface as PostCatalogueService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface as PostCatalogueRepository;
use App\Classes\Nestedsetbie;
use App\Http\Requests\Post\StorePostCatalogueRequest;
use App\Http\Requests\Post\UpdatePostCatalogueRequest;
use App\Models\Language;

use App\Models\PostCatalogue;

class PostCatalogueController extends Controller
{
    protected $postCatalogueService;
    protected $postCatalogueRepository;
    protected $nestedset;
    protected $language;


    public function __construct(
        PostCatalogueService $postCatalogueService,
        PostCatalogueRepository $postCatalogueRepository
    ) {
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language =$language->id;
            $this->initialize();
            return $next($request);
        });
        $this->postCatalogueService = $postCatalogueService;
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->initialize();

       

    }
    private function initialize(){
        $this->nestedset = new NestedSetBie([
            'table'=>'post_catalogues',
            'foreignKey'=> 'post_catalogue_id',
            'language_id'=>  $this->language,
        ]);
    }

    public function index(Request $request)
    {   

        $this->authorize('module','post.catalogue.index');
        $postCatalogues = $this->postCatalogueService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        $config['seo'] = __('messages.postCatalogue');
        $template = 'backend.post.catalogue.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'postCatalogues'
        ));
    }

    public function create()
    {
        $this->authorize('module','post.catalogue.create');

        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',

                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js',
                'backend/library/seo.js'

            ],
        ];
        $template = 'backend.post.catalogue.form';
        $config['seo'] = __('messages.postCatalogue');
        $config['method'] = 'create';
        $dropdown =$this->nestedset->dropdown();
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'dropdown'
        ));
    }

    public function store(StorePostCatalogueRequest $request)
    {
        if ($this->postCatalogueService->create($request)) {
            return redirect()->route('post.catalogue.index')->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('post.catalogue.index')->with('error', 'Thêm mới không thành công');
    }

    public function edit($id)
    {   
        $this->authorize('module','post.catalogue.update');

        $postCatalogue = $this->postCatalogueRepository->getPostCatalogueById($id, $this->language);
        $config = [
            'js' => [
                'backend/plugins/ckeditor/ckeditor.js',
                  'backend/library/seo.js',

                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/finder.js'
            ],
        ];
        $dropdown =$this->nestedset->dropdown();
        $album =json_decode($postCatalogue->album);
        $template = 'backend.post.catalogue.form';
        $config['seo'] = __('messages.postCatalogue');
        $config['method'] = 'edit';

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'postCatalogue',
            'dropdown',
            'album'

        ));
    }

    public function update($id, UpdatePostCatalogueRequest $request)
    {   

        if ($this->postCatalogueService->update($id, $request)) {
            return redirect()->route('post.catalogue.index')->with('success', 'Cập nhật thành công');
        }

        return redirect()->route('post.catalogue.index')->with('error', 'Cập nhật không thành công');
    }

    public function destroy($id, Request $request)
    {
        $this->authorize('module','post.catalogue.destroy');

        if (!PostCatalogue::isChildrenNode($id)) {
            return redirect()->route('post.catalogue.index')->with('error', 'Không thể xóa vì danh mục có con.');
        }
    
        if ($this->postCatalogueService->destroy($id, $request)) {
            return redirect()->route('post.catalogue.index')->with('success', 'Xoá thành công');
        }
    
        return redirect()->route('post.catalogue.index')->with('error', 'Xoá không thành công');
    }
    
    
}   