<style>
    /* ========= DESKTOP (hover) ========= */
    @media (min-width: 992px) {
        .navbar .dropdown:hover>.dropdown-menu {
            display: block;
            margin-top: 0;
        }

        .nav-header .dropdown-submenu>a::after {
            content: "\f105";
            float: right;
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
        }

        /* Standard Navbar Reset */
        .nav-header {
            padding: 0;
            background: #fff;
            box-shadow: none;
            border: none !important;
            border-style: none !important;
            outline: none !important;
        }

        .logo-header,
        .logo-header .container,
        .navbar-brand,
        .navbar-collapse {
            border: none !important;
            border-style: none !important;
            outline: none !important;
            box-shadow: none !important;
        }

        /* Restore Logo Header Shadow */
        .logo-header {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05) !important;
        }

        .navbar .dropdown-toggle::after {
            transition: transform 0.2s ease;
        }

        .navbar .dropdown:hover .dropdown-toggle::after {
            transform: rotate(180deg);
        }

        /* Sticky Header */
        .logo-header {
            /* position: sticky; */
            top: 0;
            z-index: 1030;
            background: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
            /* Slight shadow for visibility */
        }
    }

    /* ========= MOBILE (click) ========= */
    @media (max-width: 991.98px) {
        .navbar-nav .dropdown-menu {
            position: static !important;
            float: none;
            background-color: #fff;
            border: none;
            box-shadow: none;
            padding-left: 1rem;
        }

        .nav-header .dropdown-submenu>a::after {
            content: "\f105";
            float: right;
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
        }

        .nav-header {
            background: #fff;
        }

        .dropdown-submenu .dropdown-menu {
            display: none;
            margin-left: 1rem;
        }

        .dropdown-submenu.open>.dropdown-menu {
            display: block;
        }

        .dropdown-item {
            white-space: normal;
        }

        .logo-header img {
            width: 150px;
        }
    }

    /* ========= SHARED STYLES ========= */
    .dropdown-menu {
        transition: all 0.2s ease-in-out;
        border: none;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.08);
    }

    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-left: 0.1rem;
        margin-top: -1px;
    }

    @media (min-width: 992px) {
        .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }
    }

    /* Custom Green Buttons */
    .btn-ib-green {
        background-color: #004d25;
        /* Dark Green */
        color: #fff;
        border: 1px solid #004d25;
        border-radius: 0;
        /* Square corners */
        padding: 9px 14px;
        font-family: "Oswald", Sans-serif;
        /* Ensure font matches */
        font-size: 14px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        letter-spacing: 1.5px;
    }

    .btn-ib-green:hover {
        background-color: #003318;
        border-color: #003318;
        color: #fff;
    }

    /* Dashed Border Button Specific */
    .btn-submit-proposal {
        background-color: #004d25;
        color: #fff;
        border: none;
        /* Base border none, we use outline or pseudo element */
        position: relative;
        letter-spacing: 1.5px;
    }

    .btn-submit-proposal:hover {
        background-color: #003318;
        color: #fff;
    }

    /* Navbar Link Styling to Match Design */
    .navbar-nav .nav-link {
        font-family: "Oswald", Sans-serif;
        font-weight: 600;
        /* Bold/Heavy weight */
        font-size: 16px;
        color: #222 !important;
        /* Dark text */
        text-transform: capitalize;
        /* Ensure Title Case */
        margin-left: 15px;
        /* Spacing between items */
    }

    .navbar-nav .nav-link.active {
        color: #004d25 !important;
        font-weight: 800 !important;
        /* Active color matches button */
    }

    /* Custom Dropdown Arrow (FontAwesome) */
    .nav-item.dropdown .dropdown-toggle::after {
        content: "\f107";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        border: none;
        margin-left: .5em;
        vertical-align: middle;
        transition: transform 0.2s ease;
    }

    .navbar .dropdown:hover .dropdown-toggle::after {
        transform: rotate(180deg);
    }

    /* Rotate caret when expanded (clicked/open) on mobile/desktop via JS */
    .navbar .dropdown-toggle[aria-expanded="true"]::after {
        transform: rotate(180deg);
    }
</style>

