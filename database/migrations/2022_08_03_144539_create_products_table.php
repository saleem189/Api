<?php

use App\Models\Product;
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
            $table->string('name')->nullable();
            $table->string('description', 1000)->nullable();
            $table->integer('quantity')->unsigned();
            $table->string('status')->default(Product::UNAVAILABLE_PRODUCT);
            $table->string('image')->nullable();
            $table->unsignedBigInteger('seller_id');
            $table->timestamps();
            $table->softDeletes(); //add deleted at column in table

            $table->foreign('seller_id')->references('id')->on('users');
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
