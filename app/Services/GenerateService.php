<?php
namespace App\Services;
use App\Services\Interfaces\GenerateServiceInterface;
use App\Repositories\Interfaces\GenerateRepositoryInterface ;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\DB;



/** 
 * Class GenerateService
 * @package App\Services
 */
class GenerateService implements GenerateServiceInterface
{   
    protected $generateRepository;

    public function __construct(GenerateRepositoryInterface $generateRepository)
    {
        $this->generateRepository = $generateRepository;
    }

    /**
     * Phân trang người dùng
     *
     * @return mixed
     */
    
    public function paginate($request)
    {
        $condition['keyword'] = addslashes($request->input('keyword'));
        $condition['publish'] = $request->integer('publish');
        $columns = $this->panigateSelect(); 
        $perpage = $request ->integer('perpage');
        $generates = 
                    $this->generateRepository->pagination(
                        $columns,
                        $condition,
                        [],
                        ['path'=>'generate/index'], 
                        $perpage);
                
        return $generates;
    }
    public function create($request)
    {
        // DB::beginTransaction();
        try {
            $database = $this->makeDatabase($request);
            $model= $this->makeModel($request);
            $controller =$this->makeController($request);
            $repository= $this->makeRepository($request);    
            $service = $this->makeService($request);
            $provider =$this->makeProvider($request);

            $required=$this->makeRequest($request);
            $view = $this->makeView($request);
            $route=$this->makeRoute($request);

            $payload = $request->except('_token','send');  
            $payload['user_id'] = Auth::id();
            $generate = $this->generateRepository->create($payload);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack(); 
            echo $e->getMessage();die();
            return false;
        }
    }
    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token','send');  
            $generate = $this->generateRepository->update($id,$payload);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack(); 
            echo $e->getMessage();die();
            return false;
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $deleted = $this->generateRepository->destroy($id);
            if ($deleted) {
                DB::commit();
                return redirect()->route('generate.index')->with('success', 'generate deleted successfully.');
            } else {
                DB::rollBack();
                return redirect()->route('generate.index')->with('error', 'generate not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('generate.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    private function panigateSelect(){
        return [
            'id',
            'name',
            'schema',
        ];
    }

//database
private function makeDatabase($request)
{
   
        $payload = $request->only('schema', 'name', 'module_type');
        $module = $this->convertNameToMigrateName($payload['name']);
        $tableName = $module . 's';

        $this->generateMigrationFile($payload['schema'], $tableName);

        if ($payload['module_type'] !== 3) {
            $this->handlePivotAndRelations($module, $tableName);
        }
        Artisan::call('migrate');
        

       
    
}
private function generateMigrationFile($schema, $tableName)
{
    $migrationFileName = date('Y_m_d_His') . '_create_' . $tableName . '_table.php';
    $migrationPath = database_path('migrations/' . $migrationFileName);
    $migrationTemplate = $this->createMigrationFile([
        'schema' => $schema,
        'name' => $tableName,
    ]);
    file_put_contents($migrationPath, $migrationTemplate);
}
private function handlePivotAndRelations($module, $tableName)
{
    $foreignKey = $module . '_id';
    $pivotTableName = $module . '_language';

    // Tạo file migration cho bảng Pivot
    $pivotSchema = $this->pivotSchema($pivotTableName, $foreignKey, $tableName);
    $this->generatePivotMigration($pivotTableName, $pivotSchema);

    // Tạo file migration cho bảng quan hệ nếu cần
    $moduleExtract = explode('_', $module);
    if (count($moduleExtract) == 1) {
        $relationTable = $module . '_catalogue_' . $moduleExtract[0];
        $relationSchema = $this->relationSchema($relationTable, $moduleExtract[0]);
        $this->generateRelationMigration($relationTable, $relationSchema);
    }
}
private function generatePivotMigration($tableName, $schema)
{
    $migrationFileName = date('Y_m_d_His', time() + 10) . '_create_' . $tableName . '_table.php';
    $migrationPath = database_path('migrations/' . $migrationFileName);
    $migrationTemplate = $this->createMigrationFile([
        'schema' => $schema,
        'name' => $tableName,
    ]);
    file_put_contents($migrationPath, $migrationTemplate);
}
private function generateRelationMigration($tableName, $schema)
{
    $migrationFileName = date('Y_m_d_His', time() + 20) . '_create_' . $tableName . '_table.php';
    $migrationPath = database_path('migrations/' . $migrationFileName);
    $migrationTemplate = $this->createMigrationFile([
        'schema' => $schema,
        'name' => $tableName,
    ]);
    file_put_contents($migrationPath, $migrationTemplate);
}




    private function relationSchema($tableName='', $moduleExtract){ //module_catalogue_module
        $schema = <<<SCHEMA
 Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->unsignedBigInteger('{$moduleExtract}_catalogue_id');
            \$table->unsignedBigInteger('{$moduleExtract}_id');
            \$table->foreign('{$moduleExtract}_catalogue_id')->references('id')->on('{$moduleExtract}_catalogues')->onDelete('cascade');
            \$table->foreign('{$moduleExtract}_id')->references('id')->on('{$moduleExtract}s')->onDelete('cascade');
        });
        

SCHEMA;
    return $schema;
    }

