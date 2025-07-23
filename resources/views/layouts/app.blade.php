
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Animate CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'نظام إدارة الشحنات') }}</title>

    <!-- الخطوط -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- بوتستراب -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    
    <!-- الأنماط المخصصة -->
    <style>
    
    


#colorOptions {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.4s ease, padding 0.4s ease;
  padding: 0 10px;
  background: white;
  border-radius: 10px;
  width: 180px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  margin-top: 10px;
}

#colorSwitcher.open #colorOptions {
  max-height: 220px;
  padding: 10px;
}

.color-circle {
  display: inline-block;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  margin: 5px;
  cursor: pointer;
  border: 2px solid transparent;
  transition: border-color 0.3s;
}
.color-circle:hover {
  border-color: #000;
}



















    
    
  .mobile-menu {
    position: fixed;
    top: 0;
    right: 0;
    width: 260px;
    height: 100%;
    background: #343a40;
    z-index: 2000;
    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
    transform: translateX(100%);
    transition: transform 0.3s ease-in-out;
    overflow-y: auto;
    color: white;
}

.mobile-menu.show {
    transform: translateX(0%);
}

.mobile-link {
    display: block;
    padding: 10px 0;
    color: white;
    text-decoration: none;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.mobile-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    padding-right: 5px;
    transition: all 0.2s;
}

.menu-overlay {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    bottom: 0;
    background: rgba(0,0,0,0.4);
    z-index: 1500;
}

    
    
    
    
@media print {
    nav, .sidebar, .navbar, .btn, .actions, .pagination {
        display: none !important;
    }
}

/* إخفاء السايد بار على الشاشات الصغيرة */
@media (max-width: 950px) {
    .sidebar {
        display: none; /* إخفاء السايد بار */
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        background-color: #343a40;
        z-index: 9999;
    }

    .menu-icon {
        display: block; /* إظهار الأيقونة على الهاتف */
    }
}

/* إظهار السايد بار على الشاشات الكبيرة */
@media (min-width: 950px) {
    .sidebar {
        display: block !important;
        }

    .menu-icon {
        display: none; /* إخفاء الأيقونة على الكمبيوتر */
    }
}


#layout {
    display: flex;
    transition: all 0.3s ease-in-out;
        flex-wrap: nowrap;
    overflow-x: auto;
}

.sidebar {
    max-width: 300px;
        position: relative;

    width: 100%;
    overflow: hidden;
    transition: all 0.3s ease;
    background: #343a40;
    min-height: 100vh;
}

.sidebar.collapsed {
    width: 50px;
}

.sidebar.collapsed ul,
.sidebar.collapsed .nav-link span,
.sidebar.collapsed .sidebar-header h5 {
    display: none;
}

.sidebar-header button {
    color: white;
    transition: color 0.3s;
}

.sidebar.collapsed .sidebar-header button {
    color: #212529;
}


.main-content {
    transition: all 0.3s ease-in-out;
    padding: 20px;
    width: 100%;
    flex: 1 1 auto;
    min-width: 0;
    overflow-x: auto;

}

#toggleSidebarBtn {
    background: transparent;
    border: none;
    color: white;
    font-size: 18px;
    cursor: pointer;
    transition: color 0.3s;
}

