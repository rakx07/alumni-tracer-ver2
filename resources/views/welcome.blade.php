{{-- resources/views/welcome.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'NDMU Alumni Portal') }}</title>

    <style>
        :root{
            --ndmu-green:#0b5d2a;
            --ndmu-green-2:#0a4a22;
            --ndmu-gold:#f2c200;
            --ndmu-gold-2:#d4a800;
            --ink:#0f172a;
            --muted:#475569;
            --card:#ffffff;
            --line:rgba(15,23,42,.10);
        }

        *{box-sizing:border-box}
        body{
            margin:0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji";
            color:var(--ink);
            background:#f8fafc;
        }
        a{color:inherit;text-decoration:none}
        .container{max-width:1180px;margin:0 auto;padding:0 18px}

        /* Top brand bar */
        .topbar{
            background: linear-gradient(90deg, var(--ndmu-gold), #ffd966 55%, var(--ndmu-gold-2));
            border-bottom: 6px solid var(--ndmu-green);
        }
        .topbar-inner{
            display:flex;align-items:center;justify-content:space-between;
            padding:14px 0;
            gap:16px;
        }
        .brand{
            display:flex;align-items:center;gap:12px;min-width:260px;
        }
        .brand-badge{
            width:46px;height:46px;border-radius:12px;
            background: rgba(11,93,42,.10);
            border:1px solid rgba(11,93,42,.20);
            display:flex;align-items:center;justify-content:center;
            font-weight:900;color:var(--ndmu-green);
            overflow:hidden;
        }
        .brand-badge img{
            width:100%;height:100%;object-fit:cover;
            display:block;
        }
        .brand-title{line-height:1.1}
        .brand-title strong{display:block;font-size:14px;letter-spacing:.3px}
        .brand-title span{display:block;font-size:12px;color:rgba(15,23,42,.75)}
        .top-actions{display:flex;gap:10px;align-items:center;flex-wrap:wrap}

        .btn{
            display:inline-flex;align-items:center;justify-content:center;
            padding:10px 14px;border-radius:10px;
            border:1px solid transparent;
            font-weight:800;font-size:13px;
            cursor:pointer;
            transition:.15s ease;
            white-space:nowrap;
        }
        .btn-primary{background:var(--ndmu-green);color:#fff}
        .btn-primary:hover{background:var(--ndmu-green-2)}
        .btn-gold{background:#b91c1c;color:#fff}
        .btn-gold:hover{filter:brightness(.95)}
        .btn-outline{background:transparent;border-color:rgba(15,23,42,.25);color:rgba(15,23,42,.9)}
        .btn-outline:hover{background:rgba(255,255,255,.35)}
        .btn-soft{
            background: rgba(11,93,42,.10);
            border-color: rgba(11,93,42,.18);
            color: var(--ndmu-green);
        }
        .btn-soft:hover{background: rgba(11,93,42,.14)}

        /* Nav */
        .nav{
            background: rgba(2,6,23,.92);
            color:#fff;
            position:sticky;
            top:0;
            z-index:50;
        }
        .nav-inner{
            display:flex;align-items:center;justify-content:center;
            gap:10px;
            padding:10px 0;
            flex-wrap:wrap;
        }
        .nav a{
            font-size:12px;
            letter-spacing:.8px;
            text-transform:uppercase;
            opacity:.92;
            padding:8px 10px;
            border-radius:10px;
        }
        .nav a:hover{background:rgba(255,255,255,.10);opacity:1}

        /* Hero */
        .hero{
            position:relative;
            min-height:560px;
            display:flex;align-items:center;
            background:
                linear-gradient(90deg, rgba(11,93,42,.92), rgba(11,93,42,.62) 55%, rgba(0,0,0,.25)),
                url("{{ asset('images/ndmu-hero.jpg') }}");
            background-size:cover;
            background-position:center;
            border-bottom: 1px solid var(--line);
        }
        .hero::after{
            content:"";
            position:absolute;inset:0;
            background: radial-gradient(circle at 20% 30%, rgba(242,194,0,.22), transparent 55%);
            pointer-events:none;
        }
        .hero-content{
            position:relative;
            color:#fff;
            padding:56px 0;
            display:grid;
            grid-template-columns: 1.15fr .85fr;
            gap:24px;
            align-items:center;
        }
        .hero h1{
            margin:0 0 10px;
            font-size:42px;
            letter-spacing:.6px;
            text-transform:uppercase;
        }
        .hero p{
            margin:0 0 18px;
            max-width:720px;
            color: rgba(255,255,255,.92);
            font-size:15px;
            line-height:1.6;
        }
        .hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:10px}
        .hero-card{
            background: rgba(255,255,255,.10);
            border:1px solid rgba(255,255,255,.18);
            border-radius:18px;
            padding:18px;
            backdrop-filter: blur(6px);
        }
        .hero-card h3{
            margin:0 0 8px;
            font-size:14px;
            text-transform:uppercase;
            letter-spacing:.7px;
            color: rgba(255,255,255,.92);
        }
        .hero-card ul{
            margin:0;padding-left:16px;
            color: rgba(255,255,255,.90);
            font-size:13px;line-height:1.65;
        }

        /* Sections */
        .section{padding:44px 0}
        .section-title{
            display:flex;align-items:end;justify-content:space-between;gap:14px;
            margin-bottom:16px;
        }
        .section-title h2{
            margin:0;
            font-size:20px;
            letter-spacing:.4px;
            text-transform:uppercase;
        }
        .section-title p{
            margin:0;color:var(--muted);font-size:13px;max-width:520px
        }

        .grid{
            display:grid;
            grid-template-columns: repeat(3, 1fr);
            gap:14px;
        }
        .card{
            background:var(--card);
            border:1px solid var(--line);
            border-radius:16px;
            padding:16px;
            box-shadow: 0 6px 16px rgba(2,6,23,.05);
        }
        .card h3{margin:0 0 6px;font-size:15px}
        .card p{margin:0;color:var(--muted);font-size:13px;line-height:1.6}

        .pill{
            display:inline-flex;align-items:center;gap:8px;
            padding:6px 10px;border-radius:999px;
            background: rgba(242,194,0,.18);
            border:1px solid rgba(242,194,0,.35);
            color: rgba(15,23,42,.9);
            font-weight:800;font-size:12px;
        }

        .notice{
            margin-top:12px;
            font-size:12px;
            color: rgba(255,255,255,.88);
            line-height:1.55;
        }

        /* Footer */
        footer{
            margin-top:26px;
            background: linear-gradient(90deg, rgba(11,93,42,.95), rgba(11,93,42,.80));
            color:#fff;
            border-top: 6px solid var(--ndmu-gold);
        }
        .footer-inner{
            padding:28px 0;
            display:grid;
            grid-template-columns: 1.2fr .8fr;
            gap:18px;
        }
        .footer-inner p{margin:8px 0 0;color:rgba(255,255,255,.90);font-size:13px;line-height:1.6}
        .footer-links{display:flex;gap:10px;flex-wrap:wrap;margin-top:8px}
        .footer-links a{
            padding:8px 10px;border-radius:10px;
            background: rgba(255,255,255,.10);
            border:1px solid rgba(255,255,255,.15);
            font-size:12px;
        }
        .footer-links a:hover{background: rgba(255,255,255,.14)}
        .copyright{
            border-top:1px solid rgba(255,255,255,.18);
            padding:12px 0;
            color:rgba(255,255,255,.85);
            font-size:12px;
        }

        /* Responsive */
        @media (max-width: 980px){
            .hero-content{grid-template-columns:1fr}
            .grid{grid-template-columns:1fr}
            .topbar-inner{flex-wrap:wrap}
            .brand{min-width:auto}
            .hero h1{font-size:34px}
            .footer-inner{grid-template-columns:1fr}
        }
    </style>
</head>

<body>
    {{-- TOP BAR --}}
    <div class="topbar">
        <div class="container">
            <div class="topbar-inner">
                <div class="brand">
                    {{-- If you have a logo file, place it in: public/images/ndmu-logo.png --}}
                    <div class="brand-badge">
                        @if(file_exists(public_path('images/ndmu-logo.png')))
                            <img src="{{ asset('images/ndmu-logo.png') }}" alt="NDMU Logo">
                        @else
                            ND
                        @endif
                    </div>

                    <div class="brand-title">
                        <strong>NOTRE DAME OF MARBEL UNIVERSITY</strong>
                        <span>Office of Alumni Relations</span>
                    </div>
                </div>

                <div class="top-actions">
                    @auth
                        <a class="btn btn-primary" href="{{ route('dashboard') }}">Dashboard</a>

                        @if (Route::has('portal.records.index') && in_array(auth()->user()->role, ['admin','it_admin','alumni_officer'], true))
                            <a class="btn btn-outline" href="{{ route('portal.records.index') }}">Manage Records</a>
                        @endif

                        <a class="btn btn-soft" href="{{ route('intake.form') }}">Update Alumni Tracer</a>
                    @else
                        @if (Route::has('register'))
                            <a class="btn btn-gold" href="{{ route('register') }}">Register</a>
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
                <a href="#home">Home</a>
                <a href="#services">Online Services</a>
                <a href="#programs">Programs</a>
                <a href="#events">Events</a>
                <a href="#news">News & Features</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>
        </div>
    </div>

    {{-- HERO --}}
    <section id="home" class="hero">
        <div class="container">
            <div class="hero-content">
                <div>
                    <div class="pill">NDMU Alumni Portal</div>
                    <h1>Office of Alumni Relations</h1>
                    <p>
                        Welcome to the official alumni portal of Notre Dame of Marbel University.
                        This platform supports alumni engagement through accurate records, meaningful connections,
                        and coordinated alumni programs aligned with the University’s mission.
                    </p>

                    <div class="hero-actions">
                        {{-- Primary CTA: intake / tracer --}}
                        @auth
                            <a class="btn btn-primary" href="{{ route('intake.form') }}">Complete Alumni Tracer</a>
                            <a class="btn btn-outline" href="{{ route('dashboard') }}">Go to Dashboard</a>
                        @else
                            <a class="btn btn-primary" href="{{ route('login') }}">Complete Alumni Tracer</a>
                            <a class="btn btn-outline" href="#services">Explore Services</a>
                            @if (Route::has('register'))
                                <a class="btn btn-gold" href="{{ route('register') }}">Create an Account</a>
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
                <h2>Events</h2>
                <p>Upcoming activities and gatherings will be posted here. Check back regularly for updates.</p>
            </div>

            <div class="grid">
                <div class="card">
                    <h3>Alumni Homecoming</h3>
                    <p>Schedule and registration details will be announced by the Office of Alumni Relations.</p>
                </div>
                <div class="card">
                    <h3>College / Program Reunions</h3>
                    <p>Reunion coordination and communications will be posted for participating batches and programs.</p>
                </div>
                <div class="card">
                    <h3>Professional & Community Activities</h3>
                    <p>Seminars, talks, outreach, and volunteer programs for alumni participation.</p>
                </div>
            </div>
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
    <footer id="contact">
        <div class="container">
            <div class="footer-inner">
                <div>
                    <strong style="font-size:14px;letter-spacing:.4px;text-transform:uppercase;">
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
                        @else
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}">Login</a>
                            @endif
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">Register</a>
                            @endif
                        @endauth
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
            </div>

            <div class="copyright">
                © {{ date('Y') }} Notre Dame of Marbel University. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