    private function createMigrationFile($payload){
      
    
        $migrationTemplate= <<<MIGRATION
    <?php
    
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;
    
    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            {$payload['schema']}
            
        }
    
        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists("{$payload['name']}");

        }
    };
    
    MIGRATION;
    return $migrationTemplate;
    }

    private function convertNameToMigrateName($name)
    {
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
        return $temp ;
    }
    private function pivotSchema($tableName ='',$foreignKey ='',$pivot= ''){
       $pivotSchema = <<<SCHEMA
    Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->unsignedBigInteger('{$foreignKey}');
            \$table->unsignedBigInteger('language_id');
            \$table->foreign('{$foreignKey}')->references('id')->on('{$pivot}')->onDelete('cascade');
            \$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            \$table->string('name');
            \$table->text('description');
            \$table->longText('content');
            \$table->string('meta_title');
            \$table->string('meta_keyword');
            \$table->string('meta_description');
            \$table->string('canonical')->unique();
            \$table->timestamps();



        });
    
SCHEMA;
        return $pivotSchema;
}

// Controller
    private function makeController($request){
        $payload= $request->only('name','module_type');
        switch ($payload['module_type']) {
            case 'catalogue':
              $this-> createTemplateController($payload['name'],'TemplateCatalogueController');
              break;
            case 'detail':
                $this-> createTemplateController($payload['name'],'TemplateController');
                break;
            

            default:

          }

    }
    private function createTemplateController($name,$controllerFile){
        $controllerName =$name. 'Controller.php';
        $templateControllerPath = base_path('app\\Template\\controllers\\'.$controllerFile.'.php');
        $controllerContent = file_get_contents($templateControllerPath,'replace');
        $tableName =$this->convertNameToMigrateName($name).'s';


        $replace =[
            'ModuleTemplate' => $name,
            'moduleTemplate'=> lcfirst($name),
            'foreignKey'=> $this->convertNameToMigrateName($name).'_id',
            'moduleView'=> str_replace('_','.', $this->convertNameToMigrateName($name)),
            'tableName' => $tableName

        ];
        foreach($replace as $key => $value){
            $controllerContent = str_replace('{'.$key.'}',$replace[$key],$controllerContent);

        }
      

        $controllerPath = base_path('app/Http/Controllers/backend/'. $controllerName );
        file_put_contents($controllerPath,$controllerContent);
      
       
       
    }
 // MODEL
        private function makeModel($request){
            $payload= $request->only('name','module_type');
            $modelName =$request->input('name');

            switch ($payload['module_type']) {
            case 'catalogue':
                $this->createCatalogueModel($request,$modelName);
              break;
            case 'detail':
                $this->createModel($request,$modelName);
                $this->creatLanguageModel($request,$modelName);

                break;
            default:
                echo 1; die();

          }
           
        
        } 
        private function creatLanguageModel($request,$modelName){
            $template = base_path('app\\Template\\models\\LanguageModel.php');
            $content = file_get_contents($template,'replace');
            $module =$this->convertNameToMigrateName($modelName);
            $languageTable= $modelName.'CatalogueLanguage';
            $replace =[
                'Module' => $modelName,
                'module'=> $module,
            ];
            $newContent= $this->replaceContent($replace, $content);
            $this->createModelFile($languageTable,$newContent);
        }
        private function createModel($request,$modelName){
            $template = base_path('app\\Template\\models\\TemplateModel.php');
            $content = file_get_contents($template,'replace');
            $module =$this->convertNameToMigrateName($modelName);
            $replace =[
                'Module' => $modelName,
                'module'=> $module,
            ];
            $newContent= $this->replaceContent($replace, $content);
            $this->createModelFile($modelName,$newContent);
            
          
        }
        private function createCatalogueModel($request, $modelName){

            $templateModelPath = base_path('app\\Template\\models\\TemplateCatalogueModel.php');
            
            $modelContent = file_get_contents($templateModelPath,'replace');
    
            $module =$this->convertNameToMigrateName($modelName);
            $extractModule =explode('_',$module);
            $moduleReplace =ucfirst($extractModule[0]);
            $replace =[
                'Module' => $moduleReplace,
                'module'=> $extractModule[0],
            ];
            
            $newContent=$this->replaceContent($replace, $modelContent);
            $this->createModelFile($modelName , $newContent);
           
        }
        private function createModelFile($modelName='',$modelContent=''){
            $modelPath = base_path('app/Models/'. $modelName .'.php' );
            file_put_contents($modelPath,$modelContent);
        }
        private function replaceContent($replace,$content){
            $newContent=$content;

            foreach ($replace as $key => $value) {
                $newContent = str_replace('{' . $key . '}', $value, $newContent);
            }
            return $newContent;
        }
            

