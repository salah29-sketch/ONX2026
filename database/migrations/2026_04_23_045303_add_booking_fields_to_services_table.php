<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'hero_image'))
                $table->string('hero_image')->nullable()->after('icon');
            if (!Schema::hasColumn('services', 'time_mode'))
                $table->string('time_mode', 50)->nullable()->after('capabilities');
            if (!Schema::hasColumn('services', 'free_hours'))
                $table->unsignedInteger('free_hours')->default(0)->after('time_mode');
            if (!Schema::hasColumn('services', 'extra_hour_price'))
                $table->decimal('extra_hour_price', 12, 2)->default(0)->after('free_hours');
            if (!Schema::hasColumn('services', 'early_start_price'))
                $table->decimal('early_start_price', 12, 2)->default(0)->after('extra_hour_price');
            if (!Schema::hasColumn('services', 'late_end_price'))
                $table->decimal('late_end_price', 12, 2)->default(0)->after('early_start_price');
            if (!Schema::hasColumn('services', 'default_start_time'))
                $table->time('default_start_time')->nullable()->after('late_end_price');
            if (!Schema::hasColumn('services', 'default_end_time'))
                $table->time('default_end_time')->nullable()->after('default_start_time');
            if (!Schema::hasColumn('services', 'show_venue_selector'))
                $table->boolean('show_venue_selector')->default(false)->after('default_end_time');
            if (!Schema::hasColumn('services', 'show_wilaya_selector'))
                $table->boolean('show_wilaya_selector')->default(false)->after('show_venue_selector');
            if (!Schema::hasColumn('services', 'deposit_amount'))
                $table->decimal('deposit_amount', 12, 2)->default(0)->after('show_wilaya_selector');
            if (!Schema::hasColumn('services', 'base_price'))
                $table->decimal('base_price', 12, 2)->default(0)->after('deposit_amount');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'hero_image', 'time_mode', 'free_hours',
                'extra_hour_price', 'early_start_price', 'late_end_price',
                'default_start_time', 'default_end_time', 'show_venue_selector',
                'show_wilaya_selector', 'deposit_amount', 'base_price'
            ]);
        });
    }
};