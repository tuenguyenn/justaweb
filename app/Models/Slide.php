<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\QueriesScope;

class Slide extends Model
{
    use HasFactory,SoftDeletes,QueriesScope;
    protected $guarded = [];
    protected $table = 'slides';
    
    protected $casts = [
        'item' => 'array',
        'setting' => 'array',
    ];

    
}
