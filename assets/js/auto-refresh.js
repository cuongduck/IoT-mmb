// auto-refresh.js
// Force reload every 5 minutes to ensure fresh data
(function() {
    // Set up auto-refresh every 5 minutes (600000 ms)
    const REFRESH_INTERVAL = 600000; 
    
    let refreshTimer = setTimeout(function() {
        console.log('Auto-refreshing page...');
        window.location.reload(true); // true forces a reload from server, not cache
    }, REFRESH_INTERVAL);
    
    // Reset timer if user interacts with the page
    function resetTimer() {
        clearTimeout(refreshTimer);
        refreshTimer = setTimeout(function() {
            console.log('Auto-refreshing page after interaction...');
            window.location.reload(true);
        }, REFRESH_INTERVAL);
    }
    
    // Listen for user interactions
    window.addEventListener('click', resetTimer);
    window.addEventListener('touchstart', resetTimer);
    window.addEventListener('keydown', resetTimer);
    
    console.log('Auto-refresh initialized. Page will reload every 5 minutes.');
})();