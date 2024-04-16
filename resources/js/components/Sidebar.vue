<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="index3.html" class="brand-link">
            <img
                src=" https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png"
                alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3"
                style="opacity: 0.8"
            />
            <span class="brand-text font-weight-light">Exam BE</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        src=" https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg"
                        class="img-circle elevation-2"
                        alt="User Image"
                    />
                </div>
                <div class="info">
                    <a href="#" class="d-block"> Welcome!</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul
                    class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false"
                >
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/createproduct" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Create</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/category" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Categories</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/videos" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Videos</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/Cart" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Cart</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/orders" class="nav-link">
                            <i class="nav-icon fas fa-th"></i>
                            <p>Orders</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>
