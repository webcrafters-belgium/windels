        </main>
    </div>
    
    <!-- Theme & Accessibility Scripts -->
    <script>
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
            if (darkIcon && lightIcon) {
                if (theme === 'dark') {
                    darkIcon.classList.remove('hidden');
                    lightIcon.classList.add('hidden');
                } else {
                    darkIcon.classList.add('hidden');
                    lightIcon.classList.remove('hidden');
                }
            }
        }
        
        function toggleLargeText() {
            document.body.classList.toggle('large-text');
            localStorage.setItem('large_text', document.body.classList.contains('large-text') ? '1' : '0');
        }
        
        function toggleHighContrast() {
            document.body.classList.toggle('high-contrast');
            localStorage.setItem('high_contrast', document.body.classList.contains('high-contrast') ? '1' : '0');
        }
        
        function initAccessibility() {
            if (localStorage.getItem('large_text') === '1') document.body.classList.add('large-text');
            if (localStorage.getItem('high_contrast') === '1') document.body.classList.add('high-contrast');
        }
        
        initTheme();
        initAccessibility();
    </script>
</body>
</html>
