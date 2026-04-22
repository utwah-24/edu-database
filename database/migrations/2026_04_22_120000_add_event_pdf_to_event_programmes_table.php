<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_programmes', function (Blueprint $table) {
            $table->text('event_pdf')->nullable()->after('speaker');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_programmes', function (Blueprint $table) {
            $table->dropColumn('event_pdf');
        });
    }
};
