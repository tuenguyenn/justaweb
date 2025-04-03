<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueriesScope;

class {Module} extends Model
{
    use HasFactory, SoftDeletes, QueriesScope;
    protected $guarded = [];

    protected $table ='{module}s';
    public function languages(){
        return $this->belongsToMany(Language::class, '{module}_language','{module}_id','language_id')
        ->withPivot('name',
                    'canonical',
                    'meta_title',
                    'meta_keyword',
                    'meta_description',
                    'description',
                    'content');
    } 

    public function {module}_catalogues(){
        return $this->belongsToMany({Module}Catalogue::class,'{module}_catalogue_{module}','{module}_id','{module}_catalogue_id');
    }
   
   
}
