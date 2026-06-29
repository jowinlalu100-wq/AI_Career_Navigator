<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Career Navigator</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /*
            Shell overrides — style.css isn't visible from here, so these
            take over the page-level chrome (background, full-bleed sizing,
            nav) without touching anything page-specific like profile.blade.php.
            The !important calls are only on the handful of properties that
            were causing the white background / non-full-screen issue; safe
            to remove once the same rules are cleaned up in style.css itself.
        */

        html, body {
            margin: 0 !important;
            padding: 0 !important;
            min-height: 100% !important;
            background: #0A0E1F !important;
            color: #EDEFF7;
            font-family: 'Inter', sans-serif;
            
        }

        .layout {
            display: flex !important;
            min-height: 100vh !important;
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            background: #0A0E1F !important;
        }

        .main-content {
            flex: 1 !important;
            display: flex;
            flex-direction: column;
            min-height: 100vh !important;
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            background: #0A0E1F !important;
            padding-top: 88px;
        }

        .content {
            flex: 1;
            width: 100%;
        }

        /* ── Top bar (the conditional "Welcome, Name" header) ── */
        .topbar {
            padding: 0 32px 24px;
            display: flex;
            align-items: center;
            background: transparent;
        }
        .topbar h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 19px;
            font-weight: 600;
            color: #EDEFF7;
            margin: 0;
        }

        /* ── Glass nav bar (replaces the floating white circle) ── */
       /* 1. Add a smooth transition to your top bar */
        /* =========================
   TOP NAVBAR
========================= */

.glass-topnav{
    position:fixed;
    top:26px;
    left:16px;
    right:16px;

    height:56px;
   
    display:flex;
    align-items:center;
    gap:4px;

    padding:0 16px;

    z-index:1000;

    background:rgba(18,22,44,.55);
    backdrop-filter:blur(16px);
    -webkit-backdrop-filter:blur(16px);

    border:1px solid rgba(108,99,255,.25);
    border-radius:16px;

    box-shadow:0 10px 30px rgba(0,0,0,.35);
}

.glass-topnav-brand{
    font-family:'Space Grotesk',sans-serif;
    font-size:14px;
    font-weight:600;
    color:#EDEFF7;
    margin-left: 70px;
}

/* =========================
   HAMBURGER
========================= */

.hamburger-btn{
    width:38px;
    height:38px;
    top:8px;
    border:none;
    border-radius:10px;

    background:rgba(108,99,255,.12);

    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    gap:4px;

    cursor:pointer;

    flex-shrink:0;
}

.hamburger-btn span{
    width:16px;
    height:2px;
    border-radius:2px;
    background:#EDEFF7;
}

.hamburger-btn:hover{
    background:rgba(108,99,255,.22);
}

/* =========================
   OVERLAY
========================= */

.overlay{
    position:fixed;
    inset:0;

    background:rgba(0,0,0,.45);

    opacity:0;
    visibility:hidden;

    transition:.3s;

    z-index:998;
}

.overlay.active{
    opacity:1;
    visibility:visible;
}

/* =========================
   SIDEBAR
========================= */

.drawer{
    position:fixed;

    top:0;
    left:0;

    width:280px;
    height:100vh;

    padding:28px 22px;

    background:#12162C;

    border-right:1px solid rgba(108,99,255,.25);

    transform:translateX(-100%);
    transition:transform .3s ease;

    z-index:999;

    display:flex;
    flex-direction:column;
}

.drawer.active{
    transform:translateX(0);
}

.close-btn{
    position:absolute;
    top:20px;
    right:18px;

    background:none;
    border:none;

    color:#9CA3C4;

    cursor:pointer;
    font-size:18px;
}

.close-btn:hover{
    color:white;
}

.drawer-links{
    display:flex;
    flex-direction:column;
    gap:8px;
}

.drawer-links a{
    color:#9CA3C4;
    text-decoration:none;

    padding:12px;
    border-radius:10px;

    transition:.2s;
}