#toggleSidebarBtn:hover {
    color: #ffc107;
}







        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }
       
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 5px;
        }
        .sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .nav-link.active {
            color: white;
            background-color: #007bff;
        }
        .main-content {
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .status-delivered {
            background-color: rgba(40, 167, 69, 0.2);
        }
        .status-returned {
            background-color: rgba(220, 53, 69, 0.2);
        }
        .status-custody {
            background-color: rgba(0, 123, 255, 0.2);
        }
        
        
        
        
        body.sidebar-collapsed .sidebar {
    width: 60px !important;
    overflow: hidden;
}

body.sidebar-collapsed .sidebar .nav-link span,
body.sidebar-collapsed .sidebar .sidebar-header h5,
body.sidebar-collapsed .sidebar ul {
    display: none !important;
}

    </style>
    
</head>
<body>

@php
    $userThemeColor = auth()->check() ? auth()->user()->theme_color ?? '#2C3E50' : '#2C3E50';
@endphp

<style>
    .sidebar {
        background-color: {{ $userThemeColor }};
    }
    nav.navbar {
        background-color: {{ $userThemeColor }};
        border-color: {{ $userThemeColor }};
    }
    .btn-primary {
        background-color: {{ $userThemeColor }};
        border-color: {{ $userThemeColor }};
    }
</style>


          
@php
    $userRole = auth()->check() ? auth()->user()->role : null;
@endphp
 @auth

@if (!in_array(auth()->user()->role, ['delivery_agent', 'moderator']))

@if(auth()->check())
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm fixed-top">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- يمين: اللوجو واسم الشركة -->
        <div class="d-flex align-items-center">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="/logo.png" alt="Logo" width="30" height="30" class="me-2">
                إدارة مكتب
            </a>
        </div>


@if($userRole == 'admin' || $userRole == 'accountant')
    <button class="d-md-none btn btn-outline-dark me-2" id="menuButton" onclick="toggleSidebar()">
        <i class="bi bi-list fs-4"></i> <!-- أيقونة القائمة -->
    </button>
@endif



        <!-- شمال: اسم المستخدم وزر تسجيل الخروج -->
        <div class="d-flex align-items-center">
            <span class="me-3">مرحبًا، {{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-left"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </div>
</nav>
@endif

@if(auth()->check() && auth()->user()->expires_at && now()->diffInHours(auth()->user()->expires_at, false) <= 3 && now()->lt(auth()->user()->expires_at))
    <div id="expiry-warning" class="alert alert-warning text-center fixed-top m-0" style="z-index: 9999; font-size: 16px;">
        <i class="bi bi-exclamation-triangle-fill"></i>
        حسابك سينتهي خلال {{ now()->diffForHumans(auth()->user()->expires_at, ['parts' => 2]) }}.
        الرجاء التواصل مع الإدارة.
    </div>

    <!-- صوت تنبيهي -->
    <audio id="alertSound" autoplay>
        <source src="{{ asset('sounds/alert.mp3') }}" type="audio/mpeg">
    </audio>

    <!-- تنبيه منبثق لمرة واحدة فقط -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
          const savedColor = null; // تجاهل localStorage

            if (!sessionStorage.getItem('expiryPopupShown')) {
                alert('⚠️ تنبيه: حسابك سينتهي خلال ساعات قليلة.\nيرجى التواصل مع الإدارة لتجديده.');
                sessionStorage.setItem('expiryPopupShown', '1');
            }

            // تأخير بسيط لتشغيل الصوت على بعض الأجهزة
            const audio = document.getElementById('alertSound');
            if (audio) {
                setTimeout(() => audio.play().catch(() => {}), 100);
            }
        });
    </script>
@endif

<!-- مسافة علشان الـ Navbar ما يغطيش المحتوى -->
<div style="height: 70px;"></div>





<!-- قائمة الموبايل -->
<div id="mobileMenu" class="mobile-menu d-none">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center text-white">
        <span class="fw-bold">القائمة</span>
        <button onclick="toggleMobileMenu()" class="btn btn-sm btn-light">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <ul class="list-unstyled p-3 text-white">
        @if($userRole === 'admin')
            <li><a href="{{ route('dashboard') }}" class="mobile-link"><i class="bi bi-speedometer2 me-2"></i> لوحة التحكم</a></li>
            <li><a href="{{ route('shipping-companies.index') }}" class="mobile-link"><i class="bi bi-building me-2"></i> شركات الشحن</a></li>
            <li><a href="{{ route('shipments.index') }}" class="mobile-link"><i class="bi bi-box-seam me-2"></i> كل الشحنات</a></li>
            <li><a href="{{ route('shipment-statuses.index') }}" class="mobile-link"><i class="bi bi-tag me-2"></i> حالات الشحنة</a></li>
            <li><a href="{{ route('products.index') }}" class="mobile-link"><i class="bi bi-bag me-2"></i> المنتجات</a></li>
            <li><a href="{{ route('delivery-agents.index') }}" class="mobile-link"><i class="bi bi-person-badge me-2"></i> المندوبين</a></li>
        @endif

        @if(in_array($userRole, ['admin', 'accountant']))
            <li><a href="{{ route('accounting.index') }}" class="mobile-link"><i class="bi bi-cash-stack me-2"></i> الحسابات</a></li>
            <li><a href="{{ route('reports.index') }}" class="mobile-link"><i class="bi bi-bar-chart-line me-2"></i> التقارير</a></li>
        @endif

        @if($userRole === 'admin')
            <li><a href="{{ route('users.index') }}" class="mobile-link"><i class="bi bi-people me-2"></i> المستخدمين</a></li>
            <li><a href="{{ route('settings.index') }}" class="mobile-link"><i class="bi bi-gear me-2"></i> الإعدادات</a></li>
            <li><a href="{{ route('backup.index') }}" class="mobile-link"><i class="bi bi-cloud-arrow-down me-2"></i> النسخ الاحتياطي</a></li>
        @endif
    </ul>
</div>

<!-- طبقة خلفية سوداء -->
<div id="menuOverlay" class="menu-overlay d-none" onclick="toggleMobileMenu()"></div>


    <div class="container-fluid">
        <!-- زر فتح القائمة في الهاتف -->
        <div class="row">

<div class="d-flex" id="layout">

<div class="sidebar" id="sidebar">

    <div class="sidebar-header d-flex justify-content-end p-2">
        <button id="toggleSidebarBtn" onclick="toggleSidebar()">
            <i id="toggleIcon" class="bi bi-chevron-double-left"></i>
        </button>
    </div>
    
    <div class="position-sticky pt-3">

        <div class="text-center mb-4">
        </div>
        <ul class="nav flex-column">
  @if($userRole === 'admin')
            
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>لوحة التحكم</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shipping-companies.index') }}"><i class="bi bi-building me-2"></i>شركات الشحن</a></li>
<li class="nav-item">
    <a class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#shipmentsMenu" role="button" aria-expanded="false" aria-controls="shipmentsMenu">
        <span><i class="bi bi-box-seam me-2"></i> الشحنات</span>
        <i class="bi bi-chevron-down small"></i>
    </a>
    <div class="collapse {{ request()->is('shipments*') || request()->is('shipment-statuses*') ? 'show' : '' }}" id="shipmentsMenu">
        <ul class="nav flex-column ms-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('shipments.*') ? 'active' : '' }}" href="{{ route('shipments.index') }}">كل الشحنات</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('shipment-statuses.*') ? 'active' : '' }}" href="{{ route('shipment-statuses.index') }}">حالات الشحنة</a>
            </li>
        </ul>
    </div>
