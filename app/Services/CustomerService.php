<?php
namespace App\Services;
use App\Services\Interfaces\CustomerServiceInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface ;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


/** 
 * Class CustomerService
 * @package App\Services
 */
class CustomerService implements CustomerServiceInterface
{   
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
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
        $condition['customer_catalogue_id'] = $request->integer('customer_catalogue_id');

        $columns = ['*']; 
        $perpage = $request ->integer('perpage');
        $customers = $this->customerRepository->pagination($columns,$condition,[],
                ['path'=>'customer/index'], $perpage);
    
        return $customers;
    }
    public function create($request)
    {
        DB::beginTransaction();
        try {
            $payload = $request->except('_token','send','re_password');  
          
            if(isset($payload['birthday'])){
                $payload['birthday']= $this->convertDob($payload['birthday']);
            }
            $payload['password'] = Hash::make($payload['password']);
            $customer = $this->customerRepository->create($payload);
            
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
            if(isset($payload['fullname'])){
                $payload['name'] = $payload['fullname'];
                unset($payload['fullname']);
            }
            if(isset($payload['birthday'])){    
                $payload['birthday']= $this->convertDob($payload['birthday']);
            }
            
            $customer = $this->customerRepository->update($id,$payload);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack(); 
            echo $e->getMessage();die();
            return false;
        }
    }
    public function changePassword($id,$payload)
    {
        DB::beginTransaction();
        try {
            $payload =[
                'password'=> $payload,
            ];
           
         
            $customer = $this->customerRepository->returnModelUpdate($id,$payload);
          
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
            $deleted = $this->customerRepository->destroy($id);
            if ($deleted) {
                DB::commit();
                return redirect()->route('customer.index')->with('success', 'customer deleted successfully.');
            } else {
                DB::rollBack();
                return redirect()->route('customer.index')->with('error', 'customer not found.');
            }
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->route('customer.index')->with('error', 'An error occurred: ' . $e->getMessage());
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
    
            $customer = $this->customerRepository->update($post['modelId'],$payload);
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
    
            $flag = $this->customerRepository->updateByWhereIn('id',$post['id'],$payload);
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            return false;
        }
    }
    public function generateOtp( $customer)
    {
        $otp = rand(100000, 999999);

        $payload = [
            'email_otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5) // Hết hạn sau 5 phút
        ];
        $customer = $this->customerRepository->returnModelUpdate($customer->id,$payload);

        $this->sendOtpEmail($customer->email, $otp);

        return $otp;
    }

    public function sendOtpEmail($email, $otp)
    {
        Mail::raw("Mã OTP của bạn là: $otp. Mã này sẽ hết hạn sau 5 phút.", function ($message) use ($email) {
            $message->to($email)
                    ->subject('Mã OTP xác thực email');
        });
    }
    public function validateOtp($request){
      
        $otpArray = $request->input('otp');
       
        $otp = implode("", $otpArray);
       
        $customer = $this->customerRepository->findByWhere([
            ['email_otp','=', $otp],
            ['otp_expires_at', '>=', Carbon::now()]
        ]
        );
       

        if (!$customer) {
            return  false;
        }
        $payload = [
            'email_verified_at' => Carbon::now(),
            'email_otp' => null,
            'otp_expires_at' => null,
        ];
       
        return $this->customerRepository->update($customer->id ,$payload);
      
    }
    public function resendOtp( $id)
    {
        $otp = rand(100000, 999999);

        $payload = [
            'email_otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5) // Hết hạn sau 5 phút
        ];
        $customer = $this->customerRepository->returnModelUpdate($id,$payload);

        
        $this->sendOtpEmail($customer->email, $otp);

        return true;
    }
}   