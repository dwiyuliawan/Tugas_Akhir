<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto ?? '') }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li>
                <a href="{{route('dashboard')}}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->level == 1)
            <li class="header">MASTER</li>
            <li>
                <a href="{{route('categories.index')}}" >
                    <i class="fa fa-cube"></i> <span>Categori</span>
                </a>
            </li>
            <li>
                <a href="{{route('products.index')}}">
                    <i class="fa fa-cubes"></i> <span>Product</span>
                </a>
            </li>
            <li>
                <a href="{{route('members.index')}}">
                    <i class="fa fa-id-card"></i> <span>Member</span>
                </a>
            </li>
            <li>
                <a href="{{route('suppliers.index')}}">
                    <i class="fa fa-truck"></i> <span>Supplier</span>
                </a>
            </li>
            <li class="header">TRANSAKSI</li>
            <li>
                <a href="{{route('expenditures.index')}}">
                    <i class="fa fa-money"></i> <span>Expenditure</span>
                </a>
            </li>
            <li>
                <a href="{{route('purchases.index')}}">
                    <i class="fa fa-download"></i> <span>Purchase</span>
                </a>
            </li>
            <li>
                <a href="{{route('sales.index')}}">
                    <i class="fa fa-upload"></i> <span>Sale</span>
                </a>
            </li>
            <li>
                <a href="{{route('transactions.index')}}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Active Transaction</span>
                </a>
            </li>
            <li>
                <a href="{{route('transactions.new')}}">
                    <i class="fa fa-cart-arrow-down"></i> <span>New Transaction</span>
                </a>
            </li>
            <li class="header">REPORT</li>
            <li>
                <a href="{{route('report.index')}}">
                    <i class="fa fa-file-pdf-o"></i> <span>Report</span>
                </a>
            </li>
            <li class="header">SYSTEM</li>
            <li>
                <a href="{{route('users.index')}}">
                    <i class="fa fa-users"></i> <span>User</span>
                </a>
            </li>
            <li>
                <a href="{{route('setting.index')}}">
                    <i class="fa fa-cogs"></i> <span>Seetting</span>
                </a>
            </li>
            @else
            <li>
                <a href="{{ route('transactions.index') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Active Transaction</span>
                </a>
            </li>
            <li>
                <a href="{{ route('transactions.new') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>New Transaction</span>
                </a>
            </li>
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>