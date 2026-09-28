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
        Schema::table('consumables', function (Blueprint $table) {
            $table->string('unit')->nullable()->after('qty');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('use_measurement_units')->default(false)->after('category_type');
        });

    }


    public function down(): void
    {
        Schema::table('consumables', function (Blueprint $table) {
            $table->dropColumn('unit');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('use_measurement_units');
        });
    }
};
