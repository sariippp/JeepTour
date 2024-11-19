@extends('admin.layout.index')

@section('title', 'Users')

@section('content')
<div class="container mt-4">
        @foreach($groupedUsers as $role => $users)
            <h3 class="text-uppercase">{{ $role }}</h3> <!-- Menampilkan nama role -->
            <div class="row">
                @foreach($users as $user)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $user->username }}</h5>
                            <p class="card-text">Email</p>
                            {{-- <p class="card-text">{{ $user->email }}</p> --}}
                            <div class="d-flex justify-content-between">
                                <!-- Tombol Edit -->
                                <button class="btn btn-primary btn-sm" 
                                        onclick="showEditModal({{ $user->id }}, '{{ $user->username }}')">
                                    Edit
                                </button>
                                <!-- Tombol Delete -->
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <hr> <!-- Garis pemisah antar kelompok -->
        @endforeach
</div>

<!-- Modal Edit User MASIH BELOM BISA -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="mb-3">
                            <label for="editUserName" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUserName" name="name" required>
                        </div>
                        {{-- <div class="mb-3">
                            <label for="editUserEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editUserEmail" name="email" required>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
</div>
@endsection

@section('scripts')
<script>
    function showEditModal(id, name, email) {
        document.getElementById('editUserId').value = id;
        document.getElementById('editUserName').value = name;

        const formAction = "{{ route('admin.users.update', ':id') }}".replace(':id', id);
        document.getElementById('editUserForm').action = formAction;

        const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        editUserModal.show();
    }
</script>
@endsection
