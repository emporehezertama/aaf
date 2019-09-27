<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOvertime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtime_sheet', function (Blueprint $table) {
            $table->dateTime('hr_benefit_date')->nullable();
            $table->integer('hr_benefit_id')->nullable();
            
            $table->dateTime('hr_manager_date')->nullable();
            $table->integer('hr_manager_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overtime_sheet', function (Blueprint $table) {
            //
        });
    }
}
