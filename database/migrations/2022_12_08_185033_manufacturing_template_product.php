<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ManufacturingTemplateProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturing_template_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId("manufacturing_template_id")->default(0);
            $table->foreignId("product_id")->default(0);
            $table->double("quantity")->default(0);
            $table->double("price")->default(0);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign("manufacturing_template_id")->references("id")->on("manufacturing_templates")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturing_template_product');
    }
}
