@extends('ticketing.layout.index')

@section('title', 'Order Log')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Order Log</h1>
            <div class="flex gap-4">
                <div class="relative">
                    <input type="text" placeholder="Search by ID or name..."
                        class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="searchInput" value="{{ request('search') }}">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Total Orders</h3>
                <p class="text-2xl font-bold">{{ $invoices->total() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Paid Orders</h3>
                <p class="text-2xl font-bold text-green-600">
                    {{ $invoices->where('payment_status', 'paid')->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Pending Orders</h3>
                <p class="text-2xl font-bold text-yellow-600">
                    {{ $invoices->where('payment_status', '!=', 'paid')->count() }}
                </p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Total Revenue</h3>
                <p class="text-2xl font-bold text-blue-600">
                    Rp. {{ number_format($invoices->where('payment_status', 'paid')->sum(function ($reservation) {
        return $reservation->price * $reservation->passenger_count;
    }), 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @include('ticketing.invoices.table')
        </div>

        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function (e) {
            if (e.key === 'Enter') {
                const searchTerm = e.target.value.trim();
                window.location.href = '{{ route("ticketing.invoices") }}?search=' + encodeURIComponent(searchTerm);
            }
        });
    </script>
@endpush