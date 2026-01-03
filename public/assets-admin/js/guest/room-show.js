// Staff Room Show JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Staff Room Show Page Loaded');

    // Image Modal
    const roomImage = document.querySelector('.room-image-container img');
    if (roomImage) {
        roomImage.style.cursor = 'pointer';
        roomImage.addEventListener('click', function() {
            const modal = `
                <div class="modal fade" id="imageModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Room Image</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body p-0">
                                <img src="${this.src}"
                                     alt="${this.alt}"
                                     class="img-fluid w-100">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modal);
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();

            document.getElementById('imageModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        });
    }

    // Facilities Scroll Enhancement
    const facilitiesScroll = document.querySelector('.facilities-scroll-wrapper');
    if (facilitiesScroll) {
        // Add scroll buttons for better UX
        const container = document.querySelector('.facilities-container');

        // Check if there's overflow
        function checkOverflow() {
            return facilitiesScroll.scrollWidth > facilitiesScroll.clientWidth;
        }

        if (checkOverflow()) {
            // Add navigation buttons
            const prevBtn = document.createElement('button');
            const nextBtn = document.createElement('button');

            prevBtn.className = 'facility-scroll-btn facility-scroll-prev';
            nextBtn.className = 'facility-scroll-btn facility-scroll-next';

            prevBtn.innerHTML = '<i class="bx bx-chevron-left"></i>';
            nextBtn.innerHTML = '<i class="bx bx-chevron-right"></i>';

            container.style.position = 'relative';
            container.appendChild(prevBtn);
            container.appendChild(nextBtn);

            const scrollAmount = 200;

            prevBtn.addEventListener('click', () => {
                facilitiesScroll.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });

            nextBtn.addEventListener('click', () => {
                facilitiesScroll.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });

            // Update button visibility based on scroll position
            facilitiesScroll.addEventListener('scroll', () => {
                prevBtn.style.opacity = facilitiesScroll.scrollLeft > 0 ? '1' : '0.5';
                nextBtn.style.opacity =
                    facilitiesScroll.scrollLeft < (facilitiesScroll.scrollWidth - facilitiesScroll.clientWidth)
                    ? '1' : '0.5';
            });

            // Initial state
            prevBtn.style.opacity = '0.5';
        }
    }

    // Copy Room Information
    const copyInfoBtn = document.createElement('button');
    copyInfoBtn.className = 'btn btn-sm btn-outline-secondary ms-2';
    copyInfoBtn.innerHTML = '<i class="bx bx-copy me-1"></i> Copy Info';

    const roomHeader = document.querySelector('.card-header .badge');
    if (roomHeader) {
        roomHeader.parentNode.appendChild(copyInfoBtn);

        copyInfoBtn.addEventListener('click', function() {
            const roomInfo = {
                name: document.querySelector('.room-info .room-name')?.textContent ||
                      document.querySelector('h4.card-title')?.textContent,
                building: document.querySelector('.room-info .building-name')?.textContent ||
                         document.querySelector('dd')?.textContent,
                status: document.querySelector('.status-badge-large')?.textContent ||
                       document.querySelector('.card-header .badge')?.textContent,
                capacity: document.querySelector('[data-capacity]')?.textContent ||
                         document.querySelectorAll('dd')[2]?.textContent
            };

            const textToCopy = `
Room: ${roomInfo.name}
Building: ${roomInfo.building}
Status: ${roomInfo.status}
Capacity: ${roomInfo.capacity}
URL: ${window.location.href}
            `.trim();

            navigator.clipboard.writeText(textToCopy).then(() => {
                // Show feedback
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="bx bx-check me-1"></i> Copied!';
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-success');

                setTimeout(() => {
                    this.innerHTML = originalHTML;
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-secondary');
                }, 2000);
            });
        });
    }

    // Quick Booking Modal
    const bookBtn = document.querySelector('.btn-success[href*="bookings.create"]');
    if (bookBtn) {
        bookBtn.addEventListener('click', function(e) {
            // Optional: Add confirmation or additional logic
            console.log('Booking room:', this.href);
        });
    }

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add facility item click handler
    const facilityItems = document.querySelectorAll('.facility-item');
    facilityItems.forEach(item => {
        item.addEventListener('click', function() {
            const facilityName = this.querySelector('.facility-name').textContent;
            console.log('Facility clicked:', facilityName);

            // Optional: Show facility details in modal
            // showFacilityModal(facilityName);
        });
    });
});

// Helper function to show toast messages
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${type} border-0`;
    toast.setAttribute('role', 'alert');

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="bx ${type === 'success' ? 'bx-check-circle' : 'bx-info-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    // Remove existing toasts
    document.querySelector('.toast-container')?.remove();

    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.appendChild(toast);
    document.body.appendChild(container);

    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', function() {
        container.remove();
    });
}

// Facility modal function (optional)
function showFacilityModal(facilityName) {
    const modal = `
        <div class="modal fade" id="facilityModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${facilityName}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Detailed information about ${facilityName} will be displayed here.</p>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modal);
    new bootstrap.Modal(document.getElementById('facilityModal')).show();

    document.getElementById('facilityModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}
