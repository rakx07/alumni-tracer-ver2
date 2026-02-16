{{-- resources/views/networks/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-gray-900">Alumni Network</h2>
                <p class="text-sm text-gray-600">
                    Alumni Associations and official networks
                </p>
            </div>

            <!-- <a href="https://www.ndmu.edu.ph"
               target="_blank" rel="noopener"
               class="inline-flex items-center px-4 py-2 rounded-lg font-semibold border shadow-sm"
               style="border-color:#E3C77A; color:#0B3D2E; background:#FFFBF0;">
                NDMU Website
            </a> -->
        </div>
    </x-slot>

    <style>
        :root{
            --ndmu-green:#0B3D2E;
            --ndmu-gold:#E3C77A;
            --paper:#FFFBF0;
            --page:#FAFAF8;
            --line:#EDE7D1;
            --text:#0f172a;
        }

        .strip{
            border:1px solid var(--line);
            border-radius: 18px;
            overflow:hidden;
            background:#fff;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }

        .strip-top{
            padding: 16px 18px;
            background: var(--ndmu-green);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }

        .strip-left{
            display:flex;
            align-items:center;
            gap: 12px;
        }

        .gold-bar{
            width: 6px;
            height: 38px;
            background: var(--ndmu-gold);
            border-radius: 999px;
        }

        .strip-title{
            color:#fff;
            font-weight: 900;
        }

        .strip-sub{
            color: rgba(255,255,255,.78);
            font-size: 12px;
        }

        .pill{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,251,240,.95);
            border: 1px solid rgba(227,199,122,.85);
            color: var(--ndmu-green);
            font-size: 12px;
            font-weight: 900;
        }

        .pill-dot{
            width: 8px;
            height: 8px;
            border-radius: 999px;
            background: var(--ndmu-green);
        }

        /* Network Card */
        .network-card{
            border:1px solid var(--line);
            border-radius: 18px;
            background:#fff;
            padding: 24px 18px;
            text-align:center;
            transition: .15s ease;
            box-shadow: 0 10px 24px rgba(2,6,23,.06);
        }

        .network-card:hover{
            transform: translateY(-4px);
            box-shadow: 0 18px 36px rgba(2,6,23,.10);
            border-color: rgba(227,199,122,.85);
        }

        /* Uniform Logo Frame */
        .logo-frame{
            width: 120px;
            height: 120px;
            border-radius: 24px;
            background: #fff;
            border: 1px solid rgba(227,199,122,.75);
            box-shadow: 0 10px 20px rgba(2,6,23,.08);
            display:flex;
            align-items:center;
            justify-content:center;
            overflow:hidden;
            margin: 0 auto 16px auto;
        }

        .logo-img{
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }

        .network-title{
            font-weight: 900;
            color: var(--ndmu-green);
            margin-top: 8px;
            font-size: 16px;
        }

        .network-desc{
            margin-top: 6px;
            font-size: 13px;
            color: rgba(15,23,42,.70);
            min-height: 38px;
        }

        .network-link{
            margin-top: 10px;
            font-size: 12px;
            color: rgba(15,23,42,.60);
            word-break: break-all;
        }

        @media (max-width: 640px){
            .logo-frame{
                width: 95px;
                height: 95px;
            }
        }
    </style>

    <div class="py-8" style="background:var(--page);">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Header Strip --}}
            <div class="strip">
                <div class="strip-top">
                    <div class="strip-left">
                        <div class="gold-bar"></div>
                        <div>
                            <div class="strip-title">Associations</div>
                            <div class="strip-sub">
                                Click a logo to visit the official page.
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:flex items-center gap-2">
                        <span class="pill">
                            <span class="pill-dot"></span>
                            Count: {{ $networks->count() }}
                        </span>
                    </div>
                </div>

                <div class="p-4 text-sm text-gray-600" style="background:#fff;">
                    Alumni Associations are under your former faculty, college or institution where you can participate in and get news directly.
                </div>
            </div>

            {{-- Networks Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse($networks as $n)

                    @php
                        $href = $n->link;
                        if (!preg_match('~^https?://~i', $href)) {
                            $href = 'https://' . ltrim($href, '/');
                        }
                    @endphp

                    <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" class="block">
                        <div class="network-card">

                            <div class="logo-frame">
                                @if($n->logo_url)
                                    <img src="{{ $n->logo_url }}"
                                         alt="{{ $n->title }} logo"
                                         class="logo-img"
                                         onerror="this.style.display='none';">
                                @else
                                    <img src="{{ asset('images/ndmu-logo.png') }}"
                                         alt="NDMU"
                                         class="logo-img"
                                         onerror="this.style.display='none';">
                                @endif
                            </div>

                            <div class="network-title">
                                {{ $n->title }}
                            </div>

                            @if($n->description)
                                <div class="network-desc">
                                    {{ \Illuminate\Support\Str::limit($n->description, 90) }}
                                </div>
                            @else
                                <div class="network-desc">
                                    Visit official page
                                </div>
                            @endif

                            <div class="network-link">
                                Tap to open ↗
                            </div>

                        </div>
                    </a>

                @empty
                    <div class="col-span-3 text-center text-gray-600 py-10">
                        No networks posted yet.
                    </div>
                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
