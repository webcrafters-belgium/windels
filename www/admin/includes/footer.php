        </main>
    </div>
    
    <!-- Theme Toggle Script -->
    <script>
        // Initialize theme from localStorage
        function initTheme() {
            const theme = localStorage.getItem('admin_theme') || 'dark';
            document.documentElement.setAttribute('data-theme', theme);
            updateThemeIcons(theme);
        }
        
        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            const newTheme = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('admin_theme', newTheme);
            updateThemeIcons(newTheme);
        }
        
        function updateThemeIcons(theme) {
            const darkIcon = document.querySelector('.theme-icon-dark');
            const lightIcon = document.querySelector('.theme-icon-light');
            if (theme === 'dark') {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            } else {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            }
        }
        
        // Large text toggle
        function toggleLargeText() {
            document.body.classList.toggle('large-text');
            const enabled = document.body.classList.contains('large-text');
            localStorage.setItem('large_text', enabled ? '1' : '0');
        }
        
        // High contrast toggle
        function toggleHighContrast() {
            document.body.classList.toggle('high-contrast');
            const enabled = document.body.classList.contains('high-contrast');
            localStorage.setItem('high_contrast', enabled ? '1' : '0');
        }
        
        // Initialize accessibility settings
        function initAccessibility() {
            if (localStorage.getItem('large_text') === '1') {
                document.body.classList.add('large-text');
            }
            if (localStorage.getItem('high_contrast') === '1') {
                document.body.classList.add('high-contrast');
            }
        }
        
        // Run on load
        initTheme();
        initAccessibility();
        
        // CSRF token helper
        function getCSRFToken() {
            return document.getElementById('csrf_token').value;
        }
        
        // Ajax helper with CSRF
        async function fetchWithCSRF(url, options = {}) {
            const token = getCSRFToken();
            options.headers = options.headers || {};
            options.headers['X-CSRF-Token'] = token;
            
            if (options.body && typeof options.body === 'object') {
                options.body.csrf_token = token;
            }
            
            return fetch(url, options);
        }
    </script>
</body>
</html>