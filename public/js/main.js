/**
 * Main JavaScript file for Virtual Career Counseling Platform
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize dark mode
    initializeDarkMode();
    
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize form validation
    initializeFormValidation();
    
    // Initialize date/time pickers
    initializeDateTimePickers();
    
    // Initialize file uploads
    initializeFileUploads();
    
    // Auto-dismiss alerts
    autoDismissAlerts();
    
    // Initialize smooth scrolling
    initializeSmoothScrolling();
    
    // Initialize slideshow
    initializeSlideshow();
});

/**
 * Initialize Dark Mode Toggle
 */
function initializeDarkMode() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const darkModeIcon = document.getElementById('darkModeIcon');
    const body = document.body;
    
    if (!darkModeToggle || !darkModeIcon) return;
    
    // Check localStorage for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    
    // Apply saved theme on page load
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
        darkModeIcon.classList.remove('fa-moon');
        darkModeIcon.classList.add('fa-sun');
    }
    
    // Toggle dark mode on button click
    darkModeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        // Update icon
        if (body.classList.contains('dark-mode')) {
            darkModeIcon.classList.remove('fa-moon');
            darkModeIcon.classList.add('fa-sun');
            localStorage.setItem('theme', 'dark');
        } else {
            darkModeIcon.classList.remove('fa-sun');
            darkModeIcon.classList.add('fa-moon');
            localStorage.setItem('theme', 'light');
        }
    });
}

/**
 * Initialize Bootstrap tooltips
 */
function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    // Password confirmation validation
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm_password');
    
    if (passwordField && confirmPasswordField) {
        confirmPasswordField.addEventListener('input', function() {
            if (this.value !== passwordField.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    // Real-time email validation
    const emailFields = document.querySelectorAll('input[type="email"]');
    emailFields.forEach(function(field) {
        field.addEventListener('blur', function() {
            if (this.value && !isValidEmail(this.value)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
}

/**
 * Initialize date and time pickers
 */
function initializeDateTimePickers() {
    // Set minimum date for appointment booking to today
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(function(input) {
        const today = new Date().toISOString().split('T')[0];
        input.setAttribute('min', today);
    });
    
    // Time slot availability checking
    const timeSelect = document.getElementById('appointment_time');
    const dateSelect = document.getElementById('appointment_date');
    const counselorSelect = document.getElementById('counselor_id');
    
    if (timeSelect && dateSelect && counselorSelect) {
        function checkAvailability() {
            if (dateSelect.value && counselorSelect.value) {
                // Here you would make an AJAX call to check availability
                // For now, we'll just enable/disable time slots
                updateTimeSlots();
            }
        }
        
        dateSelect.addEventListener('change', checkAvailability);
        counselorSelect.addEventListener('change', checkAvailability);
    }
}

/**
 * Update available time slots
 */
function updateTimeSlots() {
    const timeSelect = document.getElementById('appointment_time');
    if (!timeSelect) return;
    
    // Clear existing options
    timeSelect.innerHTML = '<option value="">Select a time</option>';
    
    // Add time slots (9 AM to 5 PM)
    const timeSlots = [
        '09:00', '10:00', '11:00', '12:00',
        '13:00', '14:00', '15:00', '16:00', '17:00'
    ];
    
    timeSlots.forEach(function(time) {
        const option = document.createElement('option');
        option.value = time;
        option.textContent = formatTime(time);
        timeSelect.appendChild(option);
    });
}

/**
 * Initialize file upload handling
 */
function initializeFileUploads() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            const files = e.target.files;
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword'];
            
            for (let file of files) {
                if (file.size > maxSize) {
                    alert('File size must be less than 5MB');
                    this.value = '';
                    return;
                }
                
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid file type (JPEG, PNG, PDF, DOC)');
                    this.value = '';
                    return;
                }
            }
            
            // Show file preview if it's an image
            if (files[0] && files[0].type.startsWith('image/')) {
                showImagePreview(files[0], input);
            }
        });
    });
}

/**
 * Show image preview
 */
function showImagePreview(file, input) {
    const reader = new FileReader();
    reader.onload = function(e) {
        let preview = input.parentNode.querySelector('.image-preview');
        if (!preview) {
            preview = document.createElement('img');
            preview.className = 'image-preview mt-2';
            preview.style.maxWidth = '200px';
            preview.style.maxHeight = '200px';
            preview.style.objectFit = 'cover';
            preview.style.borderRadius = '10px';
            input.parentNode.appendChild(preview);
        }
        preview.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

/**
 * Auto-dismiss alerts after 5 seconds
 */
function autoDismissAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.parentNode) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.remove();
                    }
                }, 300);
            }
        }, 5000);
    });
}

/**
 * Initialize smooth scrolling for anchor links
 */
function initializeSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/**
 * Utility Functions
 */

/**
 * Validate email address
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Format time from 24-hour to 12-hour format
 */
function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour12 = hours % 12 || 12;
    const ampm = hours < 12 ? 'AM' : 'PM';
    return `${hour12}:${minutes} ${ampm}`;
}

/**
 * Show loading spinner
 */
function showLoading(element) {
    const spinner = document.createElement('div');
    spinner.className = 'spinner-border spinner-border-sm me-2';
    spinner.setAttribute('role', 'status');
    element.insertBefore(spinner, element.firstChild);
    element.disabled = true;
}

/**
 * Hide loading spinner
 */
function hideLoading(element) {
    const spinner = element.querySelector('.spinner-border');
    if (spinner) {
        spinner.remove();
    }
    element.disabled = false;
}

/**
 * Show confirmation modal
 */
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

/**
 * AJAX helper function
 */
function makeAjaxRequest(url, method, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                callback(null, JSON.parse(xhr.responseText));
            } else {
                callback(new Error('Request failed: ' + xhr.status));
            }
        }
    };
    
    xhr.send(data ? JSON.stringify(data) : null);
}

/**
 * Format date for display
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

/**
 * Format date and time for display
 */
function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
}

/**
 * Copy text to clipboard
 */
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            showToast('Copied to clipboard!', 'success');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('Copied to clipboard!', 'success');
    }
}

/**
 * Initialize slideshow functionality
 */
function initializeSlideshow() {
    const carousel = document.getElementById('heroCarousel');
    if (!carousel) return;
    
    // Pause slideshow on hover
    carousel.addEventListener('mouseenter', function() {
        const carouselInstance = bootstrap.Carousel.getInstance(carousel);
        if (carouselInstance) {
            carouselInstance.pause();
        }
    });
    
    // Resume slideshow when mouse leaves
    carousel.addEventListener('mouseleave', function() {
        const carouselInstance = bootstrap.Carousel.getInstance(carousel);
        if (carouselInstance) {
            carouselInstance.cycle();
        }
    });
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!carousel.matches(':hover')) return;
        
        const carouselInstance = bootstrap.Carousel.getInstance(carousel);
        if (!carouselInstance) return;
        
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            carouselInstance.prev();
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            carouselInstance.next();
        }
    });
    
    // Handle image loading errors gracefully
    const slideImages = carousel.querySelectorAll('.slideshow-img');
    slideImages.forEach(function(img) {
        img.addEventListener('error', function() {
            console.log('Slideshow image failed to load:', this.src);
            // Fallback images are already handled in HTML with onerror attribute
        });
        
        img.addEventListener('load', function() {
            // Add fade-in effect when image loads
            this.style.opacity = '0';
            this.style.transition = 'opacity 0.5s ease-in-out';
            setTimeout(() => {
                this.style.opacity = '1';
            }, 100);
        });
    });
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed`;
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.style.minWidth = '300px';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(function() {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}