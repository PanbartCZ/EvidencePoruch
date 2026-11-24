@php
    $spravce = \App\Models\User::where('role', 'spravce')
        ->orderBy('surname')
        ->orderBy('name')
        ->first();

    $initials = $spravce
        ? mb_strtoupper(mb_substr($spravce->name, 0, 1) . mb_substr($spravce->surname, 0, 1))
        : '';

    $jeSpravce = auth()->check() && auth()->user()->role === 'spravce';
    $vyvojari = [
        ['name' => 'Dominik Málek',   'instagram' => 'doda_malek'],
        ['name' => 'Martin Výchopeň', 'instagram' => 'mvychopen'],
    ];
@endphp

<div class="mt-12 bg-[#1f2a44] px-6 pb-5 pt-10 text-sm text-gray-300">
    <div class="mx-auto max-w-7xl">

        <div class="grid gap-8 sm:grid-cols-2 sm:gap-10 lg:grid-cols-[1.5fr_0.8fr_1fr_1.3fr]">

            {{-- ZNAČKA --}}
            <div>
                <div class="mb-3 flex h-10 items-center gap-2.5">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-[#2c3b5e] text-xl text-[#4facfe]">
                        <i class="bi bi-tools"></i>
                    </div>
                    <span class="text-base font-semibold text-white">Hlášení poruch</span>
                </div>

                <p class="mb-4 max-w-xs leading-relaxed text-gray-400">
                    Nahlaste závadu během minuty. Správce ji přidělí technikovi a vy uvidíte, jak se řeší.
                </p>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-[#4facfe] px-4 py-2 text-[13px] font-semibold text-[#1f2a44] transition hover:bg-[#6bbcff]">
                        <i class="bi bi-exclamation-triangle"></i> Nahlásit poruchu
                    </a>
                </div>
            </div>

            {{-- NAVIGACE --}}
            <nav aria-label="Navigace ve footeru" class="border-t border-white/10 pt-6 sm:border-0 sm:pt-0">
                <p class="mb-3 flex items-center font-medium text-white lg:h-10">Navigace</p>
                <ul class="flex flex-row flex-wrap gap-x-6 gap-y-3 sm:flex-col sm:gap-2.5">
                    <li>
                        <a href="{{ url('/home') }}" class="inline-flex items-center gap-2 transition hover:text-white">
                            <i class="bi bi-house"></i> Domů
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/profile') }}" class="inline-flex items-center gap-2 transition hover:text-white">
                            <i class="bi bi-person-circle"></i> Můj profil
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/contact') }}" class="inline-flex items-center gap-2 transition hover:text-white">
                            <i class="bi bi-telephone"></i> Kontakty
                        </a>
                    </li>
                    @if($jeSpravce)
                        <li>
                            <a href="{{ url('/spravaUzivatelu') }}" class="inline-flex items-center gap-2 transition hover:text-white">
                                <i class="bi bi-people"></i> Správa uživatelů
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

            {{-- VÝVOJÁŘI --}}
            <div class="border-t border-white/10 pt-6 sm:border-0 sm:pt-0">
                <p class="mb-3 flex items-center font-medium text-white lg:h-10">Vývojáři</p>
                <ul class="flex flex-row flex-wrap gap-x-6 gap-y-3 sm:flex-col sm:gap-2.5">
                    @foreach($vyvojari as $vyvojar)
                        <li class="flex items-center gap-2">
                            <i class="bi bi-person-circle"></i>
                            <span>{{ $vyvojar['name'] }}</span>

                            @if($vyvojar['instagram'])
                                <a href="https://www.instagram.com/{{ $vyvojar['instagram'] }}"
                                   target="_blank" rel="noopener noreferrer"
                                   aria-label="Instagram: {{ $vyvojar['name'] }}"
                                   class="group inline-block">
                                    <i class="bi bi-instagram inline-block text-gray-400 transition duration-200 group-hover:scale-110 group-hover:bg-[linear-gradient(45deg,#feda75,#fa7e1e,#d62976,#962fbf,#4f5bd5)] group-hover:bg-clip-text group-hover:text-transparent"></i>
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- KONTAKT NA SPRÁVCE --}}
            <div class="self-start rounded-xl border border-white/10 bg-white/5 p-4">
                @if($spravce)
                    <div class="mb-3 flex items-center gap-3">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-full bg-[#4facfe] text-sm font-semibold text-[#1f2a44]">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-white">{{ $spravce->name }} {{ $spravce->surname }}</p>
                            <p class="text-xs text-[#4facfe]">Správce</p>
                        </div>
                    </div>

                    <ul class="flex flex-col gap-2 text-[13px]">
                        <li>
                            <a href="tel:{{ preg_replace('/\s+/', '', $spravce->phone) }}"
                               class="inline-flex items-center gap-2 transition hover:text-white">
                                <i class="bi bi-telephone-fill text-gray-400"></i> {{ $spravce->phone }}
                            </a>
                        </li>
                        <li>
                            <a href="mailto:{{ $spravce->email }}"
                               class="inline-flex items-start gap-2 break-all transition hover:text-white">
                                <i class="bi bi-envelope-fill mt-0.5 text-gray-400"></i> {{ $spravce->email }}
                            </a>
                        </li>
                        <li class="inline-flex items-center gap-2 text-gray-400">
                            <i class="bi bi-clock"></i> Po–Pá 8:00–16:00
                        </li>
                    </ul>
                @else
                    <p class="text-gray-400">
                        <i class="bi bi-exclamation-circle"></i> Kontakt je momentálně nedostupný.
                    </p>
                @endif
            </div>
        </div>

        {{-- SPODNÍ LIŠTA --}}
        <div class="mt-6 flex flex-col items-center gap-3 border-t border-white/15 pt-4 text-xs text-gray-400 sm:flex-row sm:justify-between">
            <p class="flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-center sm:justify-start sm:text-left">
                <span>&copy; {{ date('Y') }} Hlášení poruch</span>
                <span class="hidden text-white/20 sm:inline" aria-hidden="true">|</span>
                <span class="inline-flex items-center gap-1.5">
                    <i class="bi bi-mortarboard"></i> Školní projekt - OA a VOŠ Valašské Meziříčí
                </span>
            </p>

            <div class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2">
                <a href="#" class="inline-flex items-center gap-1.5 transition hover:text-white">
                    <i class="bi bi-file-earmark-text"></i> Podmínky služby
                </a>
                <a href="#" class="inline-flex items-center gap-1.5 transition hover:text-white">
                    <i class="bi bi-shield-lock"></i> Ochrana soukromí
                </a>
                <span>v1.0</span>
                <button type="button" aria-label="Nahoru"
                        onclick="window.scrollTo({ top: 0, behavior: 'smooth' })"
                        class="inline-flex size-7 cursor-pointer items-center justify-center rounded-full border border-white/20 transition hover:border-white/50 hover:text-white">
                    <i class="bi bi-arrow-up"></i>
                </button>
            </div>
        </div>
    </div>
</div>
