/**
 * Guest Rooms JavaScript
 */
class GuestRooms {
    constructor() {
        this.init();
    }

    init() {
        this.initRoomCards();
        this.initFilters();
        this.initSorting();
        this.initTooltips();
        this.bindEvents();
    }

    initRoomCards() {
        // Make room cards clickable
        document.querySelectorAll('.room-card').forEach(card => {
            card.addEventListener('click', (e) => this.handleRoomCardClick(e, card));
        });

        // Add animation to cards
        this.animateCards();
    }

    handleRoomCardClick(event, card) {
        // Don't trigger if clicking on buttons or links
        if (event.target.tagName === 'BUTTON' ||
            event.target.tagName === 'A' ||
            event.target.closest('button') ||
            event.target.closest('a')) {
            return;
        }

        // Get the details link
        const detailsLink = card.querySelector('a[href*="/rooms/"]');
        if (detailsLink) {
            window.location.href = detailsLink.href;
        }
    }

    animateCards() {
        // Add stagger animation to cards
        const cards = document.querySelectorAll('.room-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    }

    initFilters() {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            // Real-time filter for search
            const searchInput = filterForm.querySelector('input[name="search"]');
            if (searchInput) {
                let searchTimeout;
                searchInput.addEventListener('input', (e) => {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        if (e.target.value.length > 2 || e.target.value.length === 0) {
                            this.submitFilterForm();
                        }
                    }, 500);
                });
            }

            // Reset filters
            const resetBtn = document.getElementById('resetFilters');
            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    filterForm.reset();
                    this.submitFilterForm();
                });
            }
        }
    }

    initSorting() {
        const sortSelect = document.querySelector('select[onchange*="sort"]');
        if (sortSelect) {
            // Remove inline onchange and add event listener
            sortSelect.removeAttribute('onchange');
            sortSelect.addEventListener('change', (e) => {
                this.updateSorting(e.target.value);
            });
        }

        // Items per page
        const perPageSelect = document.querySelector('select[onchange*="updatePerPage"]');
        if (perPageSelect) {
            perPageSelect.removeAttribute('onchange');
            perPageSelect.addEventListener('change', (e) => {
                this.updatePerPage(e.target.value);
            });
        }
    }

    initTooltips() {
        // Initialize Bootstrap tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(el => {
            new bootstrap.Tooltip(el);
        });
    }

    bindEvents() {
        // Quick book buttons
        document.querySelectorAll('.quick-book-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                if (btn.disabled) {
                    e.preventDefault();
                    this.showNotAvailableMessage();
                }
            });
        });

        // Load more rooms (infinite scroll)
        if (document.querySelector('.pagination')) {
            this.setupInfiniteScroll();
        }
    }

    submitFilterForm() {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.submit();
        }
    }

    updateSorting(sortValue) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortValue);
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url.toString();
    }

    updatePerPage(perPage) {
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1); // Reset to first page
        window.location.href = url.toString();
    }

    showNotAvailableMessage() {
        this.showMessage('This room is not available for booking at the moment.', 'warning');
    }

    setupInfiniteScroll() {
        // Only setup if there are more pages
        const pagination = document.querySelector('.pagination');
        if (!pagination || !pagination.querySelector('.page-item.active')) {
            return;
        }

        const currentPage = parseInt(pagination.querySelector('.page-item.active .page-link').textContent);
        const totalPages = parseInt(pagination.querySelectorAll('.page-item').length - 2); // minus prev/next

        if (currentPage < totalPages) {
            window.addEventListener('scroll', () => {
                if (this.isScrolledToBottom()) {
                    this.loadNextPage();
                }
            });
        }
    }

    isScrolledToBottom() {
        return window.innerHeight + window.scrollY >= document.body.offsetHeight - 500;
    }

    async loadNextPage() {
        const currentUrl = new URL(window.location.href);
        const currentPage = parseInt(currentUrl.searchParams.get('page') || 1);
        const nextPage = currentPage + 1;

        // Prevent multiple requests
        if (this.isLoading) return;
        this.isLoading = true;

        try {
            // Show loading indicator
            this.showLoadingIndicator();

            currentUrl.searchParams.set('page', nextPage);
            const response = await fetch(currentUrl.toString());
            const html = await response.text();

            // Parse the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newRooms = doc.querySelectorAll('.room-card');
            const newPagination = doc.querySelector('.pagination');

            // Append new rooms
            const roomsContainer = document.querySelector('.row');
            newRooms.forEach(room => {
                roomsContainer.appendChild(room);
            });

            // Update pagination
            if (newPagination) {
                const oldPagination = document.querySelector('.pagination');
                if (oldPagination) {
                    oldPagination.parentNode.replaceChild(newPagination, oldPagination);
                }
            }

            // Re-initialize for new cards
            this.initRoomCards();
            this.initTooltips();

        } catch (error) {
            console.error('Error loading next page:', error);
            this.showMessage('Error loading more rooms', 'error');
        } finally {
            this.isLoading = false;
            this.hideLoadingIndicator();
        }
    }

    showLoadingIndicator() {
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'loading-indicator';
        loadingDiv.className = 'text-center my-4';
        loadingDiv.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading more rooms...</p>
        `;

        const paginationContainer = document.querySelector('.row.mt-4');
        if (paginationContainer) {
            paginationContainer.parentNode.insertBefore(loadingDiv, paginationContainer);
        }
    }

    hideLoadingIndicator() {
        const loadingDiv = document.getElementById('loading-indicator');
        if (loadingDiv) {
            loadingDiv.remove();
        }
    }

    showMessage(message, type = 'info') {
        // Remove existing messages
        const existingMessages = document.querySelectorAll('.alert-message');
        existingMessages.forEach(msg => msg.remove());

        // Create message element
        const messageDiv = document.createElement('div');
        messageDiv.className = `alert alert-${type} alert-dismissible fade show alert-message`;
        messageDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Insert after filters
        const filtersCard = document.querySelector('.filter-card');
        if (filtersCard) {
            filtersCard.parentNode.insertBefore(messageDiv, filtersCard.nextSibling);
        } else {
            document.querySelector('.guest-rooms').prepend(messageDiv);
        }

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 5000);
    }

    // Utility function to get status color
    getStatusColor(status) {
        const colors = {
            'tersedia': 'success',
            'terisi': 'danger',
            'maintenance': 'warning'
        };
        return colors[status] || 'secondary';
    }

    // Utility function to format number
    formatNumber(num) {
        return new Intl.NumberFormat().format(num);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const guestRooms = new GuestRooms();
    window.guestRooms = guestRooms;
});
