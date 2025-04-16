<!-- SidebarSearch Form -->
<div class="form-inline mb-2">
    <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
            </button>
        </div>
    </div>
</div>

<li class="nav-item menu-open">
    <a href="{{ route('home') }}" class="nav-link {{url()->current() == route('home') ? 'active' : ''}}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
            Dashboard
        </p>
    </a>

</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-users"></i>
        <p>
            Users
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>List</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="#" class="nav-link {{ (Request::is('society/*')) ? 'active' : '' }}">
        <i class="nav-icon fas fa-gopuram"></i>
        <!-- <i class="fa-sharp fa-regular fa-house-building"></i> -->
        <p>
            Societies
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.society.create') }}" class="nav-link {{url()->current() == route('admin.society.create') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.society.list') }}" class="nav-link {{url()->current() == route('admin.society.list') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>List</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link {{ (Request::is('block/*')) ? 'active' : '' }}">
        <i class="nav-icon fas fa-solid fa-building"></i>
        <p>
            Block
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.block.create') }}" class="nav-link {{url()->current() == route('admin.block.create') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.block.list') }}" class="nav-link {{url()->current() == route('admin.block.list') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>List</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link {{ (Request::is('plot/*')) ? 'active' : '' }}">
        <i class="nav-icon fas far fa-map"></i>
        <p>
            Plots
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.plot.create') }}" class="nav-link {{url()->current() == route('admin.plot.create') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.plot.list') }}" class="nav-link {{url()->current() == route('admin.plot.list') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>List</p>
            </a>
        </li>
    </ul>
</li>

<li class="nav-item">
    <a href="#" class="nav-link {{ (Request::is('flat/*')) ? 'active' : '' }}">
        <i class="nav-icon fas fa-house-user"></i>
        <p>
            Flats
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.flat.create') }}" class="nav-link {{url()->current() == route('admin.flat.create') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.flat.list') }}" class="nav-link {{url()->current() == route('admin.flat.list') ? 'active' : ''}}">
                <i class="far fa-circle nav-icon"></i>
                <p>List</p>
            </a>
        </li>
    </ul>
</li>


<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-hand-holding-usd"></i>
        <p>
            Maintenance
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.maintenance.create') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Add</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.maintenance.list') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>List</p>
            </a>
        </li>
    </ul>
</li>


<?php /* ?>
<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link">
        <i class="nav-icon fas fa-th"></i>
        <p>
            Widgets
            <span class="right badge badge-danger">New</span>
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-copy"></i>
        <p>
            Layout Options
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">6</span>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Top Navigation</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Top Navigation + Sidebar</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Boxed</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Fixed Sidebar</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Fixed Sidebar <small>+ Custom Area</small></p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Fixed Navbar</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Fixed Footer</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Collapsed Sidebar</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-chart-pie"></i>
        <p>
            Charts
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>ChartJS</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Flot</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Inline</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>uPlot</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-tree"></i>
        <p>
            UI Elements
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>General</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Icons</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Buttons</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Sliders</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Modals & Alerts</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Navbar & Tabs</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Timeline</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Ribbons</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-edit"></i>
        <p>
            Forms
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>General Elements</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Advanced Elements</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Editors</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Validation</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-table"></i>
        <p>
            Tables
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Simple Tables</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>DataTables</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>jsGrid</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-header">EXAMPLES</li>
<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link">
        <i class="nav-icon far fa-calendar-alt"></i>
        <p>
            Calendar
            <span class="badge badge-info right">2</span>
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link">
        <i class="nav-icon far fa-image"></i>
        <p>
            Gallery
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link">
        <i class="nav-icon fas fa-columns"></i>
        <p>
            Kanban Board
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon far fa-envelope"></i>
        <p>
            Mailbox
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Inbox</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Compose</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Read</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-book"></i>
        <p>
            Pages
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Invoice</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Profile</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>E-commerce</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Projects</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Project Add</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Project Edit</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Project Detail</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Contacts</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>FAQ</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Contact us</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon far fa-plus-square"></i>
        <p>
            Extras
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                    Login & Register v1
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Login v1</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Register v1</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Forgot Password v1</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Recover Password v1</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                    Login & Register v2
                    <i class="fas fa-angle-left right"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Login v2</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Register v2</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Forgot Password v2</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Recover Password v2</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Lockscreen</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Legacy User Menu</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Language Menu</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Error 404</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Error 500</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Pace</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Blank Page</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Starter Page</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-search"></i>
        <p>
            Search
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Simple Search</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="javascript:void(0);" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Enhanced</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-header">MISCELLANEOUS</li>
<li class="nav-item">
    <a href="iframe.html" class="nav-link">
        <i class="nav-icon fas fa-ellipsis-h"></i>
        <p>Tabbed IFrame Plugin</p>
    </a>
</li>
<li class="nav-item">
    <a href="javascript:void(0);" class="nav-link">
        <i class="nav-icon fas fa-file"></i>
        <p>Documentation</p>
    </a>
</li>
<li class="nav-header">MULTI LEVEL EXAMPLE</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="fas fa-circle nav-icon"></i>
        <p>Level 1</p>
    </a>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-circle"></i>
        <p>
            Level 1
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Level 2</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                    Level 2
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Level 3</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Level 3</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Level 3</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Level 2</p>
            </a>
        </li>
    </ul>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="fas fa-circle nav-icon"></i>
        <p>Level 1</p>
    </a>
</li>
<li class="nav-header">LABELS</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon far fa-circle text-danger"></i>
        <p class="text">Important</p>
    </a>
</li>
<li class="nav-item">
    <a href="#" class="nav-link">
        <i class="nav-icon far fa-circle text-warning"></i>
        <p>Warning</p>
    </a>
</li>
<li class="nav-item mb-5">
    <a href="#" class="nav-link">
        <i class="nav-icon far fa-circle text-info"></i>
        <p>Informational</p>
    </a>
</li>
<?php */ ?>