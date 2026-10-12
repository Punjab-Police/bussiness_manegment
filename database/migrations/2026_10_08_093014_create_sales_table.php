<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('invoice_number')->unique();

            $table->date('sale_date');

            $table->decimal('subtotal', 15, 2)
                ->default(0);

            $table->decimal('discount', 15, 2)
                ->default(0);

            $table->decimal('tax', 15, 2)
                ->default(0);

            $table->decimal('total_amount', 15, 2)
                ->default(0);

            /*
             * Kept temporarily for compatibility.
             * Later customer payment allocations
             * will become the authoritative payment source.
             */
            $table->decimal('paid_amount', 15, 2)
                ->default(0);

            $table->decimal('due_amount', 15, 2)
                ->default(0);

            $table->string('status')
                ->default('posted');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('sale_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};