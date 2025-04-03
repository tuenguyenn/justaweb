<?php
namespace App\Http\Controllers\frontend;
use App\Http\Controllers\FrontendController;
use App\Repositories\Interfaces\RouterRepositoryInterface as RouterRepository;
use Illuminate\Http\Request;

class RouterController extends FrontendController
{
    protected $routerRepository;
    public function __construct(
        RouterRepository $routerRepository
    )
    {
        $this->routerRepository = $routerRepository;
       parent:: __construct();
    }

    public function index(string $canonical = '',Request $request)
    {   
        $router = $this->routerRepository->findByCondition(
            [
                ['canonical','=',$canonical],
                ['language_id','=',$this->language]
            ]
            );
        if(!is_null($router) && !empty($router)){
            $method = 'index';
            echo app($router->controllers)->{$method}($router->module_id,$request);
        }
      
    }
   
   
  
}
