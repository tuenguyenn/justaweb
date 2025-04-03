<?php

namespace App\Repositories;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Menu;

/**
 * Class MenuCatalogueService
 * @package App\Services
 */
class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    protected $model  ;
    public function __construct(Menu $model)
    {
        $this->model = $model ;
        
    }
    public function getChildMenuIds($parentId) {
        return $this->model->where('parent_id', $parentId)->pluck('id')->toArray();
    }
    public function getChildMenuIdsByCatalogue($menuCatalogueId) {
        return $this->model->where('menu_catalogue_id', $menuCatalogueId)
                            ->where('level',1)
        ->pluck('id')->toArray();
    }
    
    
  
    
    
   
   


}
