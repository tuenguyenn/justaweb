<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueriesScope;

class PostCatalogue extends Model
{
    use HasFactory, SoftDeletes,QueriesScope;
    protected $guarded = [];

    protected $table ='post_catalogues';
    public function languages(){
        return $this->belongsToMany( Language::class, 'post_catalogue_language','post_catalogue_id','language_id')
        ->withPivot('name',
                    'canonical',
                    'meta_title',
                    'meta_keyword',
                    'meta_description',
                    'description',
                    'content')->withTimestamps();
    } 
    public function posts(){
        return $this->belongsToMany(Post::class,'post_catalogue_post','post_catalogue_id','post_id');
    }
    public function post_catalogue_language(){
        return $this->hasMany(PostCatalogueLanguage::class,'post_catalogue_id','id');
    }
    public static function isChildrenNode($id = 0)
    {
        $postCatalogue = PostCatalogue::find($id);
    
        if (!$postCatalogue) {
            return false;
        }
        if ($postCatalogue->rgt - $postCatalogue->lft > 1) {
            return false;
        }
    
        return true;
    }
    
}