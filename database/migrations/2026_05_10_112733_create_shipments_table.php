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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            // Tambahkan baris ini:
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            $table->string('biteship_order_id')->nullable();
            $table->string('waybill_id')->nullable();
            $table->string('courier_company');
            $table->string('courier_type');
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('destination_address');
            $table->string('status')->default('pending');
            $table->integer('price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};