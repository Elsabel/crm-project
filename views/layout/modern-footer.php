</div><!-- Close padding div -->
</div><!-- Close main-content -->

<script>
// Mobile sidebar handling
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            document.getElementById('modernSidebar').classList.remove('mobile-open');
            const overlay = document.getElementById('mobileOverlay');
            if (overlay) overlay.style.display = 'none';
        }
    });
});

// Close mobile sidebar on overlay click
document.getElementById('mobileOverlay')?.addEventListener('click', function() {
    document.getElementById('modernSidebar').classList.remove('mobile-open');
    this.style.display = 'none';
});

// Responsive handling
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
        document.getElementById('modernSidebar').classList.remove('mobile-open');
        const overlay = document.getElementById('mobileOverlay');
        if (overlay) overlay.style.display = 'none';
    }
});
</script>
</body>
</html>