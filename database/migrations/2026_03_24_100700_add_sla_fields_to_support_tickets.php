<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_tickets', function (Blueprint $table): void {
            $table->timestamp('due_at')->nullable()->after('closed_at');
            $table->timestamp('first_response_at')->nullable()->after('due_at');
            $table->index(['status', 'due_at']);
        });
    }

    public function down(): void
    {
        Schema::table('support_tickets', function (Blueprint $table): void {
            $table->dropIndex(['status', 'due_at']);
            $table->dropColumn(['due_at', 'first_response_at']);
        });
    }
};

