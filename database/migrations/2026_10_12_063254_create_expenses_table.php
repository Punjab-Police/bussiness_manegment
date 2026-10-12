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
    Schema::create('expenses', function (Blueprint $table) {
        $table->id();

        $table->foreignId('user_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->string('expense_number')->unique();
        $table->string('category');
        $table->string('description');
        $table->decimal('amount', 15, 2);

        $table->date('expense_date');
        $table->string('payment_method')
            ->default('cash');

        $table->string('reference_number')->nullable();
        $table->text('notes')->nullable();

        $table->string('status')->default('posted');

        $table->timestamps();

        $table->index(['expense_date', 'status']);
        $table->index('category');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
