<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hlášení poruch - Přehled</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 sm:p-8 mobile-has-nav">
    @php
        $u = auth()->user();
        $isStaff = $u?->isStaff() ?? false;
        $isAdmin = $u?->isAdmin() ?? false;
    @endphp

    @include('partials.mobile-nav')

    <div class="max-w-6xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow">
        <div class="flex flex-wrap justify-between items-center gap-3 sm:gap-4 mb-5 sm:mb-6">
            <div>
                <a href="{{ route('home') }}" class="hidden md:inline text-sm text-blue-600 hover:underline">&larr; Zpět na dashboard</a>
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mt-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800">Přehled hlášených poruch</h1>
                    @if($u)
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                            Role: {{ $u->role_label }}
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('poruchy.create') }}" class="w-full sm:w-auto text-center bg-blue-600 text-white px-4 py-2.5 sm:py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                + Nahlásit poruchu
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg text-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- MOBILNÍ KARTY (< md) -->
        <div class="space-y-3 md:hidden">
            @forelse($poruchy as $porucha)
                <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-xs font-bold text-slate-400">#{{ $porucha->IDPorucha }}</span>
                            <span class="mx-1 text-slate-300">•</span>
                            <span class="text-xs text-slate-500">{{ $porucha->Datum_nahlaseni ? $porucha->Datum_nahlaseni->format('d.m.Y H:i') : '-' }}</span>
                            <h2 class="mt-0.5 text-base font-bold text-slate-900">
                                {{ $porucha->kategorie->Nazev ?? 'Nespecifikováno' }}
                            </h2>
                            <p class="text-xs font-medium text-slate-600">
                                Lokace: <span class="text-slate-800">{{ $porucha->Lokace }}</span>
                            </p>
                        </div>
                        <span class="shrink-0 px-2.5 py-1 text-xs rounded-full font-semibold
                            {{ $porucha->Status_poruchy === 'Nové' ? 'bg-red-100 text-red-800' : '' }}
                            {{ $porucha->Status_poruchy === 'V řešení' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $porucha->Status_poruchy === 'Vyřešeno' ? 'bg-green-100 text-green-800' : '' }}">
                            {{ $porucha->Status_poruchy }}
                        </span>
                    </div>

                    @if($porucha->Popis)
                        <p class="mt-2 text-xs text-slate-600 line-clamp-2">{{ $porucha->Popis }}</p>
                    @endif

                    <div class="mt-2.5 grid grid-cols-2 gap-1 text-xs text-slate-500">
                        <div>Oznamovatel: <span class="text-slate-700">{{ $porucha->uzivatel ? $porucha->uzivatel->name . ' ' . $porucha->uzivatel->surname : 'Neznámý' }}</span></div>
                        <div>Technik: <span class="text-slate-700">{{ $porucha->technik ? $porucha->technik->name . ' ' . $porucha->technik->surname : 'Nepřiřazeno' }}</span></div>
                    </div>

                    <div class="mt-3 pt-2.5 border-t border-slate-200 flex items-center justify-between gap-2">
                        <span class="px-2 py-0.5 text-xs rounded font-semibold
                            {{ in_array($porucha->Priorita, ['Vysoká', 'Kritická']) ? 'bg-red-100 text-red-800' : 'bg-gray-200/80 text-gray-800' }}">
                            Priorita: {{ $porucha->Priorita }}
                        </span>
                        <div class="flex items-center gap-3 text-xs font-semibold">
                            <a href="{{ route('poruchy.show', $porucha->IDPorucha) }}" class="text-blue-600 hover:underline">
                                {{ $isStaff ? 'Detail / Řešit' : 'Detail' }}
                            </a>
                            @if($isAdmin)
                                <form action="{{ route('poruchy.destroy', $porucha->IDPorucha) }}" method="POST" class="inline" onsubmit="return confirm('Opravdu smazat tuto poruchu?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline cursor-pointer">Smazat</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500 text-sm">Zatím nebyly nahlášeny žádné poruchy.</div>
            @endforelse
        </div>

        <!-- DESKTOP TABULKA (md+) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="p-3">ID</th>
                        <th class="p-3">Kategorie / Popis</th>
                        <th class="p-3">Lokace</th>
                        <th class="p-3">Priorita</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Oznamovatel</th>
                        <th class="p-3">Technik</th>
                        <th class="p-3">Datum hlášení</th>
                        <th class="p-3">Akce</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($poruchy as $porucha)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium">#{{ $porucha->IDPorucha }}</td>
                            <td class="p-3">
                                <div class="font-medium text-gray-900">{{ $porucha->kategorie->Nazev ?? 'Nespecifikováno' }}</div>
                                @if($porucha->Popis)
                                    <div class="text-xs text-gray-500 max-w-xs truncate" title="{{ $porucha->Popis }}">{{ $porucha->Popis }}</div>
                                @endif
                            </td>
                            <td class="p-3">{{ $porucha->Lokace }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-xs rounded font-semibold
                                    {{ in_array($porucha->Priorita, ['Vysoká', 'Kritická']) ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $porucha->Priorita }}
                                </span>
                            </td>
                            <td class="p-3 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded font-semibold
                                    {{ $porucha->Status_poruchy === 'Nové' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $porucha->Status_poruchy === 'V řešení' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $porucha->Status_poruchy === 'Vyřešeno' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ $porucha->Status_poruchy }}
                                </span>
                            </td>
                            <td class="p-3">{{ $porucha->uzivatel ? $porucha->uzivatel->name . ' ' . $porucha->uzivatel->surname : 'Neznámý' }}</td>
                            <td class="p-3">{{ $porucha->technik ? $porucha->technik->name . ' ' . $porucha->technik->surname : 'Nepřiřazeno' }}</td>
                            <td class="p-3 whitespace-nowrap">{{ $porucha->Datum_nahlaseni ? $porucha->Datum_nahlaseni->format('d.m.Y H:i') : '-' }}</td>
                            <td class="p-3 whitespace-nowrap space-x-2">
                                <a href="{{ route('poruchy.show', $porucha->IDPorucha) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $isStaff ? 'Detail / Řešit' : 'Detail' }}
                                </a>
                                @if($isAdmin)
                                    <form action="{{ route('poruchy.destroy', $porucha->IDPorucha) }}" method="POST" class="inline" onsubmit="return confirm('Opravdu smazat tuto poruchu?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs cursor-pointer">Smazat</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-500">Zatím nebyly nahlášeny žádné poruchy.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
