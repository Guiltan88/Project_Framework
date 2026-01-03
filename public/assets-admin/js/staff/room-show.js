// staff/room-show.js - Room Detail Page Functionality

class StaffRoomShow {
    constructor() {
        this.init();
    }

    init() {
        this.setupImageModal();
        this.initializeTooltips();
        this.setupBookingActions();
        this.setupCopyRoomCode();
    }

    /**
     * Setup image modal for room image
     */
    setupImageModal() {
        const roomImage = document.querySelector('.room-main-image');
        const imageModal = document.getElementById('imageModal');

        if (roomImage && imageModal) {
            // Make image clickable
            roomImage.addEventListener('click', () => {
                const modalImage = document.getElementById('modalImage');
                const modalTitle = document.getElementById('imageModalLabel');

                if (modalImage && modalTitle) {
                    modalImage.src = roomImage.src;
                    modalImage.alt = roomImage.alt;
                    modalTitle.textContent = roomImage.alt;

                    const modal = new bootstrap.Modal(imageModal);
                    modal.show();
                }
            });

            // Add cursor pointer to indicate clickability
            roomImage.style.cursor = 'zoom-in';
        }
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
     * Setup booking actions
     */
    setupBookingActions() {
        const bookRoomBtn = document.querySelector('.action-btn[href*="bookings/create"]');

        if (bookRoomBtn) {
            // Check if room is available for booking
            const statusBadge = document.querySelector('.status-badge-large');
            if (statusBadge && statusBadge.textContent.includes('Terisi')) {
                bookRoomBtn.disabled = true;
                bookRoomBtn.innerHTML = '<i class="bx bx-calendar-x me-1"></i> Ruangan Tidak Tersedia';
                bookRoomBtn.classList.remove('btn-success');
                bookRoomBtn.classList.add('btn-secondary');
                bookRoomBtn.style.cursor = 'not-allowed';
            }
        }
    }

    /**
     * Setup copy room code functionality
     */
    setupCopyRoomCode() {
        const roomCodeElement = document.querySelector('.room-code');
        const copyBtn = document.getElementById('copyRoomCodeBtn');

        if (roomCodeElement && copyBtn) {
            copyBtn.addEventListener('click', () => {
                const roomCode = roomCodeElement.textContent;

                // Copy to clipboard
                navigator.clipboard.writeText(roomCode).then(() => {
                    // Show success feedback
                    const originalText = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<i class="bx bx-check me-1"></i> Tersalin!';
                    copyBtn.classList.remove('btn-outline-secondary');
                    copyBtn.classList.add('btn-success');

                    // Revert after 2 seconds
                    setTimeout(() => {
                        copyBtn.innerHTML = originalText;
                        copyBtn.classList.remove('btn-success');
                        copyBtn.classList.add('btn-outline-secondary');
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    alert('Gagal menyalin kode ruangan');
                });
            });
        }
    }

    /**
     * Share room functionality
     */
    setupShareRoom() {
        const shareBtn = document.getElementById('shareRoomBtn');

        if (shareBtn && navigator.share) {
            shareBtn.style.display = 'inline-flex';

            shareBtn.addEventListener('click', async () => {
                try {
                    await navigator.share({
                        title: document.querySelector('.room-name').textContent,
                        text: `Lihat ruangan ${document.querySelector('.room-name').textContent}`,
                        url: window.location.href,
                    });
                } catch (err) {
                    console.log('Error sharing:', err);
                }
            });
        } else if (shareBtn) {
            // Fallback for browsers that don't support Web Share API
            shareBtn.addEventListener('click', () => {
                const roomName = document.querySelector('.room-name').textContent;
                const roomUrl = window.location.href;

                // Copy URL to clipboard
                navigator.clipboard.writeText(`${roomName}\n${roomUrl}`).then(() => {
                    alert('Link ruangan berhasil disalin ke clipboard!');
                });
            });
        }
    }

    /**
     * Print room information
     */
    setupPrintRoomInfo() {
        const printBtn = document.getElementById('printRoomBtn');

        if (printBtn) {
            printBtn.addEventListener('click', () => {
                // Open print dialog
                window.print();
            });
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new StaffRoomShow();
});

// Add print styles
const printStyles = `
    @media print {
        .navbar, .sidebar, .footer, .actions-card, .btn {
            display: none !important;
        }

        .room-info-card, .quick-stats-card, .facilities-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
            break-inside: avoid;
        }

        .container {
            max-width: 100% !important;
            padding: 0 !important;
        }

        body {
            background: white !important;
            color: black !important;
        }

        .badge {
            border: 1px solid #333 !important;
            background: white !important;
            color: black !important;
        }

        .room-main-image {
            max-height: 300px !important;
        }
    }
`;

// Add print styles to document
const styleSheet = document.createElement("style");
styleSheet.type = "text/css";
styleSheet.innerText = printStyles;
document.head.appendChild(styleSheet);
