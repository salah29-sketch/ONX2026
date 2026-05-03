<?php

/**
 * =====================================================================
 *  SQUASHED MIGRATION — onx-edge full schema
 *  يستبدل هذا الملف كل الـ 131 migration السابقة
 *
 *  طريقة الاستخدام (على قاعدة بيانات جديدة فارغة):
 *  1. احذف كل ملفات migration القديمة من database/migrations/
 *  2. ضع هذا الملف بدلاً منها
 *  3. php artisan migrate:fresh
 *  4. php artisan db:seed
 *
 *  ⚠️  لا تشغّله على قاعدة بيانات موجودة فيها بيانات — فقط على fresh install
 * =====================================================================
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // ─────────────────────────────────────────
        //  1. THIRD-PARTY / FRAMEWORK TABLES
        // ─────────────────────────────────────────

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('oauth_auth_codes', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('client_id');
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });

        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('client_id');
            $table->string('name')->nullable();
            $table->text('scopes')->nullable();
            $table->boolean('revoked');
            $table->timestamps();
            $table->dateTime('expires_at')->nullable();
        });

        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->string('id', 100)->primary();
            $table->string('access_token_id', 100)->index();
            $table->boolean('revoked');
            $table->dateTime('expires_at')->nullable();
        });

        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('secret', 100)->nullable();
            $table->string('provider')->nullable();
            $table->text('redirect');
            $table->boolean('personal_access_client');
            $table->boolean('password_client');
            $table->boolean('revoked');
            $table->timestamps();
        });

        Schema::create('oauth_personal_access_clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->timestamps();
        });

        Schema::create('telescope_entries', function (Blueprint $table) {
            $table->bigIncrements('sequence');
            $table->uuid('uuid');
            $table->uuid('batch_id');
            $table->string('family_hash')->nullable();
            $table->boolean('should_display_on_index')->default(true);
            $table->string('type', 20);
            $table->longText('content');
            $table->dateTime('created_at')->nullable();
            $table->unique('uuid');
            $table->index('batch_id');
            $table->index('family_hash');
            $table->index('created_at');
            $table->index(['type', 'should_display_on_index']);
        });

        Schema::create('telescope_entries_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('entry_uuid');
            $table->string('tag');
            $table->primary(['entry_uuid', 'tag']);
            $table->index('tag');
        });

        Schema::create('telescope_monitoring', function (Blueprint $table) {
            $table->string('tag')->primary();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->morphs('model');
            $table->uuid('uuid')->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();
            $table->nullableTimestamps();
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        // ─────────────────────────────────────────
        //  2. ACL — permissions / roles / users
        // ─────────────────────────────────────────

        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('user_id');
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });

        // ─────────────────────────────────────────
        //  3. WORKERS
        // ─────────────────────────────────────────

        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        // ─────────────────────────────────────────
        //  4. CLIENTS
        // ─────────────────────────────────────────

        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->boolean('login_disabled')->default(false);
            $table->boolean('is_company')->default(false);
            $table->string('business_name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // ─────────────────────────────────────────
        //  5. CATEGORIES & SERVICES
        // ─────────────────────────────────────────

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type', 50)->nullable();
            $table->string('icon')->nullable();
            $table->string('bg_color')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('booking_type', 50)->default('appointment');
            $table->string('pricing_mode', 50)->default('package');
            $table->boolean('availability_required')->default(false);
            $table->json('capabilities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);

            // Time-based pricing (events)
            $table->string('time_mode', 50)->nullable();
            $table->unsignedInteger('free_hours')->default(0);
            $table->decimal('extra_hour_price', 12, 2)->default(0);
            $table->decimal('early_start_price', 12, 2)->default(0);
            $table->decimal('late_end_price', 12, 2)->default(0);
            $table->time('default_start_time')->nullable();
            $table->time('default_end_time')->nullable();

            // Venue settings
            $table->boolean('show_venue_selector')->default(false);
            $table->boolean('show_wilaya_selector')->default(false);

            // Pricing
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->decimal('base_price', 12, 2)->default(0);

            $table->timestamps();
        });

        Schema::create('client_password_otps', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('code');
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('old_price', 12, 2)->nullable();
            $table->string('price_note')->nullable();
            $table->string('duration')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_reel')->default(false);
            $table->boolean('is_buildable')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('package_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
            $table->string('label');
            $table->string('type', 50);          // boolean | select | number
            $table->string('price_effect', 50)->default('fixed'); // fixed | per_unit | free
            $table->decimal('price', 12, 2)->default(0);
            $table->json('options')->nullable();
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->string('default_value')->nullable();
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ─────────────────────────────────────────
        //  6. PROMO CODES
        // ─────────────────────────────────────────

        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('discount_type', 20); // percent | fixed
            $table->decimal('value', 12, 2);
            $table->decimal('min_order_value', 12, 2)->nullable();
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->dateTime('valid_from')->nullable();
            $table->dateTime('valid_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['code', 'is_active']);
        });

        // ─────────────────────────────────────────
        //  7. GEO — travel zones / wilayas / venues
        // ─────────────────────────────────────────

        Schema::create('travel_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 10, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('wilayas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('code')->unique();
            $table->foreignId('travel_zone_id')->nullable()->constrained('travel_zones')->nullOnDelete();
            $table->boolean('is_local')->default(false);
            $table->timestamps();
        });

        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('wilaya_id')->nullable()->constrained('wilayas')->nullOnDelete();
            $table->string('address')->nullable();
            $table->decimal('travel_cost_override', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // ─────────────────────────────────────────
        //  8. BOOKINGS (core + sub-type tables)
        // ─────────────────────────────────────────

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_uuid')->nullable()->unique();
            $table->unsignedInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete();
            // contact info (denormalised for guest bookings)
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            // booking type
            $table->string('booking_type', 50)->default('event'); // event | appointment | subscription
            $table->date('event_date')->nullable();
            $table->string('status')->default('pending');
            // pricing
            $table->decimal('total_price', 12, 2)->nullable();
            $table->decimal('final_price', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('deposit', 12, 2)->nullable();
            $table->json('price_snapshot')->nullable(); // frozen price breakdown
            // misc
            $table->text('notes')->nullable();
            $table->string('final_video_path')->nullable();
            $table->timestamps();

            $table->index(['status', 'booking_type']);
            $table->index(['client_id', 'status']);
            $table->index('event_date');
        });

        // Event-specific details (one-to-one with bookings)
        Schema::create('event_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('venue_id')->nullable()->constrained('venues')->nullOnDelete();
            $table->string('venue_custom')->nullable();
            $table->foreignId('wilaya_id')->nullable()->constrained('wilayas')->nullOnDelete();
            $table->decimal('time_cost', 12, 2)->default(0);
            $table->decimal('travel_cost', 12, 2)->default(0);
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->timestamps();
            $table->unique('booking_id');
        });

        // Appointment-specific details
        Schema::create('appointment_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->time('slot_start');
            $table->time('slot_end');
            $table->unsignedInteger('duration_minutes');
            $table->timestamps();
            $table->unique('booking_id');
        });

        // Subscription-specific details
        Schema::create('subscription_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->string('billing_cycle')->default('monthly'); // monthly | quarterly | yearly
            $table->decimal('plan_price', 12, 2)->default(0);
            $table->timestamps();
            $table->unique('booking_id');
        });

        // Line items per booking
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('item_type');            // package | option | addon
            $table->string('item_name');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->json('snapshot')->nullable();
            $table->timestamps();
            $table->index(['booking_id', 'item_type']);
        });

        // Payments
        Schema::create('booking_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method')->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Delivered files (photos / videos)
        Schema::create('booking_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20)->default('image'); // image | video
            $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('label')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('booking_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_selected')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Price snapshots (detailed breakdown archive)
        Schema::create('price_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->json('payload');
            $table->timestamps();
        });

        // Security: public tokens for client access
        Schema::create('booking_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('token_hash', 64)->unique();
            $table->string('purpose', 32);
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->index(['booking_id', 'purpose']);
        });

        // Availability calendar (one slot per service per date)
        Schema::create('booking_calendar_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->date('event_date');
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['service_id', 'event_date']);
        });

        // ─────────────────────────────────────────
        //  9. SUBSCRIPTIONS
        // ─────────────────────────────────────────

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->date('start_date');
            $table->date('next_billing_date');
            $table->date('end_date')->nullable();
            $table->string('renewal_type')->default('manual'); // manual | automatic
            $table->string('status')->default('active');       // active | expired | cancelled
            $table->decimal('plan_price', 12, 2)->nullable();
            $table->unsignedInteger('used_ads')->default(0);
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->index(['client_id', 'status']);
            $table->index('next_billing_date');
        });

        Schema::create('subscription_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->dateTime('renewed_at');
            $table->date('next_billing_date');
            $table->string('renewal_type'); // manual | automatic
            $table->decimal('amount', 12, 2)->nullable();
            $table->timestamps();
            $table->index(['subscription_id', 'renewed_at']);
        });

        // ─────────────────────────────────────────
        //  10. CLIENT PORTAL
        // ─────────────────────────────────────────

        Schema::create('client_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->text('admin_reply')->nullable();
            $table->timestamp('admin_replied_at')->nullable();
            $table->timestamp('admin_read_at')->nullable();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
        });

        Schema::create('client_messages_seen', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->foreignId('message_id')->constrained('client_messages')->cascadeOnDelete();
            $table->timestamp('seen_at');
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
        });

        Schema::create('client_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->string('path');
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreign('booking_id')->references('id')->on('bookings')->nullOnDelete();
        });

        Schema::create('client_selected_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->foreignId('booking_photo_id')->constrained('booking_photos')->cascadeOnDelete();
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
        });

        Schema::create('client_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->string('type', 20);           // image | video
            $table->string('path');
            $table->string('thumbnail_path')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('label')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreign('booking_id')->references('id')->on('bookings')->nullOnDelete();
        });

        Schema::create('client_media_seen', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id');
            $table->unsignedBigInteger('media_id');
            $table->string('media_type', 30); // booking_file | booking_photo
            $table->timestamp('seen_at');
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->index(['client_id', 'media_type']);
        });

        // ─────────────────────────────────────────
        //  11. CONTENT — portfolio / faq / testimonials / messages / company
        // ─────────────────────────────────────────

        Schema::create('portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->string('service_type')->nullable();           // polymorphic type helper
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->string('media_type', 30)->default('image'); // image | youtube | reel
            $table->string('image_path')->nullable();
            $table->string('video_path')->nullable();            // local mp4 reel
            $table->string('preview_video_path')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('youtube_video_id', 100)->nullable();
            $table->boolean('is_reel')->default(false);
            $table->string('reel_source', 30)->nullable();       // youtube | mp4
            $table->string('reel_url')->nullable();              // external reel link
            $table->string('caption')->nullable();
            $table->text('description')->nullable();
            $table->string('client_name')->nullable();
            $table->string('location_name')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['service_id', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index('sort_order');
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id')->nullable();
            $table->string('client_name');
            $table->string('client_role')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('initial')->nullable();
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('client_id')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->text('admin_reply')->nullable();
            $table->timestamp('admin_replied_at')->nullable();
            $table->timestamp('admin_read_at')->nullable();
            $table->string('status')->default('new'); // new | read | replied | closed
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients')->nullOnDelete();
            $table->index(['status', 'created_at']);
        });

        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->text('map_embed')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            // content
            'company_settings', 'messages', 'testimonials', 'faqs', 'portfolio_items',
            // client portal
            'client_media_seen', 'client_files', 'client_selected_photos',
            'client_photos', 'client_messages_seen', 'client_messages',
            // subscriptions
            'subscription_renewals', 'subscriptions',
            // bookings
            'booking_calendar_slots', 'booking_access_tokens', 'price_snapshots',
            'booking_photos', 'booking_files', 'booking_payments',
            'booking_items', 'subscription_bookings', 'appointment_bookings',
            'event_bookings', 'bookings',
            // geo
            'venues', 'wilayas', 'travel_zones',
            // promo
            'promo_codes',
            // catalogue
            'package_options', 'packages', 'services', 'categories',
            // auth
            'client_password_otps', 'clients',
            'workers',
            'role_user', 'permission_role', 'users', 'roles', 'permissions',
            // framework
            'jobs', 'media',
            'telescope_monitoring', 'telescope_entries_tags', 'telescope_entries',
            'oauth_personal_access_clients', 'oauth_clients', 'oauth_refresh_tokens',
            'oauth_access_tokens', 'oauth_auth_codes', 'password_resets',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }
};