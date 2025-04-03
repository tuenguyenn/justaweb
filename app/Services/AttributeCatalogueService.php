<?php

namespace App\Services;

use App\Services\Interfaces\AttributeCatalogueServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface;
use App\Repositories\Interfaces\RouterRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class AttributeCatalogueService extends BaseService implements AttributeCatalogueServiceInterface
{
    protected $attributeCatalogueRepository;
    protected $routerRepository;
    protected $nestedset;
    protected $controllerName = 'AttributeCatalogueController';

    public function __construct(
        AttributeCatalogueRepositoryInterface $attributeCatalogueRepository,
        RouterRepositoryInterface $routerRepository
    ) {
        $this->attributeCatalogueRepository = $attributeCatalogueRepository;
        $this->routerRepository = $routerRepository;
    }
    public function paginate($request)
    {
        $languageId = $this->getRegion();
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
        $condition['where'] = [
            ['tb2.language_id', '=', $languageId],
        ];
        $columns = ['*'];
        $perpage = $request->integer('perpage');
        return $this->attributeCatalogueRepository->pagination(
            $columns,
            $condition,
            [
                ['attribute_catalogue_language as tb2', 'tb2.attribute_catalogue_id', '=', 'attribute_catalogues.id'],
            ],
            ['path' => 'attribute/catalogue/index'],
            $perpage,
            [],
            ['attribute_catalogues.lft', 'asc']
        );
    }

    public function create($request)
    {
        return $this->handleTransaction(function () use ($request) {
            $payload = $request->only($this->payload());
            $payload['user_id'] = Auth::id();
            $attributeCatalogue = $this->attributeCatalogueRepository->create($payload);
    
            if ($attributeCatalogue->id > 0) {
                $this->createLanguageAndRouter($request, $attributeCatalogue);
                $this->nestedset = new NestedSetBie([
                    'table' => 'attribute_catalogues',
                    'foreignkey' => 'attribute_catalogue_id',
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
            $this->attributeCatalogueRepository->update($id, $payload);
    
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            $this->updateLanguageAndRouter($request, $attributeCatalogue);
            $this->nestedset = new NestedSetBie([
                'table' => 'attribute_catalogues',
                'foreignkey' => 'attribute_catalogue_id',
                'language_id' => $this->getRegion(),
            ]);
            $this->updateNestedSet();
    
            return true;
        });
    }
    private function payload()
    {
        return ['parent_id', 'follow', 'publish', 'image'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            $attributeCatalogue = $this->attributeCatalogueRepository->findById($id);
            if ($this->deleteLanguageAndRouter($attributeCatalogue,$id)) {
                $this->nestedset = new NestedSetBie([
                    'table' => 'attribute_catalogues',
                    'foreignkey' => 'attribute_catalogue_id',
                    'language_id' => $this->getRegion(),
                ]);
                $this->updateNestedSet();
                
            }
            return true;
        });
    }
    public function updateStatus($attributeCatalogue)
    {
        return $this->handleTransaction(function () use ($attributeCatalogue) {
            $payload[$attributeCatalogue['field']] = $attributeCatalogue['value'] == 1 ? 2 : 1;
            $this->attributeCatalogueRepository->update($attributeCatalogue['modelId'], $payload);
            return true;
        });
    }

    public function updateAllStatus($attributeCatalogue)
    {
        return $this->handleTransaction(function () use ($attributeCatalogue) {
            $payload[$attributeCatalogue['field']] = $attributeCatalogue['value'];
            $this->attributeCatalogueRepository->updateByWhereIn('id', $attributeCatalogue['id'], $payload);
            return true;
        });
    }
    
    private function createLanguageAndRouter($request, $attributeCatalogue)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->getRegion();
        $payloadLanguage['attribute_catalogue_id'] = $attributeCatalogue->id;
        $this->attributeCatalogueRepository->createPivot($attributeCatalogue, $payloadLanguage, 'languages');
    
        $router = $this->formatRouterPayload($attributeCatalogue, $request, $this->controllerName,$this->getRegion());
        $this->routerRepository->create($router);
    }
    
    private function updateLanguageAndRouter($request, $attributeCatalogue)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->getRegion();
        $payloadLanguage['attribute_catalogue_id'] = $attributeCatalogue->id;
    
        $attributeCatalogue->languages()->detach([$payloadLanguage['language_id'], $attributeCatalogue->id]);
        $this->attributeCatalogueRepository->createPivot($attributeCatalogue, $payloadLanguage, 'languages');
    
        $condition = [
            ['module_id', '=', $attributeCatalogue->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\attributeCatalogueController'],
            ['language_id','=', $this->getRegion()],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $payloadRouter = $this->formatRouterPayload($attributeCatalogue, $request, $this->controllerName,$this->getRegion());
        $this->routerRepository->update($router->id, $payloadRouter);
    }
    private function deleteLanguageAndRouter( $attributeCatalogue,$id)
    {
        $payloadLanguage['language_id'] = $this->getRegion();
        $attributeCatalogue->languages()->detach([$payloadLanguage['language_id'], $attributeCatalogue->id]);
        $attributeCatalogueRemain = $attributeCatalogue->languages()->where('attribute_catalogue_id', $attributeCatalogue->id)->exists();


        if (!$attributeCatalogueRemain) {
            $this->attributeCatalogueRepository->destroy($id);
        }
        $condition = [
            ['module_id', '=', $attributeCatalogue->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\attributeCatalogueController'],
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
