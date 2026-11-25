<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hlášení poruch')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css', 'resources/css/main.css', 'resources/js/app.js'])
</head>

<body class="app bg-slate-50 text-slate-800">
@php
        $u = auth()->user();
        $isStaff = $u?->isStaff() ?? false;
        $isAdmin = $u?->isAdmin() ?? false;
        $initials = $u ? mb_strtoupper(mb_substr($u->name, 0, 1) . mb_substr($u->surname, 0, 1)) : 'DM';
        $roleBadgeClass = match($u?->role) {
            'spravce' => 'bg-slate-900 text-white',
            'technik' => 'bg-amber-300 text-slate-900',
            default   => 'bg-sky-100 text-sky-900',
        };
    @endphp
{{-- ===== TOAST ===== --}}
@php
    $toast = null;
    if (session('success'))            $toast = ['success', session('success')];
    elseif (session('success_heslo'))  $toast = ['success', session('success_heslo')];
    elseif (session('error'))          $toast = ['error', session('error')];

    $toastStyles = [
        'success' => ['border' => 'border-emerald-500', 'text' => 'text-emerald-500', 'bar' => 'bg-emerald-500', 'icon' => 'bi-check-circle-fill'],
        'error'   => ['border' => 'border-red-500',     'text' => 'text-red-500',     'bar' => 'bg-red-500',     'icon' => 'bi-exclamation-triangle-fill'],
    ];
@endphp

@if($toast)
    @php $t = $toastStyles[$toast[0]]; @endphp
    <div id="toast" role="alert" data-closing="false"
         class="animate-toast-in fixed inset-x-4 top-5 z-[1000] flex items-center gap-3 overflow-hidden rounded-xl border-l-4 bg-white py-3.5 pl-4 pr-3 text-[#1f2a44] shadow-xl transition duration-300 data-[closing=true]:translate-x-8 data-[closing=true]:opacity-0 sm:inset-x-auto sm:right-5 sm:min-w-[300px] sm:max-w-[420px] {{ $t['border'] }}">
        <i class="bi {{ $t['icon'] }} text-xl {{ $t['text'] }}"></i>
        <span class="flex-1 text-sm font-medium">{{ $toast[1] }}</span>
        <button type="button" data-toast-close aria-label="Zavřít"
                class="cursor-pointer px-1 text-xl leading-none text-gray-400 transition hover:text-gray-800">&times;</button>
        <div class="animate-toast-progress absolute bottom-0 left-0 h-[3px] w-full origin-left opacity-30 {{ $t['bar'] }}"></div>
    </div>
@endif

{{-- ===== POTVRZOVACÍ OKNO ===== --}}
<div id="confirm-modal" hidden
     class="animate-fade-in fixed inset-0 z-[2000] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm">
    <div role="dialog" aria-modal="true" aria-labelledby="confirm-title"
         class="animate-modal-pop w-full max-w-md rounded-2xl bg-white p-7 text-center shadow-2xl">
        <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-full bg-red-100 text-2xl text-red-600">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <h3 id="confirm-title" class="text-lg font-semibold text-[#1f2a44]">Opravdu to chcete udělat?</h3>
        <p id="confirm-text" class="mb-6 mt-2 text-sm text-gray-500"></p>
        <div class="flex gap-3">
            <button type="button" data-confirm-cancel
                    class="flex-1 cursor-pointer rounded-lg bg-gray-100 px-4 py-2.5 font-medium text-gray-700 transition hover:bg-gray-200">
                Zrušit
            </button>
            <button type="button" data-confirm-ok
                    class="flex-1 cursor-pointer rounded-lg bg-red-600 px-4 py-2.5 font-medium text-white transition hover:bg-red-700">
                Potvrdit
            </button>
        </div>
    </div>
</div>

@include('partials.mobile-nav')

<div class="flex min-h-dvh md:h-screen md:overflow-hidden">

    {{-- 1. LEVÝ PANEL (SEM PATŘÍ TVŮJ ASIDE / @include('partials.sidebar')) --}}
    <aside class="hidden md:flex w-64 bg-emerald-50/60 border-r border-emerald-100 flex-col justify-between p-4 shrink-0">
        <div>
            <div class="flex items-center gap-2 h-10 mb-6 text-sky-700 font-bold text-lg">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>HLÁŠENÍ PORUCH</span>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 bg-emerald-100/60 rounded-lg text-slate-800 font-semibold text-sm">
                    <span>Přehled</span>
                </a>
                <button onclick="openCreateModal()" class="w-full text-left flex items-center gap-3 px-3 py-2 text-slate-600 hover:bg-emerald-100/40 rounded-lg text-sm cursor-pointer">
                    <span>Nahlásit Poruchu</span>
                </button>
                <a href="{{ route('poruchy.index') }}" class="flex items-center gap-3 px-3 py-2 text-slate-600 hover:bg-emerald-100/40 rounded-lg text-sm">
                    <span>Detailní Poruchy</span>
                </a>
                <hr class="my-2 border-emerald-100">
                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-3 py-2 text-slate-600 hover:bg-emerald-100/40 rounded-lg text-sm">
                    <span>Můj profil</span>
                </a>
                <a href="{{ route('contact') }}" class="flex items-center gap-3 px-3 py-2 text-slate-600 hover:bg-emerald-100/40 rounded-lg text-sm">
                    <span>Kontakty</span>
                </a>
                @if($isAdmin)
                    <a href="{{ route('sprava-uzivatelu') }}" class="flex items-center gap-3 px-3 py-2 text-slate-600 hover:bg-emerald-100/40 rounded-lg text-sm font-medium">
                        <span>Správa uživatelů</span>
                    </a>
                @endif
            </nav>
        </div>

        <div class="space-y-3 border-t border-emerald-100 pt-3">
            @if($u)
                <div class="text-xs text-slate-500">
                    Přihlášen jako:
                    <span class="ml-1 inline-block px-2 py-0.5 rounded-full font-semibold {{ $roleBadgeClass }}">
                        {{ $u->role_label }}
                    </span>
                </div>
            @endif
            <form method="POST" action="{{ route('odhlaseni') }}">
                @csrf
                <button type="submit" class="flex items-center gap-2 text-slate-600 hover:text-slate-900 text-sm font-medium cursor-pointer">
                    <span>Odhlásit</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- 2. PRAVÁ ČÁST (MODRÝ TOPBAR + HLAVNÍ OBSAH + PATIČKA) --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <header>
            @include('topbar')
            @yield('header')
        </header>

        <main class="flex-1">
            @yield('maincontent')
        </main>

        <footer>
            @yield('footer')
            @include('footer')
        </footer>
    </div>

</div>

</body>
</html>