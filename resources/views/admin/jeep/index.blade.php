@extends('admin.layout.index')

@section('title', 'Jeep')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Jeep</h1>
                <p class="text-gray-600 mt-1">Kelola pemilik dan kendaraan jeep</p>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors shadow-sm" onclick="showOwnerModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Pemilik
            </button>
        </div>

        <!-- Owner Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($ownerData as $owner)
                @php
                    $weeklySalaryInfo = null;
                    $weeklyPassengers = 0;
                    $weeklySalary = 0;

                    if (isset($weeklySalaryData['driver_salary'][$owner->id])) {
                        $weeklySalaryInfo = $weeklySalaryData['driver_salary'][$owner->id];
                        $weeklyPassengers = $weeklySalaryInfo['total_passengers'];
                        $weeklySalary = $weeklySalaryInfo['total_salary'];
                    }
                @endphp

                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow border-l-4 border-blue-500">
                    <div class="p-6">
                        <!-- Owner Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $owner->name }}</h3>
                            <div class="flex space-x-2">
                                <button class="p-2 text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" 
                                        onclick="showOwnerModal({{ $owner->id }}, '{{ $owner->name }}')" 
                                        title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                        onclick="deleteOwner({{ $owner->id }})" 
                                        title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $owner->total_jeeps }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Jeep</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ number_format($owner->total_passengers) }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Total</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ number_format($owner->monthly_passengers) }}</div>
                                <div class="text-xs text-gray-500 uppercase tracking-wider">Bulan Ini</div>
                            </div>
                        </div>

                        <!-- Weekly Salary Section -->
                        <div class="border-t pt-4 mt-4 bg-gray-50 -mx-6 px-6 pb-6 rounded-b-lg">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-gray-800">Gaji Mingguan</h4>
                                <span class="text-xs text-gray-500">
                                    {{ Carbon\Carbon::parse($weeklySalaryData['week_start'])->format('d/m') }} - 
                                    {{ Carbon\Carbon::parse($weeklySalaryData['week_end'])->format('d/m/Y') }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div class="text-center">
                                    <div class="text-xl font-bold text-blue-600">{{ number_format($weeklyPassengers, 1) }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wider">Penumpang</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl font-bold text-green-600">Rp {{ number_format($weeklySalary) }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wider">Gaji</div>
                                </div>
                            </div>

                            @if($weeklySalaryInfo && count($weeklySalaryInfo['jeeps']) > 0)
                                <button class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 py-2 px-3 rounded-lg text-sm font-medium transition-colors" 
                                        onclick="showSalaryDetails('{{ $owner->id }}')">
                                    Lihat Detail
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Jeeps Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Jeep</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ count($jeeps) }} kendaraan terdaftar</p>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors shadow-sm" onclick="showJeepModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jeep
                </button>
            </div>
            
            <div class="overflow-x-auto">
                @if(count($jeeps) > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plat Nomor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemilik</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Perjalanan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Kapasitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Penumpang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($jeeps as $jeep)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-blue-100 rounded-lg text-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $jeep->number_plate }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $jeep->owner_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ number_format($jeep->total_trips) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ number_format($jeep->total_passenger) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ number_format($jeep->total_passengers) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button class="text-orange-600 hover:text-orange-900 p-2 hover:bg-orange-50 rounded-lg transition-colors" 
                                                    onclick="showJeepModal({{ $jeep->id }}, '{{ $jeep->number_plate }}', {{ $jeep->owner_id }})"
                                                    title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-lg transition-colors" 
                                                    onclick="deleteJeep({{ $jeep->id }})"
                                                    title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <p>Belum ada jeep yang terdaftar.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('admin.partials.owner-modal')
    @include('admin.partials.jeep-modal')

    <!-- Salary Details Modal -->
    <div class="modal fade" id="salaryDetailsModal" tabindex="-1" aria-labelledby="salaryDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="salaryDetailsModalLabel">Detail Gaji</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="salaryDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const ownerModal = new bootstrap.Modal(document.getElementById('ownerModal'));
        const jeepModal = new bootstrap.Modal(document.getElementById('jeepModal'));

        function showOwnerModal(id = null, name = '') {
            document.getElementById('ownerModalTitle').textContent = id ? 'Edit Pemilik' : 'Tambah Pemilik';
            document.getElementById('ownerId').value = id || '';
            document.getElementById('ownerName').value = name;
            ownerModal.show();
        }

        function showSalaryDetails(ownerId) {
            const modal = new bootstrap.Modal(document.getElementById('salaryDetailsModal'));
            const salaryContent = document.getElementById('salaryDetailsContent');

            const ownerSalaryData = {
                @foreach($ownerData as $owner)
                    @if(isset($weeklySalaryData['driver_salary'][$owner->id]) && count($weeklySalaryData['driver_salary'][$owner->id]['jeeps']) > 0)
                        "{{ $owner->id }}": {
                            "name": "{{ $owner->name }}",
                            "jeeps": [
                                @foreach($weeklySalaryData['driver_salary'][$owner->id]['jeeps'] as $jeepId => $jeep)
                                    {
                                        "number_plate": "{{ $jeep['number_plate'] }}",
                                        "passengers": "{{ number_format($jeep['passengers'], 1) }}",
                                        "salary": "{{ number_format($jeep['salary']) }}"
                                    },
                                @endforeach
                            ]
                        },
                    @endif
                @endforeach
            };

            if (ownerSalaryData[ownerId]) {
                const data = ownerSalaryData[ownerId];
                let content = `
                    <h6>${data.name}</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Plat</th>
                                    <th>Penumpang</th>
                                    <th>Gaji</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                data.jeeps.forEach(jeep => {
                    content += `
                        <tr>
                            <td>${jeep.number_plate}</td>
                            <td>${jeep.passengers}</td>
                            <td>Rp ${jeep.salary}</td>
                        </tr>
                    `;
                });

                content += `
                            </tbody>
                        </table>
                    </div>
                `;

                salaryContent.innerHTML = content;
                modal.show();
            }
        }

        function showJeepModal(id = null, numberPlate = '', ownerId = '') {
            document.getElementById('jeepModalTitle').textContent = id ? 'Edit Jeep' : 'Tambah Jeep';
            document.getElementById('jeepId').value = id || '';
            document.getElementById('numberPlate').value = numberPlate;
            document.getElementById('jeepOwnerId').value = ownerId || document.getElementById('jeepOwnerId').options[0].value;
            jeepModal.show();
        }

        function saveOwner() {
            const id = document.getElementById('ownerId').value;
            const name = document.getElementById('ownerName').value;
            const url = id ?
                '{{ route("admin.jeeps.owners.update", ":id") }}'.replace(':id', id) :
                '{{ route("admin.jeeps.owners.store") }}';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: { name },
                success: () => window.location.reload(),
                error: (xhr) => alert(xhr.responseJSON.message || 'Error menyimpan pemilik')
            });
        }

        function saveJeep() {
            const id = document.getElementById('jeepId').value;
            const number_plate = document.getElementById('numberPlate').value;
            const total_passenger = document.getElementById('totalPassenger').value;
            const owner_id = document.getElementById('jeepOwnerId').value;
            const url = id ?
                '{{ route("admin.jeeps.vehicles.update", ":id") }}'.replace(':id', id) :
                '{{ route("admin.jeeps.vehicles.store") }}';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: { number_plate, owner_id, total_passenger },
                success: () => window.location.reload(),
                error: (xhr) => alert(xhr.responseJSON.message || 'Error menyimpan jeep')
            });
        }

        function deleteOwner(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pemilik ini?')) {
                $.ajax({
                    url: '{{ route("admin.jeeps.owners.delete", ":id") }}'.replace(':id', id),
                    method: 'DELETE',
                    success: () => window.location.reload(),
                    error: () => alert('Error menghapus pemilik')
                });
            }
        }

        function deleteJeep(id) {
            if (confirm('Apakah Anda yakin ingin menghapus jeep ini?')) {
                $.ajax({
                    url: '{{ route("admin.jeeps.vehicles.delete", ":id") }}'.replace(':id', id),
                    method: 'DELETE',
                    success: () => window.location.reload(),
                    error: () => alert('Error menghapus jeep')
                });
            }
        }
    </script>
@endpush