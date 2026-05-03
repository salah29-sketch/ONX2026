<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            if (! Schema::hasColumn('portfolio_items', 'service_type')) {
                $table->string('service_type')->nullable()->after('service_id');
            }
            if (! Schema::hasColumn('portfolio_items', 'reel_source')) {
                $table->string('reel_source', 30)->nullable()->after('is_reel'); // youtube | mp4
            }
            if (! Schema::hasColumn('portfolio_items', 'reel_url')) {
                $table->string('reel_url')->nullable()->after('reel_source');
            }
            if (! Schema::hasColumn('portfolio_items', 'video_path')) {
                $table->string('video_path')->nullable()->after('image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_items', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('portfolio_items', 'service_type') ? 'service_type' : null,
                Schema::hasColumn('portfolio_items', 'reel_source')  ? 'reel_source'  : null,
                Schema::hasColumn('portfolio_items', 'reel_url')     ? 'reel_url'     : null,
                Schema::hasColumn('portfolio_items', 'video_path')   ? 'video_path'   : null,
            ]));
        });
    }
};
