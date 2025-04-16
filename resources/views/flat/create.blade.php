@extends('layouts.app')

@section('title', config('app.name') . ' | ' . ucfirst($title))

@section('content')

@include('partials._select2Assests')

<!-- Main content -->
<section class="content mt-2">
    <div class="container-fluid">

        <!-- Content Header (Page header) -->
        <x-content-header :title="$title" :module="$module" />

        <form action="{{ route('admin.flat.store') }}" method="POST" enctype="multipart/form-data">
            {{ method_field('POST') }}
            @csrf
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- jquery validation -->
                            <div class="card card-default">
                                <div class="card-header">
                                    <x-card-title route="{{ route('admin.flat.list') }}" type="primary" title="Record Lists" />
                                    <x-card-tools route="{{ route('admin.flat.list') }}" type="primary" title="" />
                                </div>
                                <!-- /.card-header -->

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name">Name :</label>
                                                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Name" autocomplete="off" />
                                                @if($errors->has('name'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="flat_no">Flat No :</label>
                                                <input type="text" name="flat_no" value="{{ old('flat_no') }}" id="flat_no" class="form-control {{ $errors->has('flat_no') ? 'is-invalid' : '' }}" placeholder="Flat No" autocomplete="off" />
                                                @if($errors->has('flat_no'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('flat_no') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="mobile_no">Mobile No :</label>
                                                <input type="number" minlength="1" maxlength="10" name="mobile_no" value="{{ old('mobile_no') }}" id="mobile_no" class="form-control {{ $errors->has('mobile_no') ? 'is-invalid' : '' }}" placeholder="Mobile No" autocomplete="off" />
                                                @if($errors->has('mobile_no'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('mobile_no') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="property_type">Property Type :</label>
                                                <select name="property_type" id="property_type" class="form-control select2 {{ $errors->has('property_type') ? 'is-invalid' : '' }}">
                                                    <option value="">Select</option>
                                                    @foreach($propertyTypes as $id => $name)
                                                    <option value="{{ $id }}" {{ (old("property_type") == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('property_type'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('property_type') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="society">Society :</label>
                                                <select name="society" id="society" class="form-control select2 {{ $errors->has('society') ? 'is-invalid' : '' }}">
                                                    <option value="">Select Society</option>
                                                    @foreach($societies as $id => $name)
                                                    <option value="{{ $id }}" {{ (old("society") == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('society'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('society') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="block">Block :</label>
                                                <select name="block" id="block" class="form-control select2 {{ $errors->has('block') ? 'is-invalid' : '' }}">
                                                    <option value="">Select block</option>
                                                    @foreach($blocks as $id => $name)
                                                    <option value="{{ $id }}" {{ (old("block") == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('block'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('block') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="plot">Plot :</label>
                                                <select name="plot" id="plot" class="form-control select2 {{ $errors->has('plot') ? 'is-invalid' : '' }}">
                                                    <option value="">Select</option>
                                                    @foreach($plots as $id => $name)
                                                    <option value="{{ $id }}" {{ (old("plot") == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('plot'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('plot') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 js-rented">
                                            <div class="form-group">
                                                <label for="tenant_name">Tenant Name :</label>
                                                <input type="text" name="tenant_name" value="{{ old('tenant_name') }}" id="tenant_name" class="form-control {{ $errors->has('tenant_name') ? 'is-invalid' : '' }}" placeholder="Tenant Name" autocomplete="off" />
                                                @if($errors->has('tenant_name'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('tenant_name') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 js-rented">
                                            <div class="form-group">
                                                <label for="tenant_contact">Tenant Contact :</label>
                                                <input type="number" minlength="1" maxlength="10" name="tenant_contact" value="{{ old('tenant_contact') }}" id="tenant_contact" class="form-control {{ $errors->has('tenant_contact') ? 'is-invalid' : '' }}" placeholder="Tenant Contact" autocomplete="off" />
                                                @if($errors->has('tenant_contact'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('tenant_contact') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea name="description" id="description" rows="4" class="ckeditor form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" placeholder="Description">{{ old('description') }}</textarea>
                                                @if($errors->has('description'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary ml-2">
                                            Submit
                                        </button>

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
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });

    $(document).ready(function() {
        $('#society').on('change', function() {
            var society_id = this.value;
            getSocietyBlocks(society_id);
        });

        function getSocietyBlocks(society_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('getSocietyBlocks') }}",
                type: "POST",
                data: {
                    society_id: society_id,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: 'json',
                success: function(response) {
                    if (response.options) {
                        $("#block").html(response.options);
                    }
                }
            });
        }

        $('#block').on('change', function() {
            var block_id = this.value;
            getBlockPlots(block_id);
        });

        function getBlockPlots(block_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('getBlockPlots') }}",
                type: "POST",
                data: {
                    block_id: block_id,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: 'json',
                success: function(response) {
                    if (response.options) {
                        $("#plot").html(response.options);
                    }
                }
            });
        }

        $(".js-rented").hide();
        $("#property_type").change(function(e) {
            e.preventDefault();
            if (this.value == 2) {
                $(".js-rented").show();
            } else {
                $(".js-rented").hide();
            }
        });

    });
</script>
@include('partials._ckeditor')
@endsection