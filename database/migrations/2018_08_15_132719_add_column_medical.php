<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMedical extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medical_reimbursement', function (Blueprint $table) {
            $table->integer('hr_benefit_id')->nullable();
            $table->dateTime('hr_benefit_date')->nullable();

            $table->integer('manager_hr_id')->nullable();
            $table->dateTime('manager_hr_date')->nullable();

            $table->integer('gm_hr_id')->nullable();
            $table->dateTime('gm_hr_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_reimbursement', function (Blueprint $table) {
            //
        });
    }
}
