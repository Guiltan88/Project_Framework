/**
 * Guest Dashboard JavaScript
 */
class GuestDashboard {
    constructor() {
        this.initialize();
    }

    initialize() {
        this.initTooltips();
        this.initToasts();
        this.bindEvents();
        this.setupAutoRefresh();
        this.loadStats();
    }

    initTooltips() {
        // Initialize Bootstrap tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => {
            new bootstrap.Tooltip(el);
        });
    }

    initToasts() {
        // Show success toast if exists
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    }

    bindEvents() {
        // Cancel booking confirmation
        document.querySelectorAll('[data-cancel-booking]').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleCancelBooking(e));
        });

        // Search filter for upcoming bookings
        const searchInput = document.querySelector('#booking-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.filterBookings(e.target.value));
        }

        // Load more rooms button
        const loadMoreBtn = document.querySelector('#load-more-rooms');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => this.loadMoreRooms());
        }
    }

    handleCancelBooking(event) {
        event.preventDefault();

        const bookingId = event.currentTarget.dataset.bookingId;
        const bookingCode = event.currentTarget.dataset.bookingCode;

        if (confirm(`Are you sure you want to cancel booking #${bookingCode}?`)) {
            const form = document.querySelector(`#cancel-form-${bookingId}`);
            if (form) {
                form.submit();
            }
        }
    }

    filterBookings(searchTerm) {
        const rows = document.querySelectorAll('#upcoming-bookings-table tbody tr');
        const term = searchTerm.toLowerCase();

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    }

    setupAutoRefresh() {
        // Auto-refresh if there are pending bookings
        const pendingCount = document.querySelectorAll('.badge.bg-label-warning').length;
        if (pendingCount > 0) {
            console.log(`Auto-refresh enabled for ${pendingCount} pending bookings`);

            // Refresh every 30 seconds
            setInterval(() => {
                this.refreshPendingStatus();
            }, 30000);
        }
    }

    async refreshPendingStatus() {
        try {
            // You can implement API call here to check pending status
            // Example: const response = await fetch('/api/guest/bookings/pending-status');

            console.log('Refreshing pending bookings...');
        } catch (error) {
            console.error('Error refreshing status:', error);
        }
    }

    async loadMoreRooms() {
        const loadMoreBtn = document.querySelector('#load-more-rooms');
        const currentCount = document.querySelectorAll('.room-item').length;

        try {
            // Show loading state
            loadMoreBtn.innerHTML = '<i class="bx bx-loader bx-spin"></i> Loading...';
            loadMoreBtn.disabled = true;

            // Simulate API call (replace with actual API)
            await new Promise(resolve => setTimeout(resolve, 1000));

            // For demo, we'll just show a message
            // In production, you would fetch from API
            loadMoreBtn.innerHTML = '<i class="bx bx-check"></i> No more rooms';
            loadMoreBtn.disabled = true;

        } catch (error) {
            console.error('Error loading more rooms:', error);
            loadMoreBtn.innerHTML = '<i class="bx bx-x"></i> Error';
            setTimeout(() => {
                loadMoreBtn.innerHTML = '<i class="bx bx-plus"></i> Load More Rooms';
                loadMoreBtn.disabled = false;
            }, 2000);
        }
    }

    async loadStats() {
        // Optional: Load stats via AJAX for real-time updates
        // try {
        //     const response = await fetch('/api/guest/dashboard/stats');
        //     if (response.ok) {
        //         const stats = await response.json();
        //         this.updateStats(stats);
        //     }
        // } catch (error) {
        //     console.error('Error loading stats:', error);
        // }
    }

    updateStats(stats) {
        // Update stats cards with new data
        Object.keys(stats).forEach(stat => {
            const element = document.querySelector(`#stat-${stat}`);
            if (element) {
                element.textContent = stats[stat];
            }
        });
    }

    // Utility functions
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }

    formatTime(timeString) {
        return timeString; // Add time formatting logic if needed
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const dashboard = new GuestDashboard();
    window.guestDashboard = dashboard; // Make available globally
});

// If using ES6 modules
// export default GuestDashboard;
