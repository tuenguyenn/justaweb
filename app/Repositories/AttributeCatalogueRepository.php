<?php

namespace App\Repositories;
use App\Repositories\Interfaces\AttributeCatalogueRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\AttributeCatalogue;
/**
 * Class AttributeCatalogueService
 * @package App\Services
 */
class AttributeCatalogueRepository extends BaseRepository implements AttributeCatalogueRepositoryInterface
{
    protected $model  ;
    public function __construct(AttributeCatalogue $model)
    {
        $this->model = $model ;
        
    }
    public function getAttributeCatalogueById(int $id =0,$language_id =0){
        return $this->model->select(['attribute_catalogues.id',
                                    'attribute_catalogues.parent_id', 
                                    'attribute_catalogues.image', 
                                    'attribute_catalogues.icon', 
                                    'attribute_catalogues.album', 
                                    'attribute_catalogues.publish',
                                    'attribute_catalogues.follow',
                                    'tb2.name',
                                    'tb2.description',
                                    'tb2.content',
                                    'tb2.meta_title',
                                    'tb2.meta_keyword',
                                    'tb2.meta_description',
                                    'tb2.canonical',
                                   
                                    ])
                                    ->join('attribute_catalogue_language as tb2','tb2.attribute_catalogue_id','=','attribute_catalogues.id')
                                        ->where('tb2.language_id','=',$language_id)
                                        ->find($id);

    }
    public function getAll( $languageId =0){
        return $this->model->with(['attribute_catalogue_language'=> function($query) use($languageId){
            $query->where('language_id',$languageId);
        }])->get();
    }
    public function getAttributeByIdWhereIn($id =[],$language_id, $whereInField = 'id'){
        return $this->model->select(['attribute_catalogues.id',
                                    'tb2.name',
                                    ])->join('attribute_catalogue_language as tb2','tb2.attribute_catalogue_id','=','attribute_catalogues.id')

                                    ->where('publish',2)
                                    ->whereIn($whereInField,$id)
                                    ->where('tb2.language_id','=',$language_id)
                                    ->get();

                                    ;
    }
   
   


}