<div class="p-0 logo-header">
    <nav class="navbar navbar-expand-lg bg-white navbar-light nav-header">
        <div class="container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <img src="{{ asset('index/assets/logo.jpg') }}" alt="IB Ripple Logo"
                    style="max-height: 80px; width: auto;">
            </a>

            <!-- Toggle Button for Mobile -->
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- Navigation Links -->
                <div class="navbar-nav ml-auto mr-4 align-items-center">
                    <a href="{{ url('/') }}" class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>


                    @foreach ($navPages as $page)
                        @if ($page->children->count())
                            <div class="nav-item dropdown">

                                {{-- TOP LEVEL PAGE ALWAYS ROUTE --}}
                                {{-- DESKTOP: Standard Link (Hover opens menu via CSS) --}}
                                <a href="{{ route('page', $page->sub_title) }}"
                                    class="nav-link dropdown-toggle d-none d-lg-block">
                                    {{ $page->page_title }}
                                </a>

                                {{-- MOBILE: Split Link (Text Link + Toggle Arrow) --}}
                                <div class="d-flex align-items-center justify-content-between d-lg-none">
                                    <a href="{{ route('page', $page->sub_title) }}" class="nav-link">
                                        {{ $page->page_title }}
                                    </a>
                                    <a href="#" class="nav-link dropdown-toggle px-3" role="button" data-bs-toggle="dropdown"
                                        {{-- No explicit target needed if default works, but with nested div: --}}
                                        aria-expanded="false">
                                    </a>
                                </div>

                                <div class="dropdown-menu m-0">
                                    @foreach ($page->children as $child)
                                        @if ($child->children->count())
                                                    {{-- CHILD WITH SUBMENU --}}
                                                    <div class="dropdown-submenu">

                                                        <a href="{{ Str::lower(trim($child->page_title)) === 'magazine & yearbook' || !$child->document
                                            ? route('page', $child->sub_title)
                                            : asset('storage/' . $child->document) }}" class="dropdown-item"
                                                            target="{{ Str::lower(trim($child->page_title)) === 'magazine & yearbook' || !$child->document ? '_self' : '_blank' }}">
                                                            {{ $child->page_title }}
                                                        </a>

                                                        <div class="dropdown-menu">

                                                            @foreach ($child->children as $grandchild)
                                                                            <a href="{{ Str::lower(trim($grandchild->page_title)) === 'magazine & yearbook' || !$grandchild->document
                                                                ? route('page', $grandchild->sub_title)
                                                                : asset('storage/' . $grandchild->document) }}"
                                                                                class="dropdown-item"
                                                                                target="{{ Str::lower(trim($grandchild->page_title)) === 'magazine & yearbook' || !$grandchild->document ? '_self' : '_blank' }}">
                                                                                {{ $grandchild->page_title }}
                                                                            </a>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                        @else
                                                    {{-- NORMAL CHILD --}}
                                                    <a href="{{ Str::lower(trim($child->page_title)) === 'magazine & yearbook' || !$child->document
                                            ? route('page', $child->sub_title)
                                            : asset('storage/' . $child->document) }}" class="dropdown-item"
                                                        target="{{ Str::lower(trim($child->page_title)) === 'magazine & yearbook' || !$child->document ? '_self' : '_blank' }}">
                                                        {{ $child->page_title }}
                                                    </a>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        @else
                                    {{-- TOP LEVEL SINGLE PAGE --}}
                                    <a href="{{ Str::lower(trim($page->page_title)) === 'magazine & yearbook' || !$page->document
                            ? route('page', $page->sub_title)
                            : asset('storage/' . $page->document) }}" class="nav-item nav-link text-dark"
                                        target="{{ Str::lower(trim($page->page_title)) === 'magazine & yearbook' || !$page->document ? '_self' : '_blank' }}">
                                        {{ $page->page_title }}
                                    </a>
                        @endif
                    @endforeach
                </div>

                <!-- Action Buttons -->
                <div class="d-none d-lg-flex align-items-center">
                    <a href="{{ asset('index/assets/images/IB-Ripple-2026-Call-for-Proposals-Booklet-update.pdf') }}"
                        class="btn btn-ib-green mr-2" target="_blank">IB Ripple Global Conference 2026</a>
                    <a href="{{ asset('index/assets/images/Abstract-Book.pdf') }}"
                        class="btn btn-ib-green btn-submit-proposal" target="_blank">Abstract Book</a>
                </div>
            </div>
        </div>
    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdowns = document.querySelectorAll('.dropdown-submenu > a');
        dropdowns.forEach((el) => {
            el.addEventListener('click', function (e) {
                if (window.innerWidth < 992) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    parent.classList.toggle('open');
                }
            });
        });

        // ✅ Fix: Manual Mobile Dropdown Toggle (if data-bs-toggle fails due to nesting)
        const mobileToggles = document.querySelectorAll('.d-lg-none .dropdown-toggle');
        mobileToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation(); // prevent default BS behavior

                const dropdownMenu = this.closest('.nav-item.dropdown').querySelector('.dropdown-menu');
                if (dropdownMenu) {
                    // Toggle show class on menu
                    if (dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                        this.setAttribute('aria-expanded', 'false');
                    } else {
                        // Close other open menus in mobile (optional, but good UX)
                        document.querySelectorAll('.navbar-nav .dropdown-menu.show').forEach(m => {
                            if (m !== dropdownMenu) {
                                m.classList.remove('show');
                                // Find its toggle and reset
                                const t = m.closest('.nav-item.dropdown').querySelector('.dropdown-toggle');
                                if (t) t.setAttribute('aria-expanded', 'false');
                            }
                        });

                        dropdownMenu.classList.add('show');
                        this.setAttribute('aria-expanded', 'true');
                    }
                }
            });
        });
    });
</script>