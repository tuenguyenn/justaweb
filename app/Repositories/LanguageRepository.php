<?php

namespace App\Repositories;
use App\Repositories\Interfaces\LanguageRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Language;
/**
 * Class LanguageService
 * @package App\Services
 */
class LanguageRepository extends BaseRepository implements LanguageRepositoryInterface
{
    protected $model  ;
    public function __construct(Language $model)
    {
        $this->model = $model ;
        
    }
  
  
  
   


}
