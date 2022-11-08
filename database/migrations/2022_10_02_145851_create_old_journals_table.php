<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOldJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_journal', function (Blueprint $table) {
            $table->integer("row_id")->autoIncrement();
            $table->integer("invoice_id");
            $table->integer("line");
            $table->float("debit")->default(0);
            $table->float("credit")->default(0);
            $table->float("first_part_id")->default(-1);
            $table->string("first_part_name")->default("");
            $table->float("second_part_id")->default(-1);
            $table->string("second_part_name")->default("");
            $table->float("product_id")->default(-1);
            $table->string("product_name")->default("");
            $table->integer("detail")->default(-1);
            $table->integer("invoice_type")->default(-1);
            $table->string("image")->default("systemImages#default_invoice_img.phg");
            $table->string("pound_type")->default("");
            $table->float("num_for_pound")->default(1);
            $table->timestamp("closing_date");
            $table->float("sum_of_balance")->default(0);
            $table->float("quantity")->default(0);
            $table->float("in_quantity")->default(0);
            $table->float("out_quantity")->default(0);
            $table->float("price")->default(0);
            $table->boolean("equivalent")->default(0);
            $table->boolean("posting")->default(0);
            $table->string("notes")->default(0);
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
        Schema::dropIfExists('old_journal');
    }
}
