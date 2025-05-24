import './bootstrap';

// Initialize AOS when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if AOS is loaded
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: false,
            mirror: true,
            offset: 70,
            // Disable di mobile jika perlu
            disable: function() {
                return window.innerWidth < 768;
            }
        });
    }
});

// Refresh AOS untuk dynamic content (jika diperlukan)
window.refreshAOS = function() {
    if (typeof AOS !== 'undefined') {
        AOS.refresh();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    // Hide loading screen after page is fully loaded
    window.addEventListener('load', function() {
        const loadingScreen = document.getElementById('loading-screen');
        const mainContent = document.getElementById('main-content');
        
        // Fade out loading screen
        loadingScreen.style.transition = 'opacity 3s ease-out';
        loadingScreen.style.opacity = '0';
        
        setTimeout(() => {
            loadingScreen.style.display = 'none';
            mainContent.style.display = 'block';
            
            // Fade in main content
            mainContent.style.opacity = '0';
            mainContent.style.transition = 'opacity 3s ease-in';
            setTimeout(() => {
                mainContent.style.opacity = '1';
            }, 50);
        }, 500);
    });
});
