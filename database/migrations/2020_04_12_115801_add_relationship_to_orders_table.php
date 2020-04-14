<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('product_id')->unsigned()->change();
            $table->foreign('product_id')->references('id_product')->on('products')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_product_id_foreign');
        });

        Schema::table('orders', function(Blueprint $table) {
            $table->dropIndex('orders_product_id_foreign');
        });

        Schema::table('orders', function(Blueprint $table) {
            $table->bigInteger('product_id')->change();
        });
    }
}
