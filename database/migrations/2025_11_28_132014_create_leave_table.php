<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leave', function (Blueprint $table) {
            $table->id();
            $table->integer('usersID');
            $table->dateTime('leaveStart');
            $table->dateTime('leaveEnd');
            $table->enum('status',['accepted', 'denied', 'pending']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave');
    }
};
