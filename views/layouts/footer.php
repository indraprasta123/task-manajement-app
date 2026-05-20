<footer class="site-footer">
    <p>&copy; 2026 Indraprasta Dwinanda Fahreza. All rights reserved.</p>
</footer>

<script>
    window.__APP_FLASH = {
        success: <?php echo json_encode($_SESSION['flash_success'] ?? null); ?>,
        error: <?php echo json_encode($_SESSION['flash_error'] ?? null); ?>
    };
    <?php unset($_SESSION['flash_success']); ?>
    <?php unset($_SESSION['flash_error']); ?>
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/js/form-alerts.js"></script>