.drawer-links a:hover{
    background:rgba(108,99,255,.15);
    color:white;
}
.glass-topnav {
    transition: all 0.3s ease;
}

/* Hide hamburger */
.glass-topnav.menu-open .hamburger-btn {
    opacity: 0;
    visibility: hidden;
    width: 0;
    margin: 0;
    overflow: hidden;
    transition: all 0.3s ease;
}

/* Move title right */
.glass-topnav {
    position: fixed;
    top: 16px;

    left: 16px;
    right: 16px;

    transition: all 0.3s ease;
}

/* Move entire navbar */
.glass-topnav.menu-open {
    transform: translateX(280px);
}
.glass-topnav.menu-open .hamburger-btn {
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}
/* =========================
   CONTENT
========================= */

.main-content{
    min-height:100vh;
    padding-top:88px;
}

/* =========================
   TABLET
========================= */

@media(max-width:825px){
     html,
    body {
        overflow-y: auto;
        overflow-x: hidden;
        height: auto;
    }
}

    .glass-topnav{
        left:12px;
        right:12px;
        top:12px;
    }
    .main-content{
        padding-top:80px;
    }


/* =========================
   MOBILE
========================= */

@media(max-width:480px){
    html,
    body {
        overflow-y: auto;
        overflow-x: hidden;
        height: auto;
    }
    .glass-topnav{
        height:52px;
        top:10px;
        left:10px;
        right:10px;
    }

    .glass-topnav-brand{
        font-size:12px;
    }

    .drawer{
        width:220px;
    }

    .topbar h2{
        font-size:16px;
        
    }
}
        
 
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>

<div class="layout">

    <!-- Glass nav bar -->
    <nav class="glass-topnav">
        <button id="menu-btn" class="hamburger-btn" aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>
        <a href="/dashboard" style="text-decoration: none;"><div class="glass-topnav-brand">AI Career Navigator</div></a>
    </nav>

    <div id="overlay" class="overlay"></div>

    <div id="sidebar" class="drawer">

        <button id="close-btn" class="close-btn">
            ✕
        </button>

        <h2>AI Career Navigator</h2>

        <div class="drawer-links">

            <a href="{{ route('dashboard') }}">
                Dashboard
            </a>

            <a href="/career-matches">
                Career Match
            </a>

            <a href="/live-jobs">
                Live Jobs
            </a>

            <a href="{{ route('profile.edit') }}">
                Profile
            </a>

        </div>

        <form
            method="POST"
            action="{{ route('logout') }}"
            class="logout-form">

            @csrf

            <button type="submit" class="logout-btn">
                Logout
            </button>

        </form>

    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Top Bar -->
         @if(!request()->routeIs('profile.edit'))
        <header class="topbar">

            <h2>
                Welcome {{ Auth::user()->name ?? 'User' }}
            </h2>

        </header>
        @endif
        <!-- Page Content -->
        <div class="content">

            @yield('content')

        </div>
        @include('layouts.footer')

    </div>

</div>
<script>

const menuBtn =
    document.getElementById('menu-btn');

const closeBtn =
    document.getElementById('close-btn');

const sidebar =
    document.getElementById('sidebar');

const overlay =
    document.getElementById('overlay');

function openMenu() {
    sidebar.classList.add('active');
    overlay.classList.add('active');

    document.querySelector('.glass-topnav')
        .classList.add('menu-open');
}

function closeMenu() {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');

    document.querySelector('.glass-topnav')
        .classList.remove('menu-open');
}

menuBtn.addEventListener(
    'click',
    openMenu
);

closeBtn.addEventListener(
    'click',
    closeMenu
);

overlay.addEventListener(
    'click',
    closeMenu
);

</script>
<x-career-navigator-cta
    :user-id="auth()->id()"
    :current-role="$latestResume->desired_role ?? 'Job Seeker'"
    :target-industry="$latestResume->target_industry ?? null"
/>


</body>
</html>