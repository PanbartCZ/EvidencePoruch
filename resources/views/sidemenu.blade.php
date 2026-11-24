    <a href="{{ url('/home') }}" style="{{ request()->routeIs('home', 'dashboard') ? 'background: rgba(255,255,255,0.16); border-radius: 8px; font-weight: 700;' : '' }}"><i class="bi bi-house"></i>Domů</a>
    <a href="{{ route('poruchy.index') }}" style="{{ request()->routeIs('poruchy.*') ? 'background: rgba(255,255,255,0.16); border-radius: 8px; font-weight: 700;' : '' }}"><i class="bi bi-tools"></i>Poruchy</a>
    <a href="{{ url('/profile') }}" style="{{ request()->routeIs('profile') ? 'background: rgba(255,255,255,0.16); border-radius: 8px; font-weight: 700;' : '' }}"><i class="bi bi-person-circle"></i> Můj profil</a>
    <a href="{{ url('/contact') }}" style="{{ request()->routeIs('contact') ? 'background: rgba(255,255,255,0.16); border-radius: 8px; font-weight: 700;' : '' }}"><i class="bi bi-telephone"></i>Kontakty</a>
    @auth
    @if(auth()->user()->role === 'spravce')
        <a href="{{ route('sprava-uzivatelu') }}" style="{{ request()->routeIs('sprava-uzivatelu') ? 'background: rgba(255,255,255,0.16); border-radius: 8px; font-weight: 700;' : '' }}"><i class="bi bi-pencil"></i>Správa uživatelů</a>
    @endif
@endauth

    <form method="POST" action="{{ route('odhlaseni') }}">
        @csrf
        <button type="submit">Odhlásit se</button>
    </form>
