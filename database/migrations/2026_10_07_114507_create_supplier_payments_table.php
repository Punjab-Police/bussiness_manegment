<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->date('payment_date');

            $table->decimal('amount', 15, 2);

            $table->string('payment_method')->default('cash');

            $table->string('reference_number')
                ->nullable();

            $table->text('notes')
                ->nullable();

            $table->timestamps();

            $table->index('payment_date');
            $table->index('payment_method');
            $table->index('reference_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};