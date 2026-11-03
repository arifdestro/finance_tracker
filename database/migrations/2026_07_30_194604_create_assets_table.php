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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('symbol')->nullable();
            $table->enum('type', ['crypto', 'stock', 'mutual_fund', 'gold', 'foreign_currency']);
            $table->decimal('quantity', 24, 8)->default(0);
            $table->decimal('average_buy_price', 20, 2)->default(0);
            $table->decimal('current_price', 20, 2)->default(0);
            $table->string('color')->default('indigo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
