<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         DB::statement("SET FOREIGN_KEY_CHECKS=0;");
         DB::table("users")->truncate();
         DB::table("posts")->truncate();
         DB::table("roles")->truncate();
        \App\Models\Role::factory(3)->create();
        \App\Models\User::factory(4)->create()->each(function ($user){
             $user->posts()->saveMany(\App\Models\Post::factory(5)->make());
         });
    }
}
