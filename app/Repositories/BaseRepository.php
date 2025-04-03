<?php

namespace App\Repositories;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Base;
/**
 * Class BaseService
 * @package App\Services
 */
class BaseRepository implements BaseRepositoryInterface
{
   
    protected $model;
    public function __construct(Model $model)
    {
    $this->model = $model;
    }
    public function all(array $relation =[]){
        return $this->model->with($relation)->get();
    }
    
    public function pagination(
        array $columns = ['*'],
        array $condition = [],
        array $join = [],
        array $extend =[],
        $perpage ,
        array $relations = [],
        array $orderBy = ['id','desc'],
        array $rawQuery =[],
        
    )
    {
        $query = $this->model->select($columns);
        
        return $query->keyword($condition['keyword'] ?? null)
                ->publish($condition['publish'] ?? null)
                ->CustomWhere($condition['where'] ?? null)
                ->CustomWhereRaw($rawQuery ?? null) 
                ->relationCount($relations ?? null)
                ->CustomJoin($join ?? null)
                ->CustomGroupBy($extend['groupBy'] ?? null)
                ->CustomOrderBy($orderBy ?? null)
                ->paginate($perpage)
                ->withQueryString()->withPath(env('APP_URl').$extend['path']);
                ;

    }
    
    public function create(array $payload =[] ,$flag = false){
       
        $model = $this->model->create($payload);
       
        
        return ($flag == true) ? $model ->refresh() : $model->fresh();
    }
    public function createBatch(array $payload =[]){
        return $this->model->insert($payload);
    }
    public function update(int $id =0, array $payload = []){
        $model = $this->model->find($id);
        return $model->update($payload);
    }
    public function returnModelUpdate(int $id =0, array $payload = []){
        $model = $this->model->find($id);
        $model->fill($payload);
        $model->save();

        return $model;
    }
    public function updateByWhereIn(string $whereInField ='', array $whereIn = [] , array $payload=[]){
        $this ->model->whereIn($whereInField, $whereIn)->update($payload);
    }
    public function updateByWhere($condition = [], array $payload = [], $flag = false) {
        $query = $this->model->newQuery();
    
        foreach ($condition as $val) {
            $query->where($val[0], $val[1], $val[2]);
        }
    
        if ($flag === true) {
            $model = $query->first();
            if ($model) {
                $model->fill($payload);
                $model->save();
               
            }
            return $model; // Trả về model sau khi cập nhật
        }
    
        // Nếu flag = false, thực hiện update hàng loạt
        return $query->update($payload);
    }
    public function deleteByWhere($condition = []) {
        $query = $this->model->newQuery();
    
        foreach ($condition as $val) {
            $query->where($val[0], $val[1], $val[2]);
        }
    
        
        return $query->forceDelete();
    }
    
    public function findById(
        int $modelId, 
        array $columns = ['*'], 
        array $relations = [])
    {
        return $this->model->select($columns)
        ->with($relations)
        ->findOrFail($modelId);

        
    }
    public function destroy($id)
    {
        $model = $this->model->find($id);
        if ($model) {
            $model->delete();
            return true;
        }
        return $model;
    }

    public function forceDelete($id){
        $model = $this->model->find($id);
        if ($model) {
            $model->forceDelete();
            return true;
            }
            return $model;


    }
    public function createPivot($model,array $payload=[],string $relation=''){

        return $model->{$relation}()->attach($model->id,$payload);
    }
 
    public function findByCondition($condition =[]){
        $query= $this->model->newQuery();
        foreach($condition as $key =>$val){
            $query->where($val[0],$val[1],$val[2]);
        }

        return $query->first();
    }
    public function forceDeleteByCondition(array $condition =[]){
        $query= $this->model->newQuery();
        foreach($condition as $key =>$val){
            $query->where($val[0],$val[1],$val[2]);
        }

        return $query->forceDelete();
    }

    
    public function findByWhere($condition =[],$flag=false,$relation =[],$orderBy=['id','DESC'], array $param=[],array $withCount =[]){
        
        $query= $this->model->newQuery();
       
        foreach($condition as $key =>$val){
                
            $query->where($val[0],$val[1],$val[2]);
        }
        if (isset($param['whereIn'])) {
            $query->whereIn($param['whereInField'], $param['whereIn']);
        }
       
        $query->with($relation);
        $query->withCount($withCount);

        $query->orderBy($orderBy[0],$orderBy[1]);
        return ($flag== false) ? $query->first(): $query->get();
        
    }

    public function findByWhereHas(array $condition = [], string $relation = '', string $alias = '', $flag = false)
    {   
        
        return $this->model->with('languages')->whereHas($relation, function ($query) use ($condition, $alias) {
            foreach ($condition as $key => $val) {
                $query->where($alias . '.' . $val[0], $val[1], $val[2]);
            }
        })->when($flag == false, function ($query) {
            return $query->first();
        }, function ($query) {
            return $query->get();
        });
    }

    public function recursiveCategory(string $parameter = '', $model, int $languageId)
    {
        $table = Str::snake(Str::plural(class_basename($model)));
       
        $ids = array_filter(explode(',', $parameter));
        if (empty($ids)) {
            return []; 
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $languageTable = Str::snake(class_basename($model)) . '_language';
        $key = Str::snake(class_basename($model)).'_id';

        $query = "
            WITH RECURSIVE category_tree AS (
                SELECT DISTINCT c.id, c.parent_id, c.image, pcl.name ,pcl.canonical 
                FROM `$table` AS c
                JOIN `$languageTable` AS pcl ON pcl.`$key` = c.id
                WHERE c.id IN ($placeholders) AND pcl.language_id = ? AND c.publish = 2 
                
                UNION ALL
                
                SELECT  c.id, c.parent_id, c.image, pcl.name ,pcl.canonical 
                FROM `$table` AS c
                JOIN `$languageTable` AS pcl ON pcl.`$key` = c.id
                JOIN category_tree ct ON ct.id = c.parent_id
                WHERE pcl.language_id = ?  AND c.publish = 2 
            )
            SELECT DISTINCT id , name ,canonical,image FROM category_tree
        ";

        $params = array_merge($ids, [$languageId, $languageId]);

        $childrenId = DB::select($query, $params);
        
        $childrens =[];
        foreach ($childrenId as $key => $id) {
            $childrens[$key]['ids'] = $id->id;
            $childrens[$key]['name'] = $id->name;
            $childrens[$key]['canonical'] = $id->canonical;
            $childrens[$key]['image'] = $id->image;
        }
        return $childrens;
    }
    public function findObjectByCategoryIds(array $catIds = [], string $model = '', $languageId)
    {
        $table = Str::snake(Str::singular($model)); 
 
        
        $query = $this->model
            ->join($table . '_catalogue_' . $table . ' as tb2', 'tb2.' . $table . '_id', '=', $model . '.id') 
            ->with(['languages' => function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            }])
            ->when($table === 'product', function ($query) {
                return $query->with('product_variants');
            })
            ->where('publish', 2)
            ->whereIn('tb2.' . $table . '_catalogue_id', $catIds)
            ->orderBy('order', 'desc');
        return $query->get();   
    
        
    }
    public function breadcumb($model,$languageId){
        return $this->findByWhere([
            ['lft','<=',$model->lft],
            ['rgt','>=',$model->rgt],
            config('apps.general.defaultPublish')
        ],true,
        [
            'languages'=>function ($query) use ($languageId){
                $query->where('language_id',$languageId);
            }
        ]
        ,
        ['lft','asc']
    );
    }   
    



    
    
    
    
    
    
       
    
}
