// Global utilities and functions
const Utils = {
    // Format currency to IDR
    formatCurrency: (amount) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    },

    // Format number with thousand separator
    formatNumber: (number, decimals = 2) => {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(number);
    },

    // Format date
    formatDate: (dateString, withTime = true) => {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            ...(withTime && {
                hour: '2-digit',
                minute: '2-digit'
            })
        };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    },

    // Show loading spinner
    showLoading: (elementId) => {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = '<div class="spinner"></div>';
        }
    },

    // Hide loading spinner
    hideLoading: (elementId, originalContent = '') => {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = originalContent;
        }
    },

    // Show alert message
    showAlert: (message, type = 'info') => {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} fade-in`;
        alertDiv.innerHTML = message;
        
        const container = document.querySelector('.container');
        container.insertBefore(alertDiv, container.firstChild);

        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    },

    // Validate form inputs
    validateForm: (formData, rules) => {
        const errors = {};
        
        for (const [field, value] of formData.entries()) {
            if (rules[field]) {
                if (rules[field].required && !value) {
                    errors[field] = 'Field ini wajib diisi';
                }
                if (rules[field].min && value < rules[field].min) {
                    errors[field] = `Nilai minimum adalah ${rules[field].min}`;
                }
                if (rules[field].max && value > rules[field].max) {
                    errors[field] = `Nilai maximum adalah ${rules[field].max}`;
                }
            }
        }

        return errors;
    },

    // Handle API errors
    handleApiError: (error) => {
        console.error('API Error:', error);
        Utils.showAlert(
            'Terjadi kesalahan saat memproses permintaan Anda', 
            'error'
        );
    }
};

// Global API service
const ApiService = {
    baseUrl: '/timbangan_rekap/api',

    // Generic fetch method with error handling
    async fetch(endpoint, options = {}) {
        try {
            const response = await fetch(this.baseUrl + endpoint, {
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers
                },
                ...options
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Terjadi kesalahan');
            }

            return data;
        } catch (error) {
            Utils.handleApiError(error);
            throw error;
        }
    },

    // Common API methods
    async get(endpoint) {
        return this.fetch(endpoint);
    },

    async post(endpoint, data) {
        return this.fetch(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    },

    async put(endpoint, data) {
        return this.fetch(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },

    async delete(endpoint) {
        return this.fetch(endpoint, {
            method: 'DELETE'
        });
    }
};

// Timbangan integration
const TimbanganService = {
    isConnected: false,
    weight: 0,
    interval: null,

    // Initialize connection to the weighing scale
    async connect() {
        if (typeof Android !== 'undefined') {
            try {
                this.isConnected = await Android.connectScale();
                if (this.isConnected) {
                    this.startReading();
                    Utils.showAlert('Timbangan terhubung', 'success');
                }
            } catch (error) {
                console.error('Timbangan connection error:', error);
                Utils.showAlert('Gagal menghubungkan timbangan', 'error');
            }
        } else {
            console.log('Running in demo mode');
            this.isConnected = true;
            this.startReading();
        }
    },

    // Start reading weight values
    startReading() {
        this.interval = setInterval(() => {
            this.readWeight();
        }, 100);
    },

    // Read weight from scale
    readWeight() {
        if (typeof Android !== 'undefined') {
            try {
                this.weight = Android.getWeight();
            } catch (error) {
                console.error('Error reading weight:', error);
            }
        } else {
            // Demo mode: generate random weight
            this.weight = (Math.random() * 10).toFixed(2);
        }

        this.updateDisplay();
    },

    // Update weight display
    updateDisplay() {
        const displays = document.querySelectorAll('.weight-display');
        displays.forEach(display => {
            display.textContent = Utils.formatNumber(this.weight) + ' kg';
        });
    },

    // Disconnect from scale
    disconnect() {
        if (this.interval) {
            clearInterval(this.interval);
        }
        this.isConnected = false;
        this.weight = 0;
        this.updateDisplay();
    }
};

// Event Handlers
document.addEventListener('DOMContentLoaded', () => {
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', (e) => {
            const text = e.target.getAttribute('data-tooltip');
            const tooltipEl = document.createElement('div');
            tooltipEl.className = 'tooltip-text';
            tooltipEl.textContent = text;
            e.target.appendChild(tooltipEl);
        });

        tooltip.addEventListener('mouseleave', (e) => {
            const tooltipEl = e.target.querySelector('.tooltip-text');
            if (tooltipEl) {
                tooltipEl.remove();
            }
        });
    });

    // Handle form validation
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value) {
                    e.preventDefault();
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
        });
    });

    // Initialize timbangan connection
    if (document.querySelector('.weight-display')) {
        TimbanganService.connect();
    }
});

// Export modules
// In main.js, remove export statements and make objects global
window.Utils = Utils;
window.ApiService = ApiService;
window.TimbanganService = TimbanganService;