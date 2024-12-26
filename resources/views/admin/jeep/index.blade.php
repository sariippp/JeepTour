@extends('admin.layout.index')

@section('title', 'Jeep')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Owner Statistics</h4>
                    <button class="btn btn-primary" onclick="showOwnerModal()">
                        <i class="fas fa-plus"></i> Add Owner
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        @foreach($ownerData as $owner)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">{{ $owner->name }}</h5>
                        <div>
                            <button class="btn btn-sm btn-warning" onclick="showOwnerModal({{ $owner->id }}, '{{ $owner->name }}')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteOwner({{ $owner->id }})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-muted small">Jeeps</div>
                            <h4>{{ $owner->total_jeeps }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Total Passengers</div>
                            <h4>{{ number_format($owner->total_passengers) }}</h4>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">This Month</div>
                            <h4>{{ number_format($owner->monthly_passengers) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Jeeps Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Jeeps</h5>
            <button class="btn btn-primary" onclick="showJeepModal()">
                <i class="fas fa-plus"></i> Add Jeep
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Number Plate</th>
                            <th>Owner</th>
                            <th>Total Trips</th>
                            <th>Total Passengers</th>
                            <th>Actions</th>
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
                                    <i class="fas fa-trash"></i> Delete
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

@endsection

@push('scripts')
<script>
    const ownerModal = new bootstrap.Modal(document.getElementById('ownerModal'));
    const jeepModal = new bootstrap.Modal(document.getElementById('jeepModal'));

    function showOwnerModal(id = null, name = '') {
        document.getElementById('ownerModalTitle').textContent = id ? 'Edit Owner' : 'Add Owner';
        document.getElementById('ownerId').value = id || '';
        document.getElementById('ownerName').value = name;
        ownerModal.show();
    }

    function showJeepModal(id = null, numberPlate = '', ownerId = '') {
        document.getElementById('jeepModalTitle').textContent = id ? 'Edit Jeep' : 'Add Jeep';
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
            error: (xhr) => alert(xhr.responseJSON.message || 'Error saving owner')
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
            error: (xhr) => alert(xhr.responseJSON.message || 'Error saving jeep')
        });
    }

    function deleteOwner(id) {
        if (confirm('Are you sure you want to delete this owner?')) {
            $.ajax({
                url: '{{ route("admin.jeeps.owners.delete", ":id") }}'.replace(':id', id),
                method: 'DELETE',
                success: () => window.location.reload(),
                error: () => alert('Error deleting owner')
            });
        }
    }

    function deleteJeep(id) {
        if (confirm('Are you sure you want to delete this jeep?')) {
            $.ajax({
                url: '{{ route("admin.jeeps.vehicles.delete", ":id") }}'.replace(':id', id),
                method: 'DELETE',
                success: () => window.location.reload(),
                error: () => alert('Error deleting jeep')
            });
        }
    }
</script>
@endpush