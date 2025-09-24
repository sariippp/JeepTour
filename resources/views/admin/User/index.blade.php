@extends('admin.layout.index')

@section('title', 'Pengguna')

@section('content')
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Manajemen Pengguna</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola pengguna sistem dan hak akses mereka</p>
            </div>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center"
                data-bs-toggle="modal" data-bs-target="#createUserModal">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Pengguna Baru
            </button>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" class="text-green-400 hover:text-green-600"
                            onclick="this.parentElement.parentElement.parentElement.remove()">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" class="text-red-400 hover:text-red-600"
                            onclick="this.parentElement.parentElement.parentElement.remove()">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <ul class="text-sm text-red-700 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" class="text-red-400 hover:text-red-600"
                            onclick="this.parentElement.parentElement.parentElement.remove()">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Users by Role -->
        @foreach($groupedUsers as $role => $users)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="flex items-center justify-between p-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        @if($role == 'admin')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        @elseif($role == 'ticketing')
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 11-4 0V9a2 2 0 114 0V6a2 2 0 00-2-2H5z" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @endif
                        {{ strtoupper($role) }}
                        <span
                            class="ml-2 px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">{{ count($users) }}</span>
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($users as $user)
                            <div class="bg-gray-50 rounded-lg p-4 hover:shadow-lg transition-shadow border">
                                <div class="flex items-center justify-between mb-4">
                                    <div
                                        class="flex-shrink-0 h-12 w-12 flex items-center justify-center bg-blue-100 rounded-full text-blue-600">
                                        <span class="font-semibold text-lg">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 transition-colors"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"
                                                title="Hapus Pengguna">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $user->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $user->username }}</p>
                                    </div>

                                    <div class="pt-2 space-y-1">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <a href="mailto:{{ $user->email }}"
                                                class="hover:text-blue-600 transition-colors">{{ $user->email }}</a>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <a href="tel:{{ $user->telp }}"
                                                class="hover:text-blue-600 transition-colors">{{ $user->telp }}</a>
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
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-lg border-0 shadow-xl">
                <div class="modal-header bg-blue-600 text-white border-0 rounded-t-lg">
                    <h5 class="modal-title font-semibold flex items-center" id="createUserModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Buat Pengguna Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="createUserForm" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="modal-body p-6">
                        <div id="modalAlerts"></div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <input type="text" class="form-control w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                    id="username" name="username" placeholder="Masukkan username" required>
                            </div>

                            <div class="md:col-span-2">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi</label>
                                <input type="password" class="form-control w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    id="password" name="password" placeholder="Minimal 6 karakter" minlength="6" required>
                                <p class="text-xs text-gray-500 mt-1">Kata sandi harus minimal 6 karakter</p>
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" class="form-control w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    id="name" name="name" placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" class="form-control w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    id="email" name="email" placeholder="nama@email.com" required>
                            </div>

                            <div>
                                <label for="telp" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text" class="form-control w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    id="telp" name="telp" placeholder="08xxxxxxxxxx" required>
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Peran</label>
                                <select class="form-select w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    id="role" name="role" required>
                                    <option value="" disabled selected>Pilih Peran</option>
                                    <option value="admin">Admin</option>
                                    <option value="ticketing">Ticketing</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-gray-50 border-0 rounded-b-lg p-4">
                        <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors mr-2"
                            data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const createUserForm = document.getElementById('createUserForm');
            const passwordInput = document.getElementById('password');

            // Password validation
            if (passwordInput) {
                passwordInput.addEventListener('input', function (e) {
                    if (this.value.length < 6) {
                        this.classList.add('border-red-500');
                        this.classList.remove('border-green-500');
                    } else {
                        this.classList.remove('border-red-500');
                        this.classList.add('border-green-500');
                    }
                });
            }

            // Form submission
            if (createUserForm) {
                createUserForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    document.getElementById('modalAlerts').innerHTML = '';

                    const password = passwordInput.value;
                    if (password.length < 6) {
                        showModalAlert('Kata sandi harus minimal 6 karakter', 'danger');
                        passwordInput.focus();
                        return false;
                    }

                    const formData = new FormData(createUserForm);
                    const submitButton = createUserForm.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    
                    submitButton.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>Menyimpan...';
                    submitButton.disabled = true;

                    // Validate required fields
                    let isValid = true;
                    createUserForm.querySelectorAll('input[required], select[required]').forEach(input => {
                        if (!input.value.trim()) {
                            input.classList.add('border-red-500');
                            isValid = false;
                        } else {
                            input.classList.remove('border-red-500');
                            input.classList.add('border-green-500');
                        }
                    });

                    if (!isValid) {
                        showModalAlert('Silakan isi semua bidang yang diperlukan', 'danger');
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                        return false;
                    }

                    // Submit form via AJAX
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
                        showPageAlert('Pengguna berhasil dibuat!', 'success');
                        
                        const modal = bootstrap.Modal.getInstance(document.getElementById('createUserModal'));
                        modal.hide();
                        createUserForm.reset();
                        
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showModalAlert(error.message || 'Gagal membuat pengguna', 'danger');
                    })
                    .finally(() => {
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                    });
                });
            }
        });

        function showModalAlert(message, type) {
            const alertClass = type === 'success' ? 'green' : 'red';
            const alertContainer = document.createElement('div');
            alertContainer.className = `bg-${alertClass}-50 border-l-4 border-${alertClass}-400 p-4 rounded-lg shadow-sm mb-4`;
            alertContainer.innerHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-${alertClass}-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            ${type === 'success' 
                                ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />'
                                : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />'
                            }
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-${alertClass}-700">${message}</p>
                    </div>
                </div>
            `;
            document.getElementById('modalAlerts').appendChild(alertContainer);
        }

        function showPageAlert(message, type) {
            const alertClass = type === 'success' ? 'green' : 'red';
            const alertContainer = document.createElement('div');
            alertContainer.className = `bg-${alertClass}-50 border-l-4 border-${alertClass}-400 p-4 rounded-lg shadow-sm`;
            alertContainer.innerHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-${alertClass}-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-${alertClass}-700">${message}</p>
                    </div>
                </div>
            `;
            document.querySelector('.space-y-8').insertBefore(alertContainer, document.querySelector('.space-y-8').firstChild);
        }
    </script>
@endsection