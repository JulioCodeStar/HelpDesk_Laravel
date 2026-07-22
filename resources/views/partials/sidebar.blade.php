@php
    $isDashboard = request()->routeIs('dashboard');
    $isMantenimientos = request()->routeIs('categories.*', 'departments.*');
@endphp

<aside class="app-menubar-tabs" id="appMenubar">
    <div class="app-navbar-brand">
        <a class="navbar-brand-logo" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="NexLink Admin Dashboard Logo">
        </a>
    </div>
    <div class="app-navbar-tabs" data-simplebar>
        <ul class="nav" id="appMenubarTabs" role="tablist" aria-orientation="vertical">
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Home">
                <a class="menu-link" href="#dashboardTab" role="tab" aria-controls="dashboardTab"
                   aria-selected="{{ $isDashboard ? 'true' : 'false' }}" data-bs-toggle="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
                        <rect width="7" height="9" x="3" y="3" rx="1"/>
                        <rect width="7" height="5" x="14" y="3" rx="1"/>
                        <rect width="7" height="9" x="14" y="12" rx="1"/>
                        <rect width="7" height="5" x="3" y="16" rx="1"/>
                    </svg>
                </a>
            </li>

            <li class="nav-item-hr"></li>

            {{-- MANTENIMIENTOS--}}
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Mantenimientos">
                <a class="menu-link" href="#managmentTab" role="tab" aria-controls="managmentTab"
                   aria-selected="{{ $isMantenimientos ? 'true' : 'false' }}" data-bs-toggle="tab">
                    <i class="icon-wrench fs-4"></i>
                </a>
            </li>

        </ul>
    </div>
    <div class="app-tab-content">
        <div class="app-side-brands">
            <a class="navbar-brand-text" href="{{ route('dashboard') }}">NexLink</a>
        </div>
        <div class="app-content-inner">
            <div class="tab-content" id="appMenubarTabsContent">
                <div class="tab-pane fade {{ $isDashboard ? 'show active' : '' }}" id="dashboardTab" role="tabpanel"
                     tabindex="0">
                    <nav class="app-navbar" data-simplebar>
                        <ul class="side-menubar">
                            <li class="menu-heading">
                                <span class="menu-label">Home</span>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link {{ $isDashboard ? 'active' : '' }}" href="{{ route('dashboard') }}"
                                   role="button">
                                    <i class="fi fi-rr-house-blank"></i>
                                    <span class="menu-label">Dashboard</span>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>

                {{-- Mantenimientos --}}
                <div class="tab-pane fade {{ $isMantenimientos ? 'show active' : '' }}" id="managmentTab"
                     role="tabpanel" tabindex="0">
                    <nav class="app-navbar" data-simplebar>
                        <ul class="side-menubar">
                            <li class="menu-heading">
                                <span class="menu-label">Mantenimientos</span>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                                   href="{{ route('categories.index') }}" role="button">
                                    <i class="icon-chart-bar-stacked"></i>
                                    <span class="menu-label">Categorías</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('departments.*') ? 'active' : '' }}"
                                   href="{{ route('departments.index') }}" role="button">
                                    <i class="icon-newspaper"></i>
                                    <span class="menu-label">Departamentos</span>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>

            </div>


        </div>
    </div>
</aside>
