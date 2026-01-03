<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        {{-- SVG TETAP --}}
        {{-- (dipotong biar fokus, PUNYAMU TETAP) --}}
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2">MeRu</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    <!-- Dashboard -->
    <li class="menu-item {{ request()->routeIs('dashboard') ||
                            request()->routeIs('admin.dashboard') ||
                            request()->routeIs('staff.dashboard') ||
                            request()->routeIs('guest.dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div>Dashboard</div>
      </a>
    </li>

    {{-- ================= ADMIN ================= --}}
    @role('admin')
    <!-- Staff Management -->
    <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
      <a href="{{ route('admin.users.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user"></i>
        <div>Staff Management</div>
      </a>
    </li>

    <!-- Staff Performance -->
    <li class="menu-item {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
      <a href="{{ route('admin.staff.performance') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-bar-chart"></i>
        <div>Staff Performance</div>
      </a>
    </li>

    <!-- Buildings -->
    <li class="menu-item {{ request()->routeIs('admin.buildings.*') ? 'active' : '' }}">
      <a href="{{ route('admin.buildings.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-building"></i>
        <div>Buildings</div>
      </a>
    </li>

    <!-- Rooms -->
    <li class="menu-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
      <a href="{{ route('admin.rooms.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-door-open"></i>
        <div>Rooms</div>
      </a>
    </li>

    <!-- Facilities -->
    <li class="menu-item {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
      <a href="{{ route('admin.facilities.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-tv"></i>
        <div>Facilities</div>
      </a>
    </li>

    <!-- Bookings (Admin View) -->
    <li class="menu-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
      <a href="{{ route('admin.bookings.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-book"></i>
        <div>All Bookings</div>
      </a>
    </li>

    <!-- Admin Profile -->
    <li class="menu-item {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
      <a href="{{ route('admin.profile.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-circle"></i>
        <div>My Profile</div>
      </a>
    </li>
    @endrole

    {{-- ================= STAFF ================= --}}
    @role('staff')
    <!-- Staff Bookings (Pending Approvals) -->
    <li class="menu-item {{ request()->routeIs('staff.bookings.*') ? 'active' : '' }}">
      <a href="{{ route('staff.bookings.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-book"></i>
        <div>Booking Approvals</div>
        @php
          $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
        @endphp
        @if($pendingCount > 0)
          <span class="badge bg-danger rounded-pill ms-auto">{{ $pendingCount }}</span>
        @endif
      </a>
    </li>

    <!-- Staff Rooms View -->
    <li class="menu-item {{ request()->routeIs('staff.rooms.*') ? 'active' : '' }}">
      <a href="{{ route('staff.rooms.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-door-open"></i>
        <div>Room Status</div>
      </a>
    </li>

    <!-- Staff Profile -->
    <li class="menu-item {{ request()->routeIs('staff.profile.*') ? 'active' : '' }}">
      <a href="{{ route('staff.profile.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-circle"></i>
        <div>My Profile</div>
      </a>
    </li>
    @endrole

    {{-- ================= GUEST ================= --}}
    @role('guest')

    <!-- My Bookings -->
    <li class="menu-item {{ request()->routeIs('guest.bookings.*') ? 'active' : '' }}">
      <a href="{{ route('guest.bookings.history') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-history"></i>
        <div>My Bookings</div>
      </a>
    </li>

    <!-- Browse Rooms -->
    <li class="menu-item {{ request()->routeIs('guest.rooms.*') ? 'active' : '' }}">
      <a href="{{ route('guest.rooms.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-search"></i>
        <div>Browse Rooms</div>
      </a>
    </li>

    <!-- Guest Profile -->
    <li class="menu-item {{ request()->routeIs('guest.profile.index') ? 'active' : '' }}">
      <a href="{{ route('guest.profile.index') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-circle"></i>
        <div>My Profile</div>
      </a>
    </li>
    @endrole

    <!-- Logout -->
    <li class="menu-item">
      <a href="#" class="menu-link"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="menu-icon tf-icons bx bx-log-out"></i>
        <div>Logout</div>
      </a>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </li>

  </ul>
</aside>
<!-- / Menu -->
