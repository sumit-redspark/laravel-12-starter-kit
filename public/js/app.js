/**
 * Main Application JavaScript
 *
 * This file initializes the application and loads required libraries.
 */

// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', async function () {
    // Load libraries based on settings
    await Utils.loadLibraries();

    // Initialize OverlayScrollbars if enabled
    if (AppSettings.libraries.overlayScrollbars && typeof OverlayScrollbarsGlobal !== 'undefined' && OverlayScrollbarsGlobal.OverlayScrollbars) {
        const sidebarNav = document.querySelector('.sidebar-wrapper nav');
        if (sidebarNav) {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarNav, {
                overflow: {
                    x: 'hidden',
                    y: 'scroll'
                },
                scrollbars: {
                    theme: 'os-theme-light',
                    autoHide: 'leave',
                    clickScroll: true
                }
            });
        }
    }

    // Initialize any other components or functionality here
});

/**
 * Initialize the application
 */
function initApp() {
    // Load alert libraries based on settings
    loadAlertLibraries();

    // Initialize DataTables if enabled
    initDataTables();

    // Initialize other components as needed
    // initCharts();
    // etc.
}

/**
 * Load alert libraries based on settings
 */
function loadAlertLibraries() {
    // Check if settings are available
    if (!window.appSettings || !window.appSettings.alerts) {
        console.warn('Alert settings not found. Using default settings.');
        return;
    }

    const alertSettings = window.appSettings.alerts;

    // Load SweetAlert2 if it's the selected library
    if (alertSettings.library === 'sweetalert2') {
        loadSweetAlert2();
    }
    // Add other alert libraries here as needed
    // else if (alertSettings.library === 'toastr') {
    //     loadToastr();
    // }
}

/**
 * Load SweetAlert2 library
 */
function loadSweetAlert2() {
    // Check if SweetAlert2 is already loaded
    if (typeof Swal !== 'undefined') {
        console.log('SweetAlert2 is already loaded');
        return;
    }

    // Load SweetAlert2 CSS
    const cssLink = document.createElement('link');
    cssLink.rel = 'stylesheet';
    cssLink.href = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css';
    document.head.appendChild(cssLink);

    // Load SweetAlert2 JS
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    script.onload = function () {
        console.log('SweetAlert2 loaded successfully');
    };
    script.onerror = function () {
        console.error('Failed to load SweetAlert2');
    };
    document.body.appendChild(script);
}

/**
 * Initialize DataTables with settings
 */
function initDataTables() {
    // Check if DataTables is enabled in settings
    if (!window.appSettings || !window.appSettings.dataTables || !window.appSettings.dataTables.enabled) {
        console.warn('DataTables is not enabled in settings.');
        return;
    }

    // Check if DataTables is loaded
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTables is not loaded');
        return;
    }

    // Set default DataTables options
    $.extend(true, $.fn.DataTable.defaults, {
        pageLength: window.appSettings.dataTables.pageLength,
        language: window.appSettings.dataTables.language,
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ]
    });

    console.log('DataTables initialized with settings');
}

// Add other initialization functions as needed
// function initCharts() { ... }
// etc.
