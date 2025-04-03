<?php
namespace App\Services;

use App\Services\Interfaces\MenuCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\MenuCatalogueRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


/** 
 * Class MenuCatalogueService
 * @package App\Services
 */
class MenuCatalogueService extends BaseService implements MenuCatalogueServiceInterface
{   
    protected $menuCatalogueRepository;


    public function __construct(
        MenuCatalogueRepositoryInterface $menuCatalogueRepository,
    )
    {
        $this->menuCatalogueRepository = $menuCatalogueRepository;
    }

    public function paginate($request,$languageId)
    {
        
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $perpage = $request ->integer('perpage');
        $menuCatalogue = $this->menuCatalogueRepository->pagination(
                $this->panigateSelect(),
                $condition
                ,[],
                ['path'=>'menu/index'],
                 $perpage);
        return $menuCatalogue;
    }
   


    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->only('name','keyword');
            $payload['keyword'] = Str::slug($payload['keyword']);

            $menuCatalogue = $this->menuCatalogueRepository->create($payload);
            DB::commit();
            return [
                'name'=> $menuCatalogue->name,
                'id'=>$menuCatalogue->id,
            ];
        }catch (\Exception $e) {
            DB::rollBack();
    
            \Log::error('Transaction Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
    
            die("Error: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine());
        }
           
        
    }

    
    public function update($id, $request, $languageId)
    {
        return $this->handleTransaction(function () use ($id, $request, $languageId) {
           

            return true;
        });
    }

    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {         
            if ($this->menuCatalogueRepository->forceDelete($id)) {
                return true;
            }

           
        });
    }

   
    public function updateStatus($menuCatalogue)
    {
        return $this->handleTransaction(function () use ($menuCatalogue) {
            $payload = [$menuCatalogue['field'] => $menuCatalogue['value'] == 1 ? 2 : 1];
            $this->menuCatalogueRepository->update($menuCatalogue['modelId'], $payload);
            return true;
        });
    }

  
    public function updateAllStatus($menuCatalogue)
    {
        return $this->handleTransaction(function () use ($menuCatalogue) {
            $payload = [$menuCatalogue['field'] => $menuCatalogue['value']];
            $this->menuCatalogueRepository->updateByWhereIn('id', $menuCatalogue['id'], $payload);
            return true;
        });
    }

    /**
     * Các phương thức hỗ trợ chung
     */
 

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
    
    private function payload()
    {
        return ['follow', 'publish', 'image', 'menuCatalogue_catalogue_id'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
    private function panigateSelect()
    {
        return [
            'id',
            'name',
            'keyword',
            'publish'

        ];
    }


}
