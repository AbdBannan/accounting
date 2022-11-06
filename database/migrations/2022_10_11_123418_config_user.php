<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConfigUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_user', function (Blueprint $table) {
            $table->primary(["config_id","user_id"]);
            $table->foreignId("config_id");
            $table->foreignId("user_id");
            $table->string("value");
            $table->timestamps();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
        });
//        CREATE table uuu (id int PRIMARY KEY,u_id int REFERENCES u.id ON DELETE CASCADE,CONSTRAINT F_K_1 FOREIGN KEY u_id  REFERENCES u(id))

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_user');
    }
}
