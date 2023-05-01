<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal', function (Blueprint $table) {
            $table->integer("row_id")->autoIncrement();
            $table->integer("invoice_id")->default(-1);
            $table->integer("line")->default(-1);
            $table->double("debit",20,10)->default(0);
            $table->double("credit",20,10)->default(0);
            $table->integer("first_part_id")->default(-1);
            $table->string("first_part_name")->default("");
            $table->integer("second_part_id")->default(-1);
            $table->string("second_part_name")->default("");
            $table->integer("product_id")->default(-1);
            $table->string("product_name")->default("");
            $table->integer("detail")->default(-1);
            $table->integer("invoice_type")->default(-1);
            $table->string("image")->default("default_invoice_img.png");
            $table->string("pound_type")->default("");
            $table->double("num_for_pound",20,10)->default(1);
            $table->timestamp("closing_date");
            $table->double("sum_of_balance",20,10)->default(0);
            $table->double("quantity",20,10)->default(0);
            $table->double("in_quantity",20,10)->default(0);
            $table->double("out_quantity",20,10)->default(0);
            $table->double("price",20,10)->default(0);
            $table->boolean("equivalent")->default(0);
            $table->boolean("posting")->default(0);
            $table->string("notes")->default("")->nullable();
            $table->string("s_date")->default("");
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
        Schema::dropIfExists('journal');
    }
}
