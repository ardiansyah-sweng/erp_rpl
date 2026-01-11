<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bill_of_material', function (Blueprint $table) {
            $table->string('measurement_unit', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('bill_of_material', function (Blueprint $table) {
            $table->integer('measurement_unit')->change();
        });
    }
};