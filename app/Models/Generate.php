<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\QueriesScope;

class Generate extends Model
{
    use HasFactory,QueriesScope;
    protected $table ='generates';
    protected $fillable = [
        'id',
        'name',
        'schema',
    ];
}
