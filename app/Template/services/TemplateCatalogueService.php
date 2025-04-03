<?php

namespace App\Services;

use App\Services\Interfaces\{Module}ServiceInterface;
use App\Services\BaseService;
use App\Repositories\Interfaces\{Module}RepositoryInterface;
use App\Repositories\Interfaces\RouterRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Classes\Nestedsetbie;
use App\Models\Language;

class {Module}Service extends BaseService implements {Module}ServiceInterface
{
    protected ${module}Repository;
    protected $routerRepository;
    protected $nestedset;
    protected $controllerName = '{Module}Controller';

    public function __construct(
        {Module}RepositoryInterface ${module}Repository,
        RouterRepositoryInterface $routerRepository
    ) {
        $this->{module}Repository = ${module}Repository;
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
        return $this->{module}Repository->pagination(
            $columns,
            $condition,
            [
                ['{relation}_language as tb2', 'tb2.{foreignKey}', '=', '{tableName}.id'],
            ],
            ['path' => '{path}/index'],
            $perpage,
            [],
            ['{tableName}.lft', 'asc']
        );
    }

    public function create($request)
    {
        return $this->handleTransaction(function () use ($request) {
            $payload = $request->only($this->payload());
            $payload['user_id'] = Auth::id();
            ${module} = $this->{module}Repository->create($payload);
    
            if (${module}->id > 0) {
                $this->createLanguageAndRouter($request, ${module});
                $this->nestedset = new NestedSetBie([
                    'table' => '{tableName}',
                    'foreignkey' => '{foreignKey}',
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
            $this->{module}Repository->update($id, $payload);
    
            ${module} = $this->{module}Repository->findById($id);
            $this->updateLanguageAndRouter($request, ${module});
            $this->nestedset = new NestedSetBie([
                'table' => '{tableName}',
                'foreignkey' => '{foreignKey}',
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
            if ($this->{module}Repository->destroy($id)) {
                $this->nestedset = new NestedSetBie([
                    'table' => '{tableName}',
                    'foreignkey' => '{foreignKey}',
                    'language_id' => $this->getRegion(),
                ]);
                $this->updateNestedSet();
                return true;
            }
            return false;
        });
    }
    public function updateStatus(${module})
    {
        return $this->handleTransaction(function () use (${module}) {
            $payload[${module}['field']] = ${module}['value'] == 1 ? 2 : 1;
            $this->{module}Repository->update(${module}['modelId'], $payload);
            return true;
        });
    }

    public function updateAllStatus(${module})
    {
        return $this->handleTransaction(function () use (${module}) {
            $payload[${module}['field']] = ${module}['value'];
            $this->{module}Repository->updateByWhereIn('id', ${module}['id'], $payload);
            return true;
        });
    }
    
    private function createLanguageAndRouter($request, ${module})
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->getRegion();
        $payloadLanguage['{foreignKey}'] = ${module}->id;
        $this->{module}Repository->createPivot(${module}, $payloadLanguage, 'languages');
    
        $router = $this->formatRouterPayload(${module}, $request, $this->controllerName);
        $this->routerRepository->create($router);
    }
    
    private function updateLanguageAndRouter($request, ${module})
    {
        $payloadLanguage = $request->only($this->payloadLanguage());
        $payloadLanguage['language_id'] = $this->getRegion();
        $payloadLanguage['{foreignKey}'] = ${module}->id;
    
        ${module}->languages()->detach([$payloadLanguage['language_id'], ${module}->id]);
        $this->{module}Repository->createPivot(${module}, $payloadLanguage, 'languages');
    
        $condition = [
            ['module_id', '=', ${module}->id],
            ['controllers', '=', 'App\Http\Controllers\Frontend\{Module}Controller'],
        ];
        $router = $this->routerRepository->findByCondition($condition);
        $payloadRouter = $this->formatRouterPayload(${module}, $request, $this->controllerName);
        $this->routerRepository->update($router->id, $payloadRouter);
    }

    protected function getLanguageIdByLocale($locale)
    {
        $languages = [
            'vn' => 1,
            'en' => 2,
        ];

        return $languages[$locale] ?? 1;
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

    private function getRegion(){
        $appLocale = app()->getLocale(); 
        return $this->getLanguageIdByLocale($appLocale); 
        
    }
  
}
