<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hlášení Poruch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-100 font-sans text-slate-800 antialiased mobile-has-nav">

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

    @include('partials.mobile-nav')

    <div class="flex min-h-dvh md:h-screen md:overflow-hidden">

        <!-- Desktop Sidebar -->
        <aside class="hidden md:flex w-64 bg-emerald-50/60 border-r border-emerald-100 flex-col justify-between p-4 shrink-0">
            <div>
                <div class="flex items-center gap-2 mb-8 text-sky-700 font-bold text-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
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

        <!-- Hlavní okno -->
        <div class="flex-1 flex flex-col min-w-0 md:overflow-hidden">

            <!-- Desktop Topbar s hledáním -->
            <header class="hidden md:flex bg-sky-500 text-white px-6 py-3 justify-between items-center shadow-sm">
                <div class="text-xl font-bold tracking-wide">HLÁŠENÍ PORUCH</div>

                <form action="{{ route('home') }}" method="GET" class="flex items-center gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Hledat poruchu..." class="pl-3 pr-4 py-1.5 rounded-md text-sm bg-sky-400/40 text-white placeholder-sky-100 focus:outline-none focus:bg-white focus:text-gray-900">
                    <a href="{{ route('profile') }}" class="flex items-center gap-2.5 text-sm font-medium hover:opacity-90">
                        <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold">{{ $initials }}</div>
                        <span>{{ $u ? $u->name . ' ' . $u->surname : 'Uživatel' }}</span>
                        @if($u)
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $roleBadgeClass }}">
                                {{ $u->role_label }}
                            </span>
                        @endif
                    </a>
                </form>
            </header>

            <!-- Obsah -->
            <main class="flex-1 md:overflow-y-auto p-4 sm:p-6">

                <!-- Zpráva o úspěchu -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-lg text-sm flex justify-between items-center">
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="font-bold cursor-pointer">&times;</button>
                    </div>
                @endif

                <!-- Chybová zpráva -->
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm flex justify-between items-center">
                        <span>{{ session('error') }}</span>
                        <button onclick="this.parentElement.remove()" class="font-bold cursor-pointer">&times;</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-5 sm:mb-6">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Přehled Hlášení</h1>
                    <div class="text-xs text-slate-500 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
                        @if($isAdmin)
                            Režim <strong>Správce</strong>: plná práva (vkládání, úprava stavů, mazání, správa uživatelů)
                        @elseif($isStaff)
                            Režim <strong>Technik</strong>: vkládání hlášení, úpravy a změna stavů poruch
                        @else
                            Režim <strong>Uživatel</strong>: prohlížení přehledu a hlášení nových poruch
                        @endif
                    </div>
                </div>

                <!-- KPI Karty -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                    <div class="bg-white p-3.5 sm:p-4 rounded-xl shadow-sm border-l-4 border-sky-400">
                        <div class="text-[11px] sm:text-xs font-medium text-slate-500 uppercase">Celkem Hlášení</div>
                        <div class="text-xl sm:text-2xl font-bold text-slate-800 mt-1">{{ $stats['celkem'] }}</div>
                    </div>
                    <div class="bg-white p-3.5 sm:p-4 rounded-xl shadow-sm border-l-4 border-red-500">
                        <div class="text-[11px] sm:text-xs font-medium text-slate-500 uppercase">Nové</div>
                        <div class="text-xl sm:text-2xl font-bold text-slate-800 mt-1">{{ $stats['nove'] }}</div>
                    </div>
                    <div class="bg-white p-3.5 sm:p-4 rounded-xl shadow-sm border-l-4 border-amber-500">
                        <div class="text-[11px] sm:text-xs font-medium text-slate-500 uppercase">V Řešení</div>
                        <div class="text-xl sm:text-2xl font-bold text-slate-800 mt-1">{{ $stats['v_reseni'] }}</div>
                    </div>
                    <div class="bg-white p-3.5 sm:p-4 rounded-xl shadow-sm border-l-4 border-emerald-500">
                        <div class="text-[11px] sm:text-xs font-medium text-slate-500 uppercase">Vyřešeno</div>
                        <div class="text-xl sm:text-2xl font-bold text-slate-800 mt-1">{{ $stats['vyreseno'] }}</div>
                    </div>
                </div>

                <!-- Mřížka -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Tabulka / Mobilní karty s filtry -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-4 sm:p-5">

                        <!-- FILTROVACÍ FORMULÁŘ -->
                        <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 sm:flex sm:flex-wrap items-center gap-2.5 sm:gap-3 mb-5 p-3 bg-slate-50 rounded-lg text-sm border border-slate-200">
                            <span class="font-semibold text-slate-600">Filtr:</span>

                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Hledat ID / Místnost / Popis..." class="w-full sm:w-auto sm:flex-1 sm:min-w-[180px] px-3 py-2 sm:py-1.5 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-1 focus:ring-sky-500">

                            <div class="grid grid-cols-2 sm:flex gap-2">
                                <select name="lokace" class="w-full sm:w-auto px-2.5 py-2 sm:py-1.5 bg-white border border-slate-300 rounded-md">
                                    <option value="">Všechny Lokace</option>
                                    @foreach($lokaceList as $lok)
                                        <option value="{{ $lok }}" {{ request('lokace', request('mistnost')) == $lok ? 'selected' : '' }}>{{ $lok }}</option>
                                    @endforeach
                                </select>

                                <select name="status" class="w-full sm:w-auto px-2.5 py-2 sm:py-1.5 bg-white border border-slate-300 rounded-md">
                                    <option value="">Všechny Stavy</option>
                                    <option value="Nové" {{ request('status') == 'Nové' ? 'selected' : '' }}>Nové</option>
                                    <option value="V řešení" {{ request('status') == 'V řešení' ? 'selected' : '' }}>V řešení</option>
                                    <option value="Vyřešeno" {{ request('status') == 'Vyřešeno' ? 'selected' : '' }}>Vyřešeno</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="submit" class="flex-1 sm:flex-none bg-sky-600 text-white px-4 py-2 sm:py-1.5 rounded-md hover:bg-sky-700 font-medium cursor-pointer">Filtrovat</button>
                                <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-800 text-xs underline whitespace-nowrap">Vynulovat</a>
                            </div>
                        </form>

                        <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
                            <h2 class="text-base sm:text-lg font-bold text-slate-800">Poslední Hlášení</h2>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('poruchy.index') }}" class="border border-slate-300 hover:bg-slate-50 text-slate-700 px-3 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors">
                                    Detailní přehled
                                </a>
                                <button onclick="openCreateModal()" class="bg-teal-700 hover:bg-teal-800 text-white px-3.5 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors cursor-pointer">
                                    + Nové Hlášení
                                </button>
                            </div>
                        </div>

                        <!-- MOBILNÍ KARTY (< md) -->
                        <div class="space-y-3 md:hidden">
                            @forelse($poruchy as $porucha)
                                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-3.5">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span class="text-xs font-bold text-slate-400">#{{ $porucha->IDPorucha }}</span>
                                            <span class="mx-1 text-slate-300">•</span>
                                            <span class="text-xs text-slate-500">{{ $porucha->Datum_nahlaseni ? $porucha->Datum_nahlaseni->format('d.m.Y') : '-' }}</span>
                                            <h3 class="mt-0.5 text-sm font-bold text-slate-800">
                                                {{ $porucha->Lokace }} — {{ $porucha->kategorie->Nazev ?? 'Nespecifikováno' }}
                                            </h3>
                                        </div>
                                        <span class="shrink-0 px-2.5 py-1 text-xs font-semibold rounded-full
                                            {{ $porucha->Status_poruchy === 'Nové' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $porucha->Status_poruchy === 'V řešení' ? 'bg-amber-100 text-amber-700' : '' }}
                                            {{ $porucha->Status_poruchy === 'Vyřešeno' ? 'bg-emerald-100 text-emerald-700' : '' }}">
                                            {{ $porucha->Status_poruchy }}
                                        </span>
                                    </div>

                                    @if($porucha->Popis)
                                        <p class="mt-1.5 text-xs text-slate-600 line-clamp-2">{{ $porucha->Popis }}</p>
                                    @endif

                                    <div class="mt-3 pt-2.5 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-2">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded
                                            {{ in_array($porucha->Priorita, ['Vysoká', 'Kritická']) ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                                            {{ $porucha->Priorita === 'Střední' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                            {{ $porucha->Priorita === 'Nízká' ? 'bg-slate-200/70 text-slate-600' : '' }}">
                                            Priorita: {{ $porucha->Priorita }}
                                        </span>

                                        <div class="flex items-center gap-3 text-xs font-semibold">
                                            <a href="{{ route('poruchy.show', $porucha->IDPorucha) }}" class="text-sky-600 hover:underline">
                                                {{ $isStaff ? 'Detail / Řešit' : 'Detail' }}
                                            </a>
                                            @if($isStaff)
                                                <button type="button" onclick="openEditModalById({{ $porucha->IDPorucha }})" class="text-teal-600 hover:underline cursor-pointer">
                                                    Upravit
                                                </button>
                                                @if($isAdmin)
                                                    <form action="{{ route('poruchy.destroy', $porucha->IDPorucha) }}" method="POST" class="inline" onsubmit="return confirm('Opravdu smazat tuto poruchu?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="redirect_to" value="home">
                                                        <button type="submit" class="text-red-500 hover:underline cursor-pointer">Smazat</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-sm text-slate-400">Žádná hlášení nebyla nalezena.</div>
                            @endforelse
                        </div>

                        <!-- DESKTOP TABULKA (md+) -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200">
                                    <tr>
                                        <th class="p-3">ID</th>
                                        <th class="p-3">Datum</th>
                                        <th class="p-3">Budova / Místnost</th>
                                        <th class="p-3">Kategorie</th>
                                        <th class="p-3">Priorita</th>
                                        <th class="p-3">Stav</th>
                                        <th class="p-3">Akce</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($poruchy as $porucha)
                                    <tr class="hover:bg-slate-50">
                                        <td class="p-3 font-medium text-slate-800">#{{ $porucha->IDPorucha }}</td>
                                        <td class="p-3 whitespace-nowrap">{{ $porucha->Datum_nahlaseni ? $porucha->Datum_nahlaseni->format('d.m.Y') : '-' }}</td>
                                        <td class="p-3 font-medium text-slate-800">{{ $porucha->Lokace }}</td>
                                        <td class="p-3">
                                            <div>{{ $porucha->kategorie->Nazev ?? 'Nespecifikováno' }}</div>
                                            @if($porucha->Popis)
                                                <div class="text-xs text-slate-400 truncate max-w-xs" title="{{ $porucha->Popis }}">{{ $porucha->Popis }}</div>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            <span class="px-2 py-0.5 text-xs font-semibold rounded
                                                {{ in_array($porucha->Priorita, ['Vysoká', 'Kritická']) ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                                                {{ $porucha->Priorita === 'Střední' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                                {{ $porucha->Priorita === 'Nízká' ? 'bg-slate-100 text-slate-600' : '' }}">
                                                {{ $porucha->Priorita }}
                                            </span>
                                        </td>
                                        <td class="p-3 whitespace-nowrap">
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                                {{ $porucha->Status_poruchy === 'Nové' ? 'bg-red-100 text-red-700' : '' }}
                                                {{ $porucha->Status_poruchy === 'V řešení' ? 'bg-amber-100 text-amber-700' : '' }}
                                                {{ $porucha->Status_poruchy === 'Vyřešeno' ? 'bg-emerald-100 text-emerald-700' : '' }}">
                                                {{ $porucha->Status_poruchy }}
                                            </span>
                                        </td>
                                        <td class="p-3 whitespace-nowrap font-medium space-x-2">
                                            <a href="{{ route('poruchy.show', $porucha->IDPorucha) }}" class="text-sky-600 hover:underline">
                                                {{ $isStaff ? 'Detail / Řešit' : 'Detail' }}
                                            </a>
                                            @if($isStaff)
                                                <button type="button" onclick="openEditModalById({{ $porucha->IDPorucha }})" class="text-teal-600 hover:underline cursor-pointer">Upravit</button>
                                                @if($isAdmin)
                                                    <form action="{{ route('poruchy.destroy', $porucha->IDPorucha) }}" method="POST" class="inline" onsubmit="return confirm('Opravdu smazat tuto poruchu?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="redirect_to" value="home">
                                                        <button type="submit" class="text-red-500 hover:underline text-xs cursor-pointer">Smazat</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="p-4 text-center text-slate-400">Žádná hlášení nebyla nalezena.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pravý panel s grafem -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
                            <h3 class="text-base font-bold text-slate-800 mb-4">Hlášení podle Stavů</h3>
                            <div class="w-44 h-44 sm:w-48 sm:h-48 mx-auto">
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

            </main>
        </div>
    </div>

    <!-- MODAL - NOVÉ HLÁŠENÍ -->
    <div id="createModal" class="fixed inset-0 bg-black/40 hidden flex items-end sm:items-center justify-center p-3 sm:p-4 z-[950]">
        <div class="bg-white rounded-2xl sm:rounded-xl shadow-lg w-full max-w-md max-h-[88dvh] overflow-y-auto p-5 sm:p-6">
            <h3 class="text-lg font-bold mb-4">Nové Hlášení Poruchy</h3>
            <form action="{{ route('poruchy.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="redirect_to" value="home">
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Budova / Místnost</label>
                    <input type="text" name="Lokace" required placeholder="např. A/305 nebo 364" class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Kategorie</label>
                        <select name="IDKategorie" required class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                            <option value="">-- Vyberte --</option>
                            @foreach($kategorie as $kat)
                                <option value="{{ $kat->IDKategorie }}">{{ $kat->Nazev }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Priorita</label>
                        <select name="Priorita" required class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                            <option value="Nízká">Nízká</option>
                            <option value="Střední" selected>Střední</option>
                            <option value="Vysoká">Vysoká</option>
                            <option value="Kritická">Kritická</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Popis závady</label>
                    <textarea name="Popis" rows="3" placeholder="Stručně popište problém..." class="w-full border p-2.5 sm:p-2 rounded-lg text-sm"></textarea>
                </div>

                @if($isStaff)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Stav</label>
                            <select name="Status_poruchy" class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                                <option value="Nové" selected>Nové</option>
                                <option value="V řešení">V řešení</option>
                                <option value="Vyřešeno">Vyřešeno</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Přiřadit technika</label>
                            <select name="IDTechnik" class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                                <option value="">-- Nepřiřazeno --</option>
                                @foreach($technici as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->name }} {{ $tech->surname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <div class="rounded-lg bg-slate-50 border border-slate-200 px-3 py-2 text-xs text-slate-600">
                        Stav hlášení bude automaticky nastaven na <span class="font-semibold text-red-700">Nové</span>.
                    </div>
                @endif

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 border rounded-lg text-sm cursor-pointer">Zrušit</button>
                    <button type="submit" class="px-4 py-2 bg-teal-700 text-white rounded-lg text-sm font-medium cursor-pointer">Uložit Hlášení</button>
                </div>
            </form>
        </div>
    </div>

    @if($isStaff)
    <!-- MODAL - RYCHLÁ EDITACE PORUCHY (jen technik a správce) -->
    <div id="editModal" class="fixed inset-0 bg-black/40 hidden flex items-end sm:items-center justify-center p-3 sm:p-4 z-[950]">
        <div class="bg-white rounded-2xl sm:rounded-xl shadow-lg w-full max-w-md max-h-[88dvh] overflow-y-auto p-5 sm:p-6">
            <h3 class="text-lg font-bold mb-4">Upravit Poruchu</h3>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_to" value="home">
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Budova / Místnost</label>
                    <input type="text" id="edit_lokace" name="Lokace" required class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Kategorie</label>
                        <select id="edit_kategorie" name="IDKategorie" required class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                            @foreach($kategorie as $kat)
                                <option value="{{ $kat->IDKategorie }}">{{ $kat->Nazev }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Priorita</label>
                        <select id="edit_priorita" name="Priorita" required class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                            <option value="Nízká">Nízká</option>
                            <option value="Střední">Střední</option>
                            <option value="Vysoká">Vysoká</option>
                            <option value="Kritická">Kritická</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Stav</label>
                        <select id="edit_status" name="Status_poruchy" class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                            <option value="Nové">Nové</option>
                            <option value="V řešení">V řešení</option>
                            <option value="Vyřešeno">Vyřešeno</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Přiřazený technik</label>
                        <select id="edit_technik" name="IDTechnik" class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                            <option value="">-- Nepřiřazeno --</option>
                            @foreach($technici as $tech)
                                <option value="{{ $tech->id }}">{{ $tech->name }} {{ $tech->surname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Popis závady</label>
                    <textarea id="edit_popis" name="Popis" rows="3" class="w-full border p-2.5 sm:p-2 rounded-lg text-sm"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded-lg text-sm cursor-pointer">Zrušit</button>
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded-lg text-sm font-medium cursor-pointer">Uložit Změny</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        // Graf
        const ctx = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Nové', 'V řešení', 'Vyřešeno'],
                datasets: [{
                    data: [{{ $stats['nove'] }}, {{ $stats['v_reseni'] }}, {{ $stats['vyreseno'] }}],
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '70%',
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Modální okna
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }
        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        @if($isStaff)
        const poruchyData = @json($poruchy->keyBy('IDPorucha'));

        function openEditModalById(id) {
            const p = poruchyData[id];
            if (!p) return;
            document.getElementById('editForm').action = '/poruchy/' + p.IDPorucha;
            document.getElementById('edit_lokace').value = p.Lokace || '';
            document.getElementById('edit_kategorie').value = p.IDKategorie || '';
            document.getElementById('edit_priorita').value = p.Priorita || 'Střední';
            document.getElementById('edit_status').value = p.Status_poruchy || 'Nové';
            document.getElementById('edit_technik').value = p.IDTechnik || '';
            document.getElementById('edit_popis').value = p.Popis || '';
            document.getElementById('editModal').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        @endif
    </script>

</body>
</html>
