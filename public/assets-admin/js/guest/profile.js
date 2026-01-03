/**
 * Guest Profile JavaScript
 */
class GuestProfile {
    constructor() {
        this.init();
    }

    init() {
        this.initPhotoPreview();
        this.initModal();
        this.initToast();
        this.bindEvents();
    }

    initPhotoPreview() {
        const photoInput = document.getElementById('photo');
        const profilePreview = document.getElementById('profilePreview');

        if (photoInput && profilePreview) {
            photoInput.addEventListener('change', (e) => {
                if (e.target.files && e.target.files[0]) {
                    this.previewImage(e.target.files[0], profilePreview);
                }
            });
        }
    }

    previewImage(file, previewElement) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewElement.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    initModal() {
        // Initialize modal if exists
        const editModal = document.getElementById('editProfileModal');
        if (editModal) {
            this.modal = new bootstrap.Modal(editModal);
        }
    }

    initToast() {
        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    }

    bindEvents() {
        // Remove photo button
        const removePhotoBtn = document.getElementById('removePhotoBtn');
        if (removePhotoBtn) {
            removePhotoBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleRemovePhoto();
            });
        }

        // Form validation
        const profileForm = document.querySelector('#editProfileModal form');
        if (profileForm) {
            profileForm.addEventListener('submit', (e) => {
                if (!this.validateForm(profileForm)) {
                    e.preventDefault();
                }
            });
        }

        // Tab navigation
        document.querySelectorAll('.nav-pills .nav-link').forEach(link => {
            link.addEventListener('click', (e) => {
                this.handleNavClick(e);
            });
        });
    }

    async handleRemovePhoto() {
        if (confirm('Are you sure you want to remove your profile photo?')) {
            try {
                const response = await fetch('/api/guest/profile/remove-photo', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                });

                const data = await response.json();

                if (data.success) {
                    // Update preview images
                    const previews = document.querySelectorAll('#profilePreview, .profile-photo');
                    previews.forEach(img => {
                        img.src = data.avatar_url;
                    });

                    // Show success message
                    this.showMessage('Photo removed successfully!', 'success');

                    // Hide remove button
                    const removeBtn = document.getElementById('removePhotoBtn');
                    if (removeBtn) {
                        removeBtn.style.display = 'none';
                    }
                } else {
                    this.showMessage(data.message || 'Failed to remove photo', 'error');
                }
            } catch (error) {
                console.error('Error removing photo:', error);
                this.showMessage('Error removing photo. Please try again.', 'error');
            }
        }
    }

    validateForm(form) {
        const name = form.querySelector('[name="name"]');
        const email = form.querySelector('[name="email"]');
        const password = form.querySelector('[name="password"]');
        const passwordConfirm = form.querySelector('[name="password_confirmation"]');

        let isValid = true;

        // Clear previous errors
        this.clearErrors(form);

        // Validate name
        if (!name.value.trim()) {
            this.showError(name, 'Name is required');
            isValid = false;
        }

        // Validate email
        if (!email.value.trim()) {
            this.showError(email, 'Email is required');
            isValid = false;
        } else if (!this.isValidEmail(email.value)) {
            this.showError(email, 'Please enter a valid email address');
            isValid = false;
        }

        // Validate password
        if (password.value && password.value.length < 8) {
            this.showError(password, 'Password must be at least 8 characters');
            isValid = false;
        }

        // Validate password confirmation
        if (password.value && password.value !== passwordConfirm.value) {
            this.showError(passwordConfirm, 'Passwords do not match');
            isValid = false;
        }

        return isValid;
    }

    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    showError(element, message) {
        element.classList.add('is-invalid');

        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;

        element.parentNode.appendChild(errorDiv);
    }

    clearErrors(form) {
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        form.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });
    }

    handleNavClick(event) {
        // Add active class to clicked nav item
        const navItems = document.querySelectorAll('.nav-pills .nav-link');
        navItems.forEach(item => {
            item.classList.remove('active');
        });

        event.currentTarget.classList.add('active');
    }

    showMessage(message, type = 'success') {
        // Create toast element
        const toastContainer = document.querySelector('.toast-container') || this.createToastContainer();

        const toastId = 'toast-' + Date.now();
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bx bx-${type === 'success' ? 'check-circle' : 'x-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);

        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        // Remove toast after hide
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
        return container;
    }

    // Utility function to format date
    formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        };
        return new Date(dateString).toLocaleDateString('en-US', options);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const profile = new GuestProfile();
    window.guestProfile = profile; // Make available globally
});

// If using ES6 modules
// export default GuestProfile;
