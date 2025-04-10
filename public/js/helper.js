/**
 * Helper functions for common UI operations
 */

// Default options for alerts
const defaultAlertOptions = {
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    toast: true
};

/**
 * Show a success alert
 * @param {string} message - The message to display
 * @param {object} options - Additional options to override defaults
 */
function showSuccess(message, options = {}) {
    Swal.fire({
        title: 'Success!',
        text: message,
        icon: 'success',
        ...defaultAlertOptions,
        ...options
    });
}

/**
 * Show an error alert
 * @param {string} message - The message to display
 * @param {object} options - Additional options to override defaults
 */
function showError(message, options = {}) {
    Swal.fire({
        title: 'Error!',
        text: message,
        icon: 'error',
        ...defaultAlertOptions,
        ...options
    });
}

/**
 * Show a warning alert
 * @param {string} message - The message to display
 * @param {object} options - Additional options to override defaults
 */
function showWarning(message, options = {}) {
    Swal.fire({
        title: 'Warning!',
        text: message,
        icon: 'warning',
        ...defaultAlertOptions,
        ...options
    });
}

/**
 * Show a confirmation dialog
 * @param {string} message - The message to display
 * @param {string} title - The title of the dialog
 * @param {object} options - Additional options to override defaults
 * @returns {Promise} - Resolves with the result of the confirmation
 */
function showConfirm(message, title = 'Are you sure?', options = {}) {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!',
        cancelButtonText: 'Cancel',
        ...options
    });
}
