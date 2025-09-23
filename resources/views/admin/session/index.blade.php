@extends('admin.layout.index')

@section('title', 'Sesi')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-2 text-gray-800">Dasbor Sesi</h1>
                <p class="text-muted">Kelola waktu dan kapasitas penumpang untuk setiap sesi</p>
            </div>
            <button id="generateBtn" class="btn btn-primary d-flex align-items-center">
                <span class="btn-text">Generate Monthly Session</span>
                <span class="btn-loading d-none">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    Generating...
                </span>
            </button>
        </div>

        <!-- Statistics Cards - Moved to top -->
        @php
            $currentSessions = $sessions->where('date', '>=', \Carbon\Carbon::today()->format('Y-m-d'))->groupBy('date');
            $pastSessionsGrouped = $sessions->where('date', '<', \Carbon\Carbon::today()->format('Y-m-d'))->groupBy('date')->sortKeysDesc();
        @endphp
        
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <div class="text-primary">
                            <i class="fas fa-calendar-check fa-2x mb-2"></i>
                        </div>
                        <h5 class="card-title">{{ $currentSessions->sum(function($sessions) { return $sessions->count(); }) }}</h5>
                        <p class="card-text text-muted">Sessions Aktif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <div class="text-success">
                            <i class="fas fa-users fa-2x mb-2"></i>
                        </div>
                        <h5 class="card-title">{{ $currentSessions->flatten()->sum('passenger_count') }}</h5>
                        <p class="card-text text-muted">Total Kapasitas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-warning">
                    <div class="card-body text-center">
                        <div class="text-warning">
                            <i class="fas fa-calendar-day fa-2x mb-2"></i>
                        </div>
                        <h5 class="card-title">{{ $currentSessions->count() }}</h5>
                        <p class="card-text text-muted">Hari Aktif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-info">
                    <div class="card-body text-center">
                        <div class="text-info">
                            <i class="fas fa-history fa-2x mb-2"></i>
                        </div>
                        <h5 class="card-title">{{ $pastSessionsGrouped->count() }}</h5>
                        <p class="card-text text-muted">Hari Terlaksana</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast Notification -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
            <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <span class="toast-icon me-2"></span>
                    <strong class="me-auto">Notifikasi</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body toast-message">
                    <!-- Toast message will be inserted here -->
                </div>
            </div>
        </div>

        <!-- Alert Box for Generate Button -->
        <div id="alertBox" class="mb-4"></div>

        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-4" id="sessionTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="current-tab" data-bs-toggle="tab" data-bs-target="#current-sessions" type="button" role="tab">
                    <i class="fas fa-calendar-day me-1"></i>
                    Sessions Aktif & Mendatang
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="past-tab" data-bs-toggle="tab" data-bs-target="#past-sessions" type="button" role="tab">
                    <i class="fas fa-history me-1"></i>
                    Sessions Terdahulu
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="sessionTabsContent">
            <!-- Current & Future Sessions -->
            <div class="tab-pane fade show active" id="current-sessions" role="tabpanel">
                @if($currentSessions->count() > 0)
                    @foreach($currentSessions as $date => $dailySessions)
                        <!-- Day Breaker -->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-primary text-white py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        {{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </h5>
                                    <button class="btn btn-sm btn-danger btn-close-all-day" 
                                            data-date="{{ $date }}"
                                            title="Tutup semua sesi hari ini">
                                        <span class="btn-text">
                                            <i class="fas fa-times me-1"></i>
                                            Tutup Semua Sesi
                                        </span>
                                        <span class="btn-loading d-none">
                                            <div class="spinner-border spinner-border-sm me-1" role="status"></div>
                                            Closing...
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="px-4 py-3">Jam</th>
                                                <th scope="col" class="px-4 py-3">Passenger Count</th>
                                                <th scope="col" class="px-4 py-3">Status</th>
                                                <th scope="col" class="px-4 py-3 text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dailySessions->sortBy('session_time') as $session)
                                                <tr id="session-{{ $session->id }}" 
                                                    class="session-row" 
                                                    data-date="{{ $date }}"
                                                    data-original-time="{{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}" 
                                                    data-original-count="{{ $session->passenger_count }}">
                                                    <td class="px-4 py-3">
                                                        <input type="time" 
                                                               value="{{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}" 
                                                               class="form-control session-time" 
                                                               data-id="{{ $session->id }}"
                                                               style="max-width: 150px;">
                                                        <div class="invalid-feedback error-time d-none">
                                                            Waktu tidak valid
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="input-group" style="max-width: 150px;">
                                                            <input type="number" 
                                                                   value="{{ $session->passenger_count }}" 
                                                                   class="form-control passenger-count" 
                                                                   data-id="{{ $session->id }}" 
                                                                   min="0" 
                                                                   max="999">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-users"></i>
                                                            </span>
                                                        </div>
                                                        <div class="invalid-feedback error-count d-none">
                                                            Jumlah penumpang tidak valid
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        @if($session->passenger_count > 0)
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                Aktif
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">
                                                                <i class="fas fa-times-circle me-1"></i>
                                                                Tutup
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button class="btn btn-success btn-update" 
                                                                    data-id="{{ $session->id }}"
                                                                    title="Update Session">
                                                                <span class="btn-text">
                                                                    <i class="fas fa-save me-1"></i>
                                                                    Update
                                                                </span>
                                                                <span class="btn-loading d-none">
                                                                    <div class="spinner-border spinner-border-sm me-1" role="status"></div>
                                                                    Updating
                                                                </span>
                                                            </button>
                                                            <button class="btn btn-outline-danger btn-close-session" 
                                                                    data-id="{{ $session->id }}"
                                                                    title="Tutup Session">
                                                                <span class="btn-text">
                                                                    <i class="fas fa-times me-1"></i>
                                                                    Tutup Sesi
                                                                </span>
                                                                <span class="btn-loading d-none">
                                                                    <div class="spinner-border spinner-border-sm me-1" role="status"></div>
                                                                    Closing
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            Belum ada session untuk hari ini dan mendatang. 
                            <strong>Klik "Generate Monthly Session"</strong> untuk membuat session otomatis.
                        </div>
                    </div>
                @endif
            </div>

            <!-- Past Sessions with Pagination -->
            <div class="tab-pane fade" id="past-sessions" role="tabpanel">
                @php
                    $itemsPerPage = 5; // Show 5 days per page
                    $currentPage = request()->get('page', 1);
                    $offset = ($currentPage - 1) * $itemsPerPage;
                    $paginatedPastSessions = $pastSessionsGrouped->skip($offset)->take($itemsPerPage);
                    $totalPages = ceil($pastSessionsGrouped->count() / $itemsPerPage);
                @endphp
                
                @if($pastSessionsGrouped->count() > 0)
                    @foreach($paginatedPastSessions as $date => $dailySessions)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-light py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-muted">
                                        <i class="fas fa-calendar me-2"></i>
                                        {{ \Carbon\Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM Y') }}
                                    </h6>
                                    <span class="badge bg-secondary">
                                        {{ $dailySessions->count() }} Session
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="px-3 py-2">Jam</th>
                                                <th scope="col" class="px-3 py-2">Passenger Count</th>
                                                <th scope="col" class="px-3 py-2">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dailySessions->sortBy('session_time') as $session)
                                                <tr class="text-muted">
                                                    <td class="px-3 py-2">
                                                        {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        <span class="badge bg-light text-dark">
                                                            {{ $session->passenger_count }}
                                                            <i class="fas fa-users ms-1"></i>
                                                        </span>
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        @if($session->passenger_count > 0)
                                                            <span class="badge bg-success bg-opacity-25 text-success">
                                                                Selesai
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary bg-opacity-25 text-secondary">
                                                                Ditutup
                                                            </span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    <!-- Pagination for Past Sessions -->
                    @if($totalPages > 1)
                        <div class="d-flex justify-content-center">
                            <nav aria-label="Past sessions pagination">
                                <ul class="pagination">
                                    <!-- Previous Button -->
                                    <li class="page-item {{ $currentPage <= 1 ? 'disabled' : '' }}">
                                        @if($currentPage > 1)
                                            <a class="page-link past-session-page" href="#" data-page="{{ $currentPage - 1 }}">Previous</a>
                                        @else
                                            <span class="page-link">Previous</span>
                                        @endif
                                    </li>
                                    
                                    <!-- Page Numbers -->
                                    @php
                                        $start = max(1, $currentPage - 2);
                                        $end = min($totalPages, $currentPage + 2);
                                    @endphp
                                    
                                    @if($start > 1)
                                        <li class="page-item">
                                            <a class="page-link past-session-page" href="#" data-page="1">1</a>
                                        </li>
                                        @if($start > 2)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                    @endif
                                    
                                    @for($i = $start; $i <= $end; $i++)
                                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                            @if($i == $currentPage)
                                                <span class="page-link">{{ $i }}</span>
                                            @else
                                                <a class="page-link past-session-page" href="#" data-page="{{ $i }}">{{ $i }}</a>
                                            @endif
                                        </li>
                                    @endfor
                                    
                                    @if($end < $totalPages)
                                        @if($end < $totalPages - 1)
                                            <li class="page-item disabled">
                                                <span class="page-link">...</span>
                                            </li>
                                        @endif
                                        <li class="page-item">
                                            <a class="page-link past-session-page" href="#" data-page="{{ $totalPages }}">{{ $totalPages }}</a>
                                        </li>
                                    @endif
                                    
                                    <!-- Next Button -->
                                    <li class="page-item {{ $currentPage >= $totalPages ? 'disabled' : '' }}">
                                        @if($currentPage < $totalPages)
                                            <a class="page-link past-session-page" href="#" data-page="{{ $currentPage + 1 }}">Next</a>
                                        @else
                                            <span class="page-link">Next</span>
                                        @endif
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    @endif
                @else
                    <div class="alert alert-secondary d-flex align-items-center" role="alert">
                        <i class="fas fa-archive me-2"></i>
                        <div>
                            Belum ada session terdahulu yang tercatat.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Constants
const CONSTANTS = {
    TOAST_DURATION: 4000,
    UPDATE_HIGHLIGHT_DURATION: 2000,
    AJAX_TIMEOUT: 10000,
    RETRY_ATTEMPTS: 3
};

// Bootstrap Toast Management
class BootstrapToast {
    constructor() {
        this.toastEl = document.getElementById('toast');
        this.toastIcon = this.toastEl.querySelector('.toast-icon');
        this.toastMessage = this.toastEl.querySelector('.toast-message');
        this.toast = new bootstrap.Toast(this.toastEl);
    }
    
    show(type, message) {
        // Set icon and colors based on type
        if (type === 'success') {
            this.toastIcon.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
            this.toastEl.querySelector('.toast-header').className = 'toast-header bg-success text-white';
        } else {
            this.toastIcon.innerHTML = '<i class="fas fa-exclamation-circle text-danger"></i>';
            this.toastEl.querySelector('.toast-header').className = 'toast-header bg-danger text-white';
        }
        
        this.toastMessage.textContent = message;
        this.toast.show();
    }
}

// Session Manager Class
class SessionManager {
    constructor() {
        this.toast = new BootstrapToast();
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        this.updateRoute = '{{ route("admin.sessions.update") }}';
        this.generateRoute = '{{ route("admin.sessions.generate") }}';
        
        this.initEventListeners();
    }
    
    initEventListeners() {
        // Update session buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-update') || e.target.closest('.btn-update')) {
                const button = e.target.classList.contains('btn-update') ? e.target : e.target.closest('.btn-update');
                this.handleUpdateSession(button);
            }
        });
        
        // Close session buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-close-session') || e.target.closest('.btn-close-session')) {
                const button = e.target.classList.contains('btn-close-session') ? e.target : e.target.closest('.btn-close-session');
                this.handleCloseSession(button);
            }
        });
        
        // Close all sessions for a day
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-close-all-day') || e.target.closest('.btn-close-all-day')) {
                const button = e.target.classList.contains('btn-close-all-day') ? e.target : e.target.closest('.btn-close-all-day');
                this.handleCloseAllDaySessions(button);
            }
        });
        
        // Generate monthly session
        document.getElementById('generateBtn')?.addEventListener('click', () => {
            this.handleGenerateMonthlySession();
        });
        
        // Pagination for past sessions
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('past-session-page')) {
                e.preventDefault();
                const page = e.target.dataset.page;
                this.loadPastSessionsPage(page);
            }
        });
    }
    
    // Client-side validation
    validateSessionData(time, count) {
        const errors = [];
        
        // Validate time format (HH:MM)
        const timeRegex = /^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/;
        if (!time || !timeRegex.test(time)) {
            errors.push('Format waktu tidak valid (HH:MM)');
        }
        
        // Validate passenger count
        const passengerCount = parseInt(count);
        if (isNaN(passengerCount) || passengerCount < 0 || passengerCount > 999) {
            errors.push('Jumlah penumpang harus antara 0-999');
        }
        
        return errors;
    }
    
    // Show validation errors
    showValidationErrors(sessionId, errors) {
        const row = document.getElementById(`session-${sessionId}`);
        const timeError = row.querySelector('.error-time');
        const countError = row.querySelector('.error-count');
        
        // Clear previous errors
        timeError?.classList.add('d-none');
        countError?.classList.add('d-none');
        
        errors.forEach(error => {
            if (error.includes('waktu') && timeError) {
                timeError.textContent = error;
                timeError.classList.remove('d-none');
                timeError.classList.add('d-block');
            }
            if (error.includes('penumpang') && countError) {
                countError.textContent = error;
                countError.classList.remove('d-none');
                countError.classList.add('d-block');
            }
        });
    }
    
    // Clear validation errors
    clearValidationErrors(sessionId) {
        const row = document.getElementById(`session-${sessionId}`);
        row.querySelectorAll('.error-time, .error-count').forEach(error => {
            error.classList.add('d-none');
            error.classList.remove('d-block');
        });
    }
    
    // Toggle button loading state
    toggleButtonLoading(button, isLoading) {
        const textSpan = button.querySelector('.btn-text');
        const loadingSpan = button.querySelector('.btn-loading');
        
        if (isLoading) {
            textSpan?.classList.add('d-none');
            loadingSpan?.classList.remove('d-none');
            button.disabled = true;
        } else {
            textSpan?.classList.remove('d-none');
            loadingSpan?.classList.add('d-none');
            button.disabled = false;
        }
    }
    
    // Highlight updated row
    highlightUpdatedRow(sessionId) {
        const row = document.getElementById(`session-${sessionId}`);
        row.classList.add('table-success');
        
        setTimeout(() => {
            row.classList.remove('table-success');
        }, CONSTANTS.UPDATE_HIGHLIGHT_DURATION);
    }
    
    // Check if data has changed
    hasDataChanged(sessionId) {
        const row = document.getElementById(`session-${sessionId}`);
        const currentTime = row.querySelector('.session-time')?.value;
        const currentCount = row.querySelector('.passenger-count')?.value;
        const originalTime = row.dataset.originalTime;
        const originalCount = row.dataset.originalCount;
        
        return currentTime !== originalTime || currentCount !== originalCount;
    }
    
    // Update original data after successful update
    updateOriginalData(sessionId, time, count) {
        const row = document.getElementById(`session-${sessionId}`);
        row.dataset.originalTime = time;
        row.dataset.originalCount = count;
    }
    
    // AJAX request with retry mechanism
    async makeRequest(url, data, retries = CONSTANTS.RETRY_ATTEMPTS) {
        for (let i = 0; i < retries; i++) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data),
                    timeout: CONSTANTS.AJAX_TIMEOUT
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return await response.json();
            } catch (error) {
                if (i === retries - 1) throw error;
                await new Promise(resolve => setTimeout(resolve, 1000 * (i + 1)));
            }
        }
    }
    
    // Handle update session
    async handleUpdateSession(button) {
        const sessionId = button.dataset.id;
        const row = document.getElementById(`session-${sessionId}`);
        const time = row.querySelector('.session-time')?.value;
        const count = row.querySelector('.passenger-count')?.value;
        
        if (!this.hasDataChanged(sessionId)) {
            this.toast.show('error', 'Tidak ada perubahan data untuk diupdate');
            return;
        }
        
        const validationErrors = this.validateSessionData(time, count);
        if (validationErrors.length > 0) {
            this.showValidationErrors(sessionId, validationErrors);
            this.toast.show('error', validationErrors[0]);
            return;
        }
        
        this.clearValidationErrors(sessionId);
        this.toggleButtonLoading(button, true);
        
        try {
            const response = await this.makeRequest(this.updateRoute, {
                id: sessionId,
                session_time: time,
                passenger_count: parseInt(count)
            });
            
            if (response.success) {
                this.updateOriginalData(sessionId, time, count);
                this.highlightUpdatedRow(sessionId);
                this.updateStatusBadge(sessionId, parseInt(count));
                this.toast.show('success', response.message || 'Session berhasil diupdate');
            } else {
                this.toast.show('error', response.message || 'Gagal update session');
            }
        } catch (error) {
            console.error('Update session error:', error);
            this.toast.show('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
        } finally {
            this.toggleButtonLoading(button, false);
        }
    }
    
    // Handle close session
    async handleCloseSession(button) {
        const sessionId = button.dataset.id;
        const row = document.getElementById(`session-${sessionId}`);
        
        if (!confirm('Yakin ingin menutup sesi ini? Jumlah penumpang akan diset menjadi 0.')) {
            return;
        }
        
        const time = row.querySelector('.session-time')?.value;
        this.toggleButtonLoading(button, true);
        
        try {
            const response = await this.makeRequest(this.updateRoute, {
                id: sessionId,
                session_time: time,
                passenger_count: 0
            });
            
            if (response.success) {
                const countInput = row.querySelector('.passenger-count');
                if (countInput) countInput.value = 0;
                
                this.updateOriginalData(sessionId, time, '0');
                this.highlightUpdatedRow(sessionId);
                this.updateStatusBadge(sessionId, 0);
                this.toast.show('success', 'Sesi berhasil ditutup');
            } else {
                this.toast.show('error', response.message || 'Gagal menutup sesi');
            }
        } catch (error) {
            console.error('Close session error:', error);
            this.toast.show('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
        } finally {
            this.toggleButtonLoading(button, false);
        }
    }
    
    // Handle close all day sessions
    async handleCloseAllDaySessions(button) {
        const date = button.dataset.date;
        
        if (!confirm(`Yakin ingin menutup semua sesi pada tanggal ${date}? Semua passenger count akan diset menjadi 0.`)) {
            return;
        }
        
        this.toggleButtonLoading(button, true);
        
        try {
            // Get all sessions for this date
            const sessionRows = document.querySelectorAll(`tr[data-date="${date}"]`);
            let successCount = 0;
            let totalSessions = sessionRows.length;
            
            // Update each session individually using existing update route
            const updatePromises = Array.from(sessionRows).map(async (row) => {
                const sessionId = row.querySelector('.passenger-count').dataset.id;
                const time = row.querySelector('.session-time').value;
                
                try {
                    const response = await this.makeRequest(this.updateRoute, {
                        id: sessionId,
                        session_time: time,
                        passenger_count: 0
                    });
                    
                    if (response.success) {
                        // Update UI for this session
                        const countInput = row.querySelector('.passenger-count');
                        if (countInput) {
                            countInput.value = 0;
                        }
                        this.updateOriginalData(sessionId, time, '0');
                        this.updateStatusBadge(sessionId, 0);
                        this.highlightUpdatedRow(sessionId);
                        successCount++;
                    }
                    
                    return response;
                } catch (error) {
                    console.error(`Failed to update session ${sessionId}:`, error);
                    return { success: false };
                }
            });
            
            // Wait for all updates to complete
            await Promise.all(updatePromises);
            
            if (successCount === totalSessions) {
                this.toast.show('success', `Semua ${totalSessions} sesi berhasil ditutup`);
            } else if (successCount > 0) {
                this.toast.show('success', `${successCount} dari ${totalSessions} sesi berhasil ditutup`);
            } else {
                this.toast.show('error', 'Gagal menutup semua sesi');
            }
            
        } catch (error) {
            console.error('Close all day sessions error:', error);
            this.toast.show('error', 'Terjadi kesalahan saat menutup semua sesi. Silakan coba lagi.');
        } finally {
            this.toggleButtonLoading(button, false);
        }
    }
    
    // Update status badge based on passenger count
    updateStatusBadge(sessionId, passengerCount) {
        const row = document.getElementById(`session-${sessionId}`);
        const statusCell = row.querySelector('td:nth-child(3)');
        
        if (statusCell) {
            statusCell.innerHTML = passengerCount > 0 
                ? '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Aktif</span>'
                : '<span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i>Tutup</span>';
        }
    }
    
    // Handle generate monthly session
    async handleGenerateMonthlySession() {
        if (!confirm('Yakin mau generate session bulan ini?')) return;
        
        const button = document.getElementById('generateBtn');
        this.toggleButtonLoading(button, true);
        
        try {
            const response = await this.makeRequest(this.generateRoute, {});
            
            const alertBox = document.getElementById('alertBox');
            if (response.status === 'success') {
                alertBox.innerHTML = `<div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    ${response.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                this.toast.show('success', response.message);
                
                setTimeout(() => window.location.reload(), 2000);
            } else {
                alertBox.innerHTML = `<div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${response.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
                this.toast.show('error', response.message);
            }
        } catch (error) {
            console.error('Generate session error:', error);
            const alertBox = document.getElementById('alertBox');
            alertBox.innerHTML = `<div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-times-circle me-2"></i>
                Terjadi kesalahan server!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
            this.toast.show('error', 'Terjadi kesalahan server!');
        } finally {
            this.toggleButtonLoading(button, false);
        }
    }
    
    // Load past sessions page
    loadPastSessionsPage(page) {
        // Add loading state
        const pastSessionsTab = document.getElementById('past-sessions');
        pastSessionsTab.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data...</p>
            </div>
        `;
        
        // Redirect to current URL with page parameter
        const url = new URL(window.location);
        url.searchParams.set('page', page);
        url.hash = '#past-sessions';
        window.location.href = url.toString();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    new SessionManager();
    
    // Handle browser back/forward for pagination
    window.addEventListener('popstate', function(e) {
        if (window.location.hash === '#past-sessions') {
            // Activate past sessions tab if coming back to it
            const pastTab = document.getElementById('past-tab');
            if (pastTab) pastTab.click();
        }
    });
    
    // Auto-activate past sessions tab if page parameter is present
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('page') && window.location.hash === '#past-sessions') {
        const pastTab = document.getElementById('past-tab');
        if (pastTab) pastTab.click();
    }
});
</script>
@endpush