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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            //user_id foreign key table users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            //name
            $table->string('name');
            //description
            $table->text('description');
            //location
            $table->string('location');
            //phone
            $table->string('phone');
            //city
            $table->string('city');
            //verify_status enum pending,approved,rejected default pending
            $table->enum('verify_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
