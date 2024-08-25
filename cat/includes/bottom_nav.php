<style>
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #f8f9fa;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
        box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
    }
    .bottom-nav a {
        color: #6c757d;
        text-decoration: none;
        font-size: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .bottom-nav a.active {
        color: #007bff;
    }
    .bottom-nav i {
        font-size: 24px;
        margin-bottom: 2px;
    }
    @media (min-width: 768px) {
        .bottom-nav {
            display: none;
        }
    }
</style>

<nav class="bottom-nav">
    <a href="/pc/cat/user/index.php" id="nav-home">
        <i class="fas fa-home"></i>
        <span>Beranda</span>
    </a>
    <a href="/pc/cat/user/take_test.php" id="nav-test">
        <i class="fas fa-edit"></i>
        <span>Ambil Tes</span>
    </a>
    <a href="/pc/cat/user/history.php" id="nav-history">
        <i class="fas fa-history"></i>
        <span>Riwayat</span>
    </a>
    <a href="/pc/cat/user/profile.php" id="nav-profile">
        <i class="fas fa-user"></i>
        <span>Profil</span>
    </a>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.bottom-nav a');
    
    navLinks.forEach(link => {
        if (currentPath.includes(link.getAttribute('href'))) {
            link.classList.add('active');
        }
    });
});
</script>