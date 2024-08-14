<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}">
          <i class="fa fa-tachometer menu-icon"></i>
          <span class="menu-title">Dashboard</span>
        </a>
      </li>
      <li class="nav-item nav-category">Transaksi</li>
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/penjualan') }}">
          <i class="menu-icon fa fa-credit-card"></i>
          <span class="menu-title">Transaksi</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#form-elements">
          <i class="menu-icon fa fa-paw"></i>
          <span class="menu-title">Salon</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/barang') }}">
          <i class="menu-icon fa fa-database"></i>
          <span class="menu-title">Stok Barang</span>
        </a>
      </li>
    </ul>
</nav>