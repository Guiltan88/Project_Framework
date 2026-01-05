// assets/js/staff/rooms.js

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            boundary: document.body,
            animation: true
        });
    });

    // Image lazy loading dengan efek loading
    const roomImages = document.querySelectorAll('.room-image-container-staff img');

    roomImages.forEach(img => {
        // Tambah kelas loaded ketika gambar selesai load
        if (img.complete) {
            img.classList.add('loaded');
        } else {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
        }

        // Tambah event click untuk detail
        img.addEventListener('click', function() {
            const roomCard = this.closest('.room-card-staff');
            const detailLink = roomCard.querySelector('.btn-outline-primary');
            if (detailLink) {
                window.location.href = detailLink.href;
            }
        });

        img.style.cursor = 'pointer';
    });

    // Filter collapse animation
    const filterToggle = document.querySelector('[data-bs-target="#filterCollapse"]');
    const filterCollapse = document.getElementById('filterCollapse');

    if (filterToggle && filterCollapse) {
        // Sembunyikan filter secara default di mobile
        if (window.innerWidth < 768) {
            filterCollapse.classList.remove('show');
        }

        filterToggle.addEventListener('click', function() {
            const isExpanded = filterCollapse.classList.contains('show');
            const icon = this.querySelector('i');

            if (isExpanded) {
                // Ubah icon menjadi filter
                icon.className = 'bx bx-filter-alt me-1';
            } else {
                // Ubah icon menjadi filter-open
                icon.className = 'bx bx-filter me-1';
            }
        });
    }

    // Auto-hide alerts dengan animasi
    const alerts = document.querySelectorAll('.rooms-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'all 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';

            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        }, 5000);
    });

    // Search input dengan debounce
    const searchInput = document.querySelector('.search-input');
    let searchTimeout;

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);

            // Tampilkan efek loading
            const originalPlaceholder = this.placeholder;
            this.placeholder = 'Mencari...';

            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    // Reset placeholder
                    this.placeholder = originalPlaceholder;

                    // Submit form dengan animasi
                    const form = this.closest('form');
                    const submitBtn = form.querySelector('button[type="submit"]');

                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Mencari...';
                        submitBtn.disabled = true;

                        setTimeout(() => {
                            form.submit();
                        }, 300);
                    } else {
                        form.submit();
                    }
                } else {
                    this.placeholder = originalPlaceholder;
                }
            }, 800);
        });
    }

    // Filter select dengan animasi
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Tampilkan animasi loading
            const form = this.closest('form');
            const submitBtn = form.querySelector('button[type="submit"]');

            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Memfilter...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    form.submit();
                }, 500);
            } else {
                form.submit();
            }
        });
    });

    // Smooth scroll ke top ketika filter diterapkan
    const filterForm = document.querySelector('.filter-form-grid');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Scroll ke top dengan smooth
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Submit form setelah scroll
            setTimeout(() => {
                this.submit();
            }, 500);
        });
    }

    // Parallax effect untuk room cards
    const roomCards = document.querySelectorAll('.room-card-staff');

    roomCards.forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const cardRect = this.getBoundingClientRect();
            const x = e.clientX - cardRect.left;
            const y = e.clientY - cardRect.top;

            const centerX = cardRect.width / 2;
            const centerY = cardRect.height / 2;

            const rotateY = (x - centerX) / 25;
            const rotateX = (centerY - y) / 25;

            this.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(-8px)';
        });
    });

    // Animasi untuk capacity badge
    const capacityBadges = document.querySelectorAll('.capacity-badge');
    capacityBadges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.1) rotate(5deg)';
        });

        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0)';
        });
    });

    // Copy room name on double click
    const roomTitles = document.querySelectorAll('.room-title');
    roomTitles.forEach(title => {
        title.addEventListener('dblclick', function() {
            const text = this.textContent;
            navigator.clipboard.writeText(text).then(() => {
                // Tampilkan feedback
                const originalText = this.textContent;
                this.textContent = '✓ Disalin!';
                this.style.color = '#43e97b';

                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.color = '';
                }, 1500);
            });
        });

        title.style.cursor = 'pointer';
        title.title = 'Double click to copy room name';
    });
});

// Fitur dark mode toggle (opsional)
function toggleDarkMode() {
    document.body.classList.toggle('dark-mode');

    // Simpan preference ke localStorage
    const isDarkMode = document.body.classList.contains('dark-mode');
    localStorage.setItem('darkMode', isDarkMode);
}

// Cek preferensi dark mode saat load
window.addEventListener('load', function() {
    const darkModePreference = localStorage.getItem('darkMode');
    if (darkModePreference === 'true') {
        document.body.classList.add('dark-mode');
    }
});
