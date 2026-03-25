<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS postgis;');
        }

        Schema::create('freights', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignId('company_id')->constrained('users')->cascadeOnDelete();
            $table->string('origin_city');
            $table->string('origin_state', 2);
            $table->decimal('origin_lat', 10, 7);
            $table->decimal('origin_lng', 10, 7);
            $table->string('destination_city');
            $table->string('destination_state', 2);
            $table->decimal('destination_lat', 10, 7);
            $table->decimal('destination_lng', 10, 7);
            $table->unsignedInteger('price_cents');
            $table->unsignedInteger('min_price_cents');
            $table->unsignedInteger('max_price_cents');
            $table->string('required_vehicle_type');
            $table->string('status')->default('published');
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->unsignedInteger('estimated_minutes')->nullable();
            $table->timestamps();

            $table->index(['required_vehicle_type', 'price_cents']);
            $table->index(['status', 'created_at']);
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE freights ADD COLUMN origin_point geography(Point, 4326) NOT NULL;');
            DB::statement('ALTER TABLE freights ADD COLUMN destination_point geography(Point, 4326) NOT NULL;');
            DB::statement('CREATE INDEX freights_origin_point_gist ON freights USING GIST (origin_point);');
            DB::statement('CREATE INDEX freights_destination_point_gist ON freights USING GIST (destination_point);');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('freights');
    }
};
