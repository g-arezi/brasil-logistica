<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('profile_type')->default('driver')->after('password');
            $table->string('document_number', 14)->nullable()->after('profile_type');
            $table->timestamp('document_verified_at')->nullable()->after('document_number');
            $table->index(['profile_type']);
            $table->unique(['document_number']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['document_number']);
            $table->dropIndex(['profile_type']);
            $table->dropColumn(['profile_type', 'document_number', 'document_verified_at']);
        });
    }
};
