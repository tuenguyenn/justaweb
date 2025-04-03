<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueriesScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Widget extends Model
{   
    use HasFactory, SoftDeletes, QueriesScope;

    protected $guarded = [];
    protected $table = 'widgets';

    protected $casts = [
        'model_id'=> 'json',
        'album' => 'json',
        'description'=> 'json'
    ];
}
