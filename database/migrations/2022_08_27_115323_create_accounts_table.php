<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
//            $table->id();
            $table->integer("id");
            $table->string("name")->default("");
            $table->integer("account_type")->default(1);
            $table->integer("group")->default(0);
            $table->integer("reference")->default(0);
            $table->integer("larg")->default(0);
            $table->string("notes")->default("")->nullable();
            $table->integer("debit")->default(0);
            $table->integer("credit")->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
