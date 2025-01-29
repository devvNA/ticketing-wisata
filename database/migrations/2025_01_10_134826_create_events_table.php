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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            //vendor_id foreign key table vendors
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            //event_category_id foreign key table event_categories
            $table->foreignId('event_category_id')->constrained()->onDelete('cascade');
            //name
            $table->string('name');
            //description
            $table->text('description');
            //image
            $table->string('image');
            //start_date
            $table->date('start_date');
            //end_date nullable
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
