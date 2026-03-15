<?php

namespace App\Http\Controllers;

use App\Models\Plot;
use App\Models\Block;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\PlotStoreRequest;
use App\Interfaces\PlotRepositoryInterface;

class PlotController extends Controller
{
    private PlotRepositoryInterface $plotRepositoryInterface;

    /**
     * Apply default authentication middleware for backend routes.
     *
     * @return void
     */
    public function __construct(PlotRepositoryInterface $plotRepositoryInterface)
    {
        $this->middleware('auth');
        $this->plotRepositoryInterface = $plotRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "plots";
        $module = "plot";
        $data = $this->plotRepositoryInterface->getAllPlots();
        return view('plot.index', compact('data', 'title', 'module'));
    }

    /**
     * Process datatables ajax request.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        // return Datatables::of(Block::query())->make(true);

        $plotdata = Plot::select('plots.id', 'plots.unique_code', 'plots.society_id', 'plots.block_id', 'plots.name', 'plots.total_floors' ,'plots.total_flats', 'plots.description', 'plots.status', 'plots.created_at', 'plots.updated_at');
        return Datatables::of($plotdata)
            ->filter(function ($query) use ($request) {
                if ($request->has('status') && $request->get('status') != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('plots.status', 'like', "%{$request->get('status')}%");
                    });
                }

                if ($request->has('name') && $request->get('name') != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('plots.name', 'like', "%{$request->get('name')}%");
                    });
                }

                if (!empty($request['search']['value']) && $request['search']['value'] != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('plots.name', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('plots.unique_code', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('plots.total_floors', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('plots.total_flats', 'like', "%{$request['search']['value']}%");
                        // $q->orWhere('plots.description', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('plots.created_at', 'like', "%{$request['search']['value']}%");
                    });
                }
            })
            ->addColumn('unique_code', function ($plotdata) {
                return $unique_code = (isset($plotdata->unique_code)) ? ucwords($plotdata->unique_code) : "";
            })
            ->addColumn('society', function ($plotdata) {
                return $society = (isset($plotdata->society->name)) ? ucwords($plotdata->society->name) : "";
            })
            ->addColumn('block', function ($plotdata) {
                return $block = (isset($plotdata->block->name)) ? ucwords($plotdata->block->name) : "";
            })
            ->addColumn('name', function ($plotdata) {
                return $name = (isset($plotdata->name)) ? ucwords($plotdata->name) : "";
            })
            ->addColumn('total_floors', function ($plotdata) {
                return $total_floors = (isset($plotdata->total_floors)) ? ucwords($plotdata->total_floors) : "";
            })
            ->addColumn('total_flats', function ($plotdata) {
                return $total_flats = (isset($plotdata->total_flats)) ? ucwords($plotdata->total_flats) : "";
            })
            // ->addColumn('description', function ($plotdata) {
            //     return $description = (isset($plotdata->description)) ? ucwords($plotdata->description) : "";
            // })
            ->addColumn('created_at', function ($plotdata) {
                return $created_at = (isset($plotdata->created_at)) ? date("F j, Y, g:i a", strtotime($plotdata->created_at)) : "";
            })
            ->addColumn('status', function ($plotdata) {
                return $status = (isset($plotdata->status) && ($plotdata->status == 1)) ? 'Enabled' : 'Disabled';
            })
            ->addColumn('action', function ($plotdata) {

                $link = '
                    <div class="btn-group">
                        <a href="' . route('plot.delete', $plotdata->id) . '" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Do you really want to trash the entry?\');" ><i class="fas fa-trash-alt"></i></a>
                    </div>
                ';

                $activelink = '
                        <div class="btn-group">
                            <a href="' . route('admin.plot.enable', $plotdata->id) . '" class="btn btn-sm btn-warning" title="Enable"><i class="fas fa-lock"></i></a>
                        </div>
                    ';
                $inactivelink = '
                        <div class="btn-group">
                            <a href="' . route('admin.plot.disable', $plotdata->id) . '" class="btn btn-sm btn-success" title="Disable"><i class="fas fa-lock-open"></i></a>
                        </div>
                    ';

                $editlink = '
                    <div class="btn-group">
                        <a href="' . route('admin.plot.edit', $plotdata->id) . '" class="btn btn-sm  mt-1 mb-1 bg-pink" title="Edit" ><i class="fas fa-pencil-alt"></i></a>
                    </div>
                ';

                $final = ($plotdata->status == 1) ? $editlink . $link . $inactivelink : $editlink . $link . $activelink;
                // $link = '<a href="' . route('block.delete', $plotdata->id) . '" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> Delete</a> ';
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
        $title = "create plot";
        $module = "plot";
        $societies = getSocieties();
        $blocks = getBlocks();
        return view('plot.create', compact('title', 'module', 'societies', 'blocks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlotStoreRequest $request)
    {
        try {
            request()->merge(['user_id' => Auth::user()->id]);
            request()->merge(['society_id' => $request->input('society')]);
            request()->merge(['block_id' => $request->input('block')]);
            $this->plotRepositoryInterface->createPlot(request()->only(["name", "user_id", "society_id", "block_id", "total_floors", "total_flats", "plot_area", "description"]));
            return redirect()->route('admin.plot.list')->with('success', __('messages.create_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.plot.create')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plot  $plot
     * @return \Illuminate\Http\Response
     */
    public function show(Plot $plot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plot  $plot
     * @return \Illuminate\Http\Response
     */
    public function edit(Plot $plot)
    {
        try {
            $title = "plot";
            $module = "plot";
            $societies = getSocieties();
            $blocks = getBlocks();
            $listings = $this->plotRepositoryInterface->getPlotById($plot->id);
            return view('plot.edit', compact('listings', 'title', 'module', 'societies','blocks'));
        } catch (\Exception $e) {
            return redirect()->route('admin.plot.edit')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\PlotStoreRequest  $request
     * @param  \App\Models\Plot  $plot
     * @return \Illuminate\Http\Response
     */
    public function update(PlotStoreRequest $request, Plot $plot)
    {
        try {
            request()->merge(['user_id' => Auth::user()->id]);
            request()->merge(['society_id' => $request->input('society')]);
            request()->merge(['block_id' => $request->input('block')]);
            $this->plotRepositoryInterface->updatePlot($plot->id, request()->only(["name", "user_id", "society_id", "block_id", "total_floors", "total_flats", "plot_area", "description"]));
            return redirect()->route('admin.plot.list')->with('success', __('messages.update_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.plot.list')->with('error', $e->getMessage());
        }
    }

    /**
     * Enable the specified plot in storage.
     *
     * @param $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plot $plot
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request, Plot $plot, $id)
    {
        $this->plotRepositoryInterface->enableRecord($id);
        return redirect()->route('admin.plot.list')->with('success', __('messages.enable_success'));
    }

    /**
     * Disable the specified Plot in storage.
     * 
     * @param $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plot  $plot
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request, Plot $plot, $id)
    {
        $this->plotRepositoryInterface->disableRecord($id);
        return redirect()->route('admin.plot.list')->with('warning', __('messages.disable_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plot  $plot
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plot $plot, $id)
    {
        $this->plotRepositoryInterface->deletePlot($id);
        // Shows the remaining list of plots.
        return redirect()->route('admin.plot.list')->with('error', __('messages.delete_success'));
    }
}
