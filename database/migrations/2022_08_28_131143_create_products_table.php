<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name")->default("");
            $table->boolean("is_active")->default(true);
            $table->boolean("is_raw")->default(false);
            $table->integer("account_type")->default(0);
            $table->foreignId("category_id")->default(0);
            $table->string("image")->default("default_product_img.png");
            $table->foreignId("store_id")->default(1);
            $table->integer("group")->default(0);
            $table->integer("reference")->default(0);
            $table->integer("larg")->default(0);
            $table->string("notes")->default("")->nullable();
            $table->integer("debit")->default(0);
            $table->integer("credit")->default(0);
            $table->integer("st")->default(0);
            $table->string("nu1")->default(0);
            $table->double("price",20,10)->default(0);
//            $table->double("gainful_percentage",20,10)->default(0);
            $table->double("gainful_value",20,10)->default(0);
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
        Schema::dropIfExists('products');
    }
}
