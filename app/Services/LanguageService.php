<?php
namespace App\Services;
use App\Services\Interfaces\LanguageServiceInterface;
use App\Repositories\Interfaces\LanguageRepositoryInterface ;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\RouterRepositoryInterface;
use App\Services\BaseService;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


/** 
 * Class LanguageService
 * @package App\Services
 */
class LanguageService extends BaseService implements LanguageServiceInterface
{   
    protected $languageRepository;
    protected $routerRepository;


    public function __construct(
        LanguageRepositoryInterface $languageRepository,
        RouterRepositoryInterface $routerRepository

    )
    {
        $this->languageRepository = $languageRepository;
        $this->routerRepository = $routerRepository;

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
        $columns = ['*']; 
        $perpage = $request ->integer('perpage');
        $languages = 
                    $this->languageRepository->pagination(
                        $columns,
                        $condition,
                        [],
                        ['path'=>'language/index'], 
                        $perpage);
                
        return $languages;
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token','send');  
            $payload['user_id'] = Auth::id();
            $language = $this->languageRepository->create($payload);
            
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
            $language = $this->languageRepository->update($id,$payload);
            
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
            $deleted = $this->languageRepository->destroy($id);
            if ($deleted) {
                DB::commit();
                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('language.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
      
    public function updateStatus($post=[]){
        DB::beginTransaction();
        try {
            $payload[$post['field']]= (($post['value']==1)?2:1);
    
            $language = $this->languageRepository->update($post['modelId'],$payload);
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            return false;
        }
        
    }
    public function updateAllStatus($post){
        DB::beginTransaction();
        try {
            $payload[$post['field']]= $post['value'];
    
            $flag = $this->languageRepository->updateByWhereIn('id',$post['id'],$payload);
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            return false;
        }
    }
    public function switch($id){
        DB::beginTransaction();
        try {
            $this->languageRepository->update($id,['current'=>1]);
            $payload=['current'=> 0];
            $where= [['id','!=', $id]];
            $this->languageRepository->updateByWhere($where,$payload);

    
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            return false;
        }
       
    }
    public function saveTranslate($option, $request) {
        DB::beginTransaction();
        $controllerName = $option['model'] . 'Controller';
        $modelId = $this->convertModelToField($option['model'] . '_id');
        try {
            $payload = [
                'name' => $request->input('translate_name'),
                'description' => $request->input('translate_description'),
                'content' => $request->input('translate_content'),
                'meta_title' => $request->input('translate_meta_title'),
                'meta_keyword' => $request->input('translate_meta_keyword'),
                'meta_description' => $request->input('translate_meta_description'),
                'canonical' => Str::slug($request->input('translate_canonical')),
                $modelId => $option['id'],
                'language_id' => $option['languageId']
            ];
    
            $repositoryNamespace = '\App\Repositories\\' . ucfirst($option['model']) . 'Repository';
            if (class_exists($repositoryNamespace)) {
                $repositoryInstance = app($repositoryNamespace);
            }
            $model = $repositoryInstance->findById($option['id']);
            $model->languages()->detach([$option['languageId'], $model->id]);
    
            $repositoryInstance->createPivot($model, $payload, 'languages');
    
            $routerCondition = [
                ['module_id', '=', $option['id']],
                ['controllers', '=', 'App\Http\Controllers\Frontend\\' . $controllerName],
                ['language_id', '=', $option['languageId']],
            ];
            $routerExist = $this->routerRepository->findByCondition($routerCondition);
    
            if ($routerExist) {
                $routerData = [
                    'canonical' => Str :: slug($request->input('translate_canonical')),
                    'module_id' => $option['id'],
                    'controllers' => 'App\Http\Controllers\Frontend\\' . $controllerName,
                    'language_id' => $option['languageId'],
                ];
                $this->routerRepository->update($routerExist->id, $routerData);
            } else {
                $routerData = [
                    'canonical' => Str :: slug($request->input('translate_canonical')),
                    'module_id' => $option['id'],
                    'controllers' => 'App\Http\Controllers\Frontend\\' . $controllerName,
                    'language_id' => $option['languageId'],
                ];
                $this->routerRepository->create($routerData);
            }
    
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Error: " . $e->getMessage();
            die(); 
        }
    }
    

       
    private function convertModelToField($model){
        $temp = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $model));
        return $temp;
    }
}   