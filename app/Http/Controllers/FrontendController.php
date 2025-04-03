<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cart;


use App\Models\Language;

class FrontendController extends Controller
{
 
    protected $language;

    public function __construct(
       
    ) {
        $this->setLanguage();
       
       
    }
    public function setLanguage(){
        $locale = app()->getLocale();
        $language = Language::where("canonical", $locale)->first();
        $this->language = $language->id;
    }

    

   

   

}
