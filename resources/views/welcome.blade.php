{{-- resources/views/welcome.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ config('app.name', 'NDMU Alumni Portal') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('NDMU_logo_icon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('NDMU_logo_icon.ico') }}" type="image/x-icon">

    <style>
        /* =========================================================
           NDMU Landing (scoped styles) — won't affect other pages
           ========================================================= */
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-green-2:#083325;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --page:#FAFAF8;
            --ink:#0f172a;
            --muted:#475569;
            --line:rgba(15,23,42,.10);
            --shadow: 0 10px 26px rgba(2,6,23,.08);
            --radius:16px;
        }

        /* Scope everything inside .ndmu-landing */
        .ndmu-landing, .ndmu-landing *{ box-sizing:border-box; }
        .ndmu-landing{
            margin:0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji";
            color:var(--ink);
            background:var(--page);
        }
        .ndmu-landing a{ color:inherit; text-decoration:none; }
        .ndmu-landing .container{ max-width:1180px; margin:0 auto; padding:0 16px; }

        /* =========================================================
           Top Brand Bar
           ========================================================= */
        .ndmu-landing .topbar{
            background:
                linear-gradient(90deg, rgba(227,199,122,.98), rgba(255,245,215,.96) 55%, rgba(227,199,122,.98));
            border-bottom: 6px solid var(--ndmu-green);
        }
        .ndmu-landing .topbar-inner{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:14px;
            padding:14px 0;
            flex-wrap:wrap;
        }
        .ndmu-landing .brand{
            display:flex; align-items:center; gap:12px; min-width:220px;
        }
        .ndmu-landing .brand-badge{
            width:46px; height:46px; border-radius:14px;
            background: rgba(11,61,46,.08);
            border:1px solid rgba(11,61,46,.18);
            display:flex; align-items:center; justify-content:center;
            overflow:hidden;
            flex:0 0 46px;
        }
        .ndmu-landing .brand-badge img{ width:100%; height:100%; object-fit:cover; display:block; }
        .ndmu-landing .brand-title{ line-height:1.15; }
        .ndmu-landing .brand-title strong{
            display:block; font-size:13px; letter-spacing:.35px; font-weight:900;
        }
        .ndmu-landing .brand-title span{
            display:block; font-size:12px; color: rgba(15,23,42,.70);
            font-weight:600;
        }

        /* Buttons */
        .ndmu-landing .actions{
            display:flex; gap:10px; align-items:center; flex-wrap:wrap; justify-content:flex-end;
        }
        .ndmu-landing .btn{
            display:inline-flex; align-items:center; justify-content:center;
            padding:10px 14px; border-radius:12px;
            border:1px solid transparent;
            font-weight:900; font-size:13px;
            cursor:pointer;
            transition:.15s ease;
            white-space:nowrap;
            box-shadow: 0 6px 14px rgba(2,6,23,.06);
        }
        .ndmu-landing .btn-primary{ background:var(--ndmu-green); color:#fff; }
        .ndmu-landing .btn-primary:hover{ background:var(--ndmu-green-2); }
        .ndmu-landing .btn-outline{
            background: rgba(255,255,255,.45);
            border-color: rgba(11,61,46,.22);
            color: var(--ndmu-green);
        }
        .ndmu-landing .btn-outline:hover{ filter:brightness(.98); }
        .ndmu-landing .btn-soft{
            background: var(--paper);
            border-color: rgba(227,199,122,.85);
            color: var(--ndmu-green);
        }
        .ndmu-landing .btn-soft:hover{ filter:brightness(.98); }
        .ndmu-landing .btn-danger{
            background:#991B1B;
            color:#fff;
        }
        .ndmu-landing .btn-danger:hover{ filter:brightness(.95); }

        /* =========================================================
           Sticky Nav + Mobile Drawer
           ========================================================= */
        .ndmu-landing .nav{
            position:sticky;
            top:0;
            z-index:50;
            background: rgba(2,6,23,.92);
            color:#fff;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .ndmu-landing .nav-inner{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            padding:10px 0;
        }

        .ndmu-landing .nav-left{
            display:flex; align-items:center; gap:10px;
            min-width: 0;
        }

        .ndmu-landing .nav-title{
            display:flex; align-items:center; gap:10px;
            min-width:0;
        }
        .ndmu-landing .nav-dot{
            width:10px; height:10px; border-radius:999px;
            background: var(--ndmu-gold);
            box-shadow: 0 0 0 3px rgba(227,199,122,.20);
            flex:0 0 auto;
        }
        .ndmu-landing .nav-brand{
            font-size:12px;
            font-weight:900;
            letter-spacing:.6px;
            text-transform:uppercase;
            opacity:.92;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
            max-width: 52vw;
        }

        /* Desktop links row */
        .ndmu-landing .nav-links{
            display:flex;
            align-items:center;
            gap:8px;
            flex-wrap:wrap;
            justify-content:flex-end;
        }

        .ndmu-landing .nav-links > a,
        .ndmu-landing .nav-links > .dd > button{
            height:36px;
            padding:0 12px;
            border-radius:12px;
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            font-size:13px;
            font-weight:900;
            letter-spacing:.7px;
            text-transform:uppercase;
            opacity:.92;
            border:1px solid transparent;
            background: transparent;
            color:#fff;
            cursor:pointer;
            white-space:nowrap;
        }
        .ndmu-landing .nav-links > a:hover,
        .ndmu-landing .nav-links > .dd > button:hover{
            background: rgba(255,255,255,.10);
            border-color: rgba(255,255,255,.08);
            opacity:1;
        }

        /* Dropdown */
        .ndmu-landing .dd{ position:relative; display:inline-flex; }
        .ndmu-landing .dd-menu{
            position:absolute;
            top:44px;
            right:0;
            width:min(380px, calc(100vw - 24px));
            background:#fff;
            color:var(--ink);
            border:1px solid rgba(15,23,42,.12);
            border-radius:18px;
            box-shadow: var(--shadow);
            padding:10px;
            display:none;
            z-index:60;
        }
        .ndmu-landing .dd.open .dd-menu{
            display:flex;
            flex-direction:column;
            gap:6px;
        }
        .ndmu-landing .dd-item{
            display:flex;
            gap:10px;
            padding:12px;
            border-radius:14px;
            align-items:flex-start;
            border:1px solid rgba(15,23,42,.06);
            background:#fff;
        }
        .ndmu-landing .dd-item:hover{ background:#f8fafc; }
        .ndmu-landing .dd-icon{
            width:38px; height:38px; border-radius:14px;
            display:flex; align-items:center; justify-content:center;
            font-weight:900;
            border:1px solid rgba(15,23,42,.10);
            background: rgba(2,6,23,.04);
            flex:0 0 38px;
        }
        .ndmu-landing .dd-icon.green{ background:rgba(11,61,46,.12); border-color:rgba(11,61,46,.20); color:var(--ndmu-green); }
        .ndmu-landing .dd-icon.blue{ background:rgba(24,119,242,.12); border-color:rgba(24,119,242,.20); color:#1877F2; }
        .ndmu-landing .dd-meta strong{ display:block; font-size:13px; font-weight:900; }
        .ndmu-landing .dd-meta span{ display:block; font-size:12px; color:rgba(15,23,42,.65); margin-top:2px; line-height:1.35; }

        /* Mobile menu button */
        .ndmu-landing .menu-btn{
            display:none;
            height:36px;
            padding:0 12px;
            border-radius:12px;
            border:1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.08);
            color:#fff;
            font-weight:900;
            cursor:pointer;
        }
        .ndmu-landing .menu-btn:hover{ background: rgba(255,255,255,.12); }

        /* Mobile Drawer */
        .ndmu-landing .drawer-backdrop{
            position:fixed;
            inset:0;
            background: rgba(0,0,0,.55);
            display:none;
            z-index:80;
        }
        .ndmu-landing .drawer{
            position:fixed;
            top:0; right:0;
            width: min(360px, 92vw);
            height:100%;
            background: #0b1220;
            color:#fff;
            box-shadow: -12px 0 30px rgba(2,6,23,.30);
            transform: translateX(100%);
            transition: transform .18s ease;
            z-index:90;
            display:flex;
            flex-direction:column;
        }
        .ndmu-landing .drawer.open{ transform: translateX(0); }
        .ndmu-landing .drawer-backdrop.show{ display:block; }

        .ndmu-landing .drawer-head{
            padding:14px 14px 10px;
            border-bottom: 1px solid rgba(255,255,255,.10);
            display:flex; align-items:center; justify-content:space-between; gap:10px;
        }
        .ndmu-landing .drawer-head strong{
            font-size:12px;
            letter-spacing:.7px;
            text-transform:uppercase;
            color: rgba(255,255,255,.85);
        }
        .ndmu-landing .drawer-close{
            width:36px; height:36px;
            border-radius:12px;
            border:1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.08);
            color:#fff;
            font-size:22px;
            line-height:0;
            cursor:pointer;
        }
        .ndmu-landing .drawer-close:hover{ background: rgba(255,255,255,.12); }

        .ndmu-landing .drawer-body{
            padding:12px;
            overflow:auto;
            display:flex;
            flex-direction:column;
            gap:8px;
        }
        .ndmu-landing .drawer-link{
            padding:12px 12px;
            border-radius:14px;
            border:1px solid rgba(255,255,255,.10);
            background: rgba(255,255,255,.06);
            font-weight:900;
            letter-spacing:.6px;
            text-transform:uppercase;
            font-size:13px;
        }
        .ndmu-landing .drawer-link:hover{ background: rgba(255,255,255,.10); }
        .ndmu-landing .drawer-subtitle{
            margin-top:10px;
            font-size:11px;
            font-weight:900;
            letter-spacing:.7px;
            text-transform:uppercase;
            color: rgba(227,199,122,.90);
        }
        .ndmu-landing .drawer-card{
            border-radius:14px;
            border:1px solid rgba(255,255,255,.10);
            background: rgba(255,255,255,.06);
            padding:12px;
        }
        .ndmu-landing .drawer-card a{
            display:flex; gap:10px; align-items:flex-start;
            padding:10px; border-radius:12px;
        }
        .ndmu-landing .drawer-card a:hover{ background: rgba(255,255,255,.08); }
        .ndmu-landing .drawer-mini-icon{
            width:34px;height:34px;border-radius:12px;
            display:flex;align-items:center;justify-content:center;
            font-weight:900;
            background: rgba(227,199,122,.16);
            border:1px solid rgba(227,199,122,.20);
            color: var(--ndmu-gold);
            flex:0 0 34px;
        }
        .ndmu-landing .drawer-card strong{ display:block; font-size:13px; }
        .ndmu-landing .drawer-card span{ display:block; font-size:12px; color: rgba(255,255,255,.75); margin-top:2px; line-height:1.35; }

        /* =========================================================
           Hero
           ========================================================= */
        .ndmu-landing .hero{
            position:relative;
            background:
                linear-gradient(90deg, rgba(11,61,46,.94), rgba(11,61,46,.66) 55%, rgba(0,0,0,.18)),
                url("{{ asset('images/ndmu-hero.jpg') }}");
            background-size:cover;
            background-position:center;
            border-bottom: 1px solid var(--line);
        }
        .ndmu-landing .hero::after{
            content:"";
            position:absolute; inset:0;
            background: radial-gradient(circle at 20% 30%, rgba(227,199,122,.22), transparent 58%);
            pointer-events:none;
        }
        .ndmu-landing .hero-content{
            position:relative;
            color:#fff;
            padding:56px 0;
            display:grid;
            grid-template-columns: 1.15fr .85fr;
            gap:20px;
            align-items:center;
        }
        .ndmu-landing .pill{
            display:inline-flex; align-items:center; gap:8px;
            padding:7px 10px;
            border-radius:999px;
            background: rgba(255,251,240,.95);
            border:1px solid rgba(227,199,122,.85);
            color: var(--ndmu-green);
            font-weight:900;
            font-size:12px;
        }
        .ndmu-landing .pill .dot{ width:8px; height:8px; border-radius:999px; background: var(--ndmu-green); }

        .ndmu-landing .hero h1{
            margin:10px 0 10px;
            font-size:40px;
            letter-spacing:.6px;
            text-transform:uppercase;
            line-height:1.12;
        }
        .ndmu-landing .hero p{
            margin:0 0 16px;
            max-width:720px;
            color: rgba(255,255,255,.92);
            font-size:15px;
            line-height:1.65;
        }
        .ndmu-landing .hero-actions{ display:flex; gap:10px; flex-wrap:wrap; margin-top:10px; }
        .ndmu-landing .notice{
            margin-top:12px;
            font-size:12px;
            color: rgba(255,255,255,.88);
            line-height:1.55;
        }

        .ndmu-landing .hero-card{
            background: rgba(255,255,255,.10);
            border:1px solid rgba(255,255,255,.18);
            border-radius: 20px;
            padding:18px;
            backdrop-filter: blur(6px);
            box-shadow: 0 14px 30px rgba(2,6,23,.12);
        }
        .ndmu-landing .hero-card h3{
            margin:0 0 8px;
            font-size:13px;
            text-transform:uppercase;
            letter-spacing:.7px;
            color: rgba(255,255,255,.92);
            font-weight:900;
        }
        .ndmu-landing .hero-card ul{
            margin:0;
            padding-left:18px;
            color: rgba(255,255,255,.90);
            font-size:13px;
            line-height:1.65;
        }

        /* =========================================================
           Sections
           ========================================================= */
        .ndmu-landing .section{ padding:44px 0; }
        .ndmu-landing .section-title{
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            gap:14px;
            margin-bottom:16px;
        }
        .ndmu-landing .section-title h2{
            margin:0;
            font-size:18px;
            letter-spacing:.5px;
            text-transform:uppercase;
            font-weight:1000;
            color: var(--ndmu-green);
        }
        .ndmu-landing .section-title p{
            margin:0;
            color:var(--muted);
            font-size:13px;
            max-width:560px;
            line-height:1.45;
        }

        .ndmu-landing .grid{
            display:grid;
            grid-template-columns: repeat(3, 1fr);
            gap:14px;
        }
        .ndmu-landing .card{
            background:#fff;
            border:1px solid var(--line);
            border-radius: var(--radius);
            padding:16px;
            box-shadow: 0 8px 18px rgba(2,6,23,.05);
        }
        .ndmu-landing .card h3{ margin:0 0 6px; font-size:15px; font-weight:1000; }
        .ndmu-landing .card p{ margin:0; color:var(--muted); font-size:13px; line-height:1.6; }

        /* Link cards */
        .ndmu-landing .linkcard{
            display:flex;
            gap:12px;
            align-items:flex-start;
            padding:16px;
            border-radius: 18px;
            border:1px solid rgba(227,199,122,.50);
            background:#fff;
            box-shadow: 0 10px 22px rgba(2,6,23,.06);
            transition:.15s ease;
        }
        .ndmu-landing .linkcard:hover{
            transform: translateY(-1px);
            box-shadow: 0 16px 28px rgba(2,6,23,.08);
        }
        .ndmu-landing .linkicon{
            width:46px;height:46px;border-radius:16px;
            display:flex;align-items:center;justify-content:center;
            font-weight:1000;
            border:1px solid rgba(15,23,42,.10);
            background: rgba(2,6,23,.03);
            flex:0 0 46px;
        }
        .ndmu-landing .linkicon.green{
            background: rgba(11,61,46,.10);
            border-color: rgba(11,61,46,.18);
            color: var(--ndmu-green);
        }
        .ndmu-landing .linkicon.blue{
            background: rgba(24,119,242,.10);
            border-color: rgba(24,119,242,.18);
            color: #1877F2;
        }
        .ndmu-landing .linkmeta strong{ display:block; font-size:15px; font-weight:1000; }
        .ndmu-landing .linkmeta span{ display:block; font-size:13px; color:var(--muted); line-height:1.55; margin-top:2px; }
        .ndmu-landing .linkmeta em{
            display:inline-block;
            margin-top:8px;
            font-style:normal;
            font-weight:1000;
            font-size:12px;
            color: rgba(11,61,46,.92);
        }

        /* Small badge (career status) */
        .ndmu-landing .badge{
            display:inline-flex;
            align-items:center;
            padding:6px 10px;
            border-radius:999px;
            font-size:11px;
            font-weight:1000;
            border:1px solid transparent;
            white-space:nowrap;
        }

        /* =========================================================
           Footer
           ========================================================= */
        .ndmu-landing footer{
            margin-top:26px;
            background: linear-gradient(90deg, rgba(11,61,46,.96), rgba(11,61,46,.82));
            color:#fff;
            border-top: 6px solid var(--ndmu-gold);
        }
        .ndmu-landing .footer-inner{
            padding:26px 0;
            display:grid;
            grid-template-columns: 1.2fr .8fr .8fr;
            gap:16px;
        }
        .ndmu-landing .footer-inner p{
            margin:8px 0 0;
            color:rgba(255,255,255,.90);
            font-size:13px;
            line-height:1.6;
        }
        .ndmu-landing .footer-links{
            display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;
        }
        .ndmu-landing .footer-links a{
            padding:8px 10px;
            border-radius:12px;
            background: rgba(255,255,255,.10);
            border:1px solid rgba(255,255,255,.14);
            font-size:12px;
            font-weight:900;
        }
        .ndmu-landing .footer-links a:hover{ background: rgba(255,255,255,.14); }
        .ndmu-landing .copyright{
            border-top:1px solid rgba(255,255,255,.18);
            padding:12px 0;
            color:rgba(255,255,255,.85);
            font-size:12px;
        }

        /* =========================================================
           Responsive (does not affect other pages)
           ========================================================= */
        @media (max-width: 1024px){
            .ndmu-landing .hero-content{ grid-template-columns: 1fr; padding:46px 0; }
            .ndmu-landing .hero h1{ font-size:34px; }
            .ndmu-landing .footer-inner{ grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 820px){
            .ndmu-landing .section-title{ flex-direction:column; align-items:flex-start; }
            .ndmu-landing .grid{ grid-template-columns: repeat(2, 1fr); }
            .ndmu-landing .nav-brand{ max-width: 60vw; }
        }

        @media (max-width: 640px){
            /* Show mobile menu button, hide desktop link row */
            .ndmu-landing .menu-btn{ display:inline-flex; align-items:center; gap:8px; }
            .ndmu-landing .nav-links{ display:none; }

            .ndmu-landing .brand-title strong{ font-size:12.5px; }
            .ndmu-landing .brand-badge{ width:42px; height:42px; border-radius:14px; flex:0 0 42px; }

            .ndmu-landing .hero h1{ font-size:26px; }
            .ndmu-landing .hero p{ font-size:14px; }
            .ndmu-landing .hero-actions .btn{ width:100%; justify-content:center; }

            .ndmu-landing .grid{ grid-template-columns: 1fr; }

            .ndmu-landing .footer-inner{ grid-template-columns: 1fr; }
        }

        @media (max-width: 380px){
            .ndmu-landing .hero h1{ font-size:22px; }
        }

        /* Prevent iOS tap highlight weirdness */
        .ndmu-landing button, .ndmu-landing a{ -webkit-tap-highlight-color: transparent; }
    </style>
</head>

<body class="ndmu-landing">
@php
    use App\Models\CareerPost;

    $newsUrl = 'https://www.ndmu.edu.ph/news-and-updates';
    $fbUrl   = 'https://www.facebook.com/ndmuofficial/';

    // Landing-page preview (latest 6)
    $careerPosts = CareerPost::withCount('attachments')
        ->where('is_published', true)
        ->orderByDesc('id')
        ->take(6)
        ->get();
@endphp

{{-- TOP BAR --}}
<div class="topbar">
    <div class="container">
        <div class="topbar-inner">
            <div class="brand">
                <div class="brand-badge" aria-hidden="true">
                    @if(file_exists(public_path('images/ndmu-logo.png')))
                        <img src="{{ asset('images/ndmu-logo.png') }}" alt="NDMU Logo">
                    @else
                        <span style="font-weight:1000;color:var(--ndmu-green);">ND</span>
                    @endif
                </div>

                <div class="brand-title">
                    <strong>NOTRE DAME OF MARBEL UNIVERSITY</strong>
                    <span>Office of Alumni Relations</span>
                </div>
            </div>

            <div class="actions">
                @auth
                    <a class="btn btn-primary" href="{{ route('dashboard') }}">Dashboard</a>

                    @if (Route::has('portal.records.index') && in_array(auth()->user()->role, ['admin','it_admin','alumni_officer'], true))
                        <a class="btn btn-outline" href="{{ route('portal.records.index') }}">Manage Records</a>
                    @endif

                    <a class="btn btn-soft" href="{{ route('intake.form') }}">Update Alumni Tracer</a>
                @else
                    @if (Route::has('register'))
                        <a class="btn btn-danger" href="{{ route('register') }}">Register</a>
                    @endif
                    @if (Route::has('login'))
                        <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

{{-- NAV --}}
<div class="nav">
    <div class="container">
        <div class="nav-inner">
            <div class="nav-left">
                <div class="nav-title">
                    <span class="nav-dot" aria-hidden="true"></span>
                    <div class="nav-brand">NDMU Alumni Portal</div>
                </div>
            </div>

            {{-- Desktop links --}}
            <div class="nav-links" aria-label="Primary navigation">
                <a href="#home">Home</a>
                <a href="#services">Services</a>
                <a href="#programs">Programs</a>

                {{-- Events dropdown --}}
                <div class="dd" data-dd>
                    <button type="button" data-dd-btn aria-haspopup="true" aria-expanded="false">
                        Events <span aria-hidden="true">▾</span>
                    </button>

                    <div class="dd-menu" data-dd-menu role="menu" aria-label="Events menu">
                        @if(Route::has('events.index'))
                            <a class="dd-item" href="{{ route('events.index') }}" role="menuitem">
                                <div class="dd-icon green">E</div>
                                <div class="dd-meta">
                                    <strong>Events & Updates Page</strong>
                                    <span>Official links to NDMU updates (Website & Facebook).</span>
                                </div>
                            </a>
                        @endif

                        <a class="dd-item" href="{{ $newsUrl }}" target="_blank" rel="noopener" role="menuitem">
                            <div class="dd-icon green">🌐</div>
                            <div class="dd-meta">
                                <strong>NDMU News & Updates</strong>
                                <span>Open official university postings on ndmu.edu.ph</span>
                            </div>
                        </a>

                        <a class="dd-item" href="{{ $fbUrl }}" target="_blank" rel="noopener" role="menuitem">
                            <div class="dd-icon blue">f</div>
                            <div class="dd-meta">
                                <strong>NDMU Official Facebook</strong>
                                <span>Announcements, posters, and real-time updates</span>
                            </div>
                        </a>

                        @if(Route::has('events.calendar'))
                            <a class="dd-item" href="{{ route('events.calendar') }}" role="menuitem">
                                <div class="dd-icon green">📅</div>
                                <div class="dd-meta">
                                    <strong>Calendar of Events</strong>
                                    <span>View upcoming alumni and university events.</span>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>

                <a href="#careers">Careers</a>
                <a href="#news">News</a>
                <a href="#about">About</a>
            </div>

            {{-- Mobile menu button --}}
            <button type="button" class="menu-btn" id="openDrawer" aria-label="Open menu">
                ☰ Menu
            </button>
        </div>
    </div>
</div>

{{-- Mobile Drawer --}}
<div class="drawer-backdrop" id="drawerBackdrop" aria-hidden="true"></div>
<aside class="drawer" id="drawer" aria-label="Mobile menu" aria-hidden="true">
    <div class="drawer-head">
        <strong>NDMU Menu</strong>
        <button type="button" class="drawer-close" id="closeDrawer" aria-label="Close menu">&times;</button>
    </div>
    <div class="drawer-body">
        <a class="drawer-link" href="#home">Home</a>
        <a class="drawer-link" href="#services">Services</a>
        <a class="drawer-link" href="#programs">Programs</a>
        <a class="drawer-link" href="#careers">Careers</a>
        <a class="drawer-link" href="#news">News</a>
        <a class="drawer-link" href="#about">About</a>

        <div class="drawer-subtitle">Events</div>
        <div class="drawer-card">
            @if(Route::has('events.index'))
                <a href="{{ route('events.index') }}">
                    <div class="drawer-mini-icon">E</div>
                    <div>
                        <strong>Events & Updates Page</strong>
                        <span>Official links inside the portal.</span>
                    </div>
                </a>
            @endif

            <a href="{{ $newsUrl }}" target="_blank" rel="noopener">
                <div class="drawer-mini-icon">🌐</div>
                <div>
                    <strong>NDMU News & Updates</strong>
                    <span>Official website postings.</span>
                </div>
            </a>

            <a href="{{ $fbUrl }}" target="_blank" rel="noopener">
                <div class="drawer-mini-icon">f</div>
                <div>
                    <strong>NDMU Facebook</strong>
                    <span>Announcements & posters.</span>
                </div>
            </a>

            @if(Route::has('events.calendar'))
                <a href="{{ route('events.calendar') }}">
                    <div class="drawer-mini-icon">📅</div>
                    <div>
                        <strong>Calendar of Events</strong>
                        <span>Upcoming alumni/university events.</span>
                    </div>
                </a>
            @endif
        </div>
    </div>
</aside>

{{-- HERO --}}
<section id="home" class="hero">
    <div class="container">
        <div class="hero-content">
            <div>
                <div class="pill"><span class="dot" aria-hidden="true"></span> NDMU Alumni Portal</div>
                <h1>Office of Alumni Relations</h1>
                <p>
                    Welcome to the official alumni portal of Notre Dame of Marbel University.
                    This platform supports alumni engagement through accurate records, meaningful connections,
                    and coordinated alumni programs aligned with the University’s mission.
                </p>

                <div class="hero-actions">
                    @auth
                        <a class="btn btn-primary" href="{{ route('intake.form') }}">Complete Alumni Tracer</a>
                        <a class="btn btn-outline" href="{{ route('dashboard') }}">Go to Dashboard</a>
                    @else
                        <a class="btn btn-primary" href="{{ route('login') }}">Complete Alumni Tracer</a>
                        <a class="btn btn-outline" href="#services">Explore Services</a>
                        @if (Route::has('register'))
                            <a class="btn btn-danger" href="{{ route('register') }}">Create an Account</a>
                        @endif
                    @endauth
                </div>

                <p class="notice">
                    By submitting information, you consent to the processing of personal data for alumni record keeping,
                    communication, and program development in accordance with the Data Privacy Act of 2012 (RA 10173).
                </p>
            </div>

            <div class="hero-card">
                <h3>What you can do here</h3>
                <ul>
                    <li>Update your alumni profile and academic background</li>
                    <li>Provide employment and professional information for tracer reporting</li>
                    <li>Receive alumni announcements, programs, and event invitations</li>
                    <li>Express interest in mentoring, networking, and volunteer opportunities</li>
                    <li>Securely manage your account using University-supported authentication</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ONLINE SERVICES --}}
<section id="services" class="section">
    <div class="container">
        <div class="section-title">
            <h2>Online Services</h2>
            <p>Access essential alumni services designed to keep your records complete and your connection to NDMU active.</p>
        </div>

        <div class="grid">
            <div class="card">
                <h3>Alumni Tracer / Intake Form</h3>
                <p>Submit or update your personal, academic, and employment details to support alumni tracking and reporting.</p>
            </div>
            <div class="card">
                <h3>Profile & Account Management</h3>
                <p>Maintain accurate contact information for official announcements, reunions, and alumni opportunities.</p>
            </div>
            <div class="card">
                <h3>Programs & Engagement</h3>
                <p>Participate in networking, mentoring, career talks, and alumni volunteer initiatives coordinated by the Office.</p>
            </div>
        </div>
    </div>
</section>

{{-- PROGRAMS --}}
<section id="programs" class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-title">
            <h2>Programs & Highlights</h2>
            <p>Featured initiatives that strengthen alumni involvement and support students and the wider community.</p>
        </div>

        <div class="grid">
            <div class="card">
                <h3>Mentorship & Career Support</h3>
                <p>Volunteer as a mentor, share expertise, or support career sessions for current students and young alumni.</p>
            </div>
            <div class="card">
                <h3>Alumni Networking</h3>
                <p>Reconnect with classmates, expand professional networks, and engage with fellow NDMU alumni.</p>
            </div>
            <div class="card">
                <h3>Service & Giving</h3>
                <p>Support scholarships and alumni-driven projects that advance the University’s mission and community impact.</p>
            </div>
        </div>
    </div>
</section>

{{-- EVENTS --}}
<section id="events" class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-title">
            <h2>Events & Official Updates</h2>
            <p>For official postings and announcements, please use the University’s official platforms below.</p>
        </div>

        <div class="grid">
            <a class="linkcard" href="{{ $newsUrl }}" target="_blank" rel="noopener">
                <div class="linkicon green">🌐</div>
                <div class="linkmeta">
                    <strong>NDMU News & Updates</strong>
                    <span>Official website postings, announcements, and updates.</span>
                    <em>Open website →</em>
                </div>
            </a>

            <a class="linkcard" href="{{ $fbUrl }}" target="_blank" rel="noopener">
                <div class="linkicon blue">f</div>
                <div class="linkmeta">
                    <strong>NDMU Official Facebook Page</strong>
                    <span>Real-time updates, posters, and quick announcements.</span>
                    <em>Open Facebook →</em>
                </div>
            </a>

            @if(Route::has('events.index'))
                <a class="linkcard" href="{{ route('events.index') }}">
                    <div class="linkicon green">E</div>
                    <div class="linkmeta">
                        <strong>Events & Updates Page</strong>
                        <span>View official external links in a dedicated page within this portal.</span>
                        <em>Open page →</em>
                    </div>
                </a>
            @else
                <div class="card">
                    <h3>Alumni Activities</h3>
                    <p>Upcoming alumni activities will be coordinated by the Office of Alumni Relations. Kindly check the official channels above.</p>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- CAREERS --}}
