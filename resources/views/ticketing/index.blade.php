@extends('ticketing.layout.index')

@section('title', 'Dashboard')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

@section('content')
<div class="space-y-6">
    <h1 class="text-lg font-medium mb-4">Plotting Jeep</h1>


    <div class="mb-4 d-md-none">
        <select id="dateSwitcher" class="form-select rounded-md border-gray-300 shadow-sm">
            @foreach($datesForward as $date)
                <option value="date-{{ $date->full_date }}">{{ $date->day_group }} {{ $date->month_group }} {{ $date->year_group }}</option>
            @endforeach
        </select>
    </div>

    @foreach($datesForward as $date)
        <div id="date-{{ $date->full_date }}" class="date-section bg-white p-4 md:p-6 rounded-lg border border-gray-200 shadow-sm mb-4">
            <!-- Date header with count -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-medium">{{ $date->day_group }} {{ $date->month_group }} {{ $date->year_group }}</h2>
            </div>
            
            <div class="space-y-4">
                @foreach($sessions as $session)
                    @php
                        $hasOrdersInSession = false;
                        foreach($orders as $order) {
                            if($date->full_date == $order->date && $session->session_hour == $order->session_hour) {
                                $hasOrdersInSession = true;
                                break;
                            }
                        }
                    @endphp
                    
                    @if($session->date == $date->full_date && $hasOrdersInSession)
                        <div class="bg-gray-50 rounded-md p-3 mb-3">
                            <h3 class="font-medium text-gray-700 mb-2">{{ $session->session_hour }}</h3>
                            
                            <div class="space-y-2">
                                @foreach($orders as $order)
                                    @if($date->full_date == $order->date && $session->session_hour == $order->session_hour)
                                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="p-4">
                                                <div class="flex flex-wrap md:flex-nowrap justify-between">
                                                    <div class="w-full md:w-auto mb-3 md:mb-0">
                                                        <p class="font-medium text-gray-900">{{ $order->name }}</p>
                                                        <div class="flex items-center mt-1">
                                                            <span class="text-sm text-gray-600"><i class="fas fa-map-marker-alt mr-1 text-red-500"></i>{{ $order->city }}</span>
                                                            <span class="mx-2 text-gray-300">|</span>
                                                            <span class="text-sm text-gray-600"><i class="fas fa-users mr-1 text-blue-500"></i>{{ $order->passenger_count }}</span>
                                                        </div>
                                                        <p class="font-medium text-gray-600">Order ID: {{ $order->reservation_id }}</p>
                                                    </div>
                                                    
                                                    <div class="w-full md:w-auto flex items-center">
                                                        
                                                        <button type="button" 
                                                            class="btn {{ $order->is_plotted ? 'btn-success' : 'btn-primary' }} w-full md:w-auto flex-shrink-0 px-4 py-2 rounded-md" 
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#jeepPlotModal{{ $order->reservation_id }}">
                                                            @if($order->is_plotted)
                                                                <i class="fas fa-check-circle mr-1"></i> Plotted
                                                            @else
                                                                <i class="fas fa-car mr-1"></i> Plot Jeep
                                                            @endif
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal -->
                                        <div class="modal fade" id="jeepPlotModal{{ $order->reservation_id }}" tabindex="-1"
                                            aria-labelledby="jeepPlotModalLabel{{ $order->reservation_id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                <div class="modal-content rounded-lg border-0 shadow-lg">
                                                    <div class="modal-header bg-gray-50 rounded-t-lg">
                                                        <h1 class="modal-title fs-5 font-medium" id="jeepPlotModalLabel{{ $order->reservation_id }}">
                                                            Plot Jeep for Reservation #{{ $order->reservation_id }}
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form id="plottingForm{{ $order->reservation_id }}" action="{{ route('ticketing.savePlotting') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="reservation_id" value="{{ $order->reservation_id }}">
                                                        <input type="hidden" name="session_id" value="{{ $order->session_id }}">
                                                        <input type="hidden" name="date" value="{{ $order->date }}">
                                                        <input type="hidden" name="passenger_count" value="{{ $order->passenger_count }}">

                                                        <div class="modal-body">
                                                            <div class="bg-blue-50 p-4 rounded-md mb-4">
                                                                <div class="flex items-center justify-between mb-2">
                                                                    <span class="text-gray-600">Customer:</span>
                                                                    <span class="font-medium">{{ $order->name }}</span>
                                                                </div>
                                                                <div class="flex items-center justify-between mb-2">
                                                                    <span class="text-gray-600">Location:</span>
                                                                    <span>{{ $order->city }}</span>
                                                                </div>
                                                                <div class="flex items-center justify-between mb-2">
                                                                    <span class="text-gray-600">Passengers:</span>
                                                                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded-full">{{ $order->passenger_count }}</span>
                                                                </div>
                                                                <div class="text-center mt-2 p-2 bg-white rounded-md border border-gray-200">
                                                                    <span class="font-medium">{{ $date->day_group }} {{ $date->month_group }} {{ $date->year_group }} - {{ $session->session_hour }}</span>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="mb-4">
                                                                <h5 class="font-medium mb-2">Select Jeep</h5>
                                                                <p class="text-muted small mb-2">Pilih jeep untuk penumpang (perlu total: {{ $order->passenger_count }})</p>
                                                                <div class="jeep-options">
                                                                    @foreach ($jeepSlots[$order->reservation_id] ?? [] as $jeep)
                                                                        <div class="card mb-2 jeep-card 
                                                                            {{ $jeep->is_selected ? 'border-primary' : 
                                                                              ($jeep->slots_left < $order->passenger_count ? 'border-danger-subtle' : 
                                                                               ($jeep->is_reserved_by_other ? 'border-warning-subtle' : 'border-success-subtle')) }}">
                                                                            <div class="card-body py-2 px-3">
                                                                                <div class="form-check d-flex justify-content-between align-items-center">
                                                                                    <div>
                                                                                        <input 
                                                                                            type="checkbox" 
                                                                                            class="form-check-input jeep-checkbox" 
                                                                                            name="selected_jeep" 
                                                                                            value="{{ $jeep->jeep_id }}"
                                                                                            data-slots="{{ $jeep->slots_left }}"
                                                                                            data-required="{{ $order->passenger_count }}"
                                                                                            data-is-selected="{{ $jeep->is_selected ? 'true' : 'false' }}"
                                                                                            data-original-state="{{ $jeep->is_selected ? 'checked' : '' }}"
                                                                                            {{ $jeep->is_selected ? 'checked' : '' }}
                                                                                            {{ !$jeep->is_selected && (($jeep->slots_left < $order->passenger_count) || $jeep->is_reserved_by_other) ? 'disabled' : '' }}
                                                                                            id="jeep_{{ $order->reservation_id }}_{{ $jeep->jeep_id }}"
                                                                                        >
                                                                                        <label class="form-check-label" for="jeep_{{ $order->reservation_id }}_{{ $jeep->jeep_id }}">
                                                                                            <span class="d-block">{{ $jeep->number_plate }} - {{ $jeep->owner_name }}</span>
                                                                                        </label>
                                                                                    </div>
                                                                                    @if ($jeep->is_reserved_by_other)
                                                                                        <span class="badge bg-warning">Reserved by another</span>
                                                                                    @else
                                                                                        <span class="badge {{ $jeep->slots_left < $order->passenger_count && !$jeep->is_selected ? 'bg-danger' : 'bg-success' }}">
                                                                                            {{ $jeep->slots_left }} seat{{ $jeep->slots_left != 1 ? 's' : '' }} left
                                                                                        </span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-gray-50 rounded-b-lg">
                                                            <button type="button" class="btn btn-secondary close-modal-btn bg-gray-200 hover:bg-gray-300 text-gray-800 border-0 py-2 px-4 rounded-md" data-bs-dismiss="modal" data-reservation-id="{{ $order->reservation_id }}">Cancel</button>
                                                            <button type="submit" id="saveButton{{ $order->reservation_id }}" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md" {{ !$order->is_plotted ? 'disabled' : '' }}>Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<script>
