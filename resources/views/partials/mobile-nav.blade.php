@auth
@php
    $navUser = auth()->user();
    $isAdminNav = $navUser?->isAdmin() ?? false;
    $isHomeRoute = request()->routeIs('home', 'dashboard');
    $isPoruchyRoute = request()->routeIs('poruchy.index', 'poruchy.show');
    $isCreateRoute = request()->routeIs('poruchy.create');
    $isContactRoute = request()->routeIs('contact');
    $isUsersRoute = request()->routeIs('sprava-uzivatelu');
    $isProfileRoute = request()->routeIs('profile');
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* Mobilní horní lišta a spodní navigace (zobrazuje se jen pod 768px) */
    .mobile-topbar,
    .mobile-bottom-nav {
        display: none;
    }

    @media (max-width: 767px) {
        header > nav.navbar {
            display: none !important;
        }

        body.app,
        body.mobile-has-nav {
            padding-top: 58px !important;
            padding-bottom: 88px !important;
        }

        #toast {
            top: 68px !important;
        }

        .mobile-topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 58px;
            z-index: 890;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 0 14px;
            background: #1f2a44;
            color: #ffffff;
            box-shadow: 0 2px 12px rgba(15, 23, 42, 0.16);
        }

        .mobile-topbar__brand {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.98rem;
            letter-spacing: 0.02em;
            min-width: 0;
        }

        .mobile-topbar__brand-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(56, 189, 248, 0.2);
            color: #38bdf8;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .mobile-topbar__actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .mobile-topbar__role {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.14);
            color: #e2e8f0;
        }

        .mobile-topbar__icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            text-decoration: none;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.18s ease, transform 0.15s ease;
        }

        .mobile-topbar__icon-btn:active {
            transform: scale(0.94);
        }

        .mobile-topbar__icon-btn.is-active {
            background: #0284c7;
            border-color: #38bdf8;
        }

        /* Spodní navigace */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 900;
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            align-items: end;
            gap: 2px;
            padding: 6px 6px calc(8px + env(safe-area-inset-bottom, 0px));
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid #e2e8f0;
            box-shadow: 0 -6px 24px rgba(15, 23, 42, 0.08);
        }

        .mobile-bottom-nav__item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
            padding: 4px 2px;
            border: none;
            background: transparent;
            color: #64748b;
            text-decoration: none;
            font-size: 0.68rem;
            font-weight: 500;
            line-height: 1.1;
            cursor: pointer;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
            transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.18s ease;
        }

        .mobile-bottom-nav__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 12px;
            font-size: 1.18rem;
            transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .mobile-bottom-nav__label {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            transition: all 0.18s ease;
        }

        /* Jemné zvýraznění akčního tlačítka "+ Nahlásit", když zrovna není aktivní */
        .mobile-bottom-nav__item--create:not(.is-active) .mobile-bottom-nav__icon {
            background: #e0f2fe;
            color: #0284c7;
        }

        .mobile-bottom-nav__item--create:not(.is-active) .mobile-bottom-nav__label {
            color: #0369a1;
            font-weight: 600;
        }

        /* AKTIVNÍ TLAČÍTKO: větší, vystouplé a výrazně zvýrazněné */
        .mobile-bottom-nav__item.is-active {
            transform: translateY(-7px) scale(1.07);
            color: #1f2a44;
        }

        .mobile-bottom-nav__item.is-active .mobile-bottom-nav__icon {
            width: 46px;
            height: 46px;
            border-radius: 16px;
            background: linear-gradient(135deg, #1f2a44 0%, #0284c7 100%);
            color: #ffffff;
            font-size: 1.35rem;
            box-shadow: 0 8px 18px rgba(2, 132, 199, 0.34);
        }

        .mobile-bottom-nav__item.is-active .mobile-bottom-nav__label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #1f2a44;
        }

        .mobile-bottom-nav__item.is-active::after {
            content: "";
            width: 14px;
            height: 3px;
            border-radius: 9999px;
            background: #0284c7;
            margin-top: 1px;
        }
    }
</style>

