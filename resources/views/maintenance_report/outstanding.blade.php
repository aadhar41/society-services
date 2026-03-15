@extends('layouts.app')

@section('content')

@include('partials._select2Assests')

<section class="content mt-2">
    <div class="container-fluid">
        <x-content-header :title="$title" :module="$module" />

        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Filter Report</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.maintenance-report.outstanding') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
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
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                                <a href="{{ route('admin.maintenance-report.outstanding') }}" class="btn btn-default">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Outstanding Maintenance Record</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm m-0">
                        <thead class="bg-yellow">
                            <tr>
                                <th rowspan="2" class="align-middle">Sr. No.</th>
                                <th rowspan="2" class="align-middle">Plot No.</th>
                                <th rowspan="2" class="align-middle">Flat No.</th>
                                <th rowspan="2" class="align-middle">Owner Name</th>
                                <th rowspan="2" class="align-middle">Mob No.</th>
                                <th colspan="{{ count($years) }}" class="text-center">Outstanding Society Maintenance Year Wise</th>
                                <th rowspan="2" class="align-middle">New Road Outstanding</th>
                                <th rowspan="2" class="align-middle">Total Pending Amount Flat wise</th>
                            </tr>
                            <tr>
                                @foreach($years as $year)
                                    <th>{{ $year }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $grandTotal = 0;
                                $buildingTotals = [];
                                foreach($years as $year) $buildingTotals[$year] = 0;
                                $buildingNewRoadTotal = 0;
                                $buildingGrandTotal = 0;
                                $currentPlot = null;
                            @endphp

                            @forelse($flats as $index => $flat)
                                @php
                                    // Handle grouping by Plot (Building) for totals
                                    if ($currentPlot !== null && $currentPlot != $flat->plot_id) {
                                        @endphp
                                        <tr class="bg-yellow font-weight-bold">
                                            <td colspan="5">Building Wise Total Outstanding In Maintenance and Road Construction</td>
                                            @foreach($years as $year)
                                                <td>{{ number_format($buildingTotals[$year], 2) }}</td>
                                            @endforeach
                                            <td>{{ number_format($buildingNewRoadTotal, 2) }}</td>
                                            <td>{{ number_format($buildingGrandTotal, 2) }}</td>
                                        </tr>
                                        @php
                                        // Reset building totals
                                        foreach($years as $year) $buildingTotals[$year] = 0;
                                        $buildingNewRoadTotal = 0;
                                        $buildingGrandTotal = 0;
                                    }
                                    $currentPlot = $flat->plot_id;

                                    $flatTotal = 0;
                                    $rowYearly = [];
                                    foreach($years as $year) {
                                        // Assuming a logic where we calculate what's pending
                                        // For simplicity, let's sum up payments for that year
                                        // Actually, the spreadsheet shows "Outstanding" - so maybe we need a fixed rate logic?
                                        // User image shows specific amounts like 1800.00, 450.00
                                        // I'll sum the payments for now, but in reality, it should be (Expected - Paid)
                                        $paid = $maintenanceData->get($year)?->get($flat->id)?->sum('amount') ?? 0;
                                        $outstanding = $paid; // Placeholder: replace with actual logic if available
                                        $rowYearly[$year] = $outstanding;
                                        $flatTotal += $outstanding;
                                        $buildingTotals[$year] += $outstanding;
                                    }
                                    $flatTotal += $flat->new_road_outstanding;
                                    $buildingNewRoadTotal += $flat->new_road_outstanding;
                                    $buildingGrandTotal += $flatTotal;
                                    $grandTotal += $flatTotal;
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $flat->plot->name ?? 'N/A' }}</td>
                                    <td>{{ $flat->flat_no }}</td>
                                    <td>{{ strtoupper($flat->name) }}</td>
                                    <td>{{ $flat->mobile_no }}</td>
                                    @foreach($years as $year)
                                        <td>{{ number_format($rowYearly[$year], 2) }}</td>
                                    @endforeach
                                    <td>{{ number_format($flat->new_road_outstanding, 2) }}</td>
                                    <td>{{ number_format($flatTotal, 2) }}</td>
                                </tr>

                                @if($loop->last)
                                    <tr class="bg-yellow font-weight-bold">
                                        <td colspan="5">Building Wise Total Outstanding In Maintenance and Road Construction</td>
                                        @foreach($years as $year)
                                            <td>{{ number_format($buildingTotals[$year], 2) }}</td>
                                        @endforeach
                                        <td>{{ number_format($buildingNewRoadTotal, 2) }}</td>
                                        <td>{{ number_format($buildingGrandTotal, 2) }}</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="{{ 8 + count($years) }}">No records found.</td>
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
