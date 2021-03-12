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
            $table->integer('exchange_id');
            $table->string('name');
            $table->string('asset');
            $table->string('currency');
            $table->string('min_size')->nullable();
            $table->string('max_size')->nullable();
            $table->string('min_total')->nullable();
            $table->string('increment')->nullable();
            $table->string('asset_increment')->nullable();
            $table->string('label');            
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
