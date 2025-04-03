<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Customer extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'=> 'hashed',
    ];

    protected $attributes =[
        'publish' => 2,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    public function customer_catalogues(){
        return $this->belongsTo(CustomerCatalogue::class,'customer_catalogue_id','id');
    }
    public function sources(){
        return $this->belongsTo(Source::class,'source_id','id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }
 
   
    
}