//REPOSITORY and SERVICE
    private function makeLayer($request, $type)
    {
      
            $this->initializeLayer($request, $type);
      
    }

    private function initializeLayer($request, $type)
    {
        $name = $request->input('name');
        $module = $this->convertNameToMigrateName($name);
        $moduleExtract = explode('_', $module);
        $path = str_replace('_', '/', strtolower($module));


        $option = [
            'repositoryName' => $name . 'Repository',
            'repositoryInterfaceName' => $name . 'RepositoryInterface',
            'serviceName' => $name . 'Service',
            'serviceInterfaceName' => $name . 'ServiceInterface',
        ];

        // Determine the file to generate based on type
        $templateMapping = [
            'repository'=> [
                'interface' => 'app\\Template\\repositories\\TemplateRepositoryInterface.php',
                'implementation' => 'app\\Template\\repositories\\TemplateRepository.php',
                'outputPath' => 'app\\Repositories\\',
                'nameKey' => 'repositoryName',
                'interfaceKey' => 'repositoryInterfaceName',
            ],

            'catalogueRepository' => [
                'interface' => 'app\\Template\\repositories\\TemplateRepositoryInterface.php',
                'implementation' => 'app\\Template\\repositories\\TemplateCatalogueRepository.php',
                'outputPath' => 'app\\Repositories\\',
                'nameKey' => 'repositoryName',
                'interfaceKey' => 'repositoryInterfaceName',
            ],
            'service' => [
                'interface' => 'app\\Template\\services\\ModuleServiceInterface.php',
                'implementation' => 'app\\Template\\services\\TemplateService.php',
                'outputPath' => 'app\\Services\\',
                'nameKey' => 'serviceName',
                'interfaceKey' => 'serviceInterfaceName',
            ],
            'catalogueService' => [
                'interface' => 'app\\Template\\services\\TemplateServiceInterface.php',
                'implementation' => 'app\\Template\\services\\TemplateCatalogueService.php',
                'outputPath' => 'app\\Services\\',
                'nameKey' => 'serviceName',
                'interfaceKey' => 'serviceInterfaceName',
            ],
        ];

        if (!isset($templateMapping[$type])) {
            throw new \Exception("Invalid type specified for layer generation.");
        }

        $template = $templateMapping[$type];

        // Interface
        $interfaceTemplatePath = base_path($template['interface']);
        $interfaceContent = file_get_contents($interfaceTemplatePath, 'replace');
        $interfaceContent = str_replace('{module}', $name, $interfaceContent);
        $interfaceOutputPath = base_path($template['outputPath'] . 'Interfaces\\' . $option[$template['interfaceKey']] . '.php');
        file_put_contents($interfaceOutputPath, $interfaceContent);

        // Implementation
        $implementationTemplatePath = base_path($template['implementation']);
        $implementationContent = file_get_contents($implementationTemplatePath, 'replace');

        $replace = [
            'Module' => $name,
            'module'=> lcfirst($name),
            'tableName' => $module . 's',
            'pivotTableName' => $module . '_language' ,
            'foreignKey' => $module . '_id',
            'relation'=> $module ,
            'path'=> $path,
        ];

        foreach ($replace as $key => $value) {
            $implementationContent = str_replace('{' . $key . '}', $value, $implementationContent);
        }

        $implementationOutputPath = base_path($template['outputPath'] . $option[$template['nameKey']] . '.php');
        file_put_contents($implementationOutputPath, $implementationContent);
        
        
}

