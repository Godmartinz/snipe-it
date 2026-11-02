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
            $table->boolean('undeployable')->default(false)->after('assigned_to');
            $table->timestamp('returned_at')->nullable()->after('undeployable');
        });
        Schema::table('components_assets', function (Blueprint $table) {
            $table->boolean('undeployable')->default(false)->after('asset_id');
            $table->timestamp('returned_at')->nullable()->after('undeployable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accessories_checkout', function (Blueprint $table) {
            $table->dropColumn('undeployable');
            $table->dropColumn('returned_at');
        });
        Schema::table('components_assets', function (Blueprint $table) {
            $table->dropColumn('undeployable');
            $table->dropColumn('returned_at');
        });
    }
};
