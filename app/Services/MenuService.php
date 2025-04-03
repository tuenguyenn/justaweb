<?php
namespace App\Services;

use App\Services\Interfaces\MenuServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface;
use App\Repositories\Interfaces\RouterRepositoryInterface;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\Nestedsetbie;


/** 
 * Class MenuService
 * @package App\Services
 */
class MenuService extends BaseService implements MenuServiceInterface
{   
    protected $menuRepository;
    protected $nestedset;
    protected $menuCatalogueRepository;
    protected $routerRepository;


    public function __construct(
        MenuRepositoryInterface $menuRepository,
        MenuCatalogueRepositoryInterface $menuCatalogueRepository,  
        RouterRepositoryInterface $routerRepository,
        )
    {
        $this->menuRepository = $menuRepository;
        $this->menuCatalogueRepository = $menuCatalogueRepository;
        $this->routerRepository = $routerRepository;
    }

    public function paginate($request,$languageId)
    {
       
        return [];
    }
    public function save($request, $languageId)
    {
        return $this->handleTransaction(function () use ($request, $languageId) {
            $payload = $request->only('menu', 'menu_catalogue_id');
    
            if (isset($payload['menu']['name']) && count($payload['menu']['name'])) {
                // Lấy danh sách menu con hiện tại từ database
                $existingMenuIds = $this->menuRepository->getChildMenuIdsByCatalogue($payload['menu_catalogue_id']);
    
                // Lấy danh sách menu từ request
                $requestMenuIds = isset($payload['menu']['id']) ? array_filter($payload['menu']['id']) : [];
    
                // Tìm các menu con cần xóa
                $menuIdsToDelete = array_diff($existingMenuIds, $requestMenuIds);
    
                // Xóa menu con không có trong request
                if (!empty($menuIdsToDelete)) {
                    foreach ($menuIdsToDelete as $deleteId) {
                        $this->menuRepository->destroy($deleteId);
                    }
                }
    
                foreach ($payload['menu']['name'] as $key => $val) {
                    $menuId = $payload['menu']['id'][$key];
    
                    $menuArray = [
                        'menu_catalogue_id' => $payload['menu_catalogue_id'],
                        'order' => $payload['menu']['order'][$key],
                        'user_id' => Auth::id(),
                    ];
    
                    if ($menuId == 0) {
                        $menuSave = $this->menuRepository->create($menuArray);
                    } else {
                        $menuSave = $this->menuRepository->returnModelUpdate($menuId, $menuArray);
    
                        // Nếu menu có menu con thì cập nhật catalogue_id
                        if ($menuSave->rgt - $menuSave->lft > 1) {
                            $this->menuRepository->updateByWhere(
                                [
                                    ['lft', '>', $menuSave->lft],
                                    ['rgt', '<', $menuSave->rgt],
                                ],
                                ['menu_catalogue_id' => $payload['menu_catalogue_id']]
                            );
                        }
                    }
    
                    if ($menuSave->id > 0) {
                        // Xóa và cập nhật ngôn ngữ
                        $menuSave->languages()->detach([$languageId, $menuSave->id]);
    
                        $payloadLanguage = [
                            'language_id' => $languageId,
                            'name' => $val,
                            'canonical' => $payload['menu']['canonical'][$key],
                        ];
    
                        $this->menuRepository->createPivot($menuSave, $payloadLanguage, 'languages');
                    }
                }
    
                // Cập nhật Nested Set sau khi xử lý xóa và cập nhật
                $this->nestedset = new NestedSetBie([
                    'table' => 'menus',
                    'foreignkey' => 'menu_id',
                    'isMenu' => true,
                    'language_id' => $languageId,
                ]);
                $this->updateNestedSet();
            }
    
            return true;
        });
    }
    
    public function saveChildren($request, $languageId, $menu) {
        return $this->handleTransaction(function () use ($request, $languageId, $menu) {
            $payload = $request->only('menu');
            $existingMenuIds = $this->menuRepository->getChildMenuIds($menu->id);
            $requestMenuIds = isset($payload['menu']['id']) ? array_filter($payload['menu']['id']) : [];
    
            // Tìm các menu cần xóa (có trong database nhưng không có trong request)
            $menuIdsToDelete = array_diff($existingMenuIds, $requestMenuIds);
    
            // Xóa các menu con không còn trong request
            if (!empty($menuIdsToDelete)) {
                foreach ($menuIdsToDelete as $deleteId) {
                    $this->menuRepository->destroy($deleteId);
                }
            }
    
            // Cập nhật hoặc thêm mới menu
            if (!empty($payload['menu']['name'])) {
                foreach ($payload['menu']['name'] as $key => $val) {
                    $menuId = $payload['menu']['id'][$key];
    
                    $menuArray = [
                        'menu_catalogue_id' => $menu->menu_catalogue_id,
                        'parent_id' => $menu->id,
                        'order' => $payload['menu']['order'][$key],
                        'user_id' => Auth::id(),
                    ];
    
                    $menuSave = ($menuId == 0)
                        ? $this->menuRepository->create($menuArray)
                        : $this->menuRepository->returnModelUpdate($menuId, $menuArray);
    
                    if ($menuSave->id > 0) {
                        $menuSave->languages()->detach([$languageId, $menuSave->id]);
    
                        $payloadLanguage = [
                            'language_id' => $languageId,
                            'name' => $val,
                            'canonical' => $payload['menu']['canonical'][$key]
                        ];
    
                        $this->menuRepository->createPivot($menuSave, $payloadLanguage, 'languages');
                    }
                }
            }
    
            // Cập nhật Nested Set sau khi xóa và cập nhật xong
            $this->nestedset = new NestedSetBie([
                'table' => 'menus',
                'foreignkey' => 'menu_id',
                'isMenu' => true,
                'language_id' => $languageId,
            ]);
            $this->updateNestedSet();
    
            return true;
        });
    }
    
