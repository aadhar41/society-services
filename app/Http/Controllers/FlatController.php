<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use Illuminate\Http\Request;
use App\Models\Plot;
use App\Models\Block;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\PlotStoreRequest;
use PhpParser\Node\Expr\Print_;
use App\Interfaces\FlatRepositoryInterface;

class FlatController extends Controller
{
    private FlatRepositoryInterface $flatRepositoryInterface;

    /**
     * Apply default authentication middleware for backend routes.
     *
     * @return void
     */
    public function __construct(FlatRepositoryInterface $flatRepositoryInterface)
    {
        $this->middleware('auth');
        $this->flatRepositoryInterface = $flatRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "flats";
        $module = "flat";
        $data = $this->flatRepositoryInterface->getAllFlats();
        return view('flat.index', compact('data', 'title', 'module'));
    }

    /**
     * Process datatables ajax request.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        // return Datatables::of(Flat::query())->make(true);

        $flatdata = Flat::select('flats.id', 'flats.unique_code', 'flats.society_id', 'flats.block_id', 'flats.plot_id', 'flats.name', 'flats.flat_no', 'flats.mobile_no', 'flats.property_type', 'flats.tenant_name', 'flats.tenant_contact', 'flats.status', 'flats.created_at', 'flats.updated_at');
        return Datatables::of($flatdata)
            ->filter(function ($query) use ($request) {
                if ($request->has('status') && $request->get('status') != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('flats.status', 'like', "%{$request->get('status')}%");
                    });
                }

                if ($request->has('name') && $request->get('name') != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('flats.name', 'like', "%{$request->get('name')}%");
                    });
                }

                if (!empty($request['search']['value']) && $request['search']['value'] != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('flats.name', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('flats.unique_code', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('flats.flat_no', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('flats.mobile_no', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('flats.tenant_name', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('flats.tenant_contact', 'like', "%{$request['search']['value']}%");
                        // $q->orWhere('flats.description', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('flats.created_at', 'like', "%{$request['search']['value']}%");
                    });
                }
            })
            ->addColumn('unique_code', function ($flatdata) {
                return $unique_code = (isset($flatdata->unique_code)) ? ucwords($flatdata->unique_code) : "";
            })

            ->addColumn('society', function ($flatdata) {
                return $society = (isset($flatdata->society->name)) ? ucwords($flatdata->society->name) : "";
            })
            ->addColumn('block', function ($flatdata) {
                return $block = (isset($flatdata->block->name)) ? ucwords($flatdata->block->name) : "";
            })
            ->addColumn('plot', function ($flatdata) {
                return $plot = (isset($flatdata->plot->name)) ? ucwords($flatdata->plot->name) : "";
            })
            ->addColumn('name', function ($flatdata) {
                return $name = (isset($flatdata->name)) ? ucwords($flatdata->name) : "";
            })
            ->addColumn('flat_no', function ($flatdata) {
                return $flat_no = (isset($flatdata->flat_no)) ? ucwords($flatdata->flat_no) : "";
            })
            ->addColumn('mobile_no', function ($flatdata) {
                return $mobile_no = (isset($flatdata->mobile_no)) ? ucwords($flatdata->mobile_no) : "";
            })
            ->addColumn('property_type', function ($flatdata) {
                $propertyTypes = getPropertyTypes();
                return $property_type = (isset($flatdata->property_type)) ? ucwords($propertyTypes[$flatdata->property_type]) : "";
            })
            ->addColumn('tenant_name', function ($flatdata) {
                return $tenant_name = (isset($flatdata->tenant_name) && ($flatdata->property_type == 2)) ? ucwords($flatdata->tenant_name) : "";
            })
            ->addColumn('tenant_contact', function ($flatdata) {
                return $tenant_contact = (isset($flatdata->tenant_contact) && ($flatdata->property_type == 2)) ? ucwords($flatdata->tenant_contact) : "";
            })
            // ->addColumn('description', function ($flatdata) {
            //     return $description = (isset($flatdata->description)) ? ucwords($flatdata->description) : "";
            // })
            ->addColumn('created_at', function ($flatdata) {
                return $created_at = (isset($flatdata->created_at)) ? date("F j, Y, g:i a", strtotime($flatdata->created_at)) : "";
            })
            ->addColumn('status', function ($flatdata) {
                return $status = (isset($flatdata->status) && ($flatdata->status == 1)) ? 'Enabled' : 'Disabled';
            })
            ->addColumn('action', function ($flatdata) {

                $link = '
                    <div class="btn-group">
                        <a href="' . route('flat.delete', $flatdata->id) . '" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Do you really want to trash the entry?\');" ><i class="fas fa-trash-alt"></i></a>
                    </div>
                ';

                $activelink = '
                        <div class="btn-group">
                            <a href="' . route('admin.flat.enable', $flatdata->id) . '" class="btn btn-sm btn-warning" title="Enable"><i class="fas fa-lock"></i></a>
                        </div>
                    ';
                $inactivelink = '
                        <div class="btn-group">
                            <a href="' . route('admin.flat.disable', $flatdata->id) . '" class="btn btn-sm btn-success" title="Disable"><i class="fas fa-lock-open"></i></a>
                        </div>
                    ';

                $editlink = '
                    <div class="btn-group">
                        <a href="' . route('admin.flat.edit', $flatdata->id) . '" class="btn btn-sm  mt-1 mb-1 bg-pink" title="Edit" ><i class="fas fa-pencil-alt"></i></a>
                    </div>
                ';

                $final = ($flatdata->status == 1) ? $editlink . $link . $inactivelink : $editlink . $link . $activelink;
                // $link = '<a href="' . route('flat.delete', $flatdata->id) . '" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> Delete</a> ';
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
        $title = "create flat";
        $module = "flat";
        $societies = getSocieties();
        $blocks = getBlocks();
        $plots = getPlots();
        $propertyTypes = getPropertyTypes();
        return view('flat.create', compact('title', 'module', 'societies', 'blocks', 'plots', 'propertyTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // request()->merge(['user_id' => Auth::user()->id]);
            // request()->merge(['society_id' => $request->input('society')]);
            // request()->merge(['block_id' => $request->input('block')]);
            // request()->merge(['plot_id' => $request->input('plot')]);
            // Flat::create(request()->only(["name", "user_id", "society_id", "block_id", "plot_id", "flat_no", "mobile_no", "property_type", "tenant_name", "tenant_contact", "description"]));
            request()->merge(['user_id' => Auth::user()->id]);
            $this->flatRepositoryInterface->createFlat(request()->all());
            return redirect()->route('admin.flat.list')->with('success', __('messages.create_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.flat.create')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Flat  $flat
     * @return \Illuminate\Http\Response
     */
    public function show(Flat $flat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Flat  $flat
     * @return \Illuminate\Http\Response
     */
    public function edit(Flat $flat)
    {
        try {
            $listings = Flat::findOrFail($flat->id);
            $title = "flat";
            $module = "flat";
            $societies = getSocieties();
            $blocks = getBlocks();
            $plots = getPlots();
            $propertyTypes = getPropertyTypes();
            return view('flat.edit', compact('listings', 'title', 'module', 'societies', 'blocks', 'plots', 'propertyTypes'));
        } catch (\Exception $e) {
            return redirect()->route('admin.flat.edit')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Flat  $flat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flat $flat)
    {
        try {
            request()->merge(['user_id' => Auth::user()->id]);
            request()->merge(['society_id' => $request->input('society')]);
            request()->merge(['block_id' => $request->input('block')]);
            request()->merge(['plot_id' => $request->input('plot')]);
            Flat::where(['id' => $flat->id])->update(request()->only(["name", "user_id", "society_id", "block_id", "plot_id", "flat_no", "mobile_no", "property_type", "tenant_name", "tenant_contact", "description"]));
            return redirect()->route('admin.flat.list')->with('success', __('messages.update_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.flat.list')->with('error', $e->getMessage());
        }
    }

    /**
     * Enable the specified Flat in storage.
     *
     * @param $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Flat $flat
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request, Flat $flat, $id)
    {
        $flat = Flat::findOrFail($id);
        $flat->status = "1";
        $flat->save();
        return redirect()->route('admin.flat.list')->with('success', __('messages.enable_success'));
    }

    /**
     * Disable the specified Flat in storage.
     * 
     * @param $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Flat  $flat
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request, Flat $flat, $id)
    {
        $flat = Flat::findOrFail($id);
        $flat->status = "0";
        $flat->save();
        return redirect()->route('admin.flat.list')->with('warning', __('messages.disable_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Flat  $flat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Flat $flat, $id)
    {
        $flat = Flat::findOrFail($id);
        $flat->delete();

        // Shows the remaining list of flats.
        return redirect()->route('admin.flat.list')->with('error', __('messages.delete_success'));
    }


    public function getSocietyBlocks()
    {
        $options = '<option value="">Select</option>';
        if (!empty($_POST['society_id'])) {
            $getSocietyBlocks = getSocietyBlocks($_POST['society_id']);
            foreach ($getSocietyBlocks as $id => $name) {
                $options .= "<option value='" . $id . "' (old('block') == " . $id . " ? 'selected':'') >" . ucwords($name) . "</option>";
            }
        }
        echo json_encode(['options' => $options]);
        die;
    }


    public function getBlockPlots()
    {
        $options = '<option value="">Select</option>';
        if (!empty($_POST['block_id'])) {
            $getBlockPlots = getBlockPlots($_POST['block_id']);
            foreach ($getBlockPlots as $id => $name) {
                $options .= "<option value='" . $id . "' (old('block') == " . $id . " ? 'selected':'') >" . ucwords($name) . "</option>";
            }
        }
        echo json_encode(['options' => $options]);
        die;
    }


    public function getPlotsFlats()
    {
        $options = '<option value="">Select</option>';
        if (!empty($_POST['plot_id'])) {
            $getPlotsFlats = getPlotsFlats($_POST['plot_id']);
            foreach ($getPlotsFlats as $key => $value) {
                $options .= "<option value='" . $value->id . "' (old('flat') == " . $value->id . " ? 'selected':'') >" . ucwords($value->name) . " ( " . $value->flat_no . " ) </option>";
            }
        }
        echo json_encode(['options' => $options]);
        die;
    }
}
