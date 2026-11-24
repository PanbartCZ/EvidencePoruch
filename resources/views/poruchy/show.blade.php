<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail poruchy #{{ $porucha->IDPorucha }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 sm:p-8 mobile-has-nav">
    @php
        $u = auth()->user();
        $isStaff = $u?->isStaff() ?? false;
        $isAdmin = $u?->isAdmin() ?? false;
    @endphp

    @include('partials.mobile-nav')

    <div class="max-w-4xl mx-auto bg-white p-4 sm:p-6 rounded-xl shadow mb-6">
        <div class="flex flex-wrap justify-between items-center gap-2 mb-4">
            <a href="{{ route('poruchy.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Zpět na přehled</a>
            @if($isAdmin)
                <form action="{{ route('poruchy.destroy', $porucha->IDPorucha) }}" method="POST" onsubmit="return confirm('Opravdu trvale smazat tuto poruchu?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white text-xs sm:text-sm px-3 py-1.5 rounded-lg hover:bg-red-700 cursor-pointer">
                        Smazat poruchu
                    </button>
                </form>
            @endif
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

        <h1 class="text-xl sm:text-2xl font-bold mb-4 text-slate-800">Detail poruchy #{{ $porucha->IDPorucha }}</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-6 bg-gray-50 p-4 rounded-xl border text-sm">
            <div><strong>Status:</strong> {{ $porucha->Status_poruchy }}</div>
            <div><strong>Priorita:</strong> {{ $porucha->Priorita }}</div>
            <div><strong>Lokace:</strong> {{ $porucha->Lokace }}</div>
            <div><strong>Kategorie:</strong> {{ $porucha->kategorie->Nazev ?? 'Nespecifikováno' }}</div>
            <div><strong>Datum nahlášení:</strong> {{ $porucha->Datum_nahlaseni ? $porucha->Datum_nahlaseni->format('d.m.Y H:i') : '-' }}</div>
            <div><strong>Oznamovatel:</strong> {{ $porucha->uzivatel ? $porucha->uzivatel->name . ' ' . $porucha->uzivatel->surname : 'Neznámý' }}</div>
            <div class="sm:col-span-2"><strong>Přiřazený technik:</strong> {{ $porucha->technik ? $porucha->technik->name . ' ' . $porucha->technik->surname . ($porucha->technik->specializace ? ' (' . $porucha->technik->specializace . ')' : '') : 'Zatím nepřiřazeno' }}</div>
            @if($porucha->Popis)
                <div class="sm:col-span-2 pt-2 border-t border-gray-200">
                    <strong>Popis závady:</strong>
                    <p class="mt-1 text-gray-800 whitespace-pre-line">{{ $porucha->Popis }}</p>
                </div>
            @endif
        </div>

        @if($isStaff)
            <!-- Panel pro technika / správce: úprava stavu, priority a přiřazení technika -->
            <div class="mb-6 bg-sky-50 border border-sky-200 p-4 rounded-xl">
                <h2 class="text-base sm:text-lg font-bold text-sky-900 mb-3">Správa poruchy (Technik / Správce)</h2>
                <form action="{{ route('poruchy.update', $porucha->IDPorucha) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 items-end">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium mb-1">Status poruchy</label>
                        <select name="Status_poruchy" class="w-full border p-2.5 sm:p-2 rounded-lg bg-white text-sm">
                            <option value="Nové" @selected($porucha->Status_poruchy === 'Nové')>Nové</option>
                            <option value="V řešení" @selected($porucha->Status_poruchy === 'V řešení')>V řešení</option>
                            <option value="Vyřešeno" @selected($porucha->Status_poruchy === 'Vyřešeno')>Vyřešeno</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Priorita</label>
                        <select name="Priorita" class="w-full border p-2.5 sm:p-2 rounded-lg bg-white text-sm">
                            <option value="Nízká" @selected($porucha->Priorita === 'Nízká')>Nízká</option>
                            <option value="Střední" @selected($porucha->Priorita === 'Střední')>Střední</option>
                            <option value="Vysoká" @selected($porucha->Priorita === 'Vysoká')>Vysoká</option>
                            <option value="Kritická" @selected($porucha->Priorita === 'Kritická')>Kritická</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Přiřazený technik</label>
                        <select name="IDTechnik" class="w-full border p-2.5 sm:p-2 rounded-lg bg-white text-sm">
                            <option value="">-- Nepřiřazeno --</option>
                            @foreach($technici as $tech)
                                <option value="{{ $tech->id }}" @selected($porucha->IDTechnik == $tech->id)>
                                    {{ $tech->name }} {{ $tech->surname }} ({{ $tech->role_label }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-3 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto bg-sky-600 text-white px-4 py-2.5 sm:py-2 rounded-lg text-sm font-medium hover:bg-sky-700 cursor-pointer">
                            Uložit změny poruchy
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <h2 class="text-lg sm:text-xl font-bold mb-3 text-slate-800">Záznamy řešení</h2>
        @forelse($porucha->reseni as $r)
            <div class="border-l-4 border-blue-500 bg-gray-50 p-3 rounded-r-lg mb-3 text-sm">
                <div class="text-xs text-gray-500 mb-1">
                    Zahájeno: {{ $r->Datum_zahajeni ?? 'Nezadáno' }} | Dokončeno: {{ $r->Datum_dokonceni ?? 'V řešení' }}
                </div>
                <p class="text-gray-800">{{ $r->Popis_reseni }}</p>
            </div>
        @empty
            <p class="text-gray-500 italic text-sm mb-4">K této poruše zatím nebylo zapsáno žádné řešení.</p>
        @endforelse

        @if($isStaff)
            <!-- Formulář pro zapsání řešení technika (jen technik a správce) -->
            <hr class="my-6">
            <h3 class="text-base sm:text-lg font-bold mb-3 text-slate-800">Přidat záznam o řešení</h3>
            <form action="{{ route('reseni.store', $porucha->IDPorucha) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Datum zahájení</label>
                        <input type="datetime-local" name="Datum_zahajeni" class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Datum dokončení</label>
                        <input type="datetime-local" name="Datum_dokonceni" class="w-full border p-2.5 sm:p-2 rounded-lg text-sm">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Popis řešení</label>
                    <textarea name="Popis_reseni" rows="3" class="w-full border p-2.5 sm:p-2 rounded-lg text-sm" required placeholder="Popište provedené úkony..."></textarea>
                </div>
                <button type="submit" class="w-full sm:w-auto bg-green-600 text-white px-4 py-2.5 sm:py-2 rounded-lg text-sm font-medium hover:bg-green-700 cursor-pointer">
                    Uložit řešení
                </button>
            </form>
        @endif
    </div>
</body>
</html>
