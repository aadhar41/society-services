@extends('layouts.app')

@section('content')

@include('partials._ckeditor')
@include('partials._plugins')
@include('partials._select2Assests')

<!-- Main content -->
<section class="content mt-2">
    <div class="container-fluid">

        <!-- Content Header (Page header) -->
        <x-content-header :title="$title" :module="$module" />

        <form action="{{ route('admin.maintenance.store') }}" method="POST" enctype="multipart/form-data">
            {{ method_field('POST') }}
            @csrf
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- jquery validation -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <x-card-title route="{{ route('admin.maintenance.list') }}" type="primary" title="Record Lists" />
                                    <x-card-tools route="{{ route('admin.maintenance.list') }}" type="primary" title="" />
                                </div>
                                <!-- /.card-header -->

                                <div class="card-body">
                                    <div class="row">

                                        <!-- Society -->
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <div class="form-group">
                                                <label for="society">Society :</label>
                                                <select name="society" id="society" class="form-control select2 {{ $errors->has('society') ? 'is-invalid' : '' }}">
                                                    <option value="">Select</option>
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

                                        <!-- Block -->
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <div class="form-group">
                                                <label for="block">Block :</label>
                                                <select name="block" id="block" class="form-control select2 {{ $errors->has('block') ? 'is-invalid' : '' }}">
                                                    <option value="">Select</option>
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

                                        <!-- Plot -->
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
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

                                        <!-- Flat -->
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <div class="form-group">
                                                <label for="flat">Flat :</label>
                                                <select name="flat" id="flat" class="form-control select2 {{ $errors->has('flat') ? 'is-invalid' : '' }}">
                                                    <option value="">Select</option>
                                                    @foreach($flatDetails as $key => $value)
                                                    <option value="{{ $value->id }}" {{ (old("flat") == $value->id ? "selected":"") }}>{{ ucwords($value->name) }} ( {{ $value->flat_no }} )</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('flat'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('flat') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Maintenance Type -->
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <div class="form-group">
                                                <label for="type">Maintenance Type :</label>
                                                <select name="type" id="type" class="form-control select2 {{ $errors->has('type') ? 'is-invalid' : '' }}">
                                                    <option value="">Select</option>
                                                    @foreach($maintenanceTypes as $id => $name)
                                                    <option value="{{ $id }}" {{ (old("type") == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('type'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('type') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Date -->
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <div class="form-group">
                                                <label for="date">Date :</label>
                                                <div class="input-group date" id="date" data-target-input="nearest">
                                                    <input type="text" name="date" value="{{ old('date') }}" id="date" class="form-control {{ $errors->has('date') ? 'is-invalid' : '' }} datetimepicker-date" data-target="#date" placeholder="Date" autocomplete="off" />
                                                    <div class="input-group-append" data-target="#date" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                @if($errors->has('date'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('date') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Year -->
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <div class="form-group">
                                                <label for="year">Year :</label>
                                                <div class="input-group year" id="year" data-target-input="nearest">
                                                    <input type="text" name="year" value="{{ old('year') }}" id="year" class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }} datetimepicker-year" data-target="#year" placeholder="year" autocomplete="off" />
                                                    <div class="input-group-append" data-target="#year" data-toggle="yeartimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                @if($errors->has('year'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('year') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Month -->
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <div class="form-group">
                                                <label for="month">Month :</label>
                                                <select name="month" id="month" class="form-control select2 {{ $errors->has('month') ? 'is-invalid' : '' }}">
                                                    <option value="">Select</option>
                                                    @foreach($months as $id => $name)
                                                    <option value="{{ $id }}" {{ (old("month") == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('month'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('month') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Amount -->
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <div class="form-group">
                                                <label for="amount">Amount :</label>
                                                <input type="number" minlength="1" maxlength="10" name="amount" value="{{ old('amount') }}" id="amount" class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" placeholder="Amount" autocomplete="off" />
                                                @if($errors->has('amount'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('amount') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Payment Status -->
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <div class="form-group">
                                                <label for="payment_status">Payment Status :</label>
                                                <select name="payment_status" id="payment_status" class="form-control select2 {{ $errors->has('payment_status') ? 'is-invalid' : '' }}">
                                                    <option value="">Select</option>
                                                    @foreach($paymentStatus as $id => $name)
                                                    <option value="{{ $id }}" {{ (old("payment_status") == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('payment_status'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('payment_status') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Attachments -->
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <label for="attachments">Attachment :</label>
                                                <input type="file" name="attachments" id="attachments" class="form-control" accept="image/*, application/pdf" />
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description :</label>
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

        $('#plot').on('change', function() {
            var plot_id = this.value;
            getPlotsFlats(plot_id);
        });

        function getPlotsFlats(plot_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('getPlotsFlats') }}",
                type: "POST",
                data: {
                    plot_id: plot_id,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                },
                dataType: 'json',
                success: function(response) {
                    if (response.options) {
                        $("#flat").html(response.options);
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

        //Date and time picker
        $(function() {
            $('.datetimepicker-date').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 2000,
                maxYear: parseInt(moment().format('YYYY'), 10),
                locale: {
                    format: 'DD/MM/YYYY'
                }
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
                // alert("You are " + years + " years old!");
            });

            $('.datetimepicker-year').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                minYear: 2010,
                maxYear: parseInt(moment().format('YYYY'), 10),
                locale: {
                    format: 'YYYY'
                }
            }, function(start, end, label) {
                var years = moment().diff(start, 'years');
                // alert("You are " + years + " years old!");
            });
        });

    });
</script>


@endsection