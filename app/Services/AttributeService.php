<?php
namespace App\Services;

use App\Services\Interfaces\AttributeServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\AttributeRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\RouterRepositoryInterface;


/** 
 * Class AttributeService
 * @package App\Services
 */
class AttributeService extends BaseService implements AttributeServiceInterface
{   
    protected $attributeRepository;
    protected $controllerName ='AttributeController';
    protected $routerRepository;


    public function __construct(
        AttributeRepositoryInterface $attributeRepository,
        RouterRepositoryInterface $routerRepository

        )
    {
        $this->attributeRepository = $attributeRepository;
        $this->routerRepository = $routerRepository;
    }

    /**
     * Phân trang người dùng
     */
    public function paginate($request)
    {   
        $languageId = $this->getRegion();

        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
        $condition['attribute_catalogue_id']=$request->input('attribute_catalogue_id');
        $condition['where'] = [
            ['tb2.language_id', '=', $languageId],
        ];
        

        $perpage = $request->integer('perpage');
        $attribute= $this->attributeRepository->pagination(
            $this->panigateSelect(),
            $condition,
            [
                ['attribute_language as tb2', 'tb2.attribute_id', '=', 'attributes.id'],
                ['attribute_catalogue_attribute as tb3','attributes.id','=','tb3.attribute_id']
            ],
            ['path' => 'attribute/index' ,'groupBy'=>$this->panigateSelect()],
            $perpage,
            ['attribute_catalogues'],
            ['attributes.id', 'desc'],
            $this->whereRaw($request),

        );
        return $attribute;
    }
    private function whereRaw($request)
    {
        $rawCondition = [];
        if ($request->integer('attribute_catalogue_id') > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'tb3.attribute_catalogue_id IN (
                        SELECT id
                        FROM attribute_catalogues
                        WHERE lft >= (SELECT lft FROM attribute_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM attribute_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('attribute_catalogue_id'), $request->integer('attribute_catalogue_id')]
                ]
            ];
        }
    return $rawCondition;
    }


    public function create($request,$languageId)
    {
        return $this->handleTransaction(function () use ($request, $languageId) {
            $payload = $this->preparePayload($request);
            $attribute = $this->attributeRepository->create($payload);

            if ($attribute->id) {
                $this->createLanguageAndSyncCatalogue($attribute, $request,$languageId);
                 $router = $this->formatRouterPayload($attribute, $request,$this->controllerName,$languageId);
                $this->routerRepository->create($router);
            }
            return true;
        });
    }

    public function update($id, $request, $languageId)
    {
        return $this->handleTransaction(function () use ($id, $request, $languageId) {
            $payload = $this->preparePayload($request);
            $attribute = $this->attributeRepository->findById($id);

            if ($this->attributeRepository->update($id, $payload)) {
                $attribute->languages()->detach();
                $this->createLanguageAndSyncCatalogue($attribute, $request, $languageId);
                $condition = [
                    ['module_id', '=', $attribute->id],
                    ['controllers', '=', 'App\Http\Controllers\Frontend\attributeController'],
                    ['language_id', '=', $languageId],
                ];
                $router = $this->routerRepository->findByCondition($condition);
                $payloadRouter =$this->formatRouterPayload($attribute, $request,$this->controllerName,$languageId);
                $this->routerRepository->update($router->id, $payloadRouter);
            }

            return true;
        });
    }

    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            $attribute = $this->attributeRepository->findById($id);

            if ($this->deleteLanguageAndRouter($attribute,$id)) {
                return redirect()->route('attribute.index')->with('success', 'attribute deleted successfully.');
            }

            return redirect()->route('attribute.index')->with('error', 'attribute not found.');
        });
    }

    private function deleteLanguageAndRouter( $attribute,$id)
    {   
        $payloadLanguage['language_id'] = $this->getRegion();
        $attribute->languages()->detach([$payloadLanguage['language_id'], $attribute->id]);
        
        $attributeRemain = $attribute->languages()->where('attribute_id', $attribute->id)->exists();

        if (!$attributeRemain) {
            $this->attributeRepository->destroy($id);
        }
        
        $condition = [
            ['module_id', '=', $attribute->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\attributeController'],
            ['language_id','=', $this->getRegion()],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $this->routerRepository->forceDelete($router->id);
    }
    public function updateStatus($attribute)
    {
        return $this->handleTransaction(function () use ($attribute) {
            $payload = [$attribute['field'] => $attribute['value'] == 1 ? 2 : 1];
            $this->attributeRepository->update($attribute['modelId'], $payload);
            return true;
        });
    }

  
    public function updateAllStatus($attribute)
    {
        return $this->handleTransaction(function () use ($attribute) {
            $payload = [$attribute['field'] => $attribute['value']];
            $this->attributeRepository->updateByWhereIn('id', $attribute['id'], $payload);
            return true;
        });
    }

    /**
     * Các phương thức hỗ trợ chung
     */
    private function preparePayload($request)
    {
        $payload = $request->only($this->payload());
        $payload['user_id'] = Auth::id();
        return $payload;
    }

    private function createLanguageAndSyncCatalogue($attribute, $request,$languageId)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $languageId;
        $payloadLanguage['attribute_id'] = $attribute->id;

        $this->attributeRepository->createPivot($attribute, $payloadLanguage, 'languages');
        $catalogue = $request->has('catalogue') && is_array($request->input('catalogue'))
                    ? array_unique(array_merge($request->input('catalogue'), [$request->attribute_catalogue_id]))
                    : [$request->attribute_catalogue_id];
    
        $attribute->attribute_catalogues()->sync($catalogue);
    
       
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
    
            // Ghi lỗi vào log để theo dõi
            \Log::error('Transaction Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
    
            // Hiển thị lỗi chi tiết ra màn hình
            die("Error: " . $e->getMessage() . "<br>File: " . $e->getFile() . "<br>Line: " . $e->getLine());
        }
    }
    
    private function payload()
    {
        return ['follow', 'publish', 'image', 'attribute_catalogue_id'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
    private function panigateSelect()
    {
        return ['attributes.id','attributes.publish', 'attributes.image','attributes.order','tb2.name','tb2.canonical','tb2.meta_title'];
    }


}
