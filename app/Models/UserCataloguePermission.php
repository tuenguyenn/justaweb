<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCataloguePermission extends Model
{
    use HasFactory;
    protected $fillable = [];
    protected $tables = 'user_catalogue_permission';

    public function user_catalogues(){
        return $this->belongsTo(UserCatalogue::class,'user_catalogue_id');
    }
    public function permissions(){
        return $this->belongsTo(Permission::class,'permission_id');
    }
    
}
