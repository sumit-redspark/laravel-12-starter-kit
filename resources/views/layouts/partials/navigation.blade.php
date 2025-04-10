<!-- Main Sidebar Navigation -->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!-- Brand Logo Section -->
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('assets/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">Admin Panel</span>
        </a>
    </div>

    <!-- Navigation Menu Container -->
    <div class="sidebar-wrapper" style="height: calc(100vh - 4.5rem); position: relative;">
        <nav class="mt-2" style="height: 100%; overflow-y: auto;">
            <!-- Main Navigation Menu -->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <!-- Dashboard Section -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Permissions Section -->
                @role(\App\Enums\Role::SUPER_ADMIN)
                    @can($Permission::PERMISSION_VIEW)
                        <li class="nav-item">
                            <a href="{{ route('admin.permissions.index') }}"
                                class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-shield-lock"></i>
                                <p>Permissions</p>
                            </a>
                        </li>
                    @endcan
                @endrole

                <!-- Roles Section -->
                @can($Permission::ROLE_VIEW)
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}"
                            class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                @endcan

                @can($Permission::USER_VIEW)
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endcan

            </ul>
        </nav>
    </div>
</aside>
