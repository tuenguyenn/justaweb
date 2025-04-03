<?php

namespace App\Repositories\Interfaces;
use App\Models\User;
/**
 * Interface BaseServiceInterface
 * @package App\Services\Interfaces
 */
interface BaseRepositoryInterface
{
    public function all(array $relation =[]);
    public function findById(int $id);
    public function create(array $payload =[]);
    public function createBatch(array $payload =[]);

    public function update(int $id =0, array $payload = []);
    public function returnModelUpdate(int $id =0, array $payload = []);

    public function destroy( $id);
    public function forceDelete( $id);
    public function pagination(
        array $columns = ['*'],
        array $condition = [],
        array $join = [],
        array $extend =[],


        $perpage ,
        array $relations = [],
        array $orderBy = [],
        array $rawQuery = [],


    );
    public function updateByWhereIn(string $whereInField ='', array $whereIn = [] , array $payload=[]);
    public function createPivot($model,array $payload=[],string $relation ='');
    public function findByWhere($condition =[],$flag=false,$relation =[],$orderBy=['id','DESC'],array $param =[],array $withCount =[]);
    public function findByCondition($condition =[]);
    public function forceDeleteByCondition(array $condition =[]);
    public function findByWhereHas(array $condition=[], string $relation= '' , string $alias ='',$flag = false);




}
