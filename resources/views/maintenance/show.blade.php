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
                                        <div class="col-12 col-sm-6">
                                            <div class="row mt-4">
                                                <div class="col-sm-6">
                                                    <div class="position-relative">
                                                        <div class="btn-group">
                                                            <a href="{{ asset($data->attachments) }}" class="btn btn-sm btn-default" title="Download Attachment" download target="_blank"><i class="fas fa-arrow-alt-circle-down"></i></a>
                                                        </div>
                                                        <img src="{{ asset($data->attachments) }}" alt="Photo 1" class="maintenance-img img-fluid">
                                                        <div class="ribbon-wrapper ribbon-lg">
                                                            <div class="ribbon @if($data->payment_status==1) bg-success @elseif($data->payment_status==2) bg-warning @else bg-primary @endif text-lg">
                                                                @if($data->payment_status==1)
                                                                Complete
                                                                @elseif($data->payment_status==2)
                                                                Pending
                                                                @else
                                                                Extra
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">
                                                            <i class="fas fa-text-width"></i>
                                                            Maintenances details
                                                        </h3>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <dl>
                                                            @if($data->unique_code)
                                                            <dt>Unique Code</dt>
                                                            <dd> {!! $data->unique_code !!} </dd>
                                                            @endif
                                                            @if($data->type)
                                                            <dt>Type</dt>
                                                            <dd>{!! $maintenanceTypes[$data->type] !!} </dd>
                                                            @endif
                                                            @if($data->description)
                                                            <dt>Description</dt>
                                                            <dd>{!! $data->description !!}</dd>
                                                            @endif
                                                            @if($data->year)
                                                            <dt>Year</dt>
                                                            <dd>{!! $data->year !!}</dd>
                                                            @endif
                                                            @if($data->month)
                                                            <dt>Month</dt>
                                                            <dd>{!! $months[$data->month] !!}</dd>
                                                            @endif
                                                            @if($data->amount)
                                                            <dt>Amount</dt>
                                                            <dd>{!! $data->amount !!} &#8377;</dd>
                                                            @endif
                                                        </dl>
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                                <!-- /.card -->
                                                <blockquote class="quote-secondary">
                                                    <p>@if($data->createdBy->name) {!! $data->createdBy->name !!} @endif</p>
                                                    <p>@if($data->createdBy->email) {!! $data->createdBy->email !!} @endif</p>
                                                    <small> @if($data->updated_at) {!! time_since($data->updated_at) !!} ago @endif</small>
                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <nav class="w-100">
                                            <div class="nav nav-tabs" id="maintenance-tab" role="tablist">
                                                @if($data->society->name)
                                                <a class="nav-item nav-link active" id="maintenance-society-tab" data-toggle="tab" href="#maintenance-society" role="tab" aria-controls="maintenance-society" aria-selected="true">Society</a>
                                                @endif
                                                @if($data->block->name)
                                                <a class="nav-item nav-link" id="maintenance-block-tab" data-toggle="tab" href="#maintenance-block" role="tab" aria-controls="maintenance-block" aria-selected="false">Block</a>
                                                @endif
                                                @if($data->plot->name)
                                                <a class="nav-item nav-link" id="maintenance-plot-tab" data-toggle="tab" href="#maintenance-plot" role="tab" aria-controls="maintenance-plot" aria-selected="false">plot</a>
                                                @endif
                                                @if($data->flat->name)
                                                <a class="nav-item nav-link" id="maintenance-flat-tab" data-toggle="tab" href="#maintenance-flat" role="tab" aria-controls="maintenance-flat" aria-selected="false">flat</a>
                                                @endif
                                            </div>
                                        </nav>
                                        <div class="tab-content p-3" id="nav-tabContent">
                                            @if($data->society->name)
                                            <div class="tab-pane fade show active text-justify text-capitalize" id="maintenance-society" role="tabpanel" aria-labelledby="maintenance-society-tab">
                                                <dl>
                                                    <dt>Code</dt>
                                                    <dd>{!! $data->society->unique_code !!}</dd>
                                                    <dt>Name</dt>
                                                    <dd>{!! $data->society->name !!}</dd>
                                                    <dt>Address</dt>
                                                    <dd>{!! $data->society->address !!}</dd>
                                                    <dt>Contact</dt>
                                                    <dd>{!! $data->society->contact !!}</dd>
                                                    <dt>Description</dt>
                                                    <dd>{!! $data->society->description !!}</dd>
                                                    <dt>City</dt>
                                                    <dd>{!! $data->society->associatedCity->name !!}</dd>
                                                    <dt>State</dt>
                                                    <dd>{!! $data->society->associatedState->state_title !!}</dd>
                                                    <dt>Country</dt>
                                                    <dd>{!! $data->society->associatedCountry->name !!}</dd>
                                                    <dt>Postcode</dt>
                                                    <dd>{!! $data->society->postcode !!}</dd>
                                                </dl>
                                            </div>
                                            @endif

                                            @if($data->block->name)
                                            <div class="tab-pane fade text-justify text-capitalize" id="maintenance-block" role="tabpanel" aria-labelledby="maintenance-block-tab">
                                                <dl>
                                                    <dt>Code</dt>
                                                    <dd>{!! $data->block->unique_code !!}</dd>
                                                    <dt>Name</dt>
                                                    <dd>{!! $data->block->name !!}</dd>
                                                    <dt>Total Flats</dt>
                                                    <dd>{!! $data->block->total_flats !!}</dd>
                                                    <dt>Description</dt>
                                                    <dd>{!! $data->block->description !!}</dd>
                                                </dl>
                                            </div>
                                            @endif

                                            @if($data->plot->name)
                                            <div class="tab-pane fade text-justify text-capitalize" id="maintenance-plot" role="tabpanel" aria-labelledby="maintenance-plot-tab">
                                                <dl>
                                                    <dt>Code</dt>
                                                    <dd>{!! $data->plot->unique_code !!}</dd>
                                                    <dt>Name</dt>
                                                    <dd>{!! $data->plot->name !!}</dd>
                                                    <dt>Total Floors</dt>
                                                    <dd>{!! $data->plot->total_floors !!}</dd>
                                                    <dt>Total Flats</dt>
                                                    <dd>{!! $data->plot->total_flats !!}</dd>
                                                    <dt>Description</dt>
                                                    <dd>{!! $data->plot->description !!}</dd>
                                                </dl>
                                            </div>
                                            @endif

                                            @if($data->flat->name)
                                            <div class="tab-pane fade text-justify text-capitalize" id="maintenance-flat" role="tabpanel" aria-labelledby="maintenance-flat-tab">
                                                <dl>
                                                    @if($data->flat->unique_code)
                                                    <dt>Code</dt>
                                                    <dd>{!! $data->flat->unique_code !!}</dd>
                                                    @endif
                                                    @if($data->flat->name)
                                                    <dt>Owner</dt>
                                                    <dd>{!! $data->flat->name !!}</dd>
                                                    @endif
                                                    @if($data->flat->flat_no)
                                                    <dt>Flat No</dt>
                                                    <dd>{!! $data->flat->flat_no !!}</dd>
                                                    @endif
                                                    @if($data->flat->mobile_no)
                                                    <dt>Mobile No</dt>
                                                    <dd>{!! $data->flat->mobile_no !!}</dd>
                                                    @endif
                                                    @if($data->flat->property_type)
                                                    <dt>Property Type</dt>
                                                    <dd>{!! $propertyTypes[$data->flat->property_type] !!}</dd>
                                                    @endif
                                                    @if($data->flat->tenant_name)
                                                    <dt>Tenant Name</dt>
                                                    <dd>{!! $data->flat->tenant_name !!}</dd>
                                                    @endif
                                                    @if($data->flat->tenant_contact)
                                                    <dt>Tenant Contact</dt>
                                                    <dd>{!! $data->flat->tenant_contact !!}</dd>
                                                    @endif
                                                </dl>
                                            </div>
                                            @endif
                                        </div>
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