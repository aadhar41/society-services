<?php

namespace Database\Seeders;

use App\Domain\Accounting\Models\Account;
use App\Domain\Accounting\Models\AccountGroup;
use Illuminate\Database\Seeder;

/**
 * Seeds the default Chart of Accounts for a society.
 *
 * This follows the standard Indian society accounting structure:
 * - Assets: Cash, Bank, Receivables, Fixed Assets
 * - Liabilities: Payables, Deposits
 * - Income: Maintenance, Interest, Penalties
 * - Expenses: Salary, Repairs, Utilities, Insurance
 * - Equity: Opening Balance, Reserves
 */
class DefaultChartOfAccountsSeeder extends Seeder
{
    public function run(int $societyId = 1): void
    {
        $groups = $this->getDefaultGroups();

        foreach ($groups as $groupData) {
            $group = AccountGroup::create([
                'society_id' => $societyId,
                'name' => $groupData['name'],
                'code' => $groupData['code'],
                'nature' => $groupData['nature'],
                'is_system' => true,
            ]);

            if (!empty($groupData['accounts'])) {
                foreach ($groupData['accounts'] as $accountData) {
                    Account::create([
                        'society_id' => $societyId,
                        'account_group_id' => $group->id,
                        'name' => $accountData['name'],
                        'code' => $accountData['code'],
                        'description' => $accountData['description'] ?? null,
                        'is_system' => $accountData['is_system'] ?? true,
                    ]);
                }
            }
        }
    }

