<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme"
    style="position: fixed; top: 0; height: 100vh; overflow-y: auto;">

    <!-- Logo -->
    <div class="app-brand demo mt-3 mb-4 text-center">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <img src="{{ asset('index/assets/logo.jpg') }}" alt="logo" style="width: 12rem;">
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

        {{-- Dashboard --}}
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        {{-- Pages --}}
        @can('page management')
            <li class="menu-header small text-uppercase"><span>Pages</span></li>

            <li class="menu-item {{ request()->routeIs('pages.*') ? 'active' : '' }}">
                <a href="{{ route('pages.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div>View Pages</div>
                </a>
            </li>
        @endcan

        {{-- Gallery Manager --}}
        @can('gallerymanager')
            <li class="menu-header small text-uppercase"><span>Gallery Manager</span></li>

            <li class="menu-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
                <a href="{{ route('events.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                    <div>Events</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('media-items.*') ? 'active' : '' }}">
                <a href="{{ route('media-items.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-image"></i>
                    <div>Media Items</div>
                </a>
            </li>
        @endcan



        {{-- Slider --}}
        @can('slider management')
            <li class="menu-header small text-uppercase"><span>Slider Manager</span></li>

            <li class="menu-item {{ request()->routeIs('sliders.*') ? 'active' : '' }}">
                <a href="{{ route('sliders.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-carousel"></i>
                    <div>View Slider</div>
                </a>
            </li>
        @endcan

        {{-- Contact Messages --}}
        @can('contactmessages')
            <li class="menu-header small text-uppercase"><span>Contact Message</span></li>

            <li class="menu-item {{ request()->routeIs('contactmessages') ? 'active' : '' }}">
                <a href="{{ route('contactmessages') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-envelope"></i>
                    <div>Contact Messages</div>
                </a>
            </li>
        @endcan

        {{-- Users --}}
        @can('user management')
            <li class="menu-header small text-uppercase"><span>Users Management</span></li>

            <li class="menu-item {{ request()->routeIs('users') ? 'active' : '' }}">
                <a href="{{ route('users') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>Users</div>
                </a>
            </li>
        @endcan

        {{-- Roles --}}
        @can('role management')
            <li class="menu-header small text-uppercase"><span>Roles Management</span></li>

            <li class="menu-item {{ request()->routeIs('roles') ? 'active' : '' }}">
                <a href="{{ route('roles') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
                    <div>Roles</div>
                </a>
            </li>
        @endcan

        {{-- Permissions --}}
        @can('permission management')
            <li class="menu-header small text-uppercase"><span>Permissions Management</span></li>

            <li class="menu-item {{ request()->routeIs('permissions') ? 'active' : '' }}">
                <a href="{{ route('permissions') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-lock-alt"></i>
                    <div>Permissions</div>
                </a>
            </li>
        @endcan

        {{-- Cache --}}
        <li class="menu-header small text-uppercase"><span>Cache Clear</span></li>

        <li class="menu-item {{ request()->routeIs('cacheclear') ? 'active' : '' }}">
            <a href="{{ route('cacheclear') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-refresh"></i>
                <div>Cache Clear</div>
            </a>
        </li>

    </ul>
</aside>