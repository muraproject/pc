<nav class="navbar navbar-expand fixed-bottom navbar-dark bg-dark">
    <ul class="navbar-nav w-100 justify-content-around">
        <li class="nav-item">
            <a class="nav-link text-center <?php echo ($page == 'timbang') ? 'active' : ''; ?>" href="index.php?page=timbang&user_type=<?php echo $user_type; ?>">
                <i class="fas fa-weight d-block"></i>
                <span class="small">Timbang</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-center <?php echo ($page == 'histori') ? 'active' : ''; ?>" href="index.php?page=histori&user_type=<?php echo $user_type; ?>">
                <i class="fas fa-history d-block"></i>
                <span class="small">Histori</span>
            </a>
        </li>
        <?php if ($user_type === 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link text-center <?php echo ($page == 'setting') ? 'active' : ''; ?>" href="index.php?page=setting&user_type=<?php echo $user_type; ?>">
                <i class="fas fa-cog d-block"></i>
                <span class="small">Setting</span>
            </a>
        </li>
        <?php endif; ?>
        <?php if ($user_type === 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link text-center <?php echo ($page == 'harga') ? 'active' : ''; ?>" href="index.php?page=harga&user_type=<?php echo $user_type; ?>">
                <i class="fas fa-tag d-block"></i>
                <span class="small">Edit</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>
</nav>