</li>                <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}"><i class="bi bi-bag me-2"></i>المنتجات</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('delivery-agents.index') }}"><i class="bi bi-person-badge me-2"></i>المندوبين</a></li>
            @endif

            {{-- الحسابات والتقارير للمحاسب والأدمن --}}
            @if(in_array($userRole, ['admin', 'accountant']))
                <li class="nav-item"><a class="nav-link" href="{{ route('accounting.index') }}"><i class="bi bi-cash-stack me-2"></i>الحسابات</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('reports.index') }}"><i class="bi bi-bar-chart-line me-2"></i>التقارير</a></li>
            @endif

            @if($userRole === 'admin')
                <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}"><i class="bi bi-people me-2"></i>المستخدمين</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('settings.index') }}"><i class="bi bi-gear me-2"></i>الإعدادات</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{ route('backup.index') }}"><i class="bi bi-cloud-arrow-down me-2"></i> النسخ الاحتياطي</a></li>

            @endif
        </ul>
        
    </div>
    

</div>

@endif
@endauth

            <!-- المحتوى الرئيسي -->
             <div style="overflow-x: auto; width: 100%;">
            <main class="main-content" id="mainContent">
                <!-- شريط التنقل العلوي -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('title')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('actions')
                    </div>
                </div>

                <!-- رسائل النجاح والخطأ -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- المحتوى -->
                @yield('content')
            </main>
            </div>
            </div>
            
        </div>
    </div>
@section('scripts')
    <!-- جافاسكريبت -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    @stack('scripts')

    @yield('scripts')
<script>
function toggleDesktopSidebar() {
    const sidebar = document.getElementById('sidebar');
    const icon = document.getElementById('toggleIcon');

    sidebar.classList.toggle('collapsed');

    // تغيير الأيقونة
    icon.classList.toggle('bi-chevron-double-left');
    icon.classList.toggle('bi-chevron-double-right');
}
</script>

<audio id="success-sound" src="{{ asset('sounds/success.mp3') }}" preload="auto"></audio>




<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebarBtn');
    const menuButton = document.getElementById('menuButton');

    function toggleSidebar() {
        document.body.classList.toggle('sidebar-collapsed');
        const icon = document.getElementById('toggleIcon');
        if (icon) {
            icon.classList.toggle('bi-chevron-double-left');
            icon.classList.toggle('bi-chevron-double-right');
        }
    }

    // زر التصغير
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    }

    // إخفاء تلقائي على الشاشات الصغيرة
    function responsiveSidebar() {
        if (window.innerWidth <= 950) {
            sidebar?.classList.add('d-none');
            document.body.classList.remove('sidebar-collapsed');
        } else {
            sidebar?.classList.remove('d-none');
        }
    }

    responsiveSidebar();
    window.addEventListener('resize', responsiveSidebar);

    // زر القائمة للموبايل
    if (menuButton) {
        menuButton.addEventListener('click', function () {
            sidebar?.classList.toggle('d-none');
        });
    }
});
</script>