    private function getDefaultGroups(): array
    {
        return [
            // ═══ ASSETS ═══
            [
                'name' => 'Current Assets',
                'code' => 'CA',
                'nature' => 'asset',
                'accounts' => [
                    ['name' => 'Cash in Hand', 'code' => 'cash-in-hand', 'description' => 'Physical cash held by society'],
                    ['name' => 'Bank Account', 'code' => 'bank-account', 'description' => 'Primary society bank account'],
                    ['name' => 'Accounts Receivable', 'code' => 'accounts-receivable', 'description' => 'Dues receivable from members'],
                    ['name' => 'Advance Payments', 'code' => 'advance-payments', 'description' => 'Advance paid to vendors'],
                    ['name' => 'Interest Receivable', 'code' => 'interest-receivable', 'description' => 'Bank interest or FD interest receivable'],
                ],
            ],
            [
                'name' => 'Fixed Assets',
                'code' => 'FA',
                'nature' => 'asset',
                'accounts' => [
                    ['name' => 'Building & Common Areas', 'code' => 'building-assets', 'description' => 'Society building and common property'],
                    ['name' => 'Furniture & Fixtures', 'code' => 'furniture-fixtures', 'description' => 'Office furniture, garden equipment etc.'],
                    ['name' => 'Equipment & Machinery', 'code' => 'equipment', 'description' => 'Lifts, pumps, gensets, CCTV etc.'],
                ],
            ],

            // ═══ LIABILITIES ═══
            [
                'name' => 'Current Liabilities',
                'code' => 'CL',
                'nature' => 'liability',
                'accounts' => [
                    ['name' => 'Accounts Payable', 'code' => 'accounts-payable', 'description' => 'Amounts owed to vendors/suppliers'],
                    ['name' => 'Security Deposits', 'code' => 'security-deposits', 'description' => 'Deposits collected from members'],
                    ['name' => 'Advance from Members', 'code' => 'advance-from-members', 'description' => 'Advance maintenance collected'],
                    ['name' => 'TDS Payable', 'code' => 'tds-payable', 'description' => 'Tax deducted at source payable'],
                    ['name' => 'GST Payable', 'code' => 'gst-payable', 'description' => 'GST collected and payable'],
                ],
            ],

            // ═══ INCOME ═══
            [
                'name' => 'Income from Operations',
                'code' => 'IO',
                'nature' => 'income',
                'accounts' => [
                    ['name' => 'Maintenance Income', 'code' => 'maintenance-income', 'description' => 'Monthly maintenance charges collected'],
                    ['name' => 'Sinking Fund Income', 'code' => 'sinking-fund-income', 'description' => 'Sinking fund contributions'],
                    ['name' => 'Repair Fund Income', 'code' => 'repair-fund-income', 'description' => 'Repair and maintenance fund'],
                    ['name' => 'Parking Income', 'code' => 'parking-income', 'description' => 'Parking charges'],
                    ['name' => 'Non-Occupancy Charges', 'code' => 'non-occupancy-charges', 'description' => 'Charges for rented/locked units'],
                ],
            ],
            [
                'name' => 'Other Income',
                'code' => 'OI',
                'nature' => 'income',
                'accounts' => [
                    ['name' => 'Interest Income', 'code' => 'interest-income', 'description' => 'Bank interest earned'],
                    ['name' => 'Late Fee Income', 'code' => 'late-fee-income', 'description' => 'Late payment penalties collected'],
                    ['name' => 'Facility Booking Income', 'code' => 'facility-booking-income', 'description' => 'Income from amenity bookings'],
                    ['name' => 'Transfer Fee Income', 'code' => 'transfer-fee-income', 'description' => 'Flat transfer charges'],
                    ['name' => 'Miscellaneous Income', 'code' => 'misc-income', 'description' => 'Other society income'],
                ],
            ],

            // ═══ EXPENSES ═══
            [
                'name' => 'Operating Expenses',
                'code' => 'OE',
                'nature' => 'expense',
                'accounts' => [
                    ['name' => 'Salary & Wages', 'code' => 'salary-wages', 'description' => 'Security, cleaning, admin staff salary'],
                    ['name' => 'Electricity Charges', 'code' => 'electricity-charges', 'description' => 'Common area electricity'],
                    ['name' => 'Water Charges', 'code' => 'water-charges', 'description' => 'Water supply charges'],
                    ['name' => 'Lift Maintenance', 'code' => 'lift-maintenance', 'description' => 'Lift AMC and repair'],
                    ['name' => 'Security Expenses', 'code' => 'security-expenses', 'description' => 'Security services and equipment'],
                    ['name' => 'Cleaning & Housekeeping', 'code' => 'cleaning-expenses', 'description' => 'Cleaning and sanitation'],
                    ['name' => 'Garden & Landscaping', 'code' => 'garden-expenses', 'description' => 'Garden maintenance'],
                    ['name' => 'Insurance', 'code' => 'insurance-expenses', 'description' => 'Society insurance premiums'],
                    ['name' => 'Repairs & Maintenance', 'code' => 'repairs-maintenance', 'description' => 'General repairs'],
                ],
            ],
            [
                'name' => 'Administrative Expenses',
                'code' => 'AE',
                'nature' => 'expense',
                'accounts' => [
                    ['name' => 'Office & Stationery', 'code' => 'office-stationery', 'description' => 'Stationery, printing, postage'],
                    ['name' => 'Legal & Professional', 'code' => 'legal-professional', 'description' => 'Audit fees, legal fees, consultancy'],
                    ['name' => 'Bank Charges', 'code' => 'bank-charges', 'description' => 'Bank fees and charges'],
                    ['name' => 'Depreciation', 'code' => 'depreciation', 'description' => 'Depreciation on fixed assets'],
                    ['name' => 'Miscellaneous Expenses', 'code' => 'misc-expenses', 'description' => 'Other expenses'],
                ],
            ],

            // ═══ EQUITY ═══
            [
                'name' => 'Capital & Reserves',
                'code' => 'CR',
                'nature' => 'equity',
                'accounts' => [
                    ['name' => 'Opening Balance Equity', 'code' => 'opening-balance', 'description' => 'Opening balance / capital fund'],
                    ['name' => 'Sinking Fund Reserve', 'code' => 'sinking-fund-reserve', 'description' => 'Accumulated sinking fund'],
                    ['name' => 'Repair Fund Reserve', 'code' => 'repair-fund-reserve', 'description' => 'Accumulated repair fund'],
                    ['name' => 'Retained Surplus', 'code' => 'retained-surplus', 'description' => 'Income minus expenses (retained earnings)'],
                ],
            ],
        ];
    }
}
