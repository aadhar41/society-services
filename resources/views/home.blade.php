@extends('layouts.app')

@section('content')

<!-- Main content -->
<section class="content mt-1">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-2 col-6">
                <!-- Societies -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $totalSocieties }}</h3>
                        <p>Total Societies</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <a href="{{ route('admin.society.list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <!-- Blocks -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $totalBlocks }}</h3>
                        <p>Total Blocks</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-th-large"></i>
                    </div>
                    <a href="{{ route('admin.block.list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <!-- Plots -->
                <div class="small-box bg-warning" style="background-color: #f39c12 !important;">
                    <div class="inner">
                        <h3>{{ $totalPlots }}</h3>
                        <p>Total Plots</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map"></i>
                    </div>
                    <a href="{{ route('admin.plot.list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <!-- Flats -->
                <div class="small-box bg-primary" style="background-color: #3c8dbc !important;">
                    <div class="inner">
                        <h3>{{ $totalFlats }}</h3>
                        <p>Total Flats</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <a href="{{ route('admin.flat.list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <!-- Maintenance -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $totalMaintenance }}</h3>
                        <p>Maintenance Records</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <a href="{{ route('admin.maintenance.list') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-6">
                <!-- Expenses -->
                <div class="small-box bg-purple" style="background-color: #605ca8 !important; color: #fff !important;">
                    <div class="inner">
                        <h3>{{ $totalExpenses }}</h3>
                        <p>Total Expenses</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-bolt mr-1"></i> Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-2 col-sm-4 col-6">
                                <a href="{{ route('admin.society.create') }}" class="btn btn-app bg-info">
                                    <i class="fas fa-building"></i> Add Society
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-6">
                                <a href="{{ route('admin.block.create') }}" class="btn btn-app bg-success">
                                    <i class="fas fa-th-large"></i> Add Block
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-6">
                                <a href="{{ route('admin.plot.create') }}" class="btn btn-app bg-warning">
                                    <i class="fas fa-map"></i> Add Plot
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-6">
                                <a href="{{ route('admin.flat.create') }}" class="btn btn-app bg-primary">
                                    <i class="fas fa-home"></i> Add Flat
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-6">
                                <a href="{{ route('admin.maintenance.create') }}" class="btn btn-app bg-danger">
                                    <i class="fas fa-money-bill-wave"></i> Record Payment
                                </a>
                            </div>
                            <div class="col-md-2 col-sm-4 col-6">
                                <a href="{{ route('admin.maintenance-report.grid') }}" class="btn btn-app bg-secondary">
                                    <i class="fas fa-file-invoice-dollar"></i> Reports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main row -->
        <div class="row">
        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection