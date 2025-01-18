@extends('ticketing.layout.index')

@section('title', 'Invoices')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Invoices</h1>
        <div class="flex gap-4">
            <div class="relative">
                <input type="text" 
                    placeholder="Search invoices by ID..." 
                    class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    id="searchInput"
                    value="{{ request('search') }}">
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500">Total Invoices</h3>
            <p class="text-2xl font-bold">{{ $invoices->total() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500">Paid Invoices</h3>
            <p class="text-2xl font-bold text-green-600">
                {{ $invoices->whereNotNull('time_paid')->count() }}
            </p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-sm font-medium text-gray-500">Pending Invoices</h3>
            <p class="text-2xl font-bold text-yellow-600">
                {{ $invoices->whereNull('time_paid')->count() }}
            </p>
        </div>
    </div>

    {{-- Invoices Table --}}
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
function viewInvoiceDetails(invoiceId) {
    window.location.href = `/ticketing/invoices/${invoiceId}`;
}

// function exportToExcel() {
//     window.location.href = '/admin/financial/invoices/export';
// }

document.getElementById('searchInput').addEventListener('keyup', function(e) {
    if (e.key === 'Enter') {
        const searchTerm = e.target.value.trim();
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('search', searchTerm);
        window.location.search = urlParams.toString();
    }
});
</script>
@endpush
