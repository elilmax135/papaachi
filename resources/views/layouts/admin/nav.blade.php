 <!-- { navigation menu } start -->
 <aside class="app-sidebar app-light-sidebar">
   <div class="app-navbar-wrapper">
     <div class="brand-link brand-logo">
       <a href="#" class="b-brand">
        @if(!empty($setting->logo) && file_exists(public_path('storage/' . $setting->logo)))
        <img src="{{ asset('storage/' . $setting->logo) }}" alt="" class="logo logo-lg" style="background-color: {{ $setting->logo_color }};">
        @else
            <img src="{{ asset('https://raw.githubusercontent.com/abisanthm/abisanthm.github.io/main2/1.png') }}" alt="Default Image" class="logo logo-lg" style="background-color: {{ $setting->logo_color }};">
        @endif
       </a>
     </div>
     <div class="navbar-content">
       <ul class="app-navbar">
        <li class="nav-item">
            <a href="/" class="nav-link"><span class="nav-icon"><i class="ti ti-layout-2"></i></span><span class="nav-text">Dashboard</span></a>
        </li>
        <li class="nav-item">
          <a href="/branches" class="nav-link"><span class="nav-icon"><i class="ti ti-layout-2"></i></span><span class="nav-text">Branchges</span></a>
      </li>
        <li class="nav-item">
            <a href="/databases" class="nav-link"><span class="nav-icon"><i class="ti ti-layout-2"></i></span><span class="nav-text">Databases</span></a>
        </li>
        <li class="nav-item nav-hasmenu">
            <a href="#!" class="nav-link">
              <span class="nav-icon">
                <i class="ti ti-layout-2"></i>
              </span>
              <span class="nav-text">Stock</span>
              <span class="nav-arrow">
                <i data-feather="chevron-right"></i>
              </span>
            </a>
            <ul class="nav-submenu">
              <li class="nav-item">
                <a class="nav-link" href="{{ url('/box_stock') }}">Boxes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Flowers</a>
              </li>
            </ul>
          </li>

        <li class="nav-item nav-hasmenu">
           <a href="#!" class="nav-link">
             <span class="nav-icon">
               <i class="ti ti-layout-2"></i>
             </span>
             <span class="nav-text">Settings</span>
             <span class="nav-arrow">
               <i data-feather="chevron-right"></i>
             </span>
           </a>
           <ul class="nav-submenu">
             <li class="nav-item">
               <a class="nav-link" href="{{ url('/settings') }}">Genarel Setting</a>
             </li>
             <li class="nav-item">
               <a class="nav-link" href="{{ url('/api') }}">REST API</a>
             </li>
           </ul>
         </li>
         <li class="nav-item">
           <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
             <span class="nav-icon">
               <i class="ti ti-layout-2"></i>
             </span>
             <span class="nav-text">Logout</span>
             <span class="nav-arrow">
               </i>
             </span>
           </a>
           <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"> @csrf </form>
         </li>
       </ul>
     </div>
   </div>
 </aside>
 <!-- { navigation menu } end -->
