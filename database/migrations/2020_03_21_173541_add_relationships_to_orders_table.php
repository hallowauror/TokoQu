<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('customer_id')->unsigned()->change();
            $table->foreign('customer_id')->references('id_customer')->on('customers')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::table('orders', function(Blueprint $table) {
            $table->dropForeign('orders_customer_id_foreign');
        });

        Schema::table('orders', function(Blueprint $table) {
            $table->dropIndex('orders_customer_id_foreign');
        });

        Schema::table('orders', function(Blueprint $table) {
            $table->bigInteger('customer_id')->change();
        });

        Schema::table('orders', function(Blueprint $table) {
            $table->dropForeign('orders_user_id_foreign');
        });

        Schema::table('orders', function(Blueprint $table) {
            $table->dropIndex('orders_user_id_foreign');
        });

        Schema::table('orders', function(Blueprint $table) {
            $table->bigInteger('user_id')->change();
        });
    }
}
