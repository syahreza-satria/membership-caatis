<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-mug-hot"></i>
        </div>
        <div class="sidebar-brand-text mx-3">F&B Membership</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0" />

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider" />

    <!-- Heading -->
    <div class="sidebar-heading">Navigasi Halaman</div>

    <!-- Nav Item - Kode Verifikasi -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.verification-codes') }}">
            <i class="fas fa-fw fa-code"></i>
            <span>Kode Verifikasi</span></a>
    </li>

    <!-- Nav Item - Rewards -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.rewards') }}">
            <i class="fas fa-fw fa-gift"></i>
            <span>Rewards</span></a>
    </li>

    <!-- Nav Item - Cabang -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.branches') }}">
            <i class="fas fa-fw fa-code-branch"></i>
            <span>Cabang</span></a>
    </li>

    <!-- Nav Item - Pesanan -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.orders') }}">
            <i class="fas fa-fw fa-list-alt"></i>
            <span>Pesanan</span></a>
    </li>

    <!-- Nav Item - User -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard.users') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>User</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block" />

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
