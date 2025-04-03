<?php
namespace App\Services;
use App\Services\Interfaces\PermissionServiceInterface;
use App\Repositories\Interfaces\PermissionRepositoryInterface ;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


/** 
 * Class permissionService
 * @package App\Services
 */
class PermissionService implements PermissionServiceInterface
{   
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
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
        $permissions = $this->permissionRepository->pagination($columns,$condition,[],
                ['path'=>'permission/index'], $perpage);
    
        return $permissions;
    }
    public function create($request)
    {

        DB::beginTransaction();
        try {
            $payload = $request->except('_token','send');  
            $permission = $this->permissionRepository->create($payload);
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
            $permission = $this->permissionRepository->update($id,$payload);
            
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
            $deleted = $this->permissionRepository->destroy($id);
            if ($deleted) {
                DB::commit();
                return redirect()->route('permission.index')->with('success', 'permission deleted successfully.');
            } else {
                DB::rollBack();
                return redirect()->route('permission.index')->with('error', 'permission not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('permission.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
      
    public function updateStatus($post=[]){
        DB::beginTransaction();
        try {
            $payload[$post['field']]= (($post['value']==1)?2:1);
    
            $permission = $this->permissionRepository->update($post['modelId'],$payload);
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            return false;
        }
        
    }
    
  
       
    
}   