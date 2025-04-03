<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueriesScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends Model
{   
    use HasFactory, SoftDeletes, QueriesScope;

    protected $guarded = [];
    protected $table = 'sources';

    public function customers(){
        return $this->hasMany(Customer::class, 'source_id', 'id');
    }
  
}
