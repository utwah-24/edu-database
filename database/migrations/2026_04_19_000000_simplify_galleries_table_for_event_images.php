<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Store image URLs per event only (no title/caption). Supports long URLs.
     */
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->string('url', 2048)->nullable()->after('event_id');
        });

        foreach (DB::table('galleries')->select('id', 'image_path')->cursor() as $row) {
            DB::table('galleries')->where('id', $row->id)->update(['url' => $row->image_path]);
        }

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'title', 'caption']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('event_id');
            $table->string('title')->nullable()->after('image_path');
            $table->text('caption')->nullable();
        });

        foreach (DB::table('galleries')->select('id', 'url')->cursor() as $row) {
            DB::table('galleries')->where('id', $row->id)->update(['image_path' => $row->url]);
        }

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('url');
        });
    }
};
