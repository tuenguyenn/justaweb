<?php

namespace App\Repositories;
use App\Repositories\Interfaces\SourceRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Source;

/**
 * Class SourceCatalogueService
 * @package App\Services
 */
class SourceRepository extends BaseRepository implements SourceRepositoryInterface
{
    protected $model  ;
    public function __construct(Source $model)
    {
        $this->model = $model ;
        
    }
   
    
    
    
   
   


}
