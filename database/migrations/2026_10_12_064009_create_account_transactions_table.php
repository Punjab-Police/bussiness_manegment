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
    Schema::create('account_transactions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('account_id')
            ->constrained()
            ->restrictOnDelete();

        $table->foreignId('user_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();

        $table->date('transaction_date');
        $table->string('type'); // deposit or withdrawal
        $table->string('reference')->nullable();
        $table->string('description');
        $table->decimal('amount', 15, 2);
        $table->string('status')->default('posted');
        $table->text('notes')->nullable();

        $table->timestamps();

        $table->index(['account_id', 'transaction_date']);
        $table->index('status');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_transactions');
    }
};
