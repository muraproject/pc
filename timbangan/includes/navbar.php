<nav class="navbar navbar-expand fixed-bottom navbar-dark bg-dark">
    <ul class="navbar-nav w-100 justify-content-around">
        <li class="nav-item">
            <a class="nav-link text-center <?php echo ($current_page == 'timbang') ? 'active' : ''; ?>" href="index.php?page=timbang">
                <i class="fas fa-weight d-block"></i>
                <span class="small">Timbang</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center <?php echo ($current_page == 'histori') ? 'active' : ''; ?>" href="index.php?page=histori">
                <i class="fas fa-history d-block"></i>
                <span class="small">Histori</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center <?php echo ($current_page == 'setting') ? 'active' : ''; ?>" href="index.php?page=setting">
                <i class="fas fa-cog d-block"></i>
                <span class="small">Setting</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center <?php echo ($current_page == 'harga') ? 'active' : ''; ?>" href="index.php?page=harga">
                <i class="fas fa-tag d-block"></i>
                <span class="small">Harga</span>
            </a>
        </li>
    </ul>
</nav>