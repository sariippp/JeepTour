@extends('admin.layout.index')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">Total Pengunjung</h3>
            <div class="mt-2 flex items-baseline justify-between">
                <p class="text-2xl font-semibold">{{ $totalPengunjung }}</p>
                {{-- <span class="text-sm font-medium text-green-600">+14%</span> --}}
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">Total Pendapatan</h3>
            <div class="mt-2 flex items-baseline justify-between">
                <p class="text-2xl font-semibold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                {{-- <span class="text-sm font-medium text-green-600">+7.5%</span> --}}
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <h2 class="text-lg font-medium mb-4">Recent Orders</h2>
        <div class="space-y-4">
            @foreach($recentOrders as $order)
            <div class="flex items-center justify-between py-3 border-b last:border-0">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-full bg-gray-100"></div>
                    <div>
                        <p class="font-medium">{{ $order->name }}</p> 
                        <p class="text-sm text-gray-500">{{ $order->city }}</p> 
                        <p class="text-sm text-gray-500">Count: {{ $order->count }}</p> 
                        <p class="text-sm text-gray-500">Price: {{ number_format($order->price, 0, ',', '.') }}</p> 
                        <p class="text-sm text-gray-500">
                            Total Price: Rp {{ number_format($order->price * $order->count, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                <span class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->date)->diffForHumans() }}</span> 
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection