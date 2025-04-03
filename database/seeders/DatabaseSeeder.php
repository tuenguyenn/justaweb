<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
 
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {   
        DB::table("users")->insert([
            "name"=> "tue",
            "email"=> "tueng@gmail.com",
            "password"=> Hash::make("123456")
        ]);
    
        //  $this->call([
        //     UserSeeder::class
        
        // ]);
    }
}