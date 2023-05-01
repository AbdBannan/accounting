<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturingTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturing_templates', function (Blueprint $table) {
            $table->id();
            $table->string("name")->default("");
            $table->foreignId("product_id")->default(0);
            $table->double("quantity")->default(0);
            $table->double("price")->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("product_id")->references("id")->on("products")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturing_templates');
    }
}
