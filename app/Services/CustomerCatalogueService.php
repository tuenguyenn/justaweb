<?php
namespace App\Services;
use App\Services\Interfaces\CustomerCatalogueServiceInterface;
use App\Repositories\Interfaces\CustomerCatalogueRepositoryInterface  ;
use App\Repositories\Interfaces\CustomerRepositoryInterface as CustomerRepository ;

use Illuminate\Support\Facades\DB;


/** 
 * Class CustomerCatalogueService
 * @package App\Services
 */
class CustomerCatalogueService implements CustomerCatalogueServiceInterface
{   
    protected $customerCatalogueRepository;
    protected $customerRepository;

    public function __construct(
        CustomerCatalogueRepositoryInterface $customerCatalogueRepository,
        CustomerRepository $customerRepository
        
        )
    {
        $this->customerCatalogueRepository = $customerCatalogueRepository;
        $this->customerRepository = $customerRepository;
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
        $customerCatalogue = $this->customerCatalogueRepository->pagination($columns,$condition,[],
                ['path'=>'customer/catalogue/index'], $perpage, ['customers']);
        return $customerCatalogue;
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token','send');  
            $customer = $this->customerCatalogueRepository->create($payload);
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
            $customer = $this->customerCatalogueRepository->update($id,$payload);
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
            $deleted = $this->customerCatalogueRepository->destroy($id);
            if ($deleted) {
                DB::commit();
                return redirect()->route('customer.catalogue.index')->with('success', 'customer deleted successfully.');
            } else {
                DB::rollBack();
                return redirect()->route('customer.catalogue.index')->with('error', 'customer not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('customer.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function updateStatus($post=[]){
        DB::beginTransaction();
        try {
            $payload[$post['field']] = (($post['value'] == 1) ? 2 : 1);
            $customerCatalogue = $this->customerCatalogueRepository->update($post['modelId'], $payload);
            $this->changecustomerStatus($post, $payload[$post['field']]);
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
            $flag = $this->customerCatalogueRepository->updateByWhereIn('id', $post['id'], $payload);
            
            $this->changeCustomerStatus($post, $post['value']);
            
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            return false;
        }
    }
    private function changeCustomerStatus($post, $value){
        DB::beginTransaction();
        try {
            $array = [];
            $payload[$post['field']] = $value;
    
            if (isset($post['modelId'])) {
                $array[] = $post['modelId'];
            } else {
                $array = $post['id']; 
            }
            $this->customerRepository->updateByWhereIn('customer_catalogue_id', $array, $payload);
            
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
                $customerCatalogue = $this->customerCatalogueRepository->findByid($key) ;
                $customerCatalogue->permissions()->sync($val); 
                

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