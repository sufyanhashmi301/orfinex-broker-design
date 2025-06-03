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
      Schema::create('staff_team', function (Blueprint $table) {
    $table->unsignedBigInteger('manager_id');
    $table->unsignedBigInteger('member_id');
    $table->timestamps();
    
    $table->primary(['manager_id', 'member_id']);
    $table->foreign('manager_id')->references('id')->on('admins')->onDelete('cascade');
    $table->foreign('member_id')->references('id')->on('admins')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_team_pivot');
    }
};
