<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;
use App\Models\Plot;
use App\Models\Block;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaintenanceStoreRequest;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\PlotStoreRequest;

class MaintenanceController extends Controller
{
    /**
     * Apply default authentication middleware for backend routes.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "maintenances";
        $module = "maintenance";
        $data = Maintenance::active()->latest()->get();
        return view('maintenance.index', compact('data', 'title', 'module'));
    }

    /**
     * Process datatables ajax request.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        // return Datatables::of(Maintenance::query())->make(true);

        $maintenanceData = Maintenance::select('maintenances.id', 'maintenances.unique_code', 'maintenances.society_id', 'maintenances.block_id', 'maintenances.plot_id', 'maintenances.flat_id', 'maintenances.type', 'maintenances.date', 'maintenances.year', 'maintenances.month', 'maintenances.amount', 'maintenances.attachments', 'maintenances.payment_status', 'maintenances.status', 'maintenances.created_at', 'maintenances.updated_at');
        return Datatables::of($maintenanceData)
            ->filter(function ($query) use ($request) {
                if ($request->has('status') && $request->get('status') != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('maintenances.status', 'like', "%{$request->get('status')}%");
                    });
                }

                if ($request->has('payment_status') && $request->get('payment_status') != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('maintenances.payment_status', 'like', "%{$request->get('payment_status')}%");
                    });
                }

                if (!empty($request['search']['value']) && $request['search']['value'] != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('maintenances.date', 'like', "%{$request['search']['value']}%");
                        $q->where('maintenances.year', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('maintenances.month', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('maintenances.amount', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('maintenances.created_at', 'like', "%{$request['search']['value']}%");
                    });
                }
            })
            ->addColumn('unique_code', function ($maintenanceData) {
                return $unique_code = (isset($maintenanceData->unique_code)) ? ucwords($maintenanceData->unique_code) : "";
            })
            ->addColumn('society', function ($maintenanceData) {
                return $society = (isset($maintenanceData->society->name)) ? ucwords($maintenanceData->society->name) : "";
            })
            ->addColumn('block', function ($maintenanceData) {
                return $block = (isset($maintenanceData->block->name)) ? ucwords($maintenanceData->block->name) : "";
            })
            ->addColumn('plot', function ($maintenanceData) {
                return $plot = (isset($maintenanceData->plot->name)) ? ucwords($maintenanceData->plot->name) : "";
            })
            ->addColumn('flat', function ($maintenanceData) {
                return $flat = (isset($maintenanceData->flat->name)) ? ucwords($maintenanceData->flat->flat_no) : "";
            })
            ->addColumn('type', function ($maintenanceData) {
                $maintenanceTypes = getMaintenanceTypes();
                return $type = (isset($maintenanceData->type)) ? $maintenanceTypes[$maintenanceData->type] : "";
            })
            ->addColumn('date', function ($maintenanceData) {
                return $date = (isset($maintenanceData->date)) ? ucwords($maintenanceData->date) : "";
            })
            ->addColumn('year', function ($maintenanceData) {
                return $year = (isset($maintenanceData->year)) ? ucwords($maintenanceData->year) : "";
            })
            ->addColumn('month', function ($maintenanceData) {
                return $month = (isset($maintenanceData->month)) ? ucwords($maintenanceData->month) : "";
            })
            ->addColumn('amount', function ($maintenanceData) {
                return $amount = (isset($maintenanceData->amount)) ? ucwords($maintenanceData->amount) : "";
            })
            ->addColumn('payment_status', function ($maintenanceData) {
                $paymentStatus = getPaymentStatus();
                return $payment_status = (isset($maintenanceData->payment_status)) ? $paymentStatus[$maintenanceData->payment_status] : "";
            })
            ->addColumn('created_at', function ($maintenanceData) {
                return $created_at = (isset($maintenanceData->created_at)) ? date("F j, Y, g:i a", strtotime($maintenanceData->created_at)) : "";
            })
            ->addColumn('status', function ($maintenanceData) {
                return $status = (isset($maintenanceData->status) && ($maintenanceData->status == 1)) ? 'Enabled' : 'Disabled';
            })
            ->addColumn('action', function ($maintenanceData) {
                $attachments = '
                    <div class="btn-group">
                        <a href="' . asset($maintenanceData->attachments) . '" class="btn btn-sm btn-default" title="Download Attachment" download target="_blank" ><i class="fas fa-arrow-alt-circle-down"></i></a>
                    </div>
                ';

                $link = '
                    <div class="btn-group">
                        <a href="' . route('maintenance.delete', $maintenanceData->id) . '" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Do you really want to trash the entry?\');" ><i class="fas fa-trash-alt"></i></a>
                    </div>
                ';

                $activelink = '
                        <div class="btn-group">
                            <a href="' . route('admin.maintenance.enable', $maintenanceData->id) . '" class="btn btn-sm btn-warning" title="Enable"><i class="fas fa-lock"></i></a>
                        </div>
                    ';
                $inactivelink = '
                        <div class="btn-group">
                            <a href="' . route('admin.maintenance.disable', $maintenanceData->id) . '" class="btn btn-sm btn-success" title="Disable"><i class="fas fa-lock-open"></i></a>
                        </div>
                    ';

                $editlink = '
                    <div class="btn-group">
                        <a href="' . route('admin.maintenance.edit', $maintenanceData->id) . '" class="btn btn-sm  mt-1 mb-1 bg-pink" title="Edit" ><i class="fas fa-pencil-alt"></i></a>
                    </div>
                ';

                $viewlink = '
                    <div class="btn-group">
                        <a href="' . route('admin.maintenance.show', $maintenanceData->id) . '" class="btn btn-sm smt-1 mb-1 bg-blue" title="View Details" ><i class="fas fa-eye"></i></a>
                    </div>
                ';

                $final = ($maintenanceData->status == 1) ? $attachments . $editlink . $link . $inactivelink . $viewlink : $attachments . $editlink . $link . $activelink . $viewlink;
                // $link = '<a href="' . route('maintenance.delete', $maintenanceData->id) . '" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> Delete</a> ';
                return $final;
            })
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = "add maintenance";
        $module = "maintenance";
        $societies = getSocieties();
        $blocks = getBlocks();
        $plots = getPlots();
        $flats = getFlats();
        $propertyTypes = getPropertyTypes();
        $maintenanceTypes = getMaintenanceTypes();
        $paymentStatus = getPaymentStatus();
        $months = getMonths();
        $flatDetails = getFlatDetails();
        return view('maintenance.create', compact('title', 'module', 'societies', 'blocks', 'plots', 'flats', 'propertyTypes', 'maintenanceTypes', 'paymentStatus', 'months', 'flatDetails'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\MaintenanceStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaintenanceStoreRequest $request)
    {
        $data = $request->input();
        try {
            $request->request->add(['user_id' => Auth::user()->id]);
            $request->request->add(['society_id' => $request->input('society')]);
            $request->request->add(['block_id' => $request->input('block')]);
            $request->request->add(['plot_id' => $request->input('plot')]);
            $request->request->add(['flat_id' => $request->input('flat')]);
            $request->request->remove('society');
            $request->request->remove('block');
            $request->request->remove('plot');
            $request->request->remove('flat');
            $maintenance = Maintenance::create($request->all());

            $file = $request->file('attachments');
            if ($request->file('attachments')) {
                $destinationPath = public_path('/uploads/maintenance/');
                $name = $maintenance->unique_code . '_' . $file->getSize() . time() . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $name);
                $maintenance->attachments = 'uploads/maintenance/' . $name;
                $maintenance->save();
            }
            return redirect()->route('admin.maintenance.list')->with('success', __('messages.create_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.maintenance.create')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource. array_flip
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Maintenance $maintenance, $id)
    {
        $title = "maintenances";
        $module = "maintenance";
        $months = getMonths();
        $propertyTypes = getPropertyTypes();
        $maintenanceTypes = getMaintenanceTypes();
        $paymentStatus = getPaymentStatus();
        $data = Maintenance::with("createdBy", "society", "society.associatedCountry", "society.associatedState", "society.associatedCity", "block", "plot", "flat")->active()->findOrFail($id);
        // echo "<pre>"; print_r($data); die();
        return view('maintenance.show', compact('data', 'title', 'module', 'months', 'propertyTypes', 'maintenanceTypes', 'paymentStatus'));
    }

    /**
     * Edit the form for editing the specified resource.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function edit(MaintenanceStoreRequest $maintenance)
    {
        try {
            $listings = Maintenance::findOrFail($maintenance->id);
            $title = "maintenance";
            $module = "maintenance";
            $societies = getSocieties();
            $blocks = getBlocks();
            $plots = getPlots();
            $flats = getFlats();
            $propertyTypes = getPropertyTypes();
            $maintenanceTypes = getMaintenanceTypes();
            $paymentStatus = getPaymentStatus();
            $months = getMonths();
            return view('maintenance.edit', compact('listings', 'title', 'module', 'societies', 'blocks', 'plots', 'flats', 'propertyTypes', 'maintenanceTypes', 'paymentStatus', 'months'));
        } catch (\Exception $e) {
            return redirect()->route('admin.maintenance.edit')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\MaintenanceStoreRequest  $request
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function update(MaintenanceStoreRequest $request, Maintenance $maintenance)
    {
        try {
            $request->request->add(['user_id' => Auth::user()->id]);
            $request->request->add(['society_id' => $request->input('society')]);
            $request->request->add(['block_id' => $request->input('block')]);
            $request->request->add(['plot_id' => $request->input('plot')]);
            $request->request->add(['flat_id' => $request->input('flat')]);
            $request->request->remove('society');
            $request->request->remove('block');
            $request->request->remove('plot');
            $request->request->remove('flat');
            $request->request->remove('_method');
            $request->request->remove('_token');
            Maintenance::where(['id' => $maintenance->id])->update($request->all());
            return redirect()->route('admin.maintenance.list')->with('success', __('messages.update_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.maintenance.list')->with('error', $e->getMessage());
        }
    }

    /**
     * Enable the specified Maintenance in storage.
     *
     * @param $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Maintenance $maintenance
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request, Maintenance $maintenance, $id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->status = "1";
        $maintenance->save();
        return redirect()->route('admin.maintenance.list')->with('success', __('messages.enable_success'));
    }

    /**
     * Disable the specified Maintenance in storage.
     * 
     * @param $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request, Maintenance $maintenance, $id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->status = "0";
        $maintenance->save();
        return redirect()->route('admin.maintenance.list')->with('warning', __('messages.disable_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Maintenance  $maintenance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Maintenance $maintenance, $id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();

        // Shows the remaining list of maintenances.
        return redirect()->route('admin.maintenance.list')->with('error', __('messages.delete_success'));
    }
}
