<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        
    }
    
    public function index(){
        
        $template = 'backend.dashboard.home.index';
       
      

        return view('backend.dashboard.layout',compact(
            'template'

        ));
    }
}