<section id="careers" class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-title">
            <h2>Careers</h2>
            <p>Job opportunities posted by the Office of Alumni Relations and ICT.</p>
        </div>

        @if($careerPosts->count())
            <div class="grid">
                @foreach($careerPosts as $post)
                    @php
                        $status = $post->statusLabel();
                        $badgeStyle = match($status) {
                            'Active'   => 'background:#ECFDF5;color:#065F46;border-color:#A7F3D0;',
                            'Upcoming' => 'background:#EFF6FF;color:#1E40AF;border-color:#BFDBFE;',
                            'Expired'  => 'background:#F9FAFB;color:#374151;border-color:#E5E7EB;',
                            default    => 'background:#FFFBEB;color:#92400E;border-color:#FDE68A;',
                        };
                    @endphp

                    <a class="linkcard" href="{{ url('/careers') }}" title="Open Careers">
                        <div class="linkicon green">💼</div>
                        <div class="linkmeta" style="min-width:0;">
                            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:10px;">
                                <strong style="min-width:0; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $post->title }}
                                </strong>
                                <span class="badge" style="{{ $badgeStyle }}">{{ $status }}</span>
                            </div>

                            <span>
                                {{ $post->company ?: '—' }}
                                @if($post->location) • {{ $post->location }} @endif
                                @if($post->employment_type) • {{ $post->employment_type }} @endif
                            </span>

                            <span style="margin-top:6px;">
                                @if($post->start_date || $post->end_date)
                                    {{ $post->start_date ? $post->start_date->format('M d, Y') : '—' }}
                                    —
                                    {{ $post->end_date ? $post->end_date->format('M d, Y') : '—' }}
                                @else
                                    Date range not specified
                                @endif
                                • {{ $post->attachments_count }} file(s)
                            </span>

                            <em>View careers →</em>
                        </div>
                    </a>
                @endforeach
            </div>

            <div style="margin-top:14px; display:flex; gap:10px; flex-wrap:wrap;">
                <a class="btn btn-soft" href="{{ url('/careers') }}">View all Careers</a>

                @auth
                    @if (in_array(auth()->user()->role, ['it_admin','alumni_officer'], true))
                        <a class="btn btn-outline" href="{{ route('portal.careers.admin.index') }}">Manage Careers</a>
                    @endif
                @endauth
            </div>
        @else
            <div class="card">
                <h3>No job posts yet</h3>
                <p>Career opportunities will appear here once posted by the Office.</p>
            </div>
        @endif
    </div>
