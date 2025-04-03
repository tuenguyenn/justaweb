<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueriesScope;

class {Module}Catalogue extends Model
{
    use HasFactory, SoftDeletes,QueriesScope;
    protected $guarded = [];

    protected $table ='{module}_catalogues';
    public function languages(){
        return $this->belongsToMany( Language::class, '{module}_catalogue_language','{module}_catalogue_id','language_id')
        ->withPivot('name',
                    'canonical',
                    'meta_title',
                    'meta_keyword',
                    'meta_description',
                    'description',
                    'content')->withTimestamps();
    } 
    public function {module}(){
        return $this->belongsToMany({Module}::class,'{module}_catalogue_{module}','{module}_catalogue_id','{module}_id');
    }
    public function {module}_catalogue_language(){
        return $this->hasMany({Module}CatalogueLanguage::class,'{module}_catalogue_id','id');
    }
    public static function isChildrenNode($id = 0)
    {
        ${module}Catalogue = {Module}Catalogue::find($id);
    
        if (!${module}Catalogue) {
            return false;
        }
        if (${module}Catalogue->rgt - ${module}Catalogue->lft > 1) {
            return false;
        }
    
        return true;
    }
    
}