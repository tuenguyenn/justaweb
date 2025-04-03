<?php
namespace App\Services;
use App\Services\Interfaces\UserServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface ;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;


/** 
 * Class UserService
 * @package App\Services
 */
class UserService implements UserServiceInterface
{   
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
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
        $condition['user_catalogue_id'] = $request->integer('user_catalogue_id');

        $columns = ['*']; 
        $perpage = $request ->integer('perpage');
        $users = $this->userRepository->pagination($columns,$condition,[],
                ['path'=>'user/index'], $perpage);
    
        return $users;
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token','send','re_password');  
            $payload['birthday']= $this->convertDob($payload['birthday']);

            $payload['password'] = Hash::make($payload['password']);
            $user = $this->userRepository->create($payload);
            
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

            $payload['birthday']= $this->convertDob($payload['birthday']);
            
            $user = $this->userRepository->update($id,$payload);
            
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
            $deleted = $this->userRepository->destroy($id);
            if ($deleted) {
                DB::commit();
                return redirect()->route('user.index')->with('success', 'User deleted successfully.');
            } else {
                DB::rollBack();
                return redirect()->route('user.index')->with('error', 'User not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('user.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
    private function convertDob( $birthday)
    {
        $carbonDate = Carbon::createFromFormat('Y-m-d', $birthday);
        $birthday= $carbonDate->format('Y-m-d H:i:s');
        return $birthday;
       }   
    public function updateStatus($post=[]){
        DB::beginTransaction();
        try {
            $payload[$post['field']]= (($post['value']==1)?2:1);
    
            $user = $this->userRepository->update($post['modelId'],$payload);
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
    
            $flag = $this->userRepository->updateByWhereIn('id',$post['id'],$payload);
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            return false;
        }
    }
}   