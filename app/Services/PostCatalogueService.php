<?php

namespace App\Services;

use App\Services\Interfaces\PostCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\PostCatalogueRepositoryInterface;
use App\Repositories\Interfaces\RouterRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\Nestedsetbie;
use Illuminate\Support\Str;


class PostCatalogueService extends BaseService implements PostCatalogueServiceInterface
{
    protected $postCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $controllerName ='PostCatalogueController';
    public function __construct(
        PostCatalogueRepositoryInterface $postCatalogueRepository,
        RouterRepositoryInterface $routerRepository
    ) {
        
        $this->postCatalogueRepository = $postCatalogueRepository;
        $this->routerRepository = $routerRepository;
      
    }
   

    public function paginate($request)
    {
        $languageId =$this->getRegion();
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),

        ];
        $condition['where'] = [
            ['tb2.language_id', '=', $languageId],
        ];
        $columns = ['*'];
        $perpage = $request->integer('perpage');
        return $this->postCatalogueRepository->pagination(
            $columns,
            $condition,
            [
                ['post_catalogue_language as tb2', 'tb2.post_catalogue_id', '=', 'post_catalogues.id'],
               
            ],
            ['path' => 'post/catalogue/index'],
            $perpage,
            [],
            ['post_catalogues.lft', 'asc']
        );  
    }

    public function create($request)
    {
        return $this->handleTransaction(function () use ($request) {
            $payload = $request->only($this->payload());
            $payload['user_id'] = Auth::id();
            $payload['album']= json_encode($payload['album']);
            $postCatalogue = $this->postCatalogueRepository->create($payload);
    
            if ($postCatalogue->id > 0) {
                $this->createLanguageAndRouter($request, $postCatalogue);
                $this->nestedset = new NestedSetBie([
                    'table' => 'post_catalogues',
                    'foreignkey' => 'post_catalogue_id',
                    'language_id' => $this->getRegion(),
                ]);
                $this->updateNestedSet();
            }
            return true;
        });
    }
    
    public function update($id, $request)
    {
        return $this->handleTransaction(function () use ($id, $request) {
            $payload = $request->only($this->payload());
           
            $this->postCatalogueRepository->update($id, $payload);
          
            $payload['album']= json_encode($payload['album']);

            $postCatalogue = $this->postCatalogueRepository->findById($id);
            $this->updateLanguageAndRouter($request, $postCatalogue);

            $this->nestedset = new NestedSetBie([
                'table' => 'post_catalogues',
                'foreignkey' => 'post_catalogue_id',
                'language_id' => $this->getRegion(),
            ]);
            $this->updateNestedSet();
    
            return true;
        });
    }
    private function payload()
    {
        return ['parent_id', 'follow', 'publish', 'image','album'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            $postCatalogue = $this->postCatalogueRepository->findById($id);
            if ($this->deleteLanguageAndRouter($postCatalogue,$id)) {
                $this->nestedset = new NestedSetBie([
                    'table' => 'post_catalogues',
                    'foreignkey' => 'post_catalogue_id',
                    'language_id' => $this->getRegion(),
                ]);
                $this->updateNestedSet();
                
            }
            return true;
        });
    }
    public function updateStatus($postCatalogue)
    {
        return $this->handleTransaction(function () use ($postCatalogue) {
            $payload[$postCatalogue['field']] = $postCatalogue['value'] == 1 ? 2 : 1;
            $this->postCatalogueRepository->update($postCatalogue['modelId'], $payload);
            return true;
        });
    }

    public function updateAllStatus($postCatalogue)
    {
        return $this->handleTransaction(function () use ($postCatalogue) {
            $payload[$postCatalogue['field']] = $postCatalogue['value'];
            $this->postCatalogueRepository->updateByWhereIn('id', $postCatalogue['id'], $payload);
            return true;
        });
    }
    
    private function createLanguageAndRouter($request, $postCatalogue)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->getRegion();
        $payloadLanguage['canonical'] = Str::slug($payloadLanguage['canonical']);
        $payloadLanguage['post_catalogue_id'] = $postCatalogue->id;
        $this->postCatalogueRepository->createPivot($postCatalogue, $payloadLanguage, 'languages');
    
        $router = $this->formatRouterPayload($postCatalogue, $request, $this->controllerName,$this->getRegion());
        $this->routerRepository->create($router);
    }
    
    private function updateLanguageAndRouter($request, $postCatalogue)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['canonical'] = Str::slug($payloadLanguage['canonical']);
        $payloadLanguage['language_id'] = $this->getRegion();
        $payloadLanguage['post_catalogue_id'] = $postCatalogue->id;
    
        $postCatalogue->languages()->detach([$payloadLanguage['language_id'], $postCatalogue->id]);
        $this->postCatalogueRepository->createPivot($postCatalogue, $payloadLanguage, 'languages');
    
        $condition = [
            ['module_id', '=', $postCatalogue->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\PostCatalogueController'],
            ['language_id','=', $this->getRegion()],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $payloadRouter = $this->formatRouterPayload($postCatalogue, $request, $this->controllerName,$this->getRegion());
        $this->routerRepository->update($router->id, $payloadRouter);
    }
    private function deleteLanguageAndRouter( $postCatalogue,$id)
    {
        $payloadLanguage['language_id'] = $this->getRegion();
        $postCatalogue->languages()->detach([$payloadLanguage['language_id'], $postCatalogue->id]);
        $postCatalogueRemain = $postCatalogue->languages()->where('post_catalogue_id', $postCatalogue->id)->exists();


        if (!$postCatalogueRemain) {
            $this->postCatalogueRepository->destroy($id);
        }
        $condition = [
            ['module_id', '=', $postCatalogue->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\postCatalogueController'],
            ['language_id','=', $this->getRegion()],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $this->routerRepository->forceDelete($router->id);
    }

   

    private function handleTransaction($callback)
{
    DB::beginTransaction();
    try {
        $result = $callback();
        DB::commit();
        return $result;
    } catch (\Exception $e) {
        DB::rollBack();

        // Ghi lỗi vào log để theo dõi
        \Log::error('Transaction Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

        // Hiển thị lỗi chi tiết ra màn hình
        die("Error: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine());
    }
}

  
}
