<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable()->after('id');
            $table->unsignedBigInteger('department_id')->nullable()->after('employee_id');
            $table->unsignedBigInteger('designation_id')->nullable()->after('department_id');
            $table->string('role')->after('designation_id');
            $table->string('first_name')->after('avatar');
            $table->string('last_name')->after('first_name');
            $table->string('work_phone')->nullable()->after('phone');
            $table->string('employment_type')->nullable()->after('status');
            $table->string('employment_status')->nullable()->after('employment_type');
            $table->string('source_of_hire')->nullable()->after('employment_status');
            $table->string('location')->nullable()->after('source_of_hire');
            $table->string('date_of_joining')->nullable()->after('location');
            $table->string('date_of_birth')->nullable()->after('date_of_joining');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('marital_status')->nullable()->after('gender');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn([
                'employee_id',
                'department_id',
                'designation_id',
                'role',
                'first_name',
                'last_name',
                'work_phone',
                'employment_type',
                'employment_status',
                'source_of_hire',
                'location',
                'date_of_joining',
                'date_of_birth',
                'gender',
                'marital_status',
            ]);
        });
    }
};
