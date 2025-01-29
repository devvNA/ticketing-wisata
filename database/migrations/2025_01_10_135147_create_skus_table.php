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
        Schema::create('skus', function (Blueprint $table) {
            $table->id();
            //name
            $table->string('name');
            //category string
            $table->string('category');
            //event_id foreign key table events
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            //price
            $table->decimal('price', 10, 2);
            //stock
            $table->integer('stock');
            //day_type string
            $table->string('day_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skus');
    }
};
