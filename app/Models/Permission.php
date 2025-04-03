<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueriesScope;

class Permission extends Model
{
    use HasFactory,QueriesScope;
    protected $fillable = [
        'name',
        'canonical',
    ];
    protected $table ='permissions';
    
   public function user_catalogues(){
    return $this->belongsTo(UserCatalogue::class, 'user_catalogue_permission', 'permission_id', 'user_catalogue_id');
}

   
}
