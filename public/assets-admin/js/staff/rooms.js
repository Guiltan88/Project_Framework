// staff/rooms.js - Index Page Functionality

class StaffRoomsIndex {
    constructor() {
        this.init();
    }

    init() {
        this.setupSearch();
        this.setupFilters();
        this.setupRoomCardInteractions();
        this.initializeTooltips();
    }

    /**
     * Setup search functionality
     */
    setupSearch() {
        const searchForm = document.querySelector('.search-form-inline');
        const searchInput = document.querySelector('.search-input');
        const clearBtn = document.querySelector('.clear-btn');

        if (searchForm && searchInput) {
            // Debounce search
            let debounceTimer;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    if (e.target.value.length >= 2 || e.target.value.length === 0) {
                        searchForm.submit();
                    }
                }, 500);
            });

            // Clear search
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    searchForm.submit();
                });
            }

            // Prevent form submission on enter for real-time search
            searchForm.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        }
    }

    /**
     * Setup filter functionality
     */
    setupFilters() {
        const filterSelects = document.querySelectorAll('.filter-select');

        filterSelects.forEach(select => {
            select.addEventListener('change', () => {
                // Submit the parent form when filter changes
                const form = select.closest('form');
                if (form) {
                    form.submit();
                }
            });
        });
    }

    /**
     * Setup room card interactions
     */
    setupRoomCardInteractions() {
        const roomCards = document.querySelectorAll('.room-card-staff');

        roomCards.forEach(card => {
            // Click on card body goes to detail page
            const cardBody = card.querySelector('.card-body');
            const detailLink = card.querySelector('a[href*="show"]');

            if (cardBody && detailLink) {
                cardBody.addEventListener('click', (e) => {
                    // Don't trigger if clicking on links or buttons
                    if (!e.target.closest('a') && !e.target.closest('button')) {
                        window.location.href = detailLink.href;
                    }
                });

                cardBody.style.cursor = 'pointer';
            }

            // Add hover effect
            card.addEventListener('mouseenter', () => {
                card.classList.add('card-hovered');
            });

            card.addEventListener('mouseleave', () => {
                card.classList.remove('card-hovered');
            });
        });
    }

    /**
     * Initialize Bootstrap tooltips
     */
    initializeTooltips() {
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );

        tooltipTriggerList.map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover',
                placement: 'top'
            });
        });
    }

    /**
     * Show loading state during page transitions
     */
    showLoading() {
        const roomsGrid = document.querySelector('.row[class*="rooms-grid"]');
        if (roomsGrid) {
            roomsGrid.style.opacity = '0.5';
            roomsGrid.style.transition = 'opacity 0.3s ease';

            // Add loading spinner
            const loadingSpinner = document.createElement('div');
            loadingSpinner.className = 'rooms-loading-spinner';
            loadingSpinner.innerHTML = `
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
            loadingSpinner.style.cssText = `
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 1000;
            `;

            roomsGrid.style.position = 'relative';
            roomsGrid.appendChild(loadingSpinner);
        }
    }

    /**
     * Hide loading state
     */
    hideLoading() {
        const roomsGrid = document.querySelector('.row[class*="rooms-grid"]');
        if (roomsGrid) {
            roomsGrid.style.opacity = '1';
            const spinner = roomsGrid.querySelector('.rooms-loading-spinner');
            if (spinner) {
                spinner.remove();
            }
        }
    }

    /**
     * Setup pagination click events
     */
    setupPaginationEvents() {
        const paginationLinks = document.querySelectorAll('.pagination-staff a');

        paginationLinks.forEach(link => {
            link.addEventListener('click', () => {
                this.showLoading();
            });
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const roomsIndex = new StaffRoomsIndex();

    // Setup pagination events
    roomsIndex.setupPaginationEvents();

    // Add CSS for loading spinner
    const style = document.createElement('style');
    style.textContent = `
        .rooms-loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }

        .card-hovered {
            box-shadow: 0 8px 25px rgba(105, 108, 255, 0.15) !important;
            border-color: #696cff !important;
        }

        .room-card-staff .card-body {
            transition: background-color 0.2s ease;
        }

        .room-card-staff:hover .card-body {
            background-color: #f8fafc;
        }
    `;
    document.head.appendChild(style);
});

// Handle page transitions
document.addEventListener('readystatechange', () => {
    if (document.readyState === 'complete') {
        const roomsIndex = new StaffRoomsIndex();
        roomsIndex.hideLoading();
    }
});
