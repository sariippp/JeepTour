@extends('admin.layout.index')

@section('title', 'Keuangan')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold">Dasbor Keuangan</h1>
            <div class="w-full md:w-auto">
                <a href="{{ route('admin.financial.invoices') }}"
                    class="block w-full md:w-auto text-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition duration-200">
                    Lihat Semua Pesanan
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Pendapatan Hari Ini</h3>
                <p class="text-3xl font-bold">Rp. {{ number_format($stats['today_revenue'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Pendapatan Bulanan</h3>
                <div class="mb-2">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <div class="relative w-24">
                            <select id="monthSelector" class="w-full appearance-none bg-white border border-gray-300 hover:border-gray-400 px-2 py-1 pr-6 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1 text-gray-700">
                                <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                        <div class="relative w-20">
                            <select id="yearSelector" class="w-full appearance-none bg-white border border-gray-300 hover:border-gray-400 px-2 py-1 pr-6 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">

                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1 text-gray-700">
                                <svg class="fill-current h-3 w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-3xl font-bold" id="monthlyRevenueDisplay">Rp. {{ number_format($stats['month_revenue'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Total Pendapatan</h3>
                <p class="text-3xl font-bold">Rp. {{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Rata-rata Nilai Pemesanan</h3>
                <p class="text-3xl font-bold">Rp. {{ number_format($stats['average_booking_value'], 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Pesanan Terbaru</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pesanan #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Penumpang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembayaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
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
                                    <span
                                        class="px-2 py-1 text-sm rounded-full 
                                        {{ $reservation->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $reservation->payment_status == 'paid' ? 'Dibayar' : 'Tertunda' }}
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthSelector = document.getElementById('monthSelector');
        const yearSelector = document.getElementById('yearSelector');
        const monthlyRevenueDisplay = document.getElementById('monthlyRevenueDisplay');
        
        const currentDate = new Date();
        monthSelector.value = currentDate.getMonth() + 1;
        
        fetch('{{ route('admin.api.available-years') }}')
            .then(response => response.json())
            .then(data => {
                const years = data.years;
                years.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    yearSelector.appendChild(option);
                });
                
                const currentYear = currentDate.getFullYear().toString();
                if (years.includes(currentYear)) {
                    yearSelector.value = currentYear;
                } else if (years.length > 0) {
                    yearSelector.value = years[0];
                }
                
                fetchMonthlyRevenue();
            })
            .catch(error => {
                console.error('Error fetching available years:', error);
                const option = document.createElement('option');
                option.value = currentDate.getFullYear();
                option.textContent = currentDate.getFullYear();
                yearSelector.appendChild(option);
            });
        
        function formatCurrency(amount) {
            return 'Rp. ' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
        
        function fetchMonthlyRevenue() {
            const month = monthSelector.value;
            const year = yearSelector.value;

            monthlyRevenueDisplay.textContent = 'Memuat...';

            fetch(`{{ route('admin.api.monthly-revenue') }}?month=${month}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    monthlyRevenueDisplay.textContent = formatCurrency(data.revenue);
                })
                .catch(error => {
                    console.error('Error fetching monthly revenue:', error);
                    monthlyRevenueDisplay.textContent = 'Gagal memuat data';
                });
        }
        
        monthSelector.addEventListener('change', fetchMonthlyRevenue);
        yearSelector.addEventListener('change', fetchMonthlyRevenue);
    });
</script>
@endpush