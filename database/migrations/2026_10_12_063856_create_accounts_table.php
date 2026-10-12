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
    Schema::create('accounts', function (Blueprint $table) {
        $table->id();

        $table->string('name');
        $table->string('account_number')->nullable();
        $table->string('type'); // cash or bank
        $table->decimal('opening_balance', 15, 2)->default(0);
        $table->string('status')->default('active');
        $table->text('notes')->nullable();

        $table->timestamps();

        $table->index(['type', 'status']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
