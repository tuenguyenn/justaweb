<?php
namespace App\Services;

use App\Services\Interfaces\{Module}ServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\{Module}RepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\Interfaces\RouterRepositoryInterface;


/** 
 * Class {Module}Service
 * @package App\Services
 */
class {Module}Service extends BaseService implements {Module}ServiceInterface
{   
    protected ${module}Repository;
    protected $controllerName ='{Module}Controller';
    protected $routerRepository;


    public function __construct(
        {Module}RepositoryInterface ${module}Repository,
        RouterRepositoryInterface $routerRepository

        )
    {
        $this->{module}Repository = ${module}Repository;
        $this->routerRepository = $routerRepository;
    }

    /**
     * Phân trang người dùng
     */
    public function paginate($request,$languageId)
    {
        $condition = [
            'keyword' => addslashes($request->input('keyword')),
            'publish' => $request->integer('publish'),
        ];
        $condition['{module}_catalogue_id']=$request->input('{module}_catalogue_id');
        $condition['where'] = [
            ['tb2.language_id', '=', $languageId],
        ];
        

        $perpage = $request->integer('perpage');
        ${module}= $this->{module}Repository->pagination(
            $this->panigateSelect(),
            $condition,
            [
                ['{module}_language as tb2', 'tb2.{module}_id', '=', '{module}s.id'],
                ['{module}_catalogue_{module} as tb3','{module}s.id','=','tb3.{module}_id']
            ],
            ['path' => '{module}.index' ,'groupBy'=>$this->panigateSelect()],
            $perpage,
            ['{module}_catalogues'],
            ['{module}s.id', 'desc'],
            $this->whereRaw($request),

        );
        return ${module};
    }
    private function whereRaw($request)
    {
        $rawCondition = [];
        if ($request->integer('{module}_catalogue_id') > 0) {
            $rawCondition['whereRaw'] = [
                [
                    'tb3.{module}_catalogue_id IN (
                        SELECT id
                        FROM {module}_catalogues
                        WHERE lft >= (SELECT lft FROM {module}_catalogues as pc WHERE pc.id = ?)
                        AND rgt <= (SELECT rgt FROM {module}_catalogues as pc WHERE pc.id = ?)
                    )',
                    [$request->integer('{module}_catalogue_id'), $request->integer('{module}_catalogue_id')]
                ]
            ];
        }
    return $rawCondition;
    }


    public function create($request,$languageId)
    {
        return $this->handleTransaction(function () use ($request, $languageId) {
            $payload = $this->preparePayload($request);
            ${module} = $this->{module}Repository->create($payload);

            if (${module}->id) {
                $this->createLanguageAndSyncCatalogue(${module}, $request,$languageId);
                 $router = $this->formatRouterPayload(${module}, $request,$this->controllerName,$languageId);
                $this->routerRepository->create($router);
            }
            return true;
        });
    }

    public function update($id, $request, $languageId)
    {
        return $this->handleTransaction(function () use ($id, $request, $languageId) {
            $payload = $this->preparePayload($request);
            ${module} = $this->{module}Repository->findById($id);

            if ($this->{module}Repository->update($id, $payload)) {
                ${module}->languages()->detach();
                $this->createLanguageAndSyncCatalogue(${module}, $request, $languageId);
                $condition = [
                    ['module_id', '=', ${module}->id],
                    ['controllers', '=', 'App\Http\Controllers\Frontend\{module}Controller'],
                    ['language_id', '=', $languageId],
                ];
                $router = $this->routerRepository->findByCondition($condition);
                $payloadRouter =$this->formatRouterPayload(${module}, $request,$this->controllerName,$languageId);
                $this->routerRepository->update($router->id, $payloadRouter);
            }

            return true;
        });
    }

    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            ${module} = $this->{module}Repository->findById($id);

            if ($this->deleteLanguageAndRouter(${module},$id)) {
                return redirect()->route('{module}.index')->with('success', '{module} deleted successfully.');
            }

            return redirect()->route('{module}.index')->with('error', '{module} not found.');
        });
    }

    private function deleteLanguageAndRouter( ${module},$id)
    {   
        $payloadLanguage['language_id'] = $this->getRegion();
        ${module}->languages()->detach([$payloadLanguage['language_id'], ${module}->id]);
        
        ${module}Remain = ${module}->languages()->where('{module}_id', ${module}->id)->exists();

        if (!${module}Remain) {
            $this->{module}Repository->destroy($id);
        }
        
        $condition = [
            ['module_id', '=', ${module}->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\{module}Controller'],
            ['language_id','=', $this->getRegion()],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $this->routerRepository->forceDelete($router->id);
    }
    public function updateStatus(${module})
    {
        return $this->handleTransaction(function () use (${module}) {
            $payload = [${module}['field'] => ${module}['value'] == 1 ? 2 : 1];
            $this->{module}Repository->update(${module}['modelId'], $payload);
            return true;
        });
    }

  
    public function updateAllStatus(${module})
    {
        return $this->handleTransaction(function () use (${module}) {
            $payload = [${module}['field'] => ${module}['value']];
            $this->{module}Repository->updateByWhereIn('id', ${module}['id'], $payload);
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

    private function createLanguageAndSyncCatalogue(${module}, $request,$languageId)
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $languageId;
        $payloadLanguage['{module}_id'] = ${module}->id;

        $this->{module}Repository->createPivot(${module}, $payloadLanguage, 'languages');
        $catalogue = $request->has('catalogue') && is_array($request->input('catalogue'))
                    ? array_unique(array_merge($request->input('catalogue'), [$request->{module}_catalogue_id]))
                    : [$request->{module}_catalogue_id];
    
        ${module}->{module}_catalogues()->sync($catalogue);
    
       
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
        return ['follow', 'publish', 'image', '{module}_catalogue_id'];
    }

    private function payloadLanguage()
    {
        return ['name', 'description', 'content', 'meta_title', 'meta_keyword', 'meta_description', 'canonical'];
    }
    private function panigateSelect()
    {
        return ['{module}s.id','{module}s.publish', '{module}s.image','{module}s.order','tb2.name','tb2.canonical','tb2.meta_title'];
    }


}
