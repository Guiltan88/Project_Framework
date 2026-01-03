// Booking Show Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Booking Show Page Loaded');

    // Print Booking Details
    const printBtn = document.querySelector('[data-print-booking]');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            window.print();
        });
    }

    // Copy Booking Code
    const copyBtn = document.querySelector('[data-copy-code]');
    if (copyBtn) {
        copyBtn.addEventListener('click', function() {
            const bookingCode = document.querySelector('.booking-code')?.textContent || '{{ $booking->kode_booking }}';

            navigator.clipboard.writeText(bookingCode).then(() => {
                // Show success
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="bx bx-check me-1"></i> Copied!';

                setTimeout(() => {
                    this.innerHTML = originalText;
                }, 2000);
            });
        });
    }

    // Share Booking
    const shareBtn = document.querySelector('[data-share-booking]');
    if (shareBtn && navigator.share) {
        shareBtn.style.display = 'flex';

        shareBtn.addEventListener('click', async function() {
            try {
                await navigator.share({
                    title: 'Booking Details: #{{ $booking->kode_booking }}',
                    text: `Booking #{{ $booking->kode_booking }} - {{ $booking->room->nama_ruangan }} on {{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('d M Y') }}`,
                    url: window.location.href,
                });
            } catch (err) {
                console.log('Error sharing:', err);
            }
        });
    } else if (shareBtn) {
        shareBtn.style.display = 'none';
    }

    // Initialize Bootstrap components
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide success messages
    const alerts = document.querySelectorAll('.alert-auto-hide');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
