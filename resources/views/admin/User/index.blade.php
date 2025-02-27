@extends('admin.layout.index')

@section('title', 'Users')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>User Management</h2>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="bi bi-plus-circle"></i> New User
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @foreach($groupedUsers as $role => $users)
            <h3 class="text-uppercase">{{ $role }}</h3>
            <div class="row">
                @foreach($users as $user)
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">{{ $user->name }}</h5>
                                <p class="card-text text-muted mb-1">
                                    <small><i class="bi bi-envelope"></i> {{ $user->email }}</small>
                                </p>
                                <p class="card-text text-muted mb-3">
                                    <small><i class="bi bi-telephone"></i> {{ $user->telp }}</small>
                                </p>
                                <div class="d-flex justify-content-end">
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

    <!-- Modal Create User -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="createUserForm" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserModalLabel">Create New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modalAlerts"></div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" minlength="6"
                                required>
                            <div class="form-text text-muted">Password must be at least 6 characters long</div>
                            <div id="passwordError" class="invalid-feedback">Password must be at least 6 characters long
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="telp" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="telp" name="telp" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="ticketing">Ticketing</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Form submission handling with AJAX
        document.addEventListener('DOMContentLoaded', function () {
            const createUserForm = document.getElementById('createUserForm');
            const passwordInput = document.getElementById('password');

            // Add input event listener to password field for real-time validation
            if (passwordInput) {
                passwordInput.addEventListener('input', function (e) {
                    if (this.value.length < 6) {
                        this.classList.add('is-invalid');
                        document.getElementById('passwordError').style.display = 'block';
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                        document.getElementById('passwordError').style.display = 'none';
                    }
                });
            }

            if (createUserForm) {
                createUserForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    document.getElementById('modalAlerts').innerHTML = '';

                    const password = passwordInput.value;
                    if (password.length < 6) {
                        const alertContainer = document.createElement('div');
                        alertContainer.className = 'alert alert-danger alert-dismissible fade show';
                        alertContainer.innerHTML = `
                            Password must be at least 6 characters long
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.getElementById('modalAlerts').appendChild(alertContainer);
                        passwordInput.focus();
                        return false;
                    }

                    const formData = new FormData(createUserForm);
                    const submitButton = createUserForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.innerHTML = 'Saving...';
                    submitButton.disabled = true;

                    let isValid = true;
                    createUserForm.querySelectorAll('input[required], select[required]').forEach(input => {
                        if (!input.value.trim()) {
                            input.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            input.classList.remove('is-invalid');
                        }
                    });

                    if (!isValid) {
                        const alertContainer = document.createElement('div');
                        alertContainer.className = 'alert alert-danger alert-dismissible fade show';
                        alertContainer.innerHTML = `
                            Please fill in all required fields
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        document.getElementById('modalAlerts').appendChild(alertContainer);
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                        return false;
                    }

                    fetch(createUserForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw new Error(data.message || 'Server error occurred');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Success:', data);

                            const alertContainer = document.createElement('div');
                            alertContainer.className = 'alert alert-success alert-dismissible fade show';
                            alertContainer.innerHTML = `
                            User created successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                            document.querySelector('.container').prepend(alertContainer);

                            const modal = bootstrap.Modal.getInstance(document.getElementById('createUserModal'));
                            modal.hide();

                            createUserForm.reset();

                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        })
                        .catch(error => {
                            console.error('Error:', error);

                            const alertContainer = document.createElement('div');
                            alertContainer.className = 'alert alert-danger alert-dismissible fade show';
                            alertContainer.innerHTML = `
                            Error: ${error.message || 'Failed to create user'}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                            document.querySelector('#modalAlerts').innerHTML = '';
                            document.querySelector('#modalAlerts').appendChild(alertContainer);
                        })
                        .finally(() => {
                            submitButton.innerHTML = originalButtonText;
                            submitButton.disabled = false;
                        });
                });
            }
        });
    </script>
@endsection