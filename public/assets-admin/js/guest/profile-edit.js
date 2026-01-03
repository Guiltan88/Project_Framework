/**
 * Guest Profile Edit JavaScript
 */

class GuestProfileEdit {
    constructor() {
        this.init();
    }

    init() {
        this.initPhotoUpload();
        this.initPasswordToggle();
        this.initFormValidation();
        this.initAutoHideAlerts();
        this.initToast();
        this.initResetConfirmation();
    }

    /**
     * Initialize photo upload functionality
     */
    initPhotoUpload() {
        const photoUpload = document.getElementById('photoUpload');
        const cameraBtn = document.querySelector('.btn-sm.btn-primary.position-absolute');
        const avatar = document.getElementById('editProfileAvatar');
        const photoInput = document.getElementById('photoInput');

        if (photoUpload) {
            photoUpload.addEventListener('change', (e) => {
                this.handlePhotoUpload(e, avatar, photoInput);
            });
        }

        if (cameraBtn) {
            cameraBtn.addEventListener('click', () => {
                photoUpload?.click();
            });
        }
    }

    /**
     * Handle photo upload with validation
     */
    handlePhotoUpload(e, avatar, photoInput) {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            // File size validation
            if (file.size > maxSize) {
                this.showAlert('File size exceeds 2MB limit. Please choose a smaller file.', 'error');
                e.target.value = '';
                return;
            }

            // File type validation
            if (!validTypes.includes(file.type)) {
                this.showAlert('Invalid file type. Please upload JPG, PNG, GIF, or WebP image.', 'error');
                e.target.value = '';
                return;
            }

            // Preview image
            const reader = new FileReader();
            reader.onload = (e) => {
                if (avatar) {
                    avatar.src = e.target.result;
                    avatar.onerror = () => {
                        avatar.src = this.getFallbackAvatarUrl();
                    };
                }
            };
            reader.readAsDataURL(file);

            // Set file to hidden input for form submission
            if (photoInput) {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                photoInput.files = dataTransfer.files;
            }
        }
    }

    /**
     * Initialize password toggle functionality
     */
    initPasswordToggle() {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const toggleIcon = document.getElementById('passwordToggleIcon');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';

                passwordInput.type = isPassword ? 'text' : 'password';
                if (confirmInput) confirmInput.type = isPassword ? 'text' : 'password';

                if (toggleIcon) {
                    toggleIcon.className = isPassword ? 'bx bx-show' : 'bx bx-hide';
                }

                // Update button tooltip
                toggleBtn.setAttribute('title', isPassword ? 'Hide password' : 'Show password');
            });
        }
    }

    /**
     * Initialize form validation
     */
    initFormValidation() {
        const form = document.getElementById('editProfileForm');

        if (form) {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm()) {
                    e.preventDefault();
                }
            });
        }
    }

    /**
     * Validate form before submission
     */
    validateForm() {
        const password = document.getElementById('password')?.value;
        const confirmPassword = document.getElementById('password_confirmation')?.value;
        const photoInput = document.getElementById('photoInput');

        // Password validation
        if (password) {
            if (password.length < 6) {
                this.showAlert('Password must be at least 6 characters long!', 'error');
                document.getElementById('password')?.focus();
                return false;
            }

            if (password !== confirmPassword) {
                this.showAlert('Passwords do not match!', 'error');
                document.getElementById('password_confirmation')?.focus();
                return false;
            }
        }

        // Photo validation
        if (photoInput && photoInput.files.length > 0) {
            const file = photoInput.files[0];
            const maxSize = 2 * 1024 * 1024;

            if (file.size > maxSize) {
                this.showAlert('File size exceeds 2MB limit.', 'error');
                return false;
            }
        }

        return true;
    }

    /**
     * Initialize auto-hide alerts
     */
    initAutoHideAlerts() {
        const alerts = document.querySelectorAll('.alert[data-bs-delay]');

        alerts.forEach(alert => {
            const delay = alert.getAttribute('data-bs-delay') || 5000;

            setTimeout(() => {
                const closeBtn = alert.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.click();
                }
            }, parseInt(delay));
        });
    }

    /**
     * Initialize Bootstrap toast
     */
    initToast() {
        const toastEl = document.querySelector('.toast');

        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    }

    /**
     * Initialize reset confirmation
     */
    initResetConfirmation() {
        const resetBtn = document.querySelector('button[type="reset"]');

        if (resetBtn) {
            resetBtn.addEventListener('click', (e) => {
                if (!confirm('Are you sure you want to reset all changes?')) {
                    e.preventDefault();
                }
            });
        }
    }

    /**
     * Show alert message
     */
    showAlert(message, type = 'error') {
        alert(message); // Simple alert for now
        // You can replace this with a more sophisticated notification system
    }

    /**
     * Get fallback avatar URL
     */
    getFallbackAvatarUrl() {
        const userName = document.querySelector('meta[name="user-name"]')?.getAttribute('content') || '';
        return `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=696cff&color=fff&size=150`;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new GuestProfileEdit();
});