    public function getAndConvertMenu($menu = null,$language =1): array{
        $menuList =$this->menuRepository->findByWhere([
            ['parent_id' ,'=' ,$menu->id,]
        ],TRUE,
            [
                'languages' => function($query) use ($language){
                    $query->where('language_id','=',$language);
                }
            ]
        );
        return  $this->convertMenu($menuList);
        
       
    }
    public function convertMenu($menuList)  {
        $temp =[];
        $fields = ['name','canonical','order','id'];

        if(count($menuList)){
            foreach($menuList as $key => $val){
                foreach($fields as $field){
                    if($field == 'name' || $field == 'canonical'){
                        $temp[$field][]= $val->languages->first()->pivot->{$field};
                    }else{
                        $temp[$field][]= $val->{$field};
                    }

                }
            }
        }
        return $temp;
    }
   
    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
           $this->menuRepository->forceDeleteByCondition([
                ['menu_catalogue_id','=',$id],
           ]);

           $this->menuCatalogueRepository->forceDelete($id);
           return true;

        });
    }

   
    public function updateStatus($menu)
    {
        return $this->handleTransaction(function () use ($menu) {
            $payload = [$menu['field'] => $menu['value'] == 1 ? 2 : 1];
            $this->menuRepository->update($menu['modelId'], $payload);
            return true;
        });
    }

    public function updateAllStatus($menu)
    {
        return $this->handleTransaction(function () use ($menu) {
            $payload = [$menu['field'] => $menu['value']];
            $this->menuRepository->updateByWhereIn('id', $menu['id'], $payload);
            return true;
        });
    }
    public function findMenuItemTranslate($menus , int $currentLanguage =1,  int $languageId =1){
        $output =[];
        if(count($menus)){
            foreach($menus as $menu){
                $canonical = $menu->languages->first()->pivot->canonical;

                $detailMenu= $this->menuRepository->findById($menu->id,['*'],[  
                    'languages' => function($query) use ($languageId){
                    $query->where('language_id','=',$languageId);
                }]);
                
               
                if($detailMenu->languages->isNotEmpty()){
                      
                        $menu->translate_name = $detailMenu->languages->first()->pivot->name;
                        $menu->translate_canonical = $detailMenu->languages->first()->pivot->canonical;
                       
                   
                   
                }else{
                    
                    $router = $this->routerRepository->findByCondition([
                        ['canonical','=',$canonical]
                    ]);
                    if($router){
                         
                    $basename = class_basename($router->controllers); 
                    $model = Str::before($basename, 'Controller'); 
    
                   
                    $repositoryInterfaceNamespace ='\App\Repositories\\'.$model.'Repository';
                    if(class_exists($repositoryInterfaceNamespace)){
                        $repositoryInstance = app($repositoryInterfaceNamespace);
                    } 
                    if ($model === 'Post') {
                        $alias = 's_language';
                    } else {
                        $alias = Str::snake($model) . '_language';
                    }
                    ;
    
                    $object = $repositoryInstance->findByWhereHas([
                        ['canonical' ,'=', $router->canonical],
                        ['language_id','=', $currentLanguage],
                    ],'languages', $alias);
                    if($object){
                        $translateObject = $object->languages()->where('language_id', $languageId)
                            ->first([$alias.'.name',$alias.'.canonical']);
                        if(!is_null($translateObject)){
                            $menu->translate_name = $translateObject->name;
                            $menu->translate_canonical = $translateObject->canonical;
                        }
    
                    }
                }
              
                
                }
               
                $output[]=$menu;

            }
        }
        return $output;
    }
    public function saveTranslateMenu($request, int $languageId =1){
        return $this->handleTransaction(function () use ($request, $languageId) {
            $payload = $request->only('translate');
            $temp =[];
            if(count($payload['translate']['name'])){
                foreach ($payload['translate']['name'] as $key => $value) {
                   if($value == null) continue;
                  
                   $temp =[
                        'language_id'=> $languageId,
                        'name' => $value,
                        'canonical' => Str :: slug($payload['translate']['canonical'][$key]),
                   ];
                   $menu = $this->menuRepository->findById($payload['translate']['id'][$key]);
                   $menu->languages()->detach($languageId);
                   $this->menuRepository->createPivot($menu,$temp,'languages');
                   

                }

            }

            return true;
 
         });
    }


    private function handleTransaction($callback)
    {
        DB::beginTransaction();
        try {
            $result = $callback();
            DB::commit();
            return $result;
        }catch (\Exception $e) {
            DB::rollBack();
    
            \Log::error('Transaction Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
    
            die("Error: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine());
        }
    }
    public function dragUpdate(array $json =[], int $menuCatalogueId =0 , $parentId =0){
        if(count($json)){
            foreach($json as $key => $val){
                $update = [
                    'order'=> count($json)-$key,
                    'parent_id' => $parentId,
                ];
                $menu = $this->menuRepository->update($val['id'] , $update);
                if(isset($val['children']) && count($val['children'])){
                    $this->dragUpdate($val['children'],$menuCatalogueId,$val['id']);
                }
            }
        }
            $this->nestedset = new NestedSetBie([
                'table' => 'menus',
                'foreignkey' => 'menu_id',
                'isMenu'=>TRUE,
                'language_id' => $this->getRegion(),
            ]);
            $this->updateNestedSet();
    }
    
    


}
