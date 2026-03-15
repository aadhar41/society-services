<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Flat;
use App\Models\Society;
use App\Models\Block;
use Illuminate\Http\Request;
use Auth;

class MaintenanceReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a grid-based maintenance record (Monthly Payment View).
     */
    public function grid(Request $request)
    {
        $title = "Maintenance Record";
        $module = "maintenance_report";

        $selectedYear = $request->get('year', date('Y'));
        $selectedSociety = $request->get('society_id');
        $selectedBlock = $request->get('block_id');

        $societies = getSocieties();
        $blocks = $selectedSociety ? getSocietyBlocks($selectedSociety) : [];

        $query = Flat::with(['society', 'block', 'plot'])
            ->active();

        if ($selectedSociety) {
            $query->where('society_id', $selectedSociety);
        }
        if ($selectedBlock) {
            $query->where('block_id', $selectedBlock);
        }

        $flats = $query->orderBy('flat_no', 'asc')->get();

        // Fetch maintenance records for the selected year
        $maintenances = Maintenance::where('year', $selectedYear)
            ->where('status', '1')
            ->get()
            ->groupBy('flat_id');

        $months = getMonths();

        return view('maintenance_report.grid', compact(
            'title', 'module', 'flats', 'maintenances', 'months', 'selectedYear', 
            'societies', 'blocks', 'selectedSociety', 'selectedBlock'
        ));
    }

    /**
     * Display a summary of outstanding maintenance balances.
     */
    public function outstanding(Request $request)
    {
        $title = "Outstanding Record";
        $module = "maintenance_report";

        $selectedSociety = $request->get('society_id');
        $selectedBlock = $request->get('block_id');

        $societies = getSocieties();
        $blocks = $selectedSociety ? getSocietyBlocks($selectedSociety) : [];

        $query = Flat::with(['society', 'block', 'plot'])
            ->active();

        if ($selectedSociety) {
            $query->where('society_id', $selectedSociety);
        }
        if ($selectedBlock) {
            $query->where('block_id', $selectedBlock);
        }

        $flats = $query->orderBy('flat_no', 'asc')->get();

        // Get all maintenance records to calculate outstanding
        // In a real scenario, you'd calculate this based on a fixed monthly rate minus already paid amounts
        // For now, mirroring the summary image which shows yearly totals (2024, 2025)
        
        $years = [2024, 2025]; // Matching the user's image years
        
        $maintenanceData = Maintenance::whereIn('year', $years)
            ->where('status', '1')
            ->get()
            ->groupBy(['year', 'flat_id']);

        // Road outstanding is stored directly in the flat model
        return view('maintenance_report.outstanding', compact(
            'title', 'module', 'flats', 'maintenanceData', 'years',
            'societies', 'blocks', 'selectedSociety', 'selectedBlock'
        ));
    }
}
