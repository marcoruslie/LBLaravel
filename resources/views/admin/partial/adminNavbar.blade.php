<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">UD. ASIA MOTOR</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{($currPage === 'reservasi') ? 'active' : ''}}" href="/admin/">Reservasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{($currPage === 'aksesoris') ? 'active':''}}" href="/admin/aksesoris">Aksesoris</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{($currPage === 'layanan') ? 'active' : ''}}" href="/admin/layanan">Layanan</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Admin
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item bg-danger rounded text-white " href="/">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

