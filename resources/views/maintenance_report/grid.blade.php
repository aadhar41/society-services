@extends('layouts.app')

@section('content')

@include('partials._select2Assests')

<section class="content mt-2">
    <div class="container-fluid">
        <x-content-header :title="$title" :module="$module" />

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Filter Records</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.maintenance-report.grid') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Year</label>
                                <select name="year" class="form-control select2">
                                    @for($y = date('Y')-2; $y <= date('Y')+1; $y++)
                                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Society</label>
                                <select name="society_id" id="society_id" class="form-control select2">
                                    <option value="">Select Society</option>
                                    @foreach($societies as $id => $name)
                                        <option value="{{ $id }}" {{ $selectedSociety == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Block</label>
                                <select name="block_id" id="block_id" class="form-control select2">
                                    <option value="">Select Block</option>
                                    @foreach($blocks as $id => $name)
                                        <option value="{{ $id }}" {{ $selectedBlock == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.maintenance-report.grid') }}" class="btn btn-default">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Maintenance Record - {{ $selectedYear }}</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>PLOT No.</th>
                                <th>S.No.</th>
                                <th>NAME</th>
                                <th>Mo. No.</th>
                                <th>FLAT No.</th>
                                <th>RENTED NAME</th>
                                <th>RENTED Mo.No.</th>
                                @foreach($months as $num => $name)
                                    <th>{{ substr($name, 0, 3) }}-{{ substr($selectedYear, -2) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($flats as $index => $flat)
                                <tr>
                                    <td>{{ $flat->plot->name ?? 'N/A' }}</td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ strtoupper($flat->name) }}</td>
                                    <td>{{ $flat->mobile_no }}</td>
                                    <td>{{ $flat->flat_no }}</td>
                                    <td>{{ $flat->tenant_name }}</td>
                                    <td>{{ $flat->tenant_contact }}</td>
                                    @foreach($months as $num => $monthName)
                                        @php
                                            $record = $maintenances->get($flat->id)?->where('month', $monthName)->first();
                                        @endphp
                                        <td>
                                            @if($record)
                                                <b class="text-success">{{ number_format($record->amount, 0) }}</b>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 7 + count($months) }}">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2();

    $('#society_id').on('change', function() {
        var society_id = $(this).val();
        if (society_id) {
            $.ajax({
                url: "{{ route('getSocietyBlocks') }}",
                type: "POST",
                data: {
                    society_id: society_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(data) {
                    $('#block_id').empty().append('<option value="">Select Block</option>');
                    $.each(data, function(key, value) {
                        $('#block_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        }
    });
});
</script>
@endpush
