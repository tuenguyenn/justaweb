<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Services\Interfaces\CustomerServiceInterface;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use function App\Services\payload;

class AuthController extends Controller
{   
    protected $customerRepository;
    protected $customerService;

    
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerServiceInterface $customerService
    )
    {
        $this->customerRepository = $customerRepository;
        $this->customerService = $customerService;

    }

    public function index()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard.index');
        }
        return view('backend.auth.login');
    }

    public function login(AuthRequest $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
    
        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();  
    
            $avatar = $user->image ?? 'default-avatar.png';  
            session(['avatar' => $avatar]);
    
            return redirect()->route('dashboard.index')->with('success', value: 'Đăng nhập thành công');
        }
    
        return redirect()->route('auth.admin')->with('error', 'Email hoặc mật khẩu không chính xác');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('auth.admin')->with('success', 'Đã đăng xuất thành công'); 
    }
    public function customer(Request $request)
    {
        if (auth('customer')->check()) {
            return redirect(''); 
        }
        $seo = (__('frontend.seo-login'));
        $config =[
            'js' =>[
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
            ],
            'css' =>[
                'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',

            ]
           
        ];
        return view('frontend.customer.index' ,compact(
            'seo',
            'config'
        )); 
    }
   public function customerLogin(AuthRequest $request)
    {
        {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
    
            if (Auth::guard('customer')->attempt($credentials)) {
                return redirect()->route('home.index')->with('success', value: 'Đăng nhập thành công');;
            }
    
            return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác']);
        }
    }
    public function customerLogout(Request $request)
    {
        Auth::guard('customer')->logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('customer')->with('success', 'Đã đăng xuất thành công'); 
    }
    public function handleGoogleCallback()
    {
        
            $googleUser = Socialite::driver('google')->user();
         
            // Kiểm tra dữ liệu từ Google
            if (!$googleUser || !$googleUser->getEmail()) {
                throw new \Exception("Google không trả về email.");
            }

            $customer = $this->customerRepository->findByCondition([
                ['email','=', $googleUser->getEmail()],
            ]);

            if (is_null($customer)) {
                $payload = [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'image' => $googleUser->getAvatar(),
                ];

                $customer = $this->customerRepository->create($payload);
            } else {
                // Nếu khách hàng đã tồn tại nhưng chưa có Google ID, cập nhật
                if (is_null($customer->google_id)) {
                    $customer->update(['google_id' => $googleUser->getId()]);
                }
            }

            // Đăng nhập user
            Auth::guard('customer')->login($customer);

            return redirect()->route('home.index')->with('success', 'Đăng nhập thành công'); 

        }

        public function showForgotPasswordForm()
        {
            $seo = (__('frontend.seo-login'));
            $config =[
                'js' =>[
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
                ],
                'css' =>[
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    
                ]
               
            ];
            return view('frontend.customer.forgot-password' ,compact(
                'seo',
                'config'
            )); 
         
        }
    
        public function sendResetEmail(ForgotPasswordRequest $request)
        {
           
            $customer =  $this->customerRepository->findByCondition([
                ['email','=', $request->input('email')],
            ]);
            
    
            if (!$customer) {
                return back()->with('error', 'Email không tồn tại!');
            }
    
            $token = Str::random(60);
            
            DB::table('password_resets')->updateOrInsert(
                ['email' => $customer->email],
                [
                    'token' => bcrypt($token),
                    'created_at' => now(),
                ]
            );
    
           
            $resetUrl = url("/reset-password?token=$token&email=" . urlencode($customer->email));
           
            Mail::to($customer->email)->send(new ResetPasswordMail($resetUrl));
           
            return back()->with('success', 'Đã gửi email đặt lại mật khẩu!');
        }
    
        
        public function showResetForm(Request $request)
        {   
            
            $seo = (__('frontend.seo-login'));
            $config =[
                'js' =>[
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
                ],
                'css' =>[
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    
                ]
               
            ];
            $token = $request->token;
            $email = $request->email;
           
            return view('frontend.customer.reset-password' ,compact(
                'seo',
                'config',
                'token' ,
                'email' 
            )); 
        }
    
        // Xử lý reset mật khẩu
        public function resetPassword(ResetPasswordRequest $request)
        {
            
            $resetData = DB::table('password_resets')
                ->where('email', $request->email)
                ->first();
         
            if (!$resetData || !password_verify($request->token, $resetData->token)) {
                return back()->withErrors(['email' => 'Token không hợp lệ hoặc đã hết hạn!']);
            }
         
           
            
            $customer = $this->customerRepository->findByCondition([
                ['email','=', $request->email],
            ]);
            
            $payload['password'] =  Hash::make($request->password);
            
            $customer = $this->customerService->changePassword($customer->id,$payload['password']);
    
            
            DB::table('password_resets')->where('email', $request->email)->delete();
    
            return redirect()->route('customer')->with('success', 'Mật khẩu đã được đặt lại thành công!');
        }
}


    

