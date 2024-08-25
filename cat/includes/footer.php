</div> <!-- Penutup dari container yang dibuka di header.php -->
    
    <?php
    // Hanya tampilkan bottom nav untuk user yang sudah login dan bukan admin
    if (isset($_SESSION['user_id']) && $_SESSION['role'] !== 'admin') {
        include $_SERVER['DOCUMENT_ROOT'] . '/pc/cat/includes/bottom_nav.php';
    }
    ?>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">&copy; 2023 Simulasi CAT CPNS. All rights reserved.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="/pc/cat/assets/js/custom.js"></script>
</body>
</html>