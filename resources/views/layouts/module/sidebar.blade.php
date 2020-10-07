<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <span class="brand-text font-weight-light">TokoQta</span>
    </a>
​
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src='/storage/photos/{{ Auth::user()->profile_photo }}'  class="img-circle elevation-2" alt="User Image" style="width:50px;height:50px;">
            </div>
            <br>
            <div class="info">
                <a href="#" class="d-block">
                     {{Auth::user()->name}}
                </a>
            </div>
        </div>
​
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview menu-open">
                    <a href="{{route('home')}}" class="nav-link active">
                        <i class="nav-icon fa fa-dashboard"></i>
                        <p>
                            Dashboard
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                </li>

                
                <li class="nav-item has-treeview">
                <a href="{{route('produk.index')}}" class="nav-link">
                        <i class="nav-icon fa fa-server"></i>
                        <p>
                            Manajemen Produk
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a> 
                    <ul class="nav nav-treeview">
                    @if(auth()->user()->can('Create Category') && auth()->user()->can('Edit Category') && auth()->user()->can('Delete Category'))
                        <li class="nav-item">
                            <a href="{{ route('kategori.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->can('Create Product') && auth()->user()->can('Edit Product') && auth()->user()->can('Delete Product'))
                        <li class="nav-item">
                            <a href="{{route('produk.index')}}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                

                @role('Owner')
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Manajemen Users
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('role.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Role</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('users.roles_permission') }}" class="nav-link">
                                <i class="fa fa-circle-o nav-icon"></i>
                                <p>Role Permission</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole

                @if(auth()->user()->can('Edit Customer') || auth()->user()->can('Delete Customer'))
                <li class="nav-item">
                    <a href="{{ route('customer.index') }}" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Manajemen Customer
                        </p>
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('order.index') }}" class="nav-link">
                        <i class="nav-icon fa fa-shopping-cart"></i>
                        <p>
                            Manajemen Order
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('setting.profile') }}" class="nav-link">
                        <i class="nav-icon fa fa-gear"></i>
                        <p>
                            Setting Profile
                        </p>
                    </a>
                </li>
                
                @if(auth()->user()->can('Check Ongkir'))
                <li class="nav-item">
                    <a href="{{ route('ongkir') }}" class="nav-link">
                        <i class="nav-icon fa fa-truck"></i>
                        <p>
                            Cek Ongkir
                        </p>
                    </a>
                </li>
                @endif

                <li class="nav-item has-treeview">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                        <i class="nav-icon fa fa-sign-out"></i>
                        <p>
                            {{ __('Logout') }}
                        </p>
                    </a>
                ​
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
