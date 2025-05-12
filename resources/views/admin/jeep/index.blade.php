@extends('admin.layout.index')

@section('title', 'Jeep')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Pemilik</h4>
                        <button class="btn btn-primary" onclick="showOwnerModal()">
                            <i class="fas fa-plus"></i> Tambah Pemilik
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            @foreach($ownerData as $owner)

                <!-- Weekly salary data for this owner -->
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

                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0">{{ $owner->name }}</h5>
                                <div>
                                    <button class="btn btn-sm btn-warning"
                                        onclick="showOwnerModal({{ $owner->id }}, '{{ $owner->name }}')">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteOwner({{ $owner->id }})">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>

                            <!-- Standard Stats -->
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="text-muted small">Jeep</div>
                                    <h4>{{ $owner->total_jeeps }}</h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted small">Total Penumpang</div>
                                    <h4>{{ number_format($owner->total_passengers) }}</h4>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted small">Bulan Ini</div>
                                    <h4>{{ number_format($owner->monthly_passengers) }}</h4>
                                </div>
                            </div>

                            <!-- Weekly Salary Section -->
                            <div class="border-top pt-3 mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">Gaji Mingguan</h6>
                                    <small
                                        class="text-muted">{{ Carbon\Carbon::parse($weeklySalaryData['week_start'])->format('d/m/Y') }}
                                        - {{ Carbon\Carbon::parse($weeklySalaryData['week_end'])->format('d/m/Y') }}</small>
                                </div>

                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="text-muted small">Penumpang</div>
                                        <h5 class="mb-0">{{ number_format($weeklyPassengers, 1) }}</h5>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">Gaji</div>
                                        <h5 class="mb-0 text-success">Rp {{ number_format($weeklySalary) }}</h5>
                                    </div>
                                </div>

                                @if($weeklySalaryInfo && count($weeklySalaryInfo['jeeps']) > 0)
                                    <div class="mt-2">
                                        @if($weeklySalaryInfo && count($weeklySalaryInfo['jeeps']) > 0)
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-info w-100"
                                                    onclick="showSalaryDetails('{{ $owner->id }}')">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                        @endif
                                    </div>

                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Jeeps Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Jeep</h5>
                <button class="btn btn-primary" onclick="showJeepModal()">
                    <i class="fas fa-plus"></i> Tambah Jeep
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Plat Nomor</th>
                                <th>Pemilik</th>
                                <th>Total Perjalanan</th>
                                <th>Total Penumpang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jeeps as $jeep)
                                <tr>
                                    <td>{{ $jeep->number_plate }}</td>
                                    <td>{{ $jeep->owner_name }}</td>
                                    <td>{{ number_format($jeep->total_trips) }}</td>
                                    <td>{{ number_format($jeep->total_passengers) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning"
                                            onclick="showJeepModal({{ $jeep->id }}, '{{ $jeep->number_plate }}', {{ $jeep->owner_id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteJeep({{ $jeep->id }})">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- Modals -->
    @include('admin.partials.owner-modal')
    @include('admin.partials.jeep-modal')

    <!-- Salary Details Modal -->
    <div class="modal fade" id="salaryDetailsModal" tabindex="-1" aria-labelledby="salaryDetailsModalLabel"
        aria-hidden="true">
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
            // Get the modal element
            const modal = new bootstrap.Modal(document.getElementById('salaryDetailsModal'));
            const salaryContent = document.getElementById('salaryDetailsContent');

            // Find the salary data for this owner
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

        // Build the content
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

            // Add each jeep row
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

            // Show the modal
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
            const owner_id = document.getElementById('jeepOwnerId').value;
            const url = id ?
                '{{ route("admin.jeeps.vehicles.update", ":id") }}'.replace(':id', id) :
                '{{ route("admin.jeeps.vehicles.store") }}';
            const method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                method: method,
                data: { number_plate, owner_id },
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