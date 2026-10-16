<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('images', function (Blueprint $table): void {
            $table->nullableMorphs('imageable');
        });

        DB::table('images')->update([
            'imageable_id'   => DB::raw('product_id'),
            'imageable_type' => 'App\\Product\\Models\\Product',
        ]);

        Schema::table('images', function (Blueprint $table): void {
            $table->dropForeign(['product_id']);
        });

        Schema::table('images', function (Blueprint $table): void {
            $table->dropUnique('images_product_primary_unique');
        });

        Schema::table('images', function (Blueprint $table): void {
            $table->dropColumn('product_id');
        });

        Schema::table('images', function (Blueprint $table): void {
            $table->unsignedBigInteger('imageable_id')->nullable(false)->change();
            $table->string('imageable_type')->nullable(false)->change();
        });

        Schema::table('images', function (Blueprint $table): void {
            $table->unique(
                ['imageable_type', 'imageable_id', 'is_primary'],
                'images_imageable_primary_unique',
            );
        });
    }

    public function down(): void
    {
        Schema::table('images', function (Blueprint $table): void {
            $table->dropUnique('images_imageable_primary_unique');
        });

        Schema::table('images', function (Blueprint $table): void {
            $table->foreignId('product_id')->nullable()->after('id');
        });

        DB::table('images')
            ->where('imageable_type', 'App\\Product\\Models\\Product')
            ->update(['product_id' => DB::raw('imageable_id')]);

        Schema::table('images', function (Blueprint $table): void {
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();

            $table->unique(
                ['product_id', 'is_primary'],
                'images_product_primary_unique',
            );
        });

        Schema::table('images', function (Blueprint $table): void {
            $table->dropMorphs('imageable');
        });
    }
};
