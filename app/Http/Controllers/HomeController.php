<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
            'totalSocieties'   => \App\Models\Society::count(),
            'totalBlocks'      => \App\Models\Block::count(),
            'totalPlots'       => \App\Models\Plot::count(),
            'totalFlats'       => \App\Models\Flat::count(),
            'totalMaintenance' => \App\Models\Maintenance::count(),
            'totalExpenses'    => \App\Models\Expense::count(),
        ];

        return view('home', $data);
    }
}
