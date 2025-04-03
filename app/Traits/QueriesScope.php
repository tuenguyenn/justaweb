<?php

namespace App\Traits;


trait QueriesScope 
{
    

    public function scopeKeyword($query, $keyword,$field =[],$whereHas =[]){
        if (!empty($keyword)) {
          
            if(count($field)){
               foreach($field as $key => $val){
              
                $query->orWhere($val, 'like', '%' . $keyword . '%');
               }

            }else{
               
                $query->where('name', 'like', '%' . $keyword . '%');
            }
            if(isset($whereHas) && count($whereHas)){
                $field = $whereHas['field'];
                $query->orWhereHas($whereHas['relation'],function ($query) use($field,$keyword){
                    $query->where($field, 'like', '%' . $keyword . '%');
                });
            }
        }
      
        return $query;
    }
   
    
    public function scopeCustomWhereCondition($query, $condition){
        if (count($condition)) {
          
            foreach($condition as $key => $val){
               
                if($val != 'none' && $val != ""){
                 
                    $query->where($key, $val);
                }
            }
        
            
        }
        return $query;
    }
    public function scopeCustomWhereCustomer($query, $customerId){
        if ( $customerId != null) {
            $query->where('customer_id', '=', $customerId);
        }
        return $query;

    }
    public function scopePublish($query, $publish){
        if ( $publish != 0) {
            $query->where('publish', '=', $publish);
        }
        return $query;

    }
    public function scopeCustomWhere($query, $where=[]){
        if ( !empty($where)) {
            foreach ($where as $key => $value) {
                $query->where($value[0],$value[1],$value[2]);
            }
        }
        return $query;
    }
    public function scopeCustomWhereRaw($query, $rawQuery){
        if (!empty($rawQuery['whereRaw'])) {
            foreach ($rawQuery['whereRaw'] as $key => $val) {
                if (!empty($val[1]) && is_array($val[1])) { 
                    $query->whereRaw($val[0], $val[1]);
                } 
            }
        }
        return $query;

    }
    public function scopeRelationCount($query, $relations){
      
        if (!empty($relations) && $this->model) { 
            dd($relations);
            foreach ($relations as $relation) {
                if (method_exists($this->model, $relation)) { 
                    $query->withCount($relation);
                }
            }
        }
        return $query;

    }
    public function scopeRelation($query, $relations){
        if (!empty($relations)) {
            foreach ($relations as $relation) {
                if (method_exists($this->model, $relation)) {
                    $query->with($relation);
                } 
            }
        }
        return $query;
    }
    public function scopeCustomJoin($query, $join){
        if (is_array($join)&& count($join)) {
            foreach ($join as $key => $value) {
                $query->join($value[0],$value[1],$value[2],$value[3]);
                
            }
        }
        return $query;
    }
    public function scopeCustomGroupBy($query, $groupBy){
        if (!empty($groupBy)) {
            $query->groupBy($groupBy);
        }
        return $query;
    }
    public function scopeCustomOrderBy($query, $orderBy){
        if (!empty($orderBy)) {
            $query->orderBy($orderBy[0],$orderBy[1]);
        }

        return $query;
    }
    public function scopecustomConditionCreated($query, $date){
        
        if (!empty($date)) {
            $explode = explode('-',$date);
            $explode = array_map('trim', $explode);
            $startDate = formatDate($explode[0],'Y-m-d 00:00:00');
            $endDate = formatDate($explode[1],'Y-m-d 23:59:59');
            
            $query->whereBetween('created_at',[$startDate,$endDate]);
           
        }

        return $query;
    } 

}
