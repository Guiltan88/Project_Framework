// public/assets-admin/js/admin/buildings.js

class BuildingsPage {
    constructor() {
        this.init();
    }

    init() {
        this.initializeTooltips();
        this.setupSearch();
        this.setupImagePreview();
        this.setupDeleteConfirmations();
    }

    /**
     * Initialize Bootstrap tooltips
     */
    initializeTooltips() {
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );

        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover'
            });
        });
    }

    /**
     * Setup search functionality
     */
    setupSearch() {
        const searchInput = document.querySelector('input[name="search"]');
        const searchForm = document.querySelector('form[method="GET"]');

        if (searchInput && searchForm) {
            // Debounce search to prevent too many requests
            let debounceTimer;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    if (e.target.value.length >= 2 || e.target.value.length === 0) {
                        searchForm.submit();
                    }
                }, 500);
            });

            // Clear search button
            const clearBtn = document.querySelector('.clear-search-btn');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    searchForm.submit();
                });
            }
        }
    }

    /**
     * Setup image preview functionality
     */
    setupImagePreview() {
        const imageElements = document.querySelectorAll('.building-image');
        const imageModal = document.getElementById('imageModal');

        if (imageModal) {
            this.imageModal = new bootstrap.Modal(imageModal);
        }

        imageElements.forEach(img => {
            img.addEventListener('click', (e) => {
                const imageSrc = e.target.src;
                const buildingName = e.target.dataset.buildingName ||
                                   e.target.alt ||
                                   'Gambar Gedung';

                this.showImageModal(imageSrc, buildingName);
            });
        });
    }

    /**
     * Show image in modal
     */
    showImageModal(imageSrc, title) {
        const modalImage = document.getElementById('modalImage');
        const modalTitle = document.getElementById('imageModalLabel');

        if (modalImage && modalTitle && this.imageModal) {
            modalImage.src = imageSrc;
            modalImage.alt = title;
            modalTitle.textContent = title;
            this.imageModal.show();
        }
    }

    /**
     * Setup delete confirmations
     */
    setupDeleteConfirmations() {
        const deleteForms = document.querySelectorAll('form[action*="destroy"]');

        deleteForms.forEach(form => {
            const deleteBtn = form.querySelector('button[type="submit"]');

            if (deleteBtn) {
                deleteBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.confirmDelete(form);
                });
            }
        });
    }

    /**
     * Confirm delete action
     */
    confirmDelete(form) {
        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
            form.submit();
        }
    }

    /**
     * Show loading state
     */
    showLoadingState() {
        const tableBody = document.querySelector('tbody');
        if (tableBody) {
            tableBody.style.opacity = '0.6';
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new BuildingsPage();
});
