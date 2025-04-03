<?php
namespace App\Services;
use App\Services\Interfaces\UserCatalogueServiceInterface;
use App\Repositories\Interfaces\UserCatalogueRepositoryInterface  ;
use App\Repositories\Interfaces\UserRepositoryInterface as UserRepository ;

use Illuminate\Support\Facades\DB;


/** 
 * Class UserCatalogueService
 * @package App\Services
 */
class UserCatalogueService implements UserCatalogueServiceInterface
{   
    protected $userCatalogueRepository;
    protected $userRepository;

    public function __construct(
        UserCatalogueRepositoryInterface $userCatalogueRepository,
        UserRepository $userRepository
        
        )
    {
        $this->userCatalogueRepository = $userCatalogueRepository;
        $this->userRepository = $userRepository;
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
        $userCatalogue = $this->userCatalogueRepository->pagination($columns,$condition,[],
                ['path'=>'user/catalogue/index'], $perpage, ['users']);
        return $userCatalogue;
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token','send');  
            $user = $this->userCatalogueRepository->create($payload);
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
            $user = $this->userCatalogueRepository->update($id,$payload);
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
            $deleted = $this->userCatalogueRepository->destroy($id);
            if ($deleted) {
                DB::commit();
                return redirect()->route('user.catalogue.index')->with('success', 'User deleted successfully.');
            } else {
                DB::rollBack();
                return redirect()->route('user.catalogue.index')->with('error', 'User not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('user.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function updateStatus($post=[]){
        DB::beginTransaction();
        try {
            $payload[$post['field']] = (($post['value'] == 1) ? 2 : 1);
            $userCatalogue = $this->userCatalogueRepository->update($post['modelId'], $payload);
            $this->changeUserStatus($post, $payload[$post['field']]);
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
            $payload[$post['field']] = $post['value'];
            $flag = $this->userCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            
            $this->changeUserStatus($post, $post['value']);
            
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            return false;
        }
    }
    private function changeUserStatus($post, $value){
        DB::beginTransaction();
        try {
            $array = [];
            $payload[$post['field']] = $value;
    
            if (isset($post['modelId'])) {
                $array[] = $post['modelId'];
            } else {
                $array = $post['id']; 
            }
            $this->userRepository->updateByWhereIn('user_catalogue_id', $array, $payload);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack(); 
            echo $e->getMessage(); die();
            return false;
        }
    }
    public function setPermission($request){
        DB::beginTransaction();
        try {
            $permissions = $request->input('permission');
            foreach($permissions as $key=>$val){
                $userCatalogue = $this->userCatalogueRepository->findByid($key) ;
                $userCatalogue->permissions()->sync($val); 
                

            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack(); 
            echo $e->getMessage(); die();
            return false;
        }
    }
}   