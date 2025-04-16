<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\Datatables\Datatables;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\BlockStoreRequest;
use App\Interfaces\BlockRepositoryInterface;

class BlockController extends Controller
{
    private BlockRepositoryInterface $blockRepositoryInterface;

    /**
     * Apply default authentication middleware for backend routes.
     *
     * @return void
     */
    public function __construct(BlockRepositoryInterface $blockRepositoryInterface)
    {
        $this->middleware('auth');
        $this->blockRepositoryInterface = $blockRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "blocks";
        $module = "block";
        $data = $this->blockRepositoryInterface->getAllBlocks();
        return view('block.index', compact('data', 'title', 'module'));
    }

    /**
     * Process datatables ajax request.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        // return Datatables::of(Block::query())->make(true);

        $blockdata = Block::select('blocks.id', 'blocks.unique_code', 'blocks.society_id', 'blocks.name', 'blocks.total_flats', 'blocks.description', 'blocks.status', 'blocks.created_at', 'blocks.updated_at');
        return Datatables::of($blockdata)
            ->filter(function ($query) use ($request) {
                if ($request->has('status') && $request->get('status') != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('blocks.status', 'like', "%{$request->get('status')}%");
                    });
                }

                if ($request->has('name') && $request->get('name') != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('blocks.name', 'like', "%{$request->get('name')}%");
                    });
                }

                if (!empty($request['search']['value']) && $request['search']['value'] != '') {
                    $query->where(function ($q) use ($request) {
                        $q->where('blocks.name', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('blocks.unique_code', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('blocks.total_flats', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('blocks.description', 'like', "%{$request['search']['value']}%");
                        $q->orWhere('blocks.created_at', 'like', "%{$request['search']['value']}%");
                    });
                }
            })
            ->addColumn('unique_code', function ($blockdata) {
                return $unique_code = (isset($blockdata->unique_code)) ? ucwords($blockdata->unique_code) : "";
            })
            ->addColumn('society', function ($blockdata) {
                return $society = (isset($blockdata->society->name)) ? ucwords($blockdata->society->name) : "";
            })
            ->addColumn('name', function ($blockdata) {
                return $name = (isset($blockdata->name)) ? ucwords($blockdata->name) : "";
            })
            ->addColumn('total_flats', function ($blockdata) {
                return $total_flats = (isset($blockdata->total_flats)) ? ucwords($blockdata->total_flats) : "";
            })
            // ->addColumn('description', function ($blockdata) {
            //     return $description = (isset($blockdata->description)) ? ucwords($blockdata->description) : "";
            // })
            ->addColumn('created_at', function ($blockdata) {
                return $created_at = (isset($blockdata->created_at)) ? date("F j, Y, g:i a", strtotime($blockdata->created_at)) : "";
            })
            ->addColumn('status', function ($blockdata) {
                return $status = (isset($blockdata->status) && ($blockdata->status == 1)) ? 'Enabled' : 'Disabled';
            })
            ->addColumn('action', function ($blockdata) {

                $link = '
                    <div class="btn-group">
                        <a href="' . route('block.delete', $blockdata->id) . '" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm(\'Do you really want to trash the entry?\');" ><i class="fas fa-trash-alt"></i></a>
                    </div>
                ';

                $activelink = '
                        <div class="btn-group">
                            <a href="' . route('admin.block.enable', $blockdata->id) . '" class="btn btn-sm btn-warning" title="Enable"><i class="fas fa-lock"></i></a>
                        </div>
                    ';
                $inactivelink = '
                        <div class="btn-group">
                            <a href="' . route('admin.block.disable', $blockdata->id) . '" class="btn btn-sm btn-success" title="Disable"><i class="fas fa-lock-open"></i></a>
                        </div>
                    ';

                $editlink = '
                    <div class="btn-group">
                        <a href="' . route('admin.block.edit', $blockdata->id) . '" class="btn btn-sm  mt-1 mb-1 bg-pink" title="Edit" ><i class="fas fa-pencil-alt"></i></a>
                    </div>
                ';

                $final = ($blockdata->status == 1) ? $editlink . $link . $inactivelink : $editlink . $link . $activelink;
                // $link = '<a href="' . route('block.delete', $blockdata->id) . '" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> Delete</a> ';
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
        $title = "create block";
        $module = "block";
        $societies = getSocieties();
        return view('block.create', compact('title', 'module', 'societies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\BlockStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlockStoreRequest $request)
    {
        try {
            request()->merge(['user_id' => Auth::user()->id]);
            request()->merge(['society_id' => $request->input('society')]);
            $this->blockRepositoryInterface->createBlock(request()->only(["name", "user_id", "society_id", "total_flats", "description"]));
            return redirect()->route('admin.block.list')->with('success', __('messages.create_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.block.create')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function show(Block $block)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function edit(Block $block, Request $request)
    {
        try {
            $title = "block";
            $module = "block";
            $societies = getSocieties();
            $listings = $this->blockRepositoryInterface->getBlockById($block->id);
            return view('block.edit', compact('listings', 'title', 'module', 'societies'));
        } catch (\Exception $e) {
            return redirect()->route('admin.block.edit')->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\BlockStoreRequest  $request
     * @param  \App\Models\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function update(BlockStoreRequest $request, Block $block)
    {
        try {
            request()->merge(['user_id' => Auth::user()->id]);
            request()->merge(['society_id' => $request->input('society')]);
            $this->blockRepositoryInterface->updateBlock($block->id, request()->only(["name", "user_id", "society_id", "total_flats", "description"]));
            return redirect()->route('admin.block.list')->with('success', __('messages.update_success'));
        } catch (\Exception $e) {
            return redirect()->route('admin.block.list')->with('error', $e->getMessage());
        }
    }

    /**
     * Enable the specified block in storage.
     *
     * @param $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Block $block
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request, Block $block, $id)
    {
        $this->blockRepositoryInterface->enableRecord($id);
        return redirect()->route('admin.block.list')->with('success', __('messages.enable_success'));
    }

    /**
     * Disable the specified block in storage.
     *
     * @param $id
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request, Block $block, $id)
    {
        $this->blockRepositoryInterface->disableRecord($id);
        return redirect()->route('admin.block.list')->with('warning', __('messages.disable_success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Block  $block
     * @return \Illuminate\Http\Response
     */
    public function destroy(Block $block, $id)
    {
        $this->blockRepositoryInterface->deleteBlock($id);
        // Shows the remaining list of blocks.
        return redirect()->route('admin.block.list')->with('error', __('messages.delete_success'));
    }
}