</section>

{{-- NEWS --}}
<section id="news" class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-title">
            <h2>News & Features</h2>
            <p>Official announcements, alumni stories, and institutional updates curated by the Office.</p>
        </div>

        <div class="grid">
            <div class="card">
                <h3>Announcements</h3>
                <p>Official advisories and alumni office updates will appear here.</p>
            </div>
            <div class="card">
                <h3>Alumni Stories</h3>
                <p>Feature highlights and accomplishments of NDMU alumni across disciplines.</p>
            </div>
            <div class="card">
                <h3>Publications</h3>
                <p>Periodic releases and updates from the Office of Alumni Relations.</p>
            </div>
        </div>
    </div>
</section>

{{-- ABOUT --}}
<section id="about" class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-title">
            <h2>About the Office</h2>
            <p>The Office of Alumni Relations coordinates alumni engagement, records, and communications.</p>
        </div>

        <div class="card">
            <h3 style="margin-bottom:8px;">Mandate</h3>
            <p>
                The Office fosters lifelong relationships with alumni by managing alumni records, organizing alumni activities,
                and promoting opportunities for mentorship, service, and institutional support—ensuring alumni remain connected
                to Notre Dame of Marbel University’s mission and community.
            </p>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer>
    <div class="container">
        <div class="footer-inner">
            <div>
                <strong style="font-size:13px;letter-spacing:.5px;text-transform:uppercase;">
                    Notre Dame of Marbel University — Alumni Relations
                </strong>
                <p>
                    For inquiries, coordination of alumni activities, or profile concerns, please contact the Office of Alumni Relations.
                    This portal supports alumni record keeping and engagement initiatives of the University.
                </p>

                <div class="footer-links">
                    @auth
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <a href="{{ route('intake.form') }}">Alumni Tracer</a>
                        @if (Route::has('portal.records.index') && in_array(auth()->user()->role, ['admin','it_admin','alumni_officer'], true))
                            <a href="{{ route('portal.records.index') }}">Manage Records</a>
                        @endif
                        @if(Route::has('profile.edit'))
                            <a href="{{ route('profile.edit') }}">Account Settings</a>
                        @endif
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}">Login</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth

                    <a href="{{ $newsUrl }}" target="_blank" rel="noopener">NDMU News</a>
                    <a href="{{ $fbUrl }}" target="_blank" rel="noopener">NDMU Facebook</a>
                    <a href="#services">Services</a>
                    <a href="#about">About</a>
                </div>
            </div>

            <div class="card" style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.18);color:#fff;">
                <h3 style="margin:0 0 8px;">Contact Information</h3>
                <p style="color:rgba(255,255,255,.90);margin:0;">
                    <strong>Office:</strong> Office of Alumni Relations<br>
                    <strong>University:</strong> Notre Dame of Marbel University<br>
                    <strong>Email:</strong> alumni@ndmu.edu.ph<br>
                    <strong>Phone:</strong> (083) 228-3598 Local 166
                </p>
            </div>

            <div class="card" style="background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.18);color:#fff;">
                <h3 style="margin:0 0 8px;">Careers</h3>
                <p style="color:rgba(255,255,255,.90);margin:0;">
                    View job opportunities posted for NDMU alumni. Open the Careers section above to see the latest openings,
                    including upcoming and expired postings with labels.
                </p>

                <div class="footer-links" style="margin-top:10px;">
                    <a href="#careers">Careers (Landing)</a>
                    <a href="{{ url('/careers') }}">View All Careers</a>

                    @auth
                        @if (in_array(auth()->user()->role, ['it_admin','alumni_officer'], true))
                            <a href="{{ route('portal.careers.admin.index') }}">Manage Careers</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="copyright">
            © {{ date('Y') }} Notre Dame of Marbel University. All rights reserved.
        </div>
    </div>
