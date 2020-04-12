<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipsToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->bigInteger('type_id')->unsigned()->change();
            $table->foreign('type_id')->references('id_type')->on('types')
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
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign('customers_type_id_foreign');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_type_id_foreign');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->bigInteger('type_id')->change();
        });


    }
}
