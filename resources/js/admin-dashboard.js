// Admin Dashboard JavaScript - Barba.js Integration Test
console.log('Admin Dashboard JS loaded');

// Test Barba.js integration
if (typeof window.barba !== 'undefined') {
    console.log('Barba.js is properly loaded and initialized');
    
    // Add a simple test transition
    barba.hooks.after(() => {
        console.log('Page transition completed');
        
        // Add fade-in animation to new content
        const newContent = document.querySelector('[data-barba="container"]');
        if (newContent) {
            newContent.classList.add('fade-in');
        }
    });
} else {
    console.log('Barba.js not found - falling back to standard navigation');
}

// Test page-specific functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Dashboard DOM loaded');
    
    // Test if we're on a specific page
    const dashboardElement = document.querySelector('[data-page="dashboard"]');
    if (dashboardElement) {
        console.log('Dashboard page detected');
    }
    
    const attendanceElement = document.querySelector('[data-page="attendance"]');
    if (attendanceElement) {
        console.log('Attendance page detected');
    }
}); 