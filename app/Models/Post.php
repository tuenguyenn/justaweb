<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueriesScope;

class Post extends Model
{
    use HasFactory, SoftDeletes, QueriesScope;
    protected $guarded = [];

    protected $table ='posts';
    public function languages(){
        return $this->belongsToMany(Language::class, 'posts_language','post_id','language_id')
        ->withPivot('name',
                    'canonical',
                    'meta_title',
                    'meta_keyword',
                    'meta_description',
                    'description',
                    'content');
    } 

    public function post_catalogues(){
        return $this->belongsToMany(PostCatalogue::class,'post_catalogue_post','post_id','post_catalogue_id');
    }
   
   
}
