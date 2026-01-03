/**
 * Guest Booking Create JavaScript
 */

class GuestBookingCreate {
    constructor() {
        this.init();
    }

    init() {
        this.initRoomSelection();
        this.initDateValidation();
        this.initTimeValidation();
        this.initFormValidation();
        this.initBookingSummary();
    }

    /**
     * Initialize room selection
     */
    initRoomSelection() {
        const roomSelect = document.getElementById('roomSelect');
        const capacityInfo = document.getElementById('capacityInfo');

        if (roomSelect) {
            // Update capacity info on room selection
            roomSelect.addEventListener('change', () => {
                this.updateCapacityInfo();
                this.updateBookingSummary();
            });

            // Initial update
            this.updateCapacityInfo();
        }
    }

    /**
     * Update capacity information
     */
    updateCapacityInfo() {
        const roomSelect = document.getElementById('roomSelect');
        const capacityInfo = document.getElementById('capacityInfo');
        const participantsInput = document.getElementById('jumlahPeserta');

        if (roomSelect && capacityInfo && participantsInput) {
            const selectedOption = roomSelect.options[roomSelect.selectedIndex];

            if (selectedOption.value) {
                const capacity = selectedOption.getAttribute('data-capacity');
                if (capacity) {
                    capacityInfo.textContent = `Maximum capacity: ${capacity} people`;
                    participantsInput.setAttribute('max', capacity);

                    // Update participants if exceeding capacity
                    const currentParticipants = parseInt(participantsInput.value);
                    if (currentParticipants > parseInt(capacity)) {
                        participantsInput.value = capacity;
                        this.showAlert(`Participants reduced to room capacity (${capacity})`, 'warning');
                    }
                }
            } else {
                capacityInfo.textContent = 'Maximum capacity: --';
            }
        }
    }

    /**
     * Initialize date validation
     */
    initDateValidation() {
        const startDate = document.getElementById('tanggalMulai');
        const endDate = document.getElementById('tanggalSelesai');

        if (startDate) {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            startDate.min = today;

            // Update end date minimum when start date changes
            startDate.addEventListener('change', () => {
                if (endDate) {
                    endDate.min = startDate.value;
                    this.validateDates();
                    this.updateDateDuration();
                }
                this.updateBookingSummary();
            });
        }

        if (endDate) {
            endDate.addEventListener('change', () => {
                this.validateDates();
                this.updateDateDuration();
                this.updateBookingSummary();
            });

            // Initial validation
            this.updateDateDuration();
        }
    }

    /**
     * Validate dates
     */
    validateDates() {
        const startDate = document.getElementById('tanggalMulai');
        const endDate = document.getElementById('tanggalSelesai');

        if (!startDate || !endDate) return true;

        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);

            if (end < start) {
                this.showAlert('End date cannot be before start date', 'error');
                endDate.value = startDate.value;
                return false;
            }

            // Check if booking is too far in advance (optional)
            const maxDays = 30; // Maximum 30 days in advance
            const today = new Date();
            const daysInAdvance = Math.ceil((start - today) / (1000 * 60 * 60 * 24));

