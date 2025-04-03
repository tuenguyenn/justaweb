<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueriesScope;

class Review extends Model
{
    use HasFactory, QueriesScope;
    protected $guarded = [];

    protected $table ='reviews';
    public function reviewable(){
        return $this->morphTo();
    }   
    public function customers(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
}
