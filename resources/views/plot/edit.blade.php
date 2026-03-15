@extends('layouts.app')

@section('title', config('app.name') . ' | ' . ucfirst($title))

@section('content')

@include('partials._select2Assests')

<form action="{{ route('admin.plot.update', $listings->id) }}" method="POST" enctype="multipart/form-data">
    {{ method_field('PUT') }}
    @csrf

    <!-- Main content -->
    <section class="content mt-2">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-default">
                        <div class="card-header">
                            <x-card-title route="{{ route('admin.plot.list') }}" type="primary" title="Record lists" />
                            <x-card-tools route="{{ route('admin.plot.list') }}" type="primary" title="" />
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <div class="row">

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" value="{{ old('name', $listings->name) }}" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Name" autocomplete="off" />
                                        @if($errors->has('name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label for="society">Society :</label>
                                        <select name="society" id="society" class="form-control select2 {{ $errors->has('society') ? 'is-invalid' : '' }}">
                                            <option value="">Select Society</option>
                                            @foreach($societies as $id => $name)
                                            <option value="{{ $id }}" {{ (old("society", $listings->society_id) == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('society'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('society') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label for="block">Block :</label>
                                        <select name="block" id="block" class="form-control select2 {{ $errors->has('block') ? 'is-invalid' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($blocks as $id => $name)
                                            <option value="{{ $id }}" {{ (old("block", $listings->block_id) == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('block'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('block') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label for="total_flats">Total Flats :</label>
                                        <input type="number" minlength="1" maxlength="10" name="total_flats" value="{{ old('total_flats', $listings->total_flats) }}" id="total_flats" class="form-control {{ $errors->has('total_flats') ? 'is-invalid' : '' }}" placeholder="Total Flats" autocomplete="off" />
                                        @if($errors->has('total_flats'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('total_flats') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label for="total_floors">Total Floors :</label>
                                        <input type="number" minlength="1" maxlength="10" name="total_floors" value="{{ old('total_floors', $listings->total_floors) }}" id="total_floors" class="form-control {{ $errors->has('total_floors') ? 'is-invalid' : '' }}" placeholder="Total Floors" autocomplete="off" />
                                        @if($errors->has('total_floors'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('total_floors') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label for="plot_area">Plot Area</label>
                                        <input type="text" name="plot_area" value="{{ old('plot_area', $listings->plot_area) }}" id="plot_area" class="form-control {{ $errors->has('plot_area') ? 'is-invalid' : '' }}" placeholder="Plot Area" autocomplete="off" />
                                        @if($errors->has('plot_area'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('plot_area') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" rows="4" class="ckeditor form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" placeholder="Description">{{ old('description', $listings->description) }}</textarea>
                                        @if($errors->has('description'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('description') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary ml-2"><i class="far fa-hand-point-up"></i>&nbsp;&nbsp;Update</button>

                            </div>
                            <!-- /.card-body -->

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6"></div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->


</form>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@include('partials._ckeditor')
@endsection