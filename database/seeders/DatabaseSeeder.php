<?php

namespace Database\Seeders;
use App\functions\globalFunctions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
         DB::table("pounds")->truncate();
         DB::table("stores")->truncate();
         DB::table("roles")->truncate();
         DB::table("config")->truncate();
         DB::table("config_user")->truncate();
         DB::table("role_user")->truncate();
//        \App\Models\User::factory(4)->create()->each(function ($user){
//             $user->posts()->saveMany(\App\Models\Post::factory(5)->make());
//         });

        \App\Models\User::factory(1)->create(
            [
                "first_name" => "عبد القادر",
                "last_name" => "بناَّن",
                "email" => "abdulkhader@gmail.com",
                "password" => Hash::make('password'),
                "active" => 1,
                "profile_image" => "default_user_img.png"
            ]
        )->each(function ($user){
            $user->roles()->saveMany(
                \App\Models\Role::factory(1)->make(
                    [
                        "name" => "Admin",
                        "slug" => "Admin"
                    ]
                )
            );
            globalFunctions::initialUserConfig($user);
        });

        \App\Models\Pound::factory(1)->create(
            [
                "name" => "ل.س",
                "value" => 1,
            ]
        );

        \App\Models\Pound::factory(1)->create(
            [
                "name" => "دولار",
                "value" => 4500,
            ]
        );


        \App\Models\Store::factory(1)->create(
            [
                "name" => "المخزن الإفتراضي"
            ]
        );


    }
}



