/**
 * Utility Functions
 *
 * This file contains utility functions for the application.
 */

const Utils = (function () {
    /**
     * Dynamically load a CSS file
     * @param {string} url - URL of the CSS file
     * @returns {Promise} - Resolves when the CSS is loaded
     */
    function loadCSS(url) {
        return new Promise((resolve, reject) => {
            // Check if the stylesheet is already loaded
            if (document.querySelector(`link[href="${url}"]`)) {
                resolve();
                return;
            }

            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = url;
            link.onload = () => resolve();
            link.onerror = () => reject(new Error(`Failed to load CSS: ${url}`));
            document.head.appendChild(link);
        });
    }

    /**
     * Dynamically load a JavaScript file
     * @param {string} url - URL of the JavaScript file
     * @returns {Promise} - Resolves when the JavaScript is loaded
     */
    function loadJS(url) {
        return new Promise((resolve, reject) => {
            // Check if the script is already loaded
            if (document.querySelector(`script[src="${url}"]`)) {
                resolve();
                return;
            }

            const script = document.createElement('script');
            script.src = url;
            script.onload = () => resolve();
            script.onerror = () => reject(new Error(`Failed to load JavaScript: ${url}`));
            document.body.appendChild(script);
        });
    }

    /**
     * Load libraries based on settings
     * @returns {Promise} - Resolves when all libraries are loaded
     */
    async function loadLibraries() {
        const {
            libraries
        } = window.AppSettings || {
            libraries: {}
        };

        try {
            // Load DataTables if enabled
            if (libraries.dataTables) {
                await loadCSS('https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css');
                await loadJS('https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js');
                await loadJS('https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js');
            }

            // Load OverlayScrollbars if enabled
            if (libraries.overlayScrollbars) {
                await loadCSS('https://cdn.jsdelivr.net/npm/overlayscrollbars@2.4.5/styles/overlayscrollbars.min.css');
                await loadJS('https://cdn.jsdelivr.net/npm/overlayscrollbars@2.4.5/browser/overlayscrollbars.browser.es6.min.js');
            }

            return true;
        } catch (error) {
            console.error('Error loading libraries:', error);
            return false;
        }
    }

    // Public API
    return {
        loadCSS,
        loadJS,
        loadLibraries
    };
})();

// Make it globally available
window.Utils = Utils;