<div id="colorSwitcher" style="position: fixed; bottom: 20px; left: 20px; z-index: 1050;">
  <button id="toggleColorPicker"
      style="background:#2C3E50; border:none; border-radius: 50%; width: 48px; height: 48px; color:white; font-size:24px; cursor:pointer; box-shadow:0 0 8px rgba(0,0,0,0.3);">
      <i class="bi bi-palette"></i>
  </button>
  <div id="colorOptions">
  <span title="أزرق داكن ملكي" class="color-circle" data-color="#1F3A93" style="background:#1F3A93;"></span>
  <span title="أخضر زمردي عميق" class="color-circle" data-color="#196F3D" style="background:#196F3D;"></span>
  <span title="ذهبي معتّم" class="color-circle" data-color="#B8860B" style="background:#B8860B;"></span>
  <span title="خمري نبيذي" class="color-circle" data-color="#7B1F28" style="background:#7B1F28;"></span>
  <span title="رمادي فحمي" class="color-circle" data-color="#2F2F2F" style="background:#2F2F2F;"></span>
  <span title="أزرق رمادي داكن" class="color-circle" data-color="#34495E" style="background:#34495E;"></span>
  <span title="بنفسجي غامق" class="color-circle" data-color="#4B0082" style="background:#4B0082;"></span>
  </div>
</div>




<script>
document.addEventListener('DOMContentLoaded', function () {
  const toggleBtn = document.getElementById('toggleColorPicker');
  const colorSwitcher = document.getElementById('colorSwitcher');
  const colorOptions = document.getElementById('colorOptions');

  toggleBtn.addEventListener('click', () => {
    colorSwitcher.classList.toggle('open');
  });

  colorOptions.addEventListener('click', (e) => {
    if (e.target.classList.contains('color-circle')) {
      const selectedColor = e.target.dataset.color;
      applyThemeColor(selectedColor);
      saveColorToServer(selectedColor);
      colorSwitcher.classList.remove('open');
    }
  });

  // لا تستخدم localStorage هنا، بل استعمل اللون الموجود في Blade مباشرة
  // لأن لون المستخدم يتم جلبه من السيرفر عبر Blade في CSS inline

  // تطبيق اللون من Blade (موجود في الستايل المضمّن في صفحة HTML)
  // لذلك ما تحتاج تطبقه هنا عند التحميل

  function applyThemeColor(color) {
    const sidebar = document.querySelector('.sidebar');
    const navbar = document.querySelector('nav.navbar');
    const btns = document.querySelectorAll('.btn-primary, .btn-success, .btn-danger');

    if (sidebar) sidebar.style.backgroundColor = color;
    if (navbar) {
      navbar.style.backgroundColor = color;
      navbar.style.borderColor = color;
    }
    btns.forEach(btn => {
      if (btn.classList.contains('btn-primary')) {
        btn.style.backgroundColor = color;
        btn.style.borderColor = color;
      }
      if (btn.classList.contains('btn-success')) {
        btn.style.backgroundColor = '#27AE60';
        btn.style.borderColor = '#27AE60';
      }
      if (btn.classList.contains('btn-danger')) {
        btn.style.backgroundColor = '#C0392B';
        btn.style.borderColor = '#C0392B';
      }
    });

    const sidebarLinks = sidebar.querySelectorAll('.nav-link');
    sidebarLinks.forEach(link => link.style.color = 'white');
  }

  function saveColorToServer(color) {
    fetch('/user/theme-color', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ color })
    })
    .then(res => res.json())
    .then(data => {
      console.log(data.message);

    })
    .catch(err => console.error(err));
  }
});

</script>




</body>


<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('menuOverlay');

    if (menu.classList.contains('show')) {
        menu.classList.remove('show');
        menu.classList.add('d-none');
        overlay.classList.add('d-none');
    } else {
        menu.classList.remove('d-none');
        menu.classList.add('show');
        overlay.classList.remove('d-none');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('menuButton');
    if (button) {
        button.addEventListener('click', toggleMobileMenu);
    }
});
</script>






<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleSidebarBtn');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSidebar);
        }
    });
</script>


</html>
