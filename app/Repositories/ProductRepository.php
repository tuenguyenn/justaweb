<?php

namespace App\Repositories;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductCatalogueService
 * @package App\Services
 */
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    protected $model  ;
    public function __construct(Product $model)
    {
        $this->model = $model ;
        
    }
    public function getProductById(int $id =0,$language_id =0 ,$customerId = null){
        return $this->model->select(['products.id',
                                    'products.product_catalogue_id', 
                                    'products.image', 
                                    'products.icon', 
                                    'products.album', 
                                    'products.code', 
                                    'products.price', 
                                    'products.made_in', 
                                    'products.attributeCatalogue',
                                    'products.attribute',
                                    'products.publish',
                                    'products.follow',
                                    'products.variant',
                                    'tb2.name',
                                    'tb2.description',
                                    'tb2.content',
                                    'tb2.meta_title',
                                    'tb2.meta_keyword',
                                    'tb2.meta_description',
                                    'tb2.canonical',
                                   
                                    ])
                                    ->join('product_language as tb2','tb2.product_id','=','products.id')
                                    ->with([
                                        'product_catalogues',
                                        'product_variants' => function($query) use ($language_id){
                                           $query->with(['attributes'=>function($query) use($language_id){
                                                $query->with(['attribute_language'=> function($query) use($language_id){
                                                    $query->where('language_id','=',$language_id);
                                                }]);
                                           }]);
                                        },
                                        'reviews' =>function($query) {
                                            $query->with('customers');
                                        }
                                    ]
                                        )
                                        ->where('tb2.language_id','=',$language_id)
                                        ->find($id);

    }
    public function findProductPromotion($condition = [], $relation =[],$language_id){
        $query = $this->model->newQuery();
           
        $query->select([
            'products.id',
            
            'products.image',
            'tb2.name',
        
            'tb3.id as product_variant_id',
            'tb3.uuid',
            DB::raw('CONCAT(tb2.name,"-",COALESCE(tb4.name,"default")) as variant_name'),
            DB::raw('COALESCE(tb3.sku, products.code) as sku'),
            DB::raw('COALESCE(tb3.price, products.price) as price'),
            ]);

            $query->join('product_language as tb2','tb2.product_id','=','products.id');
            $query->leftjoin('product_variants as tb3','tb3.product_id','=','products.id');
            $query->leftjoin('product_variant_language as tb4','tb4.product_variant_id','=','tb3.id');
        foreach($condition as $key => $val){
            $query->where($val[0],$val[1],$val[2]);
        }
        $query->where('tb2.language_id','=',$language_id);
        if(count($relation)){
            $query->with($relation);
        }
        $query->orderBy('id','desc');
        return $query->paginate(10);
    }
    public function filter($param, $perpage) {
        $query = $this->model->newQuery();
        $query->select('products.id', 'products.price', 'products.image');
        $query->selectRaw('AVG(reviews.score) as avg_score');
       
        if (isset($param['select']) && count($param['select'])) {
            foreach ($param['select'] as $key => $val) {
               
                if (is_string($val)) {
                    $query->selectRaw($val);
                }
            }
        }
    
        
        if (isset($param['join']) && count($param['join'])) {
            foreach ($param['join'] as $key => $val) {
                if ($val != null && count($val) === 4) {
                    $query->leftjoin($val[0], $val[1], $val[2], $val[3]);
                }
            }
        }
    
        // Điều kiện WHERE: kiểm tra để thêm điều kiện
        $query->where('products.publish', 2);
        
        if (isset($param['where']) && count($param['where'])) {
            foreach ($param['where'] as $key => $val) {
                if ($val != null) {
                    $query->where($val);
                }
            }
        }
        if (isset($param['whereRaw']) && count($param['whereRaw'])) {
            $query->whereRaw($param['whereRaw'][0], $param['whereRaw'][1]);
        }

    
      
        if (isset($param['having']) && count($param['having'])) {
            foreach ($param['having'] as $key => $val) {
                if ($val != null) {
                    $query->having($val);
                }
            }
        }
    
        
        if (isset($param['orderBy']) && !is_null($param['orderBy'][0]) && count($param['orderBy'])) {
           
            $query->orderBy('avg_score', $param['orderBy'][0]);
        }
        
    
        $query->groupBy(['products.id', 'products.price', 'products.image']);
        
        if (isset($param['groupBy']) && count($param['groupBy'])) {
            foreach ($param['groupBy'] as $key => $val) {
                if ($val != null) {
                    $query->groupBy([$val[0],$val[1]]);
                }
            }
        }
        $query->with(['reviews','languages','product_catalogues']);

        return $query->paginate($perpage);
    }
    public function getAllProducts(){
        return $this->model->select(  'products.id','products.image','tb2.name','tb2.canonical','products.price')
       
        ->join('product_language as tb2', 'tb2.product_id', '=', 'products.id')
        ->join('product_catalogue_product as tb3','products.id','=','tb3.product_id')
        ->where('products.publish','=',2)
        ->get();
    }
   
   


}
