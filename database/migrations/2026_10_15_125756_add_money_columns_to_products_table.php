<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->unsignedBigInteger('price_amount')->default(0)->after('price');
            $table->string('price_currency')->default(config('shop.currency', 'USD'))->after('price_amount');
        });

        DB::statement('UPDATE products SET price_amount = ROUND(price * 100)');

        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->decimal('price', 8, 2)->default(0)->after('price_currency');
        });

        DB::statement('UPDATE products SET price = price_amount / 100');

        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn(['price_amount', 'price_currency']);
        });
    }
};
