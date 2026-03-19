<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ERP Foundation Migration
 *
 * Creates all core tables for the Society Accounting ERP system.
 * Tables are created in dependency order.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ─── 1. ENHANCED SOCIETIES TABLE ────────────────────────────
        Schema::create('erp_societies', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('registration_no')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('India');
            $table->string('pincode', 10)->nullable();
            $table->string('logo')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->unsignedTinyInteger('financial_year_start')->default(4); // April
            $table->json('settings')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ─── 2. SOCIETY-USER PIVOT ─────────────────────────────────
        Schema::create('society_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('joined_at')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['society_id', 'user_id']);
        });

        // ─── 3. ADD SUPERADMIN FLAG TO USERS ───────────────────────
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_superadmin')->default(false)->after('remember_token');
            $table->string('phone', 20)->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            $table->string('avatar')->nullable()->after('phone_verified_at');
        });

        // ─── 4. WINGS (BLOCKS/TOWERS) ──────────────────────────────
        Schema::create('wings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 10)->nullable();
            $table->unsignedSmallInteger('total_floors')->default(0);
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('society_id');
        });

        // ─── 5. FLOORS ─────────────────────────────────────────────
        Schema::create('floors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('wing_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('floor_number');
            $table->string('name')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index(['society_id', 'wing_id']);
        });

        // ─── 6. UNITS (FLATS/SHOPS) ────────────────────────────────
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('wing_id')->constrained()->cascadeOnDelete();
            $table->foreignId('floor_id')->nullable()->constrained()->nullOnDelete();
            $table->string('unit_number', 20);
            $table->enum('unit_type', ['flat', 'shop', 'office'])->default('flat');
            $table->decimal('area_sqft', 10, 2)->default(0);
            $table->unsignedSmallInteger('parking_count')->default(0);
            $table->string('intercom_no', 20)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['society_id', 'wing_id']);
            $table->unique(['society_id', 'wing_id', 'unit_number']);
        });

        // ─── 7. PARKING SLOTS ──────────────────────────────────────
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slot_number', 20);
            $table->enum('slot_type', ['two_wheeler', 'four_wheeler', 'visitor'])->default('four_wheeler');
            $table->string('location')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->index('society_id');
        });

        // ─── 8. MEMBERS ────────────────────────────────────────────
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('member_type', ['owner', 'tenant', 'family'])->default('owner');
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('aadhar_no', 20)->nullable();
            $table->string('pan_no', 20)->nullable();
            $table->string('occupation')->nullable();
            $table->date('move_in_date')->nullable();
            $table->date('move_out_date')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['society_id', 'unit_id']);
            $table->index('member_type');
        });

        // ─── 9. MEMBER DOCUMENTS ───────────────────────────────────
        Schema::create('member_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->enum('document_type', ['aadhar', 'pan', 'passport', 'agreement', 'other'])->default('other');
            $table->string('file_path');
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // ─── 10. MEMBER VEHICLES ───────────────────────────────────
        Schema::create('member_vehicles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parking_slot_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('vehicle_type', ['two_wheeler', 'four_wheeler'])->default('four_wheeler');
            $table->string('registration_no', 20);
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });

        // ─── 11. ACCOUNT GROUPS (Chart of Accounts Hierarchy) ──────
        Schema::create('account_groups', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 20);
            $table->foreignId('parent_id')->nullable()->constrained('account_groups')->nullOnDelete();
            $table->enum('nature', ['asset', 'liability', 'income', 'expense', 'equity']);
            $table->boolean('is_system')->default(false);
            $table->timestamps();

            $table->unique(['society_id', 'code']);
            $table->index('nature');
        });

        // ─── 12. ACCOUNTS (GL Accounts) ────────────────────────────
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('account_group_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 20);
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['society_id', 'code']);
            $table->index(['society_id', 'account_group_id']);
        });

        // ─── 13. FINANCIAL YEARS ───────────────────────────────────
        Schema::create('financial_years', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->timestamps();

            $table->index('society_id');
        });

        // ─── 14. JOURNAL ENTRIES ───────────────────────────────────
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('financial_year_id')->constrained()->cascadeOnDelete();
            $table->string('entry_number', 30);
            $table->date('date');
            $table->text('narration')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->enum('entry_type', ['manual', 'billing', 'payment', 'receipt', 'auto'])->default('manual');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_posted')->default(false);
            $table->timestamp('voided_at')->nullable();
            $table->text('void_reason')->nullable();
            $table->timestamps();

            $table->index(['society_id', 'date']);
            $table->index(['society_id', 'financial_year_id']);
            $table->index(['reference_type', 'reference_id']);
        });

        // ─── 15. JOURNAL ENTRY LINES ───────────────────────────────
        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('narration')->nullable();
            $table->timestamps();

            $table->index('account_id');
        });

        // ─── 16. CHARGE HEADS ──────────────────────────────────────
        Schema::create('charge_heads', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->enum('frequency', ['monthly', 'quarterly', 'yearly', 'onetime'])->default('monthly');
            $table->boolean('is_area_based')->default(false);
            $table->decimal('rate_per_sqft', 10, 2)->default(0);
            $table->enum('applies_to', ['all', 'flat', 'shop', 'office'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('society_id');
        });

        // ─── 17. INVOICES ──────────────────────────────────────────
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number', 30)->unique();
            $table->foreignId('financial_year_id')->nullable()->constrained()->nullOnDelete();
            $table->date('billing_period_start');
            $table->date('billing_period_end');
            $table->date('due_date');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('late_fee', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2)->default(0);
            $table->enum('status', ['draft', 'sent', 'paid', 'partial', 'overdue', 'cancelled'])->default('draft');
            $table->foreignId('journal_entry_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['society_id', 'status']);
            $table->index(['society_id', 'unit_id']);
            $table->index('due_date');
        });

        // ─── 18. INVOICE ITEMS ─────────────────────────────────────
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('charge_head_id')->constrained()->cascadeOnDelete();
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->timestamps();
        });

        // ─── 19. PAYMENTS ──────────────────────────────────────────
        Schema::create('erp_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('member_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'cheque', 'upi', 'neft', 'rtgs', 'razorpay', 'stripe'])->default('cash');
            $table->string('transaction_reference')->nullable();
            $table->string('cheque_no', 30)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('receipt_number', 30)->nullable();
            $table->foreignId('journal_entry_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'confirmed', 'failed', 'refunded'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['society_id', 'invoice_id']);
            $table->index('payment_date');
        });

        // ─── 20. LATE FEE RULES ───────────────────────────────────
        Schema::create('late_fee_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->unsignedSmallInteger('days_after_due');
            $table->enum('fee_type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('fee_value', 10, 2);
            $table->decimal('max_fee', 10, 2)->default(0);
            $table->boolean('is_compounding')->default(false);
            $table->timestamps();
        });

        // ─── 21. OPENING BALANCES ──────────────────────────────────
        Schema::create('opening_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('financial_year_id')->constrained()->cascadeOnDelete();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->timestamps();

            $table->unique(['account_id', 'financial_year_id']);
        });

        // ─── 22. COMPLAINT CATEGORIES ──────────────────────────────
        Schema::create('complaint_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sla_hours')->default(48);
            $table->timestamps();
        });

        // ─── 23. COMPLAINTS ────────────────────────────────────────
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('complaint_categories')->cascadeOnDelete();
            $table->string('ticket_number', 20)->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed', 'reopened'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['society_id', 'status']);
        });

        // ─── 24. COMPLAINT COMMENTS ────────────────────────────────
        Schema::create('complaint_comments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('complaint_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('comment');
            $table->json('attachments')->nullable();
            $table->boolean('is_internal')->default(false);
            $table->timestamps();
        });

        // ─── 25. VISITORS ──────────────────────────────────────────
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('vehicle_no', 20)->nullable();
            $table->string('purpose')->nullable();
            $table->enum('visitor_type', ['guest', 'delivery', 'service'])->default('guest');
            $table->string('photo')->nullable();
            $table->string('otp', 6)->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'checked_in', 'checked_out'])->default('pending');
            $table->timestamps();

            $table->index(['society_id', 'check_in_at']);
        });

        // ─── 26. FACILITIES ────────────────────────────────────────
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('capacity')->default(0);
            $table->decimal('booking_fee', 10, 2)->default(0);
            $table->decimal('advance_required', 10, 2)->default(0);
            $table->text('rules')->nullable();
            $table->json('images')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ─── 27. FACILITY SLOTS ────────────────────────────────────
        Schema::create('facility_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week'); // 0=Sun, 6=Sat
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // ─── 28. BOOKINGS ──────────────────────────────────────────
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('facility_id')->constrained()->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->date('booking_date');
            $table->foreignId('slot_id')->nullable()->constrained('facility_slots')->nullOnDelete();
            $table->decimal('amount', 10, 2)->default(0);
            $table->foreignId('payment_id')->nullable()->constrained('erp_payments')->nullOnDelete();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ─── 29. NOTICES ───────────────────────────────────────────
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->enum('category', ['general', 'maintenance', 'emergency', 'event'])->default('general');
            $table->enum('priority', ['low', 'normal', 'high'])->default('normal');
            $table->enum('target_audience', ['all', 'owners', 'tenants', 'committee'])->default('all');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // ─── 30. NOTICE ATTACHMENTS ────────────────────────────────
        Schema::create('notice_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notice_id')->constrained()->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type', 50)->nullable();
            $table->timestamps();
        });

        // ─── 31. POLLS ─────────────────────────────────────────────
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('options');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_anonymous')->default(false);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // ─── 32. POLL VOTES ────────────────────────────────────────
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('option_index');
            $table->timestamp('voted_at');
            $table->timestamps();

            $table->unique(['poll_id', 'user_id']);
        });

        // ─── 33. NOTIFICATION LOGS ─────────────────────────────────
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('society_id')->nullable()->constrained('erp_societies')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('channel', ['email', 'sms', 'push'])->default('email');
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->enum('status', ['queued', 'sent', 'failed'])->default('queued');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        // ─── 34. STAFF ─────────────────────────────────────────────
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->string('role')->nullable();
            $table->string('department')->nullable();
            $table->decimal('salary', 10, 2)->default(0);
            $table->date('joining_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // ─── 35. STAFF ATTENDANCE ──────────────────────────────────
        Schema::create('staff_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->date('date');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->enum('status', ['present', 'absent', 'half_day', 'leave'])->default('present');
            $table->timestamps();

            $table->unique(['staff_id', 'date']);
        });

        // ─── 36. VENDORS ───────────────────────────────────────────
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('gst_no', 30)->nullable();
            $table->string('pan_no', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('service_type')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // ─── 37. VENDOR CONTRACTS ──────────────────────────────────
        Schema::create('vendor_contracts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->text('payment_terms')->nullable();
            $table->string('document_path')->nullable();
            $table->enum('status', ['active', 'expired', 'terminated'])->default('active');
            $table->timestamps();
        });

        // ─── 38. ASSETS ────────────────────────────────────────────
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('location')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('current_value', 12, 2)->default(0);
            $table->enum('condition', ['new', 'good', 'fair', 'poor', 'disposed'])->default('good');
            $table->date('warranty_expires_at')->nullable();
            $table->timestamps();
        });

        // ─── 39. DOCUMENTS ─────────────────────────────────────────
        Schema::create('erp_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('society_id')->constrained('erp_societies')->cascadeOnDelete();
            $table->string('title');
            $table->enum('category', ['minutes', 'resolution', 'bylaw', 'report', 'other'])->default('other');
            $table->string('file_path');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->date('meeting_date')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        // ─── 40. ACTIVITY LOGS (Audit Trail) ───────────────────────
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('society_id')->nullable()->constrained('erp_societies')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 50);
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index(['society_id', 'created_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        // Drop in reverse order
        $tables = [
            'activity_logs', 'erp_documents', 'assets', 'vendor_contracts', 'vendors',
            'staff_attendance', 'staff', 'notification_logs', 'poll_votes', 'polls',
            'notice_attachments', 'notices', 'bookings', 'facility_slots', 'facilities',
            'visitors', 'complaint_comments', 'complaints', 'complaint_categories',
            'opening_balances', 'late_fee_rules', 'erp_payments', 'invoice_items',
            'invoices', 'charge_heads', 'journal_entry_lines', 'journal_entries',
            'financial_years', 'accounts', 'account_groups', 'member_vehicles',
            'member_documents', 'members', 'parking_slots', 'units', 'floors',
            'wings', 'society_user',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_superadmin', 'phone', 'phone_verified_at', 'avatar']);
        });

        Schema::dropIfExists('erp_societies');
    }
};
