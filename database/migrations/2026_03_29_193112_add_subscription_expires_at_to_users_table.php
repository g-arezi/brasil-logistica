<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('subscription_expires_at')->nullable()->after('status');
        });

        // Atualizar usuarios existentes para terem 30 dias de hoje
        DB::table('users')->where('profile_type', '!=', 'admin')->update([
            'subscription_expires_at' => now()->addDays(30),
        ]);

        // Admin nunca expira
        DB::table('users')->where('profile_type', 'admin')->update([
            'subscription_expires_at' => null,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('subscription_expires_at');
        });
    }
};
