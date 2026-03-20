<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('images/ag-logo-small.png') }}" alt="Admin Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light" style="font-size: 1.1rem;">Society ERP</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.partials._admin-menu')
            </ul>
        </nav>
    </div>

</aside>