@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Financial Report</h1>
        <div class="flex gap-4">
            <button onclick="printReport()" 
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Print Report
            </button>
            <button onclick="exportReport()" 
                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                Export Report
            </button>
        </div>
    </div>

    {{-- Date Range Selector --}}
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form action="{{ route('admin.financial.report') }}" method="GET" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="start_date" 
                    value="{{ request('start_date') }}" 
                    class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="end_date" 
                    value="{{ request('end_date') }}"
                    class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" 
                class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                Generate Report
            </button>
        </form>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
            <p class="text-2xl font-bold">₱{{ number_format($report['revenue'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500">Total Bookings</h3>
            <p class="text-2xl font-bold">{{ $report['bookings'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500">Active Jeeps</h3>
            <p class="text-2xl font-bold">{{ $report['active_jeeps'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500">Average Daily Revenue</h3>
            <p class="text-2xl font-bold">
                ₱{{ number_format($report['daily_stats']->avg('daily_total'), 2) }}
            </p>
        </div>
    </div>

    {{-- Daily Stats Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <h2 class="text-lg font-semibold p-6 bg-gray-50 border-b">Daily Statistics</h2>
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Revenue
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Number of Invoices
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Average Invoice Value
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($report['daily_stats'] as $stat)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($stat->date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ₱{{ number_format($stat->daily_total, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $stat->invoice_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ₱{{ number_format($stat->daily_total / $stat->invoice_count, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function printReport() {
    window.print();
}

function exportReport() {
    alert('Export functionality will be implemented');
}
</script>
@endpush