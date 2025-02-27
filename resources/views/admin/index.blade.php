@extends('admin.layout.index')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pengunjung</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPengunjung }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</h3>
                        <p class="text-3xl font-bold text-gray-800 mt-2">Rp
                            {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Recent Orders</h2>
                <a href="{{ route('admin.financial.invoices') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                    View All
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                @if(count($recentOrders) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Passengers</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-blue-100 rounded-full text-blue-600">
                                                <span class="font-medium">{{ substr($order->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $order->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->city }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $order->count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp {{ number_format($order->price * $order->count, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($order->date)->format('d M Y') }}
                                        <span
                                            class="block text-xs text-gray-400">{{ \Carbon\Carbon::parse($order->date)->diffForHumans() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p>No recent orders found.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- City Distribution Pie Chart -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Reservations by City</h2>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row">
                    <div class="w-full md:w-2/3" id="cityPieChartContainer" style="height: 400px;"></div>
                    <div class="w-full md:w-1/3 mt-4 md:mt-0">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Top Cities</h3>
                            <div id="topCitiesList" class="space-y-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fetch city distribution data
            fetch('{{ route("admin.api.city-distribution") }}')
                .then(response => response.json())
                .then(data => {
                    renderCityPieChart(data);
                    renderTopCitiesList(data);
                })
                .catch(error => {
                    console.error('Error fetching city distribution data:', error);
                    document.getElementById('cityPieChartContainer').innerHTML =
                        '<div class="flex items-center justify-center h-full">' +
                        '<p class="text-gray-500">Failed to load city distribution data.</p>' +
                        '</div>';
                });
        });

        function renderCityPieChart(data) {
            const ctx = document.createElement('canvas');
            ctx.id = 'cityPieChart';
            document.getElementById('cityPieChartContainer').appendChild(ctx);

            // Generate colors
            const colors = generateColors(data.length);

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.map(item => item.city),
                    datasets: [{
                        data: data.map(item => item.count),
                        backgroundColor: colors,
                        borderColor: colors.map(color => color.replace('0.7', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} people (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function renderTopCitiesList(data) {
            // Sort data by count in descending order
            const sortedData = [...data].sort((a, b) => b.count - a.count);
            const topCities = sortedData.slice(0, 5); // Get top 5 cities
            const total = data.reduce((acc, item) => acc + item.count, 0);

            const listContainer = document.getElementById('topCitiesList');

            topCities.forEach((item, index) => {
                const percentage = Math.round((item.count / total) * 100);

                const cityItem = document.createElement('div');
                cityItem.classList.add('flex', 'items-center', 'justify-between');
                cityItem.innerHTML = `
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-5 h-5 mr-2 text-xs font-medium rounded-full bg-gray-200 text-gray-700">${index + 1}</span>
                        <span class="text-sm font-medium text-gray-700">${item.city}</span>
                    </div>
                    <div class="text-sm text-gray-500">${item.count} (${percentage}%)</div>
                `;

                listContainer.appendChild(cityItem);
            });
        }

        function generateColors(count) {
            const baseColors = [
                'rgba(54, 162, 235, 0.7)',   // blue
                'rgba(75, 192, 192, 0.7)',   // green
                'rgba(255, 206, 86, 0.7)',   // yellow
                'rgba(153, 102, 255, 0.7)',  // purple
                'rgba(255, 99, 132, 0.7)',   // red
                'rgba(255, 159, 64, 0.7)',   // orange
                'rgba(199, 199, 199, 0.7)',  // gray
            ];

            let colors = [];
            for (let i = 0; i < count; i++) {
                colors.push(baseColors[i % baseColors.length]);
            }

            return colors;
        }
    </script>
@endpush