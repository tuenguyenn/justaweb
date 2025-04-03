<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueriesScope;

class Menu extends Model
{
    use HasFactory,SoftDeletes,QueriesScope;
    protected $guarded = [];

    public function languages(){
        return $this->belongsToMany( Language::class, 'menu_language','menu_id','language_id')
        ->withPivot('menu_id',
                    'language_id',
                    'name',
                    'canonical',
                    )->withTimestamps();
    } 
    public function menu_catalogues(){
        return $this->belongsTo( MenuCatalogue::class, 'menu_catalogue_id','id');
    }
}
