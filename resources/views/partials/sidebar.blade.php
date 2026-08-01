@php
    $isDashboard = request()->routeIs('dashboard');
    $isMantenimientos = request()->routeIs('categories.*', 'departments.*', 'faqs.*', 'users.*');
    $isTickets = request()->routeIs('tickets.index', 'tickets.create', 'tickets.show');
    $isGestion = request()->routeIs('tickets.gestion');
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

            <li class="nav-item-hr"></li>

            {{-- TICKETS --}}
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Tickets">
                <a class="menu-link" href="#ticketsTab" role="tab" aria-controls="ticketsTab"
                   aria-selected="{{ $isTickets ? 'true' : 'false' }}" data-bs-toggle="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-tag-icon lucide-tag">
                        <path
                            d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/>
                        <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>
                    </svg>
                </a>
            </li>

            <li class="nav-item-hr"></li>

            {{-- GESTIÓN --}}
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Gestión">
                <a class="menu-link" href="#gestionTab" role="tab" aria-controls="gestionTab"
                   aria-selected="{{ $isGestion ? 'true' : 'false' }}" data-bs-toggle="tab">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-square-chart-gantt-icon lucide-square-chart-gantt">
                        <rect width="18" height="18" x="3" y="3" rx="2"/>
                        <path d="M9 8h7"/>
                        <path d="M8 12h6"/>
                        <path d="M11 16h5"/>
                    </svg>
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
                            <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('faqs.*') ? 'active' : '' }}"
                                   href="{{ route('faqs.index') }}" role="button">
                                    <i class="icon-mails"></i>
                                    <span class="menu-label">FAQS</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                                   href="{{ route('users.index') }}" role="button">
                                    <i class="icon-user-round-cog"></i>
                                    <span class="menu-label">Usuarios</span>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>

                {{-- Tickets --}}
                <div class="tab-pane fade {{ $isTickets ? 'show active' : '' }}" id="ticketsTab"
                     role="tabpanel" tabindex="0">
                    <nav class="app-navbar" data-simplebar>
                        <ul class="side-menubar">
                            <li class="menu-heading">
                                <span class="menu-label">Tickets</span>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('tickets.index', 'tickets.show') ? 'active' : '' }}"
                                   href="{{ route('tickets.index') }}" role="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round"
                                         class="lucide lucide-tag-icon lucide-tag">
                                        <path
                                            d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/>
                                        <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>
                                    </svg>
                                    <span class="menu-label">Mis Tickets</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('tickets.create') ? 'active' : '' }}"
                                   href="{{ route('tickets.create') }}" role="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="lucide lucide-tag-plus-icon lucide-tag-plus">
                                        <path d="M16 13h6"/>
                                        <path
                                            d="m16.5 6.5-3.914-3.914A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l1.79-1.79"/>
                                        <path d="M19 10v6"/>
                                        <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>
                                    </svg>
                                    <span class="menu-label">Nuevo Ticket</span>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>

                {{-- Gestión --}}
                <div class="tab-pane fade {{ $isGestion ? 'show active' : '' }}" id="gestionTab"
                     role="tabpanel" tabindex="0">
                    <nav class="app-navbar" data-simplebar>
                        <ul class="side-menubar">
                            <li class="menu-heading">
                                <span class="menu-label">Gestión</span>
                            </li>
                            <li class="menu-item">
                                <a class="menu-link {{ request()->routeIs('tickets.gestion') ? 'active' : '' }}"
                                   href="{{ route('tickets.gestion') }}" role="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="lucide lucide-square-chart-gantt-icon lucide-square-chart-gantt">
                                        <rect width="18" height="18" x="3" y="3" rx="2"/>
                                        <path d="M9 8h7"/>
                                        <path d="M8 12h6"/>
                                        <path d="M11 16h5"/>
                                    </svg>
                                    <span class="menu-label">Gestionar Tickets</span>
                                </a>
                            </li>


                        </ul>
                    </nav>
                </div>
            </div>


        </div>
    </div>
</aside>
