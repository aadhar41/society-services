<?php

namespace App\Domain\Accounting\Controllers;

use App\Domain\Accounting\Services\AccountingService;
use App\Domain\Accounting\Services\BillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * ReportController — Financial reports API.
 *
 * Endpoints:
 *   GET /api/v2/accounting/reports/trial-balance
 *   GET /api/v2/accounting/reports/balance-sheet
 *   GET /api/v2/accounting/reports/profit-loss
 *   GET /api/v2/accounting/reports/defaulters
 */
class ReportController extends Controller
{
    public function __construct(
        private AccountingService $accountingService,
        private BillingService $billingService
    ) {}

    /**
     * Trial Balance Report.
     *
     * Query params: ?as_of_date=2026-03-31
     */
    public function trialBalance(Request $request): JsonResponse
    {
        $societyId = app('current_society_id');
        $asOfDate = $request->input('as_of_date');

        $data = $this->accountingService->getTrialBalance($societyId, $asOfDate);

        $totalDebit = $data->sum('total_debit');
        $totalCredit = $data->sum('total_credit');

        return response()->json([
            'report' => 'Trial Balance',
            'as_of_date' => $asOfDate ?? now()->format('Y-m-d'),
            'accounts' => $data,
            'totals' => [
                'total_debit' => $totalDebit,
                'total_credit' => $totalCredit,
                'is_balanced' => abs($totalDebit - $totalCredit) < 0.01,
            ],
        ]);
    }

    /**
     * Balance Sheet Report.
     *
     * Query params: ?as_of_date=2026-03-31
     */
    public function balanceSheet(Request $request): JsonResponse
    {
        $societyId = app('current_society_id');
        $asOfDate = $request->input('as_of_date', now()->format('Y-m-d'));

        $data = $this->accountingService->getBalanceSheet($societyId, $asOfDate);

        return response()->json([
            'report' => 'Balance Sheet',
            ...$data,
        ]);
    }

    /**
     * Profit & Loss Statement.
     *
     * Query params: ?start_date=2025-04-01&end_date=2026-03-31
     */
    public function profitAndLoss(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $societyId = app('current_society_id');

        $data = $this->accountingService->getProfitAndLoss(
            $societyId,
            $request->input('start_date'),
            $request->input('end_date')
        );

        return response()->json([
            'report' => 'Profit & Loss Statement',
            ...$data,
        ]);
    }

    /**
     * Defaulter Report — units with outstanding dues.
     */
    public function defaulters(): JsonResponse
    {
        $societyId = app('current_society_id');

        $data = $this->billingService->getDefaulterReport($societyId);

        return response()->json([
            'report' => 'Defaulter Report',
            'generated_at' => now()->toISOString(),
            'total_defaulters' => $data->count(),
            'total_outstanding' => $data->sum('total_outstanding'),
            'defaulters' => $data,
        ]);
    }
}
