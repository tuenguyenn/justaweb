<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueriesScope;

class MenuCatalogue extends Model
{
    use HasFactory,SoftDeletes,QueriesScope;
    protected $table = 'menu_catalogues';
    protected $guarded = [];

    public function menus(){
        return $this->hasMany(Menu::class,'menu_catalogue_id','id');
    }

}
