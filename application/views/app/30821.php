<style>
    body {
        min-height: 100vh;
        min-height: -webkit-fill-available;
    }

    html {
        height: -webkit-fill-available;
    }

    main {
        height: 100vh;
        height: -webkit-fill-available;
        max-height: 100vh;
        overflow-x: auto;
        overflow-y: hidden;
    }

    .dropdown-toggle { outline: 0; }

    .btn-toggle {
        padding: .25rem .5rem;
        font-weight: 600;
        color: rgba(0, 0, 0, .65);
        background-color: transparent;
    }
    .btn-toggle:hover,
    .btn-toggle:focus {
        color: rgba(0, 0, 0, .85);
        background-color: #d2f4ea;
    }

    .btn-toggle::before {
        width: 1.25em;
        line-height: 0;
        content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%280,0,0,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
        transition: transform .35s ease;
        transform-origin: .5em 50%;
    }

    .btn-toggle[aria-expanded="true"] {
        color: rgba(0, 0, 0, .85);
    }
    .btn-toggle[aria-expanded="true"]::before {
        transform: rotate(90deg);
    }

    .btn-toggle-nav a {
        padding: .1875rem .5rem;
        margin-top: .125rem;
        margin-left: 1.25rem;
    }
    .btn-toggle-nav a:hover,
    .btn-toggle-nav a:focus {
        background-color: #d2f4ea;
    }

    .scrollarea {
        overflow-y: auto;
    }
</style>

<main class="d-flex flex-nowrap">
    <h1 class="visually-hidden">Sidebars examples</h1>

    <div class="d-flex flex-column flex-shrink-0 p-3 text-bg-dark" style="width: 280px;">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-4">Sidebar</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link active" aria-current="page">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                    Home
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                    Orders
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                    Products
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"></use></svg>
                    Customers
                </a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong>mdo</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="#">New project...</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
            </ul>
        </div>
    </div>

    <div class="b-example-divider b-example-vr"></div>

    <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
            <svg class="bi pe-none me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-4">Sidebar</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link active" aria-current="page">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                    Home
                </a>
            </li>
            <li>
                <a href="#" class="nav-link link-dark">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link link-dark">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
                    Orders
                </a>
            </li>
            <li>
                <a href="#" class="nav-link link-dark">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
                    Products
                </a>
            </li>
            <li>
                <a href="#" class="nav-link link-dark">
                    <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#people-circle"></use></svg>
                    Customers
                </a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong>mdo</strong>
            </a>
            <ul class="dropdown-menu text-small shadow">
                <li><a class="dropdown-item" href="#">New project...</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
            </ul>
        </div>
    </div>

    <div class="b-example-divider b-example-vr"></div>

    <div class="d-flex flex-column flex-shrink-0 bg-light" style="width: 4.5rem;">
        <a href="/" class="d-block p-3 link-dark text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Icon-only">
            <svg class="bi pe-none" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            <span class="visually-hidden">Icon-only</span>
        </a>
        <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
            <li class="nav-item">
                <a href="#" class="nav-link active py-3 border-bottom rounded-0" aria-current="page" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Home" data-bs-original-title="Home">
                    <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Home"><use xlink:href="#home"></use></svg>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link py-3 border-bottom rounded-0" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Dashboard" data-bs-original-title="Dashboard">
                    <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Dashboard"><use xlink:href="#speedometer2"></use></svg>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link py-3 border-bottom rounded-0" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Orders" data-bs-original-title="Orders">
                    <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Orders"><use xlink:href="#table"></use></svg>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link py-3 border-bottom rounded-0" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Products" data-bs-original-title="Products">
                    <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Products"><use xlink:href="#grid"></use></svg>
                </a>
            </li>
            <li>
                <a href="#" class="nav-link py-3 border-bottom rounded-0" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Customers" data-bs-original-title="Customers">
                    <svg class="bi pe-none" width="24" height="24" role="img" aria-label="Customers"><use xlink:href="#people-circle"></use></svg>
                </a>
            </li>
        </ul>
        <div class="dropdown border-top">
            <a href="#" class="d-flex align-items-center justify-content-center p-3 link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://github.com/mdo.png" alt="mdo" width="24" height="24" class="rounded-circle">
            </a>
            <ul class="dropdown-menu text-small shadow">
                <li><a class="dropdown-item" href="#">New project...</a></li>
                <li><a class="dropdown-item" href="#">Settings</a></li>
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
            </ul>
        </div>
    </div>

    <div class="b-example-divider b-example-vr"></div>

    <div class="flex-shrink-0 p-3 bg-white" style="width: 280px;">
        <a href="/" class="d-flex align-items-center pb-3 mb-3 link-dark text-decoration-none border-bottom">
            <svg class="bi pe-none me-2" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-5 fw-semibold">Collapsible</span>
        </a>
        <ul class="list-unstyled ps-0">
            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#home-collapse" aria-expanded="true">
                    Home
                </button>
                <div class="collapse show" id="home-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Overview</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Updates</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Reports</a></li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                    Dashboard
                </button>
                <div class="collapse" id="dashboard-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Overview</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Weekly</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Monthly</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Annually</a></li>
                    </ul>
                </div>
            </li>
            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                    Orders
                </button>
                <div class="collapse" id="orders-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">New</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Processed</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Shipped</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Returned</a></li>
                    </ul>
                </div>
            </li>
            <li class="border-top my-3"></li>
            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                    Account
                </button>
                <div class="collapse" id="account-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">New...</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Profile</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Settings</a></li>
                        <li><a href="#" class="link-dark d-inline-flex text-decoration-none rounded">Sign out</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>

    <div class="b-example-divider b-example-vr"></div>

    <div class="d-flex flex-column align-items-stretch flex-shrink-0 bg-white" style="width: 380px;">
        <a href="/" class="d-flex align-items-center flex-shrink-0 p-3 link-dark text-decoration-none border-bottom">
            <svg class="bi pe-none me-2" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-5 fw-semibold">List group</span>
        </a>
        <div class="list-group list-group-flush border-bottom scrollarea">
            <a href="#" class="list-group-item list-group-item-action active py-3 lh-sm" aria-current="true">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small>Wed</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Tues</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Mon</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>

            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm" aria-current="true">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Wed</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Tues</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Mon</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm" aria-current="true">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Wed</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Tues</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Mon</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm" aria-current="true">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Wed</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Tues</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-3 lh-sm">
                <div class="d-flex w-100 align-items-center justify-content-between">
                    <strong class="mb-1">List group item heading</strong>
                    <small class="text-muted">Mon</small>
                </div>
                <div class="col-10 mb-1 small">Some placeholder content in a paragraph below the heading and date.</div>
            </a>
        </div>
    </div>

    <div class="b-example-divider b-example-vr"></div>
</main>