// Wrapper methods for specific layers
private function makeRepository($request)
{   
    $module_type = $request->input('module_type');
   
    switch ($module_type) {
        case 'catalogue':
            return $this->makeLayer($request, 'catalogueRepository');         
             break;
        case 'detail':
            return $this->makeLayer($request, 'repository');         

            break;
        default:
            echo 1; die();

      }
    
}

private function makeService($request)
{   
    $module_type = $request->input('module_type');

    switch ($module_type) {
        case 'catalogue':
            return $this->makeLayer($request, 'catalogueService');
            break;
        case 'detail':
            return $this->makeLayer($request, 'service');

            break;
        default:
            echo 1; die();

      }
}

//Provider
    private function makeProvider($request){
        
            $name = $request->input('name');
            $provider = [
                'providerPath' => base_path('app/Providers/AppServiceProvider.php'),
                'repositoryProviderPath' => base_path('app/Providers/RepositoryServiceProvider.php'),
            ];
            
            foreach ($provider as $key => $value) {
                $content = file_get_contents($value);
              
            
                $insertLine = ($key == 'providerPath')
                    ? "'App\Services\Interfaces\\{$name}ServiceInterface' => 'App\Services\\{$name}Service',"
                    : "'App\Repositories\Interfaces\\{$name}RepositoryInterface' => 'App\Repositories\\{$name}Repository',";
            
                $position = strpos($content, '];');
                if ($position !== false) {
                    $newContent = substr($content, 0, $position) 
                                . "    " . $insertLine . PHP_EOL 
                                . substr($content, $position);
            
                    file_put_contents($value, $newContent);
                } 
                
            }
            
            

    }
    //REQUEST
    public function makeRequest($request){
      
            $name =$request->input('name');
            $requestArray =['Store'.$name.'Request','Update'.$name.'Request'];
            
            $requestTemplate=['TemplateRequestStore','TemplateRequestUpdate'];
    
            foreach ($requestTemplate as $key => $value) {
                $requestPath = base_path('app/Template/requests/'.$value.'.php');
                $requestContent = file_get_contents($requestPath);
                $requestContent = str_replace('{Module}',$name, $requestContent);
                $requestPut=base_path('app/Http/Requests/'.$requestArray[$key].'.php');
                file_put_contents($requestPut, $requestContent);
            }
            
      
       
    }
    //VIEW
    private function makeView($request)
    {
       
            $name = $request->input('name'); 
            $module = $this->convertNameToMigrateName($name);
            $extracModule = explode('_', $module);
            $basePath = resource_path('views/backend/'.$extracModule[0]);
    
            // Đường dẫn thư mục chính và component
            $folderPath = (count($extracModule) == 2) 
                ? "$basePath/{$extracModule[1]}" 
                : "$basePath/{$extracModule[0]}";
            $componentPath = "{$folderPath}/component";
           
            // Tạo thư mục nếu chưa tồn tại
            $this->createDirectory($folderPath);
            $this->createDirectory($componentPath);

            // Xử lý các file template chính
            $sourcePath = base_path('app/Template/views/'. ((count($extracModule) == 2) ? 'catalogue' : 'module').'/');
            $destinationComponentPath =$sourcePath.'component/';
            $componentSaveTableAddress = $componentPath .'/table.blade.php';

            $this->processFiles(['form.blade.php', 'index.blade.php'], $sourcePath, $folderPath, $name, $module, $extracModule);
           
            $this->processFile('table.blade.php',$destinationComponentPath , $componentSaveTableAddress, $name, $module,$extracModule);

            // Nếu count != 2, thêm file aside.blade.php vào component
            if (count($extracModule) != 2) {
                $componentSaveAsideAddress = $componentPath .'/aside.blade.php';
                $this->processFile('aside.blade.php', $destinationComponentPath, $componentSaveAsideAddress, $name, $module,$extracModule);
            }
           
          
    }
    
    
    private function createDirectory($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }
    }
    /**
     * Xử lý danh sách file: sao chép và thay thế nội dung.
     */
    private function processFiles(array $files, $sourcePath, $destinationPath, $name, $module, $extracModule = [])
    {
        foreach ($files as $file) {
            $this->processFile($file, $sourcePath, "{$destinationPath}/" . basename($file), $name, $module, $extracModule);
        }
    }
    
    /**
     * Xử lý file: thay thế nội dung và ghi file.
     */
    private function processFile($sourceFile, $sourcePath, $destinationFile, $name, $module, $extracModule = [])
    {
        $source = $sourcePath . $sourceFile;
        if (file_exists($source)) {
            $content = file_get_contents($source);
            
            $view = (count($extracModule) == 2) 
                ? "{$extracModule[0]}.{$extracModule[1]}" 
                : "{$extracModule[0]}";
            $link = str_replace("_", "/", $module);
    
            $replace = [
                'view' => $view,
                'module' => lcfirst($name),
                'Module' => $name,
                'link' => $link,
            ];
    
            foreach ($replace as $key => $value) {
                $content = str_replace('{' . $key . '}', $value, $content);
            }
            if (!file_exists($destinationFile)) {
                file_put_contents($destinationFile, $content);
            }
        }
    }
    // ROUTE 
    public function makeRoute($request){
      
        DB::beginTransaction();
        try {
            $name =$request->input('name');
            $module =$this->convertNameToMigrateName($name);
            $moduleExtract= explode('_', $module);
            $routesPath =base_path('routes/web.php');
            $content = file_get_contents($routesPath);
            $routeUrl =(count($moduleExtract) == 2) ? "{$moduleExtract[0]}/{$moduleExtract[1]}" : $moduleExtract[0];
            $routeName =(count($moduleExtract) == 2) ? "{$moduleExtract[0]}.{$moduleExtract[1]}" : $moduleExtract[0];
    
            $routeGroup = <<<ROUTE
        Route :: group(['prefix'=> '{$routeUrl}'],function(){
            Route :: get('/index',[{$name}Controller :: class, 'index'])-> name('{$routeName}.index');
            Route :: get('/create',[{$name}Controller :: class, 'create'])-> name('{$routeName}.create');
            Route :: post('/store',[{$name}Controller :: class, 'store'])-> name('{$routeName}.store');
            Route :: get('{id}/edit',[{$name}Controller :: class, 'edit'])->where(['id'=>'[0-9]+'])-> name('{$routeName}.edit');
            Route :: post('{id}/update',[{$name}Controller :: class, 'update'])->where(['id'=>'[0-9]+'])-> name('{$routeName}.update');
            Route :: post('destroy/{id}',[{$name}Controller :: class, 'destroy'])->where(['id'=>'[0-9]+'])-> name('{$routeName}.destroy');
    });
    
    //@@new-module@@
    
    ROUTE;
            $userController = <<<ROUTE
    use App\Http\Controllers\backend\\{$name}Controller;
    
    //@@userController@@
    
    
    ROUTE;
            $content = str_replace("//@@new-module@@", $routeGroup, $content);
    
            $content = str_replace("//@@userController@@", $userController, $content);
    
            file_put_contents($routesPath, $content);
            return true;
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('generate.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
        
        
    }
}
    