$(document).ready(function() {
    // Handle jeep checkboxes for all modals
    $('.jeep-checkbox').on('change', function() {
        const reservationId = $(this).closest('form').find('input[name="reservation_id"]').val();
        const form = $(`#plottingForm${reservationId}`);
        const saveButton = $(`#saveButton${reservationId}`);
        
        // If this checkbox is checked, disable all other checkboxes
        if ($(this).is(':checked')) {
            // Disable all other checkboxes
            form.find('.jeep-checkbox').not(this).prop('disabled', true);
            // Enable the save button
            saveButton.prop('disabled', false);
        } else {
            // Re-enable checkboxes that are not reserved by others and have sufficient capacity or are already selected
            form.find('.jeep-checkbox').each(function() {
                const slots = parseInt($(this).data('slots'));
                const requiredPassengers = parseInt($(this).data('required'));
                const isSelected = $(this).data('is-selected') === 'true';
                const isDisabledByOther = $(this).closest('.jeep-card').hasClass('border-warning-subtle');
                
                // Allow selection of checkboxes that:
                // 1. Were originally selected for this reservation, OR
                // 2. Have enough capacity AND are not reserved by others
                if (isSelected) {
                    $(this).prop('disabled', false); // Always allow unselecting the originally selected jeep
                } else {
                    const shouldDisable = (slots < requiredPassengers) || isDisabledByOther;
                    $(this).prop('disabled', shouldDisable);
                }
            });
            
            // Disable save button since no jeep is selected
            saveButton.prop('disabled', true);
        }
    });
    
    // Reset form when modal is closed
    $('.close-modal-btn').on('click', function() {
        const reservationId = $(this).data('reservation-id');
        resetModalForm(reservationId);
    });
    
    // Also reset form when modal is closed by clicking the X or outside the modal
    $('.modal').on('hidden.bs.modal', function() {
        const reservationId = $(this).find('input[name="reservation_id"]').val();
        resetModalForm(reservationId);
    });
    
    // Function to reset the form to its original state
    function resetModalForm(reservationId) {
        const form = $(`#plottingForm${reservationId}`);
        
        // Reset all checkboxes to their original state
        form.find('.jeep-checkbox').each(function() {
            const originalState = $(this).data('original-state');
            const isSelected = $(this).data('is-selected') === 'true';
            const slots = parseInt($(this).data('slots'));
            const requiredPassengers = parseInt($(this).data('required'));
            const isDisabledByOther = $(this).closest('.jeep-card').hasClass('border-warning-subtle');
            
            // Reset checkbox to checked state if it was originally selected
            if (originalState === 'checked') {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
            
            // Reset disabled state - don't disable originally selected jeeps
            if (isSelected) {
                $(this).prop('disabled', false);
            } else {
                const shouldDisable = (slots < requiredPassengers) || isDisabledByOther;
                $(this).prop('disabled', shouldDisable);
            }
        });
        
        // Reset save button state
        const saveButton = $(`#saveButton${reservationId}`);
        const hasSelectedJeep = form.find('.jeep-checkbox:checked').length > 0;
        saveButton.prop('disabled', !hasSelectedJeep);
    }
    
    // Initialize state for pre-selected jeeps
    $('.jeep-checkbox:checked').trigger('change');
    
    // Date switcher for mobile view
    $('#dateSwitcher').on('change', function() {
        const selectedDateId = $(this).val();
        $('.date-section').hide();
        $(`#${selectedDateId}`).show();
    });
    
    // Initialize date sections display
    if (window.innerWidth < 768) {
        $('.date-section').hide();
        $('.date-section:first').show();
    }
    
    // Handle resize events
    $(window).resize(function() {
        if (window.innerWidth >= 768) {
            $('.date-section').show();
        } else {
            $('.date-section').hide();
            const selectedDateId = $('#dateSwitcher').val();
            $(`#${selectedDateId}`).show();
        }
    });
    
});

// Improved notification system
function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    // Create content
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">
                ${type === 'success' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-exclamation-circle"></i>'}
            </span>
            <span class="notification-message">${message}</span>
        </div>
        <button class="notification-close"><i class="fas fa-times"></i></button>
        <div class="notification-progress"></div>
    `;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Show notification with animation
    setTimeout(() => notification.classList.add('show'), 10);
    
    // Add progress animation
    const progress = notification.querySelector('.notification-progress');
    progress.style.animation = 'notification-progress 5s linear forwards';
    
    // Auto-close after 5 seconds
    const timeout = setTimeout(() => {
        closeNotification(notification);
    }, 5000);
    
    // Manual close button
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        clearTimeout(timeout);
        closeNotification(notification);
    });
}

// Update the close-modal-btn click handler to refresh the page
$('.close-modal-btn').on('click', function() {
    const reservationId = $(this).data('reservation-id');
    // First reset the form to its original state
    resetModalForm(reservationId);
    // Then reload the page after the modal is fully hidden
    $('#jeepPlotModal' + reservationId).on('hidden.bs.modal', function() {
        location.reload();
    });
});

// Also update the modal hidden event handler to refresh when closed by clicking X or outside
$('.modal').on('hidden.bs.modal', function() {
    location.reload();
});

function closeNotification(notification) {
    notification.classList.remove('show');
    notification.classList.add('hide');
    
    // Remove from DOM after animation completes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

// Replace alert calls with this function
window.addEventListener('load', function() {
    @if(session('success'))
        showNotification("{{ session('success') }}");
    @endif
    
    @if(session('error'))
        showNotification("{{ session('error') }}", "error");
    @endif
});

// Improved new orders notification system
function checkForNewOrders() {
    const lastCheck = localStorage.getItem('lastOrderCheck') || '0';
    
    $.ajax({
        url: '{{ route("ticketing.checkNewOrders") }}',
        method: 'GET',
        data: {
            last_check: lastCheck
        },
        success: function(response) {
            if (response.new_orders > 0) {
                // Remove any existing notification before showing a new one
                const existingNotification = document.querySelector('.new-orders-notification');
                if (existingNotification) {
                    existingNotification.remove();
                }
                
                // Show notification bar
                const notification = document.createElement('div');
                notification.className = 'new-orders-notification';
                notification.innerHTML = `
                    <div class="container">
                        <span><i class="fas fa-bell me-2"></i> ${response.new_orders} new order${response.new_orders > 1 ? 's' : ''} received!</span>
                        <a href="javascript:location.reload()" class="refresh-link">Refresh to view</a>
                        <button class="close-notification"><i class="fas fa-times"></i></button>
                    </div>
                `;
                
                document.body.insertBefore(notification, document.body.firstChild);
                
                // Play sound alert (optional)
                const audio = new Audio('/audio/notification.mp3');
                audio.volume = 0.5;
                audio.play().catch(e => console.log('Audio play failed: Browser requires user interaction before playing audio'));
                
                // Handle close button
                notification.querySelector('.close-notification').addEventListener('click', function() {
                    notification.remove();
                });
            }
            
            // Always update the last check time regardless of new orders
            localStorage.setItem('lastOrderCheck', response.current_time);
        },
        error: function(xhr, status, error) {
            console.error('Error checking for new orders:', error);
        }
    });
}

// Check for new orders every 15 seconds (more frequent than before)
setInterval(checkForNewOrders, 15000);

// Initial check on page load
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure everything is loaded
    setTimeout(checkForNewOrders, 1000);
});
</script>

<style>
/* Base styles */
body {
    background-color: #f5f7fa;
    color: #333;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* New Orders Notification */
.new-orders-notification {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: #2563eb;
    color: white;
    padding: 12px 0;
    z-index: 9999;
    animation: slideDown 0.4s ease-out;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.new-orders-notification .container {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.new-orders-notification i {
    margin-right: 8px;
}

.new-orders-notification .refresh-link {
    margin-left: 15px;
    color: white;
    background-color: rgba(255, 255, 255, 0.2);
    padding: 4px 12px;
    border-radius: 50px;
    font-weight: 500;
    transition: background-color 0.2s;
    text-decoration: none;
}

.new-orders-notification .refresh-link:hover {
    background-color: rgba(255, 255, 255, 0.3);
}

.new-orders-notification .close-notification {
    position: absolute;
    right: 15px;
    background: none;
    border: none;
    color: white;
    opacity: 0.7;
    cursor: pointer;
    transition: opacity 0.2s;
}

.new-orders-notification .close-notification:hover {
    opacity: 1;
}

@keyframes slideDown {
    from { transform: translateY(-100%); }
    to { transform: translateY(0); }
}

/* Regular Notifications */
.notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    min-width: 320px;
    max-width: 450px;
    background-color: white;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    border-radius: 8px;
    padding: 16px 20px;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 9999;
    overflow: hidden;
}

.notification.show {
    transform: translateY(0);
    opacity: 1;
}

.notification.hide {
    transform: translateY(50px);
    opacity: 0;
}

.notification.success {
    border-left: 4px solid #10b981;
}

.notification.error {
    border-left: 4px solid #ef4444;
}

.notification-content {
    display: flex;
    align-items: center;
}

.notification-icon {
    margin-right: 15px;
    font-size: 20px;
}

.notification-icon .fa-check-circle {
    color: #10b981;
}

.notification-icon .fa-exclamation-circle {
    color: #ef4444;
}

.notification-message {
    flex: 1;
    font-size: 14px;
}

.notification-close {
    background: none;
    border: none;
    font-size: 14px;
    cursor: pointer;
    color: #9ca3af;
    position: absolute;
    top: 12px;
    right: 12px;
    transition: color 0.2s;
}

.notification-close:hover {
    color: #6b7280;
}

.notification-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    width: 100%;
    background-color: #f0f0f0;
}

@keyframes notification-progress {
    from { width: 0; }
    to { width: 100%; }
}

.notification.success .notification-progress::before {
    content: '';
    position: absolute;
    height: 100%;
    width: 0;
    background-color: #10b981;
    animation: notification-progress 5s linear forwards;
}

.notification.error .notification-progress::before {
    content: '';
    position: absolute;
    height: 100%;
    width: 0;
    background-color: #ef4444;
    animation: notification-progress 5s linear forwards;
}

/* Button Styling */
.btn-primary {
    background-color: #2563eb !important;
    border-color: #2563eb !important;
    color: white !important;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    background-color: #1d4ed8 !important;
    border-color: #1d4ed8 !important;
}

.btn-success {
    background-color: #10b981 !important;
    border-color: #10b981 !important;
    color: white !important;
}

.btn-success:hover {
    background-color: #059669 !important;
    border-color: #059669 !important;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Modal styling */
.modal-content {
    border: none;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.modal-header {
    border-bottom: 1px solid #f0f0f0;
}

.modal-footer {
    border-top: 1px solid #f0f0f0;
}

/* Jeep cards */
.jeep-card {
    transition: all 0.2s ease;
}

.jeep-card:hover:not(.border-warning-subtle):not(.border-danger-subtle) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.jeep-card.border-primary {
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.5);
}

/* Responsive styles */
@media (max-width: 767px) {
    .notification {
        min-width: unset;
        left: 20px;
        right: 20px;
    }
    
    .modal-dialog {
        margin: 0.75rem;
    }
    
    .form-check {
        padding-left: 0;
    }
    
    .form-check-input {
        margin-top: 0;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .modal-footer .btn {
        margin-bottom: 0;
    }
    
    .badge {
        white-space: normal;
        text-align: center;
    }
}

/* Extra enhancements */
.bg-blue-50 {
    background-color: #eff6ff;
}

.bg-green-50 {
    background-color: #ecfdf5;
}

.bg-red-50 {
    background-color: #fef2f2;
}

.bg-yellow-50 {
    background-color: #fffbeb;
}

.rounded-full {
    border-radius: 9999px;
}

.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

.hover\:shadow-md:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>
@endsection