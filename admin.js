/**
 * Admin Dashboard JavaScript
 * Patisserie Haloui
 */

document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // User menu dropdown
    const userAvatar = document.getElementById('userAvatar');
    if (userAvatar) {
        userAvatar.addEventListener('click', function() {
            // Implement user dropdown menu
            console.log('User menu clicked');
            // You can add code to show/hide a dropdown menu
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isMobile = window.innerWidth < 992;
        if (isMobile && sidebar.classList.contains('active')) {
            if (!sidebar.contains(event.target) && event.target !== sidebarToggle) {
                sidebar.classList.remove('active');
            }
        }
    });
    
    // Initialize any charts or data visualizations here
    // Example: if you're using a chart library
    
    // Handle notifications
    const notificationsBtn = document.getElementById('notificationsBtn');
    if (notificationsBtn) {
        notificationsBtn.addEventListener('click', function() {
            // Show notifications panel
            console.log('Notifications clicked');
            // You can implement a dropdown or modal for notifications
        });
    }
});