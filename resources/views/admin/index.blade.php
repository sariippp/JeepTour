@extends('admin.layout.index')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">Total Users</h3>
            <div class="mt-2 flex items-baseline justify-between">
                <p class="text-2xl font-semibold">12,361</p>
                <span class="text-sm font-medium text-green-600">+14%</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">Revenue</h3>
            <div class="mt-2 flex items-baseline justify-between">
                <p class="text-2xl font-semibold">$34,545</p>
                <span class="text-sm font-medium text-green-600">+7.5%</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">Active Sessions</h3>
            <div class="mt-2 flex items-baseline justify-between">
                <p class="text-2xl font-semibold">1,245</p>
                <span class="text-sm font-medium text-green-600">+22%</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg border border-gray-200">
            <h3 class="text-sm font-medium text-gray-500">Conversion Rate</h3>
            <div class="mt-2 flex items-baseline justify-between">
                <p class="text-2xl font-semibold">3.42%</p>
                <span class="text-sm font-medium text-green-600">+4.3%</span>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white p-6 rounded-lg border border-gray-200">
        <h2 class="text-lg font-medium mb-4">Recent Activity</h2>
        <div class="space-y-4">
            @foreach(range(1, 5) as $i)
            <div class="flex items-center justify-between py-3 border-b last:border-0">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-full bg-gray-100"></div>
                    <div>
                        <p class="font-medium">User Action #{{ $i }}</p>
                        <p class="text-sm text-gray-500">Description of the action taken</p>
                    </div>
                </div>
                <span class="text-sm text-gray-500">2 hours ago</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection