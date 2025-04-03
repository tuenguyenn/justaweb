<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Interfaces\MenuServiceInterface as MenuService;
use App\Repositories\Interfaces\MenuRepositoryInterface as MenuRepository;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;



use App\Models\Language;
use App\Http\Requests\Menu\StoreMenuRequest;
use App\Http\Requests\Menu\StoreChildrenMenu;





class MenuController extends Controller
{
    protected $menuService;
    protected $menuRepository;
    protected $menuCatalogueRepository;
    protected $menuCatalogueService;
    protected $languageRepository;
    public function __construct(
        MenuService $menuService,
        MenuRepository $menuRepository,
        MenuCatalogueRepository $menuCatalogueRepository,
        MenuCatalogueService $menuCatalogueService
        
        ,LanguageRepository $languageRepository

    ) {
        $this->menuService = $menuService;
        $this->menuRepository = $menuRepository;
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuCatalogueService = $menuCatalogueService;
        $this->languageRepository = $languageRepository;
        $this->middleware(function($request, $next){
            $locale = app()->getLocale();
            $language = Language::where("canonical", $locale)->first();
            $this->language =$language->id;
            return $next($request);
        });
    }


    public function index(Request $request)
    {
        // $this->authorize('module','menu.index');
        $menuCatalogues = $this->menuCatalogueService->paginate($request,$this->language);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css'
            ]
        ];
        
        $config['seo'] =  __('messages.menu');
        $template = 'backend.menu.menu.index';
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuCatalogues',
            

        ));
    }
    
    public function create()
    {   
        // $this->authorize('module','menu.create');
        $menuCatalogues =$this->menuCatalogueRepository->all();
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'backend/library/menuModal.js',


            ],
        ];
        $template = 'backend.menu.menu.form';
        $config['seo'] =  __('messages.menu');
        $config['method']= 'create';
        
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuCatalogues',

           
        ));
    }

    public function edit($id)
    {
        // $this->authorize('module','menu.update');
        $language =$this->language;
        $menus = $this->menuRepository->findByWhere(
            ['where'=>['menu_catalogue_id','=',$id]],TRUE,
            [
                'languages' => function($query) use ($language){
                    $query->where('language_id','=',$language);
                }
            ],['order','DESC']);
        $menuCatalogue =$this->menuCatalogueRepository->findById($id);

        $menuRecursive = recursive($menus);
        $menuString = recursiveMenu($menuRecursive);
        $config = [
            'js' => [
                'backend/library/menuModal.js',
                'backend/js/plugins/nestable/jquery.nestable.js'

            ],
        ];

        $template = 'backend.menu.menu.show';
        $config['seo'] =  __('messages.menu');
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menus',
            'menuString',
            'menuCatalogue',
            'id'
            
        ));
    }

    public function editMenu($id) {
        $language =$this->language;

        $menus = $this->menuRepository->findByWhere(
            ['where'=>
                ['menu_catalogue_id','=',$id],
                ['parent_id','=',0]
            ],TRUE,
            [
                'languages' => function($query) use ($language){
                    $query->where('language_id','=',$language);
                }
            ],['order','DESC']);
        $config = [
                'js' => [
                    'backend/js/plugins/switchery/switchery.js',
                    'backend/library/menuModal.js',
    
    
                ],
            ];
        $menuList = $this->menuService->convertMenu($menus);
        $config['seo'] =  __('messages.menu');
        $config['method']= 'update';
        $template = 'backend.menu.menu.form';
        $menuCatalogues =$this->menuCatalogueRepository->all();
        $menuCatalogue =$this->menuCatalogueRepository->findById($id);

        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menuList',
            'menuCatalogues',
            'menuCatalogue',
            'id'
        ));
       
        
    }
    public function store(StoreMenuRequest $request)
    {
        if ($this->menuService->save($request ,$this->language)) {
            $menuCatalogueId = $request->input('menu_catalogue_id');
            return redirect()->route('menu.edit',$menuCatalogueId)->with('success', 'Cập nhật bản ghi thành công');
        }
        return redirect()->route('menu.edit')->with('error', 'Cập nhật bản ghi không thành công');
    }
    public function destroy($id ){

        
        if ($this->menuService->destroy($id)) {
            return redirect()->route('menu.index')->with('success', 'Xoá thành công');
        }
        return redirect()->route('menu.index')->with('error', 'Xoá không thành công');
        
    }
    public function children($id){
        $language =$this->language;
        $menu = $this->menuRepository->findById($id,['*'],
        [
            'languages' => function($query) use ($language){
                $query->where('language_id','=',$language);
            }
        ]);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'backend/library/menuModal.js',


            ],
        ];
       
        $menuList = $this->menuService->getAndConvertMenu($menu,$language);
        $template = 'backend.menu.menu.children';
        $config['seo'] =  __('messages.menu');
        $config['method']= 'children';
        
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'menu',
            'menuList'

        ));
    }
    public function saveChildren(StoreChildrenMenu $request, $id)
    {   
        $menu = $this->menuRepository->findById($id);

        if ($this->menuService->saveChildren($request ,$this->language,$menu)) {
            return redirect()->route('menu.edit',['id'=>$menu->menu_catalogue_id])->with('success', 'Thêm mới thành công');
        }
        return redirect()->route('menu.edit',['id'=>$menu->menu_catalogue_id])->with('error', 'Thêm mới không thành công');
    }
    public function translate(int $languageId = 1, int $id =0){
        $language =$this->languageRepository->findById($languageId);
        $menuCatalogue = $this->menuCatalogueRepository->findById($id);
        
        $currentLanguage = $this->language;
        $menus = $this->menuRepository->findByWhere(
            ['where'=>
                ['menu_catalogue_id','=',$id],
               
            ],TRUE,
            [
                'languages' => function($query) use ($currentLanguage){
                    $query->where('language_id','=',$currentLanguage);
                }
            ],['lft','ASC']);
        $menus = $this->menuService->findMenuItemTranslate($menus,$currentLanguage,$languageId);
        $template = 'backend.menu.menu.translate';
        $config['seo'] =  __('messages.menu');
        $config['method']= 'translate';
        
        return view('backend.dashboard.layout', compact(
            'template',
            'config',
            'language',
            'languageId',
            'menuCatalogue',
            'menus'

           

        ));
    }
    public function saveTranslate(Request $request, int $languageId = 1, int $id =0){

        if ($this->menuService->saveTranslateMenu($request ,$languageId)) {
            return redirect()->route('menu.index')->with('success', 'Cập nhật bản dịch thành công');
        }
        return redirect()->route('menu.index')->with('error', 'Cập nhật bản dịch không thành công');
    }
    

}