</footer>

<script>
    (function () {
        // Desktop dropdown (Events)
        const dropdowns = document.querySelectorAll('.ndmu-landing [data-dd]');
        dropdowns.forEach(dd => {
            const btn = dd.querySelector('[data-dd-btn]');
            const menu = dd.querySelector('[data-dd-menu]');

            if (!btn || !menu) return;

            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                dropdowns.forEach(other => {
                    if (other !== dd) {
                        other.classList.remove('open');
                        const b = other.querySelector('[data-dd-btn]');
                        if (b) b.setAttribute('aria-expanded', 'false');
                    }
                });

                const isOpen = dd.classList.toggle('open');
                btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });

            menu.addEventListener('click', (e) => e.stopPropagation());
        });

        document.addEventListener('click', () => {
            dropdowns.forEach(dd => {
                dd.classList.remove('open');
                const btn = dd.querySelector('[data-dd-btn]');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            });
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                dropdowns.forEach(dd => {
                    dd.classList.remove('open');
                    const btn = dd.querySelector('[data-dd-btn]');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                });
            }
        });

        // Mobile drawer
        const drawer = document.getElementById('drawer');
        const backdrop = document.getElementById('drawerBackdrop');
        const openBtn = document.getElementById('openDrawer');
        const closeBtn = document.getElementById('closeDrawer');

        function openDrawer() {
            if (!drawer || !backdrop) return;
            backdrop.classList.add('show');
            drawer.classList.add('open');
            drawer.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            if (!drawer || !backdrop) return;
            backdrop.classList.remove('show');
            drawer.classList.remove('open');
            drawer.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        if (openBtn) openBtn.addEventListener('click', openDrawer);
        if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
        if (backdrop) backdrop.addEventListener('click', closeDrawer);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeDrawer();
        });

        // Close drawer when clicking an internal anchor link
        document.querySelectorAll('.ndmu-landing .drawer a[href^="#"]').forEach(a => {
            a.addEventListener('click', () => closeDrawer());
        });
    })();
</script>

</body>
</html>
