<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accessories_checkout', function (Blueprint $table) {
            $table->boolean('deployable')->default(false)->after('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accessories_checkout', function (Blueprint $table) {
            $table->dropColumn('deployable');
        });
    }
};
