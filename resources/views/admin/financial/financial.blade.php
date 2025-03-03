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

        <!-- Income Line Graph -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                <h3 class="text-lg font-semibold">Grafik Pendapatan</h3>
                <div class="flex flex-wrap gap-2 mt-2 md:mt-0">
                    <select id="yearFilter" class="px-3 py-1 text-sm rounded border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="all">Tanpa Tahun</optio>
                <!-- Tahun-tahun -->
                    </select>
                    <button id="filter3Months" class="px-3 py-1 text-sm rounded bg-blue-500 text-white hover:bg-blue-600 active-filter">3 Bulan</button>
                    <button id="filter6Months" class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700 hover:bg-gray-300">6 Bulan</button>
                    <button id="filter12Months" class="px-3 py-1 text-sm rounded bg-gray-200 text-gray-700 hover:bg-gray-300">12 Bulan</button>
                </div>
            </div>

            <div class="h-80">
                <canvas id="incomeLineChart"></canvas>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Pembayaran</th>
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
                                        {{ $reservation->payment_status == 'paid' ? 'Sudah Dibayar' : 'Menunggu Pembayaran' }}
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthSelector = document.getElementById('monthSelector');
        const yearSelector = document.getElementById('yearSelector');
        const monthlyRevenueDisplay = document.getElementById('monthlyRevenueDisplay');
        const yearFilter = document.getElementById('yearFilter');

        let incomeLineChart;
        const ctx = document.getElementById('incomeLineChart').getContext('2d');

        const filter3Months = document.getElementById('filter3Months');
        const filter6Months = document.getElementById('filter6Months');
        const filter12Months = document.getElementById('filter12Months');

        let currentFilter = 3;
        let selectedYear = 'all';

        function updateActiveFilter(activeButton) {
            [filter3Months, filter6Months, filter12Months].forEach(btn => {
                btn.classList.remove('bg-blue-500', 'text-white', 'active-filter');
                btn.classList.add('bg-gray-200', 'text-gray-700');
            });
            
            activeButton.classList.remove('bg-gray-200', 'text-gray-700');
            activeButton.classList.add('bg-blue-500', 'text-white', 'active-filter');
        }

        filter3Months.addEventListener('click', function() {
            currentFilter = 3;
            updateActiveFilter(this);
            fetchIncomeData(3, selectedYear);
        });
        
        filter6Months.addEventListener('click', function() {
            currentFilter = 6;
            updateActiveFilter(this);
            fetchIncomeData(6, selectedYear);
        });
        
        filter12Months.addEventListener('click', function() {
            currentFilter = 12;
            updateActiveFilter(this);
            fetchIncomeData(12, selectedYear);
        });
        
        fetch('{{ route('admin.api.available-years') }}')
            .then(response => response.json())
            .then(data => {
                const years = data.years;
                
                years.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    yearFilter.appendChild(option);
                });
                
                years.forEach(year => {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    yearSelector.appendChild(option);
                });
                
                const currentYear = new Date().getFullYear().toString();
                if (years.includes(currentYear)) {
                    yearSelector.value = currentYear;
                } else if (years.length > 0) {
                    yearSelector.value = years[0];
                }
                
                fetchMonthlyRevenue();
                fetchIncomeData(currentFilter, selectedYear);
            })
            .catch(error => {
                console.error('Error fetching available years:', error);
                const currentYear = new Date().getFullYear().toString();
                
                const option = document.createElement('option');
                option.value = currentYear;
                option.textContent = currentYear;
                yearFilter.appendChild(option);
                
                const yearOption = document.createElement('option');
                yearOption.value = currentYear;
                yearOption.textContent = currentYear;
                yearSelector.appendChild(yearOption);
            });
            
        yearFilter.addEventListener('change', function() {
            selectedYear = this.value;
            
            const monthFilters = [filter3Months, filter6Months, filter12Months];
            
            if (selectedYear === 'all') {
                monthFilters.forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                });
                updateActiveFilter(document.querySelector('.active-filter') || filter3Months);
            } else {
                monthFilters.forEach(btn => {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                    btn.classList.remove('bg-blue-500', 'text-white', 'active-filter');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                });
            }
            
            fetchIncomeData(currentFilter, selectedYear);
        });
        
        function fetchIncomeData(months, year = 'all') {
            if (incomeLineChart) {
                incomeLineChart.destroy();
            }

            const loadingText = document.createElement('div');
            loadingText.className = 'text-center text-gray-500 mt-8';
            loadingText.textContent = 'Memuat data...';
            document.getElementById('incomeLineChart').parentNode.appendChild(loadingText);

            fetch(`{{ route('admin.api.income-data') }}?months=${months}&year=${year}`)
                .then(response => response.json())
                .then(data => {
                    if (loadingText.parentNode) {
                        loadingText.parentNode.removeChild(loadingText);
                    }

                    createIncomeChart(data);
                })
                .catch(error => {
                    console.error('Error fetching income data:', error);
                    if (loadingText.parentNode) {
                        loadingText.textContent = 'Gagal memuat data';
                    }
                });
        }
        
        function createIncomeChart(data) {
            const labels = data.map(item => item.month);
            const values = data.map(item => item.revenue);
            
            let chartTitle = '';
            if (selectedYear !== 'all') {
                chartTitle = `Pendapatan Tahun ${selectedYear}`;
            } else {
                if (currentFilter === 3) {
                    chartTitle = 'Pendapatan 3 Bulan Terakhir';
                } else if (currentFilter === 6) {
                    chartTitle = 'Pendapatan 6 Bulan Terakhir';
                } else if (currentFilter === 12) {
                    chartTitle = 'Pendapatan 12 Bulan Terakhir';
                }
            }
            
            const config = {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: values,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 1,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'K';
                                },
                                stepSize: 100000
                            },
                            title: {
                                display: true,
                                text: 'Jumlah (Rupiah)'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Bulan'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#000',
                            bodyColor: '#000',
                            borderColor: 'rgba(59, 130, 246, 0.5)',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y;
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: chartTitle,
                            font: {
                                size: 16
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            };
            incomeLineChart = new Chart(ctx, config);
        }
        
        fetchIncomeData(currentFilter, selectedYear);
        
        const currentDate = new Date();
        monthSelector.value = currentDate.getMonth() + 1;
        
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
                    
                    const currentDate = new Date();
                    const currentMonth = currentDate.getMonth() + 1;
                    const currentYear = currentDate.getFullYear().toString();
                    
                    if (month == currentMonth && year == currentYear && incomeLineChart) {
                        fetchIncomeData(currentFilter, selectedYear);
                    }
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