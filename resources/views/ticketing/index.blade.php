@extends('ticketing.layout.index')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <h1 class="text-lg font-medium mb-4">Plotting Jeep</h1>

    <!-- Plotting -->
     <!-- perlu data reservasi dan sesi -->
    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <!-- tanggal di loop untuk semua tanggal -->
        <h2 class="text-lg font-medium mb-4">Tanggal</h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between py-3 border-b last:border-0">
                <h3 class="font-medium">Sesi</h3>
            </div>
            @foreach(range(1, 5) as $i)
                <div class="flex items-center justify-between py-3 border-b last:border-0">
                    <div class="flex items-center space-x-4">
                        <div>
                            <p class="font-medium">Pelanggan {{ $i }} - Kota</p>
                            <p class="text-sm text-gray-500">Jumlah: 2</p>
                            <p class="text-sm text-gray-500">QRIS (status pembayaran)</p>
                        </div>
                    </div>
                    <span>
                        <button>Plot</button>
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection