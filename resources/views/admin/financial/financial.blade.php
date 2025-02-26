@extends('admin.layout.index')

@section('title', 'Financial')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Financial Dashboard</h1>
        <div>
            {{-- report masih belum bisa hiksss --}}
            {{-- <button class="bg-blue-500 text-white px-4 py-2 rounded mr-2" onclick="generateReport()">
                Generate Report
            </button> --}}
            <a href="{{ route('admin.financial.invoices') }}" class="bg-blue-500 text-white px-4 py-2 rounded mr-2">
                View All Orders
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Today's Revenue</h3>
            <p class="text-3xl font-bold">Rp. {{ number_format($stats['today_revenue'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Monthly Revenue</h3>
            <p class="text-3xl font-bold">Rp. {{ number_format($stats['month_revenue'], 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Pending Payments</h3>
            <p class="text-3xl font-bold">{{ $stats['pending_payments'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-2">Average Booking Value</h3>
            <p class="text-3xl font-bold">Rp. {{ number_format($stats['average_booking_value'], 2) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Passengers</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($recentReservations as $reservation)
                    <tr>
                        <td class="px-6 py-4">#{{ $reservation->id }}</td>
                        <td class="px-6 py-4">{{ $reservation->name }}</td>
                        <td class="px-6 py-4">{{ $reservation->count }}</td>
                        <td class="px-6 py-4">Rp. {{ number_format($reservation->price * $reservation->count, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-sm rounded-full 
                                {{ $reservation->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($reservation->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $reservation->created_at->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection