<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->string('path');
            $table->string('mobile_path')->nullable();
            $table->boolean('is_primary')->nullable()->default(null);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('alt_text')->nullable();

            $table->unique(['product_id', 'is_primary'], 'images_product_primary_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