{{-- Kompaktní horní lišta pro mobil --}}
<div class="mobile-topbar">
    <a href="{{ route('home') }}" class="mobile-topbar__brand">
        <span class="mobile-topbar__brand-badge">
            <i class="bi bi-wrench-adjustable-circle-fill"></i>
        </span>
        <span class="truncate">Hlášení poruch</span>
    </a>

    <div class="mobile-topbar__actions">
        @if($navUser)
            <span class="mobile-topbar__role">{{ $navUser->role_label }}</span>
        @endif

        @if($isAdminNav)
            <a href="{{ route('contact') }}"
               class="mobile-topbar__icon-btn {{ $isContactRoute ? 'is-active' : '' }}"
               aria-label="Kontakty" title="Kontakty">
                <i class="bi bi-telephone-fill"></i>
            </a>
        @endif

        <form method="POST" action="{{ route('odhlaseni') }}" class="m-0">
            @csrf
            <button type="submit" class="mobile-topbar__icon-btn" aria-label="Odhlásit se" title="Odhlásit se">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</div>

{{-- Spodní mobilní navigace se zvětšením aktivní položky --}}
<nav class="mobile-bottom-nav" aria-label="Mobilní navigace">
    <a href="{{ route('home') }}"
       class="mobile-bottom-nav__item {{ $isHomeRoute ? 'is-active' : '' }}"
       @if($isHomeRoute) aria-current="page" @endif>
        <span class="mobile-bottom-nav__icon">
            <i class="bi {{ $isHomeRoute ? 'bi-house-door-fill' : 'bi-house-door' }}"></i>
        </span>
        <span class="mobile-bottom-nav__label">Domů</span>
    </a>

    <a href="{{ route('poruchy.index') }}"
       class="mobile-bottom-nav__item {{ $isPoruchyRoute ? 'is-active' : '' }}"
       @if($isPoruchyRoute) aria-current="page" @endif>
        <span class="mobile-bottom-nav__icon">
            <i class="bi bi-tools"></i>
        </span>
        <span class="mobile-bottom-nav__label">Poruchy</span>
    </a>

    @if($isHomeRoute)
        <button type="button"
                onclick="if (typeof openCreateModal === 'function') { openCreateModal(); } else { window.location.href='{{ route('poruchy.create') }}'; }"
                class="mobile-bottom-nav__item mobile-bottom-nav__item--create">
            <span class="mobile-bottom-nav__icon">
                <i class="bi bi-plus-lg"></i>
            </span>
            <span class="mobile-bottom-nav__label">Nahlásit</span>
        </button>
    @else
        <a href="{{ route('poruchy.create') }}"
           class="mobile-bottom-nav__item mobile-bottom-nav__item--create {{ $isCreateRoute ? 'is-active' : '' }}"
           @if($isCreateRoute) aria-current="page" @endif>
            <span class="mobile-bottom-nav__icon">
                <i class="bi {{ $isCreateRoute ? 'bi-plus-circle-fill' : 'bi-plus-lg' }}"></i>
            </span>
            <span class="mobile-bottom-nav__label">Nahlásit</span>
        </a>
    @endif

    @if($isAdminNav)
        <a href="{{ route('sprava-uzivatelu') }}"
           class="mobile-bottom-nav__item {{ $isUsersRoute ? 'is-active' : '' }}"
           @if($isUsersRoute) aria-current="page" @endif>
            <span class="mobile-bottom-nav__icon">
                <i class="bi {{ $isUsersRoute ? 'bi-people-fill' : 'bi-people' }}"></i>
            </span>
            <span class="mobile-bottom-nav__label">Uživatelé</span>
        </a>
    @else
        <a href="{{ route('contact') }}"
           class="mobile-bottom-nav__item {{ $isContactRoute ? 'is-active' : '' }}"
           @if($isContactRoute) aria-current="page" @endif>
            <span class="mobile-bottom-nav__icon">
                <i class="bi {{ $isContactRoute ? 'bi-telephone-fill' : 'bi-telephone' }}"></i>
            </span>
            <span class="mobile-bottom-nav__label">Kontakty</span>
        </a>
    @endif

    <a href="{{ route('profile') }}"
       class="mobile-bottom-nav__item {{ $isProfileRoute ? 'is-active' : '' }}"
       @if($isProfileRoute) aria-current="page" @endif>
        <span class="mobile-bottom-nav__icon">
            <i class="bi {{ $isProfileRoute ? 'bi-person-fill' : 'bi-person' }}"></i>
        </span>
        <span class="mobile-bottom-nav__label">Profil</span>
    </a>
</nav>
@endauth
