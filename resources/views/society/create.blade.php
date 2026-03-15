@extends('layouts.app')

@section('title', config('app.name') . ' | ' . ucwords($title))

@section('content')

@include('partials._select2Assests')

<!-- Main content -->
<section class="content mt-2">
    <div class="container-fluid">

        <!-- Content Header (Page header) -->
        <x-content-header :title="$title" :module="$module" />

        <form action="{{ route('admin.society.store') }}" method="POST" enctype="multipart/form-data">
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
                                    <x-card-title route="{{ route('admin.society.list') }}" type="primary" title="Record Lists" />
                                    <x-card-tools route="{{ route('admin.society.list') }}" type="primary" title="" />
                                </div>
                                <!-- /.card-header -->

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="name"> {{__('labels.name')}} </label>
                                                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="{{__('placeholders.name')}}" autocomplete="off" />
                                                @if($errors->has('name'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="registration_no"> Registration No </label>
                                                <input type="text" name="registration_no" value="{{ old('registration_no') }}" id="registration_no" class="form-control {{ $errors->has('registration_no') ? 'is-invalid' : '' }}" placeholder="Enter Registration No" autocomplete="off" />
                                                @if($errors->has('registration_no'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('registration_no') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="contact">{{__('labels.contact')}}</label>
                                                <input type="number" minlength="10" maxlength="10" name="contact" value="{{ old('contact') }}" id="contact" class="form-control {{ $errors->has('contact') ? 'is-invalid' : '' }}" placeholder="{{__('placeholders.contact')}}" autocomplete="off" />
                                                @if($errors->has('contact'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('contact') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="contact_email"> Contact Email </label>
                                                <input type="email" name="contact_email" value="{{ old('contact_email') }}" id="contact_email" class="form-control {{ $errors->has('contact_email') ? 'is-invalid' : '' }}" placeholder="Enter Contact Email" autocomplete="off" />
                                                @if($errors->has('contact_email'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('contact_email') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="office_contact"> Office Contact </label>
                                                <input type="text" name="office_contact" value="{{ old('office_contact') }}" id="office_contact" class="form-control {{ $errors->has('office_contact') ? 'is-invalid' : '' }}" placeholder="Enter Office Contact" autocomplete="off" />
                                                @if($errors->has('office_contact'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('office_contact') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="postcode">{{__('labels.postcode')}}</label>
                                                <input type="number" name="postcode" minlength="6" maxlength="6" value="{{ old('postcode') }}" id="postcode" class="form-control {{ $errors->has('postcode') ? 'is-invalid' : '' }}" placeholder="{{__('placeholders.postcode')}}" autocomplete="off" />
                                                @if($errors->has('postcode'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('postcode') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="country">{{__('labels.country')}}</label>
                                                <select name="country" id="country" class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}">
                                                    <option value="">{{__('placeholders.country')}}</option>
                                                    @foreach($countries as $id => $name)
                                                    <option value="{{ $id }}" {{ (old("country") == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('country'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('country') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="state">{{__('labels.state')}}</label>
                                                <select name="state" id="state" class="form-control select2 {{ $errors->has('state') ? 'is-invalid' : '' }}">
                                                    <option value="">{{__('placeholders.state')}}</option>
                                                    @foreach($states as $id => $name)
                                                    <option value="{{ $id }}" {{ (old("state") == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('state'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('state') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="city">{{__('labels.city')}}</label>
                                                <select name="city" id="city" class="form-control select2 {{ $errors->has('city') ? 'is-invalid' : '' }}">
                                                    <option value="">{{__('placeholders.city')}}</option>
                                                    @foreach($cities as $id => $name)
                                                    <option value="{{ $id }}" {{ (old("city") == $id ? "selected":"") }}>{{ ucwords($name) }}</option>
                                                    @endforeach
                                                </select>
                                                @if($errors->has('city'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('city') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address">{{__('labels.address')}}</label>
                                                <textarea name="address" id="address" rows="4" class="ckeditor form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" placeholder="{{__('placeholders.address')}}">{{ old('address') }}</textarea>
                                                @if($errors->has('address'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="map_link"> Map Link (Google Maps Embed/URL) </label>
                                                <input type="text" name="map_link" value="{{ old('map_link') }}" id="map_link" class="form-control {{ $errors->has('map_link') ? 'is-invalid' : '' }}" placeholder="Enter Map Link" autocomplete="off" />
                                                @if($errors->has('map_link'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('map_link') }}</strong>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">{{__('labels.description')}}</label>
                                                <textarea name="description" id="description" rows="4" class="ckeditor form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" placeholder="{{__('placeholders.description')}}">{{ old('description') }}</textarea>
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
</script>
@include('partials._ckeditor')
@endsection