            if (daysInAdvance > maxDays) {
                this.showAlert(`Bookings can only be made up to ${maxDays} days in advance`, 'error');
                startDate.value = '';
                startDate.focus();
                return false;
            }
        }

        return true;
    }

    /**
     * Update date duration display
     */
    updateDateDuration() {
        const startDate = document.getElementById('tanggalMulai');
        const endDate = document.getElementById('tanggalSelesai');
        const durationInfo = document.getElementById('dateDurationInfo');

        if (startDate && endDate && durationInfo) {
            if (startDate.value && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);

                // Calculate difference in days
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include both days

                if (diffDays > 0) {
                    durationInfo.textContent = `Duration: ${diffDays} day${diffDays > 1 ? 's' : ''}`;

                    // Add warning for long durations
                    if (diffDays > 7) {
                        durationInfo.classList.add('text-warning');
                    } else {
                        durationInfo.classList.remove('text-warning');
                    }
                }
            } else {
                durationInfo.textContent = 'Duration: -- days';
            }
        }
    }

    /**
     * Initialize time validation
     */
    initTimeValidation() {
        const startTime = document.getElementById('waktuMulai');
        const endTime = document.getElementById('waktuSelesai');

        if (startTime && endTime) {
            // Add change event listeners
            startTime.addEventListener('change', () => {
                this.validateTimes();
                this.updateTimeDuration();
                this.updateBookingSummary();
            });

            endTime.addEventListener('change', () => {
                this.validateTimes();
                this.updateTimeDuration();
                this.updateBookingSummary();
            });

            // Initial duration update
            this.updateTimeDuration();
        }
    }

    /**
     * Validate start and end times
     */
    validateTimes() {
        const startTime = document.getElementById('waktuMulai');
        const endTime = document.getElementById('waktuSelesai');

        if (!startTime || !endTime) return true;

        const start = startTime.value;
        const end = endTime.value;

        if (start && end) {
            if (start >= end) {
                this.showAlert('End time must be after start time', 'error');
                endTime.value = '';
                endTime.focus();
                return false;
            }

            // Calculate duration in hours
            const startHour = parseInt(start.split(':')[0]);
            const startMinute = parseInt(start.split(':')[1]);
            const endHour = parseInt(end.split(':')[0]);
            const endMinute = parseInt(end.split(':')[1]);

            const duration = (endHour + endMinute/60) - (startHour + startMinute/60);

            if (duration > 8) {
                this.showAlert('Maximum booking duration per day is 8 hours', 'error');
                endTime.value = '';
                endTime.focus();
                return false;
            }

            if (duration < 0.5) {
                this.showAlert('Minimum booking duration is 30 minutes', 'warning');
            }
        }

        return true;
    }

    /**
     * Update time duration display
     */
    updateTimeDuration() {
        const startTime = document.getElementById('waktuMulai');
        const endTime = document.getElementById('waktuSelesai');
        const durationInfo = document.getElementById('timeDurationInfo');

        if (startTime && endTime && durationInfo) {
            const start = startTime.value;
            const end = endTime.value;

            if (start && end) {
                const startHour = parseInt(start.split(':')[0]);
                const startMinute = parseInt(start.split(':')[1]);
                const endHour = parseInt(end.split(':')[0]);
                const endMinute = parseInt(end.split(':')[1]);

                const duration = (endHour + endMinute/60) - (startHour + startMinute/60);

                if (duration > 0) {
                    const hours = Math.floor(duration);
                    const minutes = Math.round((duration - hours) * 60);

                    let durationText = 'Duration: ';
                    if (hours > 0) {
                        durationText += `${hours} hour${hours > 1 ? 's' : ''}`;
                    }
                    if (minutes > 0) {
                        durationText += ` ${minutes} minute${minutes > 1 ? 's' : ''}`;
                    }

                    durationInfo.textContent = durationText;

                    // Add warning for long durations
                    if (duration > 6) {
                        durationInfo.classList.add('text-warning');
                    } else {
                        durationInfo.classList.remove('text-warning');
                    }
                } else {
                    durationInfo.textContent = 'Duration: Invalid';
                }
            } else {
                durationInfo.textContent = 'Duration: --';
            }
        }
    }

    /**
     * Initialize form validation
     */
    initFormValidation() {
        const form = document.getElementById('bookingForm');
        if (form) {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm()) {
                    e.preventDefault();
                }
            });
        }
    }

    /**
     * Validate entire form
     */
    validateForm() {
        // Validate dates
        if (!this.validateDates()) {
            return false;
        }

        // Validate times
        if (!this.validateTimes()) {
            return false;
        }

        // Check participants against room capacity
        const roomSelect = document.getElementById('roomSelect');
        const participantsInput = document.getElementById('jumlahPeserta');

        if (roomSelect && participantsInput) {
            const selectedOption = roomSelect.options[roomSelect.selectedIndex];
            const capacity = selectedOption.getAttribute('data-capacity');
            const participants = parseInt(participantsInput.value);

            if (capacity && participants > parseInt(capacity)) {
                this.showAlert(`Number of participants exceeds room capacity (max: ${capacity})`, 'error');
                participantsInput.focus();
                return false;
            }
        }

        // Check terms and conditions
        const termsCheckbox = document.getElementById('termsCheckbox');
        if (!termsCheckbox.checked) {
            this.showAlert('Please agree to the terms and conditions', 'error');
            termsCheckbox.focus();
            return false;
        }

        return true;
    }

    /**
     * Initialize booking summary
     */
    initBookingSummary() {
        const formInputs = document.querySelectorAll('#bookingForm input, #bookingForm select, #bookingForm textarea');

        formInputs.forEach(input => {
            input.addEventListener('change', () => {
                this.updateBookingSummary();
            });
            input.addEventListener('input', () => {
                this.updateBookingSummary();
            });
        });

        // Initial update
        this.updateBookingSummary();
    }

    /**
     * Update booking summary
     */
    updateBookingSummary() {
        // Room
        const roomSelect = document.getElementById('roomSelect');
        const summaryRoom = document.getElementById('summaryRoom');
        if (roomSelect && roomSelect.value && summaryRoom) {
            summaryRoom.textContent = roomSelect.options[roomSelect.selectedIndex].text.split(' (')[0];
        } else if (!roomSelect && summaryRoom) {
            // Room is pre-selected from URL
            summaryRoom.textContent = document.getElementById('summaryRoom').dataset.roomName || '--';
        }

        // Date
        const startDate = document.getElementById('tanggalMulai');
        const endDate = document.getElementById('tanggalSelesai');
        const summaryDate = document.getElementById('summaryDate');
        if (startDate && endDate && startDate.value && endDate.value && summaryDate) {
            const formatDate = (dateString) => {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
            };
            summaryDate.textContent = `${formatDate(startDate.value)} to ${formatDate(endDate.value)}`;
        }

        // Time
        const startTime = document.getElementById('waktuMulai');
        const endTime = document.getElementById('waktuSelesai');
        const summaryTime = document.getElementById('summaryTime');
        if (startTime && endTime && startTime.value && endTime.value && summaryTime) {
            summaryTime.textContent = `${startTime.value} to ${endTime.value}`;
        }

        // Purpose
        const purposeInput = document.querySelector('input[name="tujuan"]');
        const summaryPurpose = document.getElementById('summaryPurpose');
        if (purposeInput && purposeInput.value && summaryPurpose) {
            summaryPurpose.textContent = purposeInput.value;
        }

        // Participants
        const participantsInput = document.getElementById('jumlahPeserta');
        const summaryParticipants = document.getElementById('summaryParticipants');
        if (participantsInput && participantsInput.value && summaryParticipants) {
            summaryParticipants.textContent = participantsInput.value;
        }

        // Duration
        const dateDurationInfo = document.getElementById('dateDurationInfo');
        const timeDurationInfo = document.getElementById('timeDurationInfo');
        const summaryDuration = document.getElementById('summaryDuration');
        if (dateDurationInfo && timeDurationInfo && summaryDuration) {
            summaryDuration.textContent = `${dateDurationInfo.textContent.replace('Duration: ', '')}, ${timeDurationInfo.textContent.replace('Duration: ', '')}`;
        }
    }

    /**
     * Show alert message
     */
    showAlert(message, type = 'error') {
        // Create alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : 'warning'} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Insert at top of form
        const form = document.getElementById('bookingForm');
        if (form) {
            const cardBody = form.closest('.card-body');
            if (cardBody) {
                cardBody.insertBefore(alertDiv, cardBody.firstChild);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        } else {
            alert(message);
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new GuestBookingCreate();
});
