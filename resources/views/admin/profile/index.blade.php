@extends('admin.layout.index')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="font-weight-bold mb-0">Profile</h2>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">User Information</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.profile.update') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-4 mb-4 mb-md-0">
                                    <div class="text-center">
                                        <div class="avatar-placeholder mb-3">
                                            <span class="avatar-text">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                        </div>
                                        <h5>{{ $user->name }}</h5>
                                        <p class="text-muted">{{ $user->username }}</p>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <!-- Name -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="name" class="form-label">Full Name</label>
                                                <input type="text" class="form-control" id="name" name="name"
                                                    value="{{ old('name', $user->name) }}" required>
                                            </div>
                                        </div>

                                        <!-- Username -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" name="username"
                                                    value="{{ old('username', $user->username) }}" required>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ old('email', $user->email) }}" required>
                                            </div>
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="telp" class="form-label">Phone Number</label>
                                                <input type="text" class="form-control" id="telp" name="telp"
                                                    value="{{ old('telp', $user->telp) }}" required>
                                            </div>
                                        </div>

                                        <!-- Divider -->
                                        <div class="col-12">
                                            <hr class="my-3">
                                            <h5>Change Password</h5>
                                            <p class="text-muted small">Enter your current password and new password to
                                                update</p>
                                        </div>

                                        <!-- Current Password -->
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="current_password" class="form-label">Current Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="current_password"
                                                        name="current_password">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </button>
                                                </div>
                                                @error('current_password')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- New Password -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="new_password" class="form-label">New Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="new_password"
                                                        name="new_password">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Confirm New Password -->
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="new_password_confirmation" class="form-label">Confirm New
                                                    Password</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control"
                                                        id="new_password_confirmation" name="new_password_confirmation">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-end mt-4">
                                <button type="reset" class="btn btn-outline-secondary me-2">Reset</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom styles -->
    <style>
        .avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #3490dc;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }

        .avatar-text {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .form-label {
            font-weight: 500;
        }

        /* Additional responsive styles */
        @media (max-width: 767.98px) {
            .avatar-placeholder {
                width: 100px;
                height: 100px;
            }

            .avatar-text {
                font-size: 2rem;
            }
        }
    </style>

    <!-- Password toggle script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.toggle-password');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }
                });
            });
        });
    </script>
@endsection