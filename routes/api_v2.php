<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Society ERP v2
|--------------------------------------------------------------------------
|
| API-first architecture. All routes require authentication via Sanctum
| and the set.society middleware for multi-tenancy scoping.
|
*/

// ═══ PUBLIC AUTH ROUTES ═══════════════════════════════════════════════

Route::prefix('v2/auth')->group(function () {
    Route::post('/register', [\App\Domain\Auth\Controllers\AuthController::class, 'register']);
    Route::post('/login', [\App\Domain\Auth\Controllers\AuthController::class, 'login']);
    Route::post('/login/otp', [\App\Domain\Auth\Controllers\AuthController::class, 'sendOtp']);
    Route::post('/login/otp/verify', [\App\Domain\Auth\Controllers\AuthController::class, 'verifyOtp']);
    Route::post('/password/forgot', [\App\Domain\Auth\Controllers\AuthController::class, 'forgotPassword']);
    Route::post('/password/reset', [\App\Domain\Auth\Controllers\AuthController::class, 'resetPassword']);
});

// ═══ AUTHENTICATED ROUTES ════════════════════════════════════════════

Route::prefix('v2')->middleware('auth:sanctum')->group(function () {

    // ─── Auth ──────────────────────────────────────────────────────
    Route::post('/auth/logout', [\App\Domain\Auth\Controllers\AuthController::class, 'logout']);
    Route::get('/auth/me', [\App\Domain\Auth\Controllers\AuthController::class, 'me']);

    // ─── Societies (no society scope needed) ───────────────────────
    Route::apiResource('societies', \App\Domain\Society\Controllers\SocietyController::class);

    // ─── Society-scoped routes ─────────────────────────────────────
    Route::middleware('set.society')->group(function () {

        // Wings
        Route::apiResource('wings', \App\Domain\Society\Controllers\WingController::class);

        // Units
        Route::apiResource('units', \App\Domain\Society\Controllers\UnitController::class);

        // Parking
        Route::apiResource('parking-slots', \App\Domain\Society\Controllers\ParkingSlotController::class);

        // Members
        Route::apiResource('members', \App\Domain\Member\Controllers\MemberController::class);
        Route::post('members/{member}/move-out', [\App\Domain\Member\Controllers\MemberController::class, 'moveOut']);
        Route::post('members/{member}/documents', [\App\Domain\Member\Controllers\MemberController::class, 'uploadDocument']);
        Route::get('units/{unit}/members', [\App\Domain\Member\Controllers\MemberController::class, 'byUnit']);

        // ─── Accounting ────────────────────────────────────────────
        Route::prefix('accounting')->group(function () {
            // Chart of Accounts
            Route::apiResource('account-groups', \App\Domain\Accounting\Controllers\AccountGroupController::class);
            Route::apiResource('accounts', \App\Domain\Accounting\Controllers\AccountController::class);

            // Ledger
            Route::get('ledger/{account}', [\App\Domain\Accounting\Controllers\LedgerController::class, 'show']);

            // Journal Entries
            Route::apiResource('journal-entries', \App\Domain\Accounting\Controllers\JournalEntryController::class);
            Route::post('journal-entries/{journal_entry}/post', [\App\Domain\Accounting\Controllers\JournalEntryController::class, 'post']);
            Route::post('journal-entries/{journal_entry}/void', [\App\Domain\Accounting\Controllers\JournalEntryController::class, 'void']);

            // Charge Heads
            Route::apiResource('charge-heads', \App\Domain\Accounting\Controllers\ChargeHeadController::class);

            // Invoicing
            Route::post('invoices/generate', [\App\Domain\Accounting\Controllers\InvoiceController::class, 'generate']);
            Route::apiResource('invoices', \App\Domain\Accounting\Controllers\InvoiceController::class)->only(['index', 'show']);

            // Payments
            Route::post('payments', [\App\Domain\Accounting\Controllers\PaymentController::class, 'store']);
            Route::get('payments', [\App\Domain\Accounting\Controllers\PaymentController::class, 'index']);

            // Financial Years
            Route::apiResource('financial-years', \App\Domain\Accounting\Controllers\FinancialYearController::class);

            // Reports
            Route::prefix('reports')->group(function () {
                Route::get('trial-balance', [\App\Domain\Accounting\Controllers\ReportController::class, 'trialBalance']);
                Route::get('balance-sheet', [\App\Domain\Accounting\Controllers\ReportController::class, 'balanceSheet']);
                Route::get('profit-loss', [\App\Domain\Accounting\Controllers\ReportController::class, 'profitAndLoss']);
                Route::get('defaulters', [\App\Domain\Accounting\Controllers\ReportController::class, 'defaulters']);
            });
        });

        // ─── Complaints ────────────────────────────────────────────
        Route::apiResource('complaint-categories', \App\Domain\Complaint\Controllers\ComplaintCategoryController::class);
        Route::apiResource('complaints', \App\Domain\Complaint\Controllers\ComplaintController::class);
        Route::post('complaints/{complaint}/comments', [\App\Domain\Complaint\Controllers\ComplaintController::class, 'addComment']);
        Route::put('complaints/{complaint}/assign', [\App\Domain\Complaint\Controllers\ComplaintController::class, 'assign']);

        // ─── Visitors ──────────────────────────────────────────────
        Route::post('visitors/pre-approve', [\App\Domain\Visitor\Controllers\VisitorController::class, 'preApprove']);
        Route::post('visitors/check-in', [\App\Domain\Visitor\Controllers\VisitorController::class, 'checkIn']);
        Route::put('visitors/{visitor}/check-out', [\App\Domain\Visitor\Controllers\VisitorController::class, 'checkOut']);
        Route::get('visitors', [\App\Domain\Visitor\Controllers\VisitorController::class, 'index']);

        // ─── Facility Booking ──────────────────────────────────────
        Route::apiResource('facilities', \App\Domain\Booking\Controllers\FacilityController::class);
        Route::get('facilities/{facility}/availability', [\App\Domain\Booking\Controllers\FacilityController::class, 'availability']);
        Route::apiResource('bookings', \App\Domain\Booking\Controllers\BookingController::class);

        // ─── Communication ─────────────────────────────────────────
        Route::apiResource('notices', \App\Domain\Communication\Controllers\NoticeController::class);
        Route::apiResource('polls', \App\Domain\Communication\Controllers\PollController::class);
        Route::post('polls/{poll}/vote', [\App\Domain\Communication\Controllers\PollController::class, 'vote']);
        Route::get('polls/{poll}/results', [\App\Domain\Communication\Controllers\PollController::class, 'results']);

        // ─── Documents ─────────────────────────────────────────────
        Route::apiResource('documents', \App\Domain\Document\Controllers\DocumentController::class);

        // ─── Staff ─────────────────────────────────────────────────
        Route::apiResource('staff', \App\Domain\Staff\Controllers\StaffController::class);
        Route::post('staff/{staff}/attendance', [\App\Domain\Staff\Controllers\StaffController::class, 'recordAttendance']);

        // ─── Vendors ───────────────────────────────────────────────
        Route::apiResource('vendors', \App\Domain\Vendor\Controllers\VendorController::class);
        Route::apiResource('vendors.contracts', \App\Domain\Vendor\Controllers\VendorContractController::class);
        Route::apiResource('assets', \App\Domain\Vendor\Controllers\AssetController::class);

        // ─── Audit Logs ────────────────────────────────────────────
        Route::get('audit-logs', [\App\Domain\Audit\Controllers\AuditController::class, 'index']);
    });
});

// ─── Keep existing v1 API routes ───────────────────────────────────
// The original api.php routes are preserved for backward compatibility.
