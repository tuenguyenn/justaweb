<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface as MenuCatalogueRepository;
use App\Services\Interfaces\MenuCatalogueServiceInterface as MenuCatalogueService;
use App\Services\Interfaces\MenuServiceInterface as MenuService;


use App\Http\Requests\Menu\StoreMenuCatalogueRequest;
use Illuminate\Http\Request;  // Đảm bảo import đúng lớp này

class AjaxMenuController extends Controller
{   
    protected $menuCatalogueRepository;
    protected $menuCatalogueService;
    protected $menuService;

    public function __construct(
        MenuCatalogueRepository $menuCatalogueRepository,
        MenuCatalogueService $menuCatalogueService,
        MenuService $menuService
    )
    {
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->menuCatalogueService = $menuCatalogueService;
        $this->menuService = $menuService;
       
    }
    public function createCatalogue(StoreMenuCatalogueRequest $request){
        $menuCatalogue = $this->menuCatalogueService->create($request);
        if ($menuCatalogue !== FALSE) {
            return response()->json([
                'code'=> 0,
                'message' => 'Tạo thành công',
                'data' => $menuCatalogue
            ]);
        }
        return response()->json([
            'messages'  => 'Lỗi khi thêm mới',
            'code' =>1
            
        ]);
    }
    public function drag(Request $request){
        $json = json_decode($request->string('json'),TRUE);
        $menuCatalogue = $request->integer('menu_catalogue_id');
        
        $flag = $this->menuService->dragUpdate($json,$menuCatalogue);
    }
  
   

    
}
