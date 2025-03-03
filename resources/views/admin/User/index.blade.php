@extends('admin.layout.index')

@section('title', 'Pengguna')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">
                <i class="bi bi-people-fill me-2"></i>
                Manajemen Pengguna
            </h2>
            <button class="btn btn-success btn-lg rounded-pill shadow-sm" data-bs-toggle="modal"
                data-bs-target="#createUserModal">
                <i class="bi bi-plus-circle me-1"></i> Pengguna Baru
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-start border-success border-4"
                role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-start border-danger border-4"
                role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-start border-danger border-4"
                role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @foreach($groupedUsers as $role => $users)
            <div class="card shadow-sm mb-4 border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-gradient-to-r from-indigo-500 to-blue-500 p-3 text-white">
                    <h3 class="card-title m-0 fw-bold">
                        @if($role == 'admin')
                            <i class="bi bi-shield-lock-fill me-2"></i>
                        @elseif($role == 'ticketing')
                            <i class="bi bi-ticket-perforated-fill me-2"></i>
                        @else
                            <i class="bi bi-person-badge-fill me-2"></i>
                        @endif
                        {{ strtoupper($role) }}
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @foreach($users as $user)
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm border-0 rounded-3 hover-shadow transition">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">

                                            <div>
                                                <h5 class="card-title mb-0 fw-bold">{{ $user->name }}</h5>
                                                <span class="badge bg-light text-primary">{{ $user->username }}</span>
                                            </div>
                                        </div>

                                        <div class="user-info my-3">
                                            <p class="card-text mb-2 d-flex align-items-center">
                                                <i class="bi bi-envelope-fill me-2 text-primary"></i>
                                                <a href="mailto:{{ $user->email }}"
                                                    class="text-decoration-none text-muted">{{ $user->email }}</a>
                                            </p>
                                            <p class="card-text mb-0 d-flex align-items-center">
                                                <i class="bi bi-telephone-fill me-2 text-primary"></i>
                                                <a href="tel:{{ $user->telp }}"
                                                    class="text-decoration-none text-muted">{{ $user->telp }}</a>
                                            </p>
                                        </div>

                                        <hr class="my-3">

                                        <div class="d-flex justify-content-end mt-3">
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger rounded-pill"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    <i class="bi bi-trash-fill me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Create User -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow border-0">
                <div class="modal-header bg-gradient-to-r from-success to-teal p-4 text-white border-0">
                    <h5 class="modal-title fw-bold text-blue-600" id="createUserModalLabel">
                        <i class="bi bi-person-plus-fill me-2"></i>
                        BUAT PENGGUNA BARU
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="createUserForm" method="POST" action="{{ route('admin.users.store') }}" class="needs-validation">
                    @csrf
                    <div class="modal-body p-4">
                        <div id="modalAlerts"></div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3" id="username" name="username"
                                placeholder="Username" required>
                            <label for="username">Username</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3" id="password" name="password"
                                placeholder="Password" minlength="6" required>
                            <label for="password">Kata Sandi</label>
                            <div class="form-text mt-1 d-flex align-items-center">
                                <i class="bi bi-info-circle me-1"></i>
                                Kata sandi harus minimal 6 karakter
                            </div>
                            <div id="passwordError" class="invalid-feedback">Kata sandi harus minimal 6 karakter</div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3" id="name" name="name" placeholder="Nama"
                                required>
                            <label for="name">Nama</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" class="form-control rounded-3" id="email" name="email" placeholder="Email"
                                required>
                            <label for="email">Email</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3" id="telp" name="telp"
                                placeholder="Nomor Telepon" required>
                            <label for="telp">Nomor Telepon</label>
                        </div>

                        <div class="form-floating mb-3">
                            <select class="form-select rounded-3" id="role" name="role" required>
                                <option value="" disabled selected>Pilih Peran</option>
                                <option value="admin">Admin</option>
                                <option value="ticketing">Ticketing</option>
                            </select>
                            <label for="role">Peran</label>
                        </div>
                    </div>
                    <div class="modal-footer bg-light p-3 border-0 rounded-bottom-4">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                            <i class="bi bi-check-circle me-1"></i> Simpan Pengguna
                        </button>
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

            // Add floating labels class
            document.querySelectorAll('.form-control, .form-select').forEach(field => {
                if (field.value) {
                    field.classList.add('is-valid');
                }

                field.addEventListener('focus', function () {
                    this.parentElement.classList.add('focused');
                });

                field.addEventListener('blur', function () {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });
            });

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
                        alertContainer.className = 'alert alert-danger alert-dismissible fade show border-start border-danger border-4';
                        alertContainer.innerHTML = `
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        Kata sandi harus minimal 6 karakter
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    `;
                        document.getElementById('modalAlerts').appendChild(alertContainer);
                        passwordInput.focus();
                        return false;
                    }

                    const formData = new FormData(createUserForm);
                    const submitButton = createUserForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Menyimpan...';
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
                        alertContainer.className = 'alert alert-danger alert-dismissible fade show border-start border-danger border-4';
                        alertContainer.innerHTML = `
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        Silakan isi semua bidang yang diperlukan
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
                                    throw new Error(data.message || 'Terjadi kesalahan server');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Success:', data);

                            const alertContainer = document.createElement('div');
                            alertContainer.className = 'alert alert-success alert-dismissible fade show border-start border-success border-4';
                            alertContainer.innerHTML = `
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Pengguna berhasil dibuat!
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
                            alertContainer.className = 'alert alert-danger alert-dismissible fade show border-start border-danger border-4';
                            alertContainer.innerHTML = `
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        Error: ${error.message || 'Gagal membuat pengguna'}
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

    <style>
        /* Additional styles */
        .bg-gradient-to-r {
            background-image: linear-gradient(to right, var(--bs-primary), var(--bs-info));
        }

        .from-indigo-500 {
            --bs-primary: #6366f1;
        }

        .to-blue-500 {
            --bs-info: #3b82f6;
        }

        .from-success {
            --bs-primary: #10b981;
        }

        .to-teal {
            --bs-info: #14b8a6;
        }

        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .transition {
            transition: all 0.3s ease;
        }

        .form-floating.focused label {
            opacity: 0.65;
            transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
        }

        .user-avatar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover .user-avatar {
            transform: scale(1.1);
        }
    </style>
@endsection