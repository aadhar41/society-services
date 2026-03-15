@extends('layouts.app')

@section('content')

@include('partials._select2Assests')

<form action="{{ route('admin.maintenance.update', $listings->id) }}" method="POST" enctype="multipart/form-data">
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
                            <x-card-title route="{{ route('admin.maintenance.list') }}" type="primary" title="Record Lists" />
                            <x-card-tools route="{{ route('admin.maintenance.list') }}" type="primary" title="" />
                        </div>
                        <!-- /.card-header -->

                        <div class="card-body">
                            <div class="row">

                                <div class="col-lg-3 col-md-3">
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

                                <div class="col-lg-3 col-md-3">
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

                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="plot">Plot :</label>
                                        <select name="plot" id="plot" class="form-control select2 {{ $errors->has('plot') ? 'is-invalid' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($plots as $id => $name)
                                            <option value="{{ $id }}" {{ (old("plot", $listings->plot_id) == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('plot'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('plot') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="flat">Flat :</label>
                                        <select name="flat" id="flat" class="form-control select2 {{ $errors->has('flat') ? 'is-invalid' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($flats as $id => $name)
                                            <option value="{{ $id }}" {{ (old("flat", $listings->flat_id) == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('flat'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('flat') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="type">Maintenance Type :</label>
                                        <select name="type" id="type" class="form-control select2 {{ $errors->has('type') ? 'is-invalid' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($maintenanceTypes as $id => $name)
                                            <option value="{{ $id }}" {{ (old("type", $listings->type) == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('type'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('type') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="date">Date :</label>
                                        <input type="date" name="date" value="{{ old('date', $listings->date) }}" id="date" class="form-control {{ $errors->has('date') ? 'is-invalid' : '' }}" placeholder="Date" autocomplete="off" />
                                        @if($errors->has('date'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('date') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2">
                                    <div class="form-group">
                                        <label for="year">Year :</label>
                                        <input type="number" name="year" value="{{ old('year', $listings->year) }}" id="year" class="form-control {{ $errors->has('year') ? 'is-invalid' : '' }}" placeholder="Year" autocomplete="off" />
                                        @if($errors->has('year'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('year') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2">
                                    <div class="form-group">
                                        <label for="month">Month :</label>
                                        <select name="month" id="month" class="form-control select2 {{ $errors->has('month') ? 'is-invalid' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($months as $id => $name)
                                            <option value="{{ $id }}" {{ (old("month", $listings->month) == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('month'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('month') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-2 col-md-2">
                                    <div class="form-group">
                                        <label for="amount">Amount :</label>
                                        <input type="number" name="amount" value="{{ old('amount', $listings->amount) }}" id="amount" class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" placeholder="Amount" autocomplete="off" />
                                        @if($errors->has('amount'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="payment_status">Payment Status :</label>
                                        <select name="payment_status" id="payment_status" class="form-control select2 {{ $errors->has('payment_status') ? 'is-invalid' : '' }}">
                                            <option value="">Select</option>
                                            @foreach($paymentStatus as $id => $name)
                                            <option value="{{ $id }}" {{ (old("payment_status", $listings->payment_status) == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('payment_status'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('payment_status') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="payment_mode">Payment Mode :</label>
                                        <select name="payment_mode" id="payment_mode" class="form-control select2 {{ $errors->has('payment_mode') ? 'is-invalid' : '' }}">
                                            <option value="">Select Mode</option>
                                            <option value="Cash" {{ old('payment_mode', $listings->payment_mode) == 'Cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="Cheque" {{ old('payment_mode', $listings->payment_mode) == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                            <option value="Online" {{ old('payment_mode', $listings->payment_mode) == 'Online' ? 'selected' : '' }}>Online</option>
                                        </select>
                                        @if($errors->has('payment_mode'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('payment_mode') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="transaction_id">Transaction Id :</label>
                                        <input type="text" name="transaction_id" value="{{ old('transaction_id', $listings->transaction_id) }}" id="transaction_id" class="form-control {{ $errors->has('transaction_id') ? 'is-invalid' : '' }}" placeholder="Transaction Id" autocomplete="off" />
                                        @if($errors->has('transaction_id'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('transaction_id') }}</strong>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="attachments">Attachments :</label>
                                        <input type="file" name="attachments" id="attachments" class="form-control {{ $errors->has('attachments') ? 'is-invalid' : '' }}" />
                                        @if($listings->attachments)
                                            <p><a href="{{ asset($listings->attachments) }}" target="_blank">View Current Attachment</a></p>
                                        @endif
                                        @if($errors->has('attachments'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('attachments') }}</strong>
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