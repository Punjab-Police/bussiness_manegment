<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('company_name')->nullable();

            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();

            $table->text('address')->nullable();

            $table->decimal('opening_balance', 15, 2)
                ->default(0);

            $table->boolean('status')
                ->default(true);

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('name');
            $table->index('phone');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};