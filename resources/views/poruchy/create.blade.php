<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nahlásit poruchu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-4 sm:p-8 mobile-has-nav">
    @include('partials.mobile-nav')

    <div class="max-w-2xl mx-auto bg-white p-5 sm:p-6 rounded-xl shadow">
        <h1 class="text-xl sm:text-2xl font-bold mb-5 sm:mb-6 text-slate-800">Nahlásit novou poruchu</h1>

        <form action="{{ route('poruchy.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-medium text-sm mb-1">Kategorie poruchy</label>
                <select name="IDKategorie" class="w-full border border-slate-300 p-2.5 sm:p-2 rounded-lg text-sm" required>
                    <option value="">-- Vyberte kategorii --</option>
                    @foreach($kategorie as $kat)
                        <option value="{{ $kat->IDKategorie }}">{{ $kat->Nazev }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm mb-1">Lokace / Místnost</label>
                <input type="text" name="Lokace" class="w-full border border-slate-300 p-2.5 sm:p-2 rounded-lg text-sm" placeholder="např. Kancelář 204 nebo 364" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm mb-1">Priorita</label>
                <select name="Priorita" class="w-full border border-slate-300 p-2.5 sm:p-2 rounded-lg text-sm" required>
                    <option value="Nízká">Nízká</option>
                    <option value="Střední" selected>Střední</option>
                    <option value="Vysoká">Vysoká</option>
                    <option value="Kritická">Kritická</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium text-sm mb-1">Popis závady</label>
                <textarea name="Popis" rows="3" class="w-full border border-slate-300 p-2.5 sm:p-2 rounded-lg text-sm" placeholder="Popište podrobněji, co nefunguje..."></textarea>
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-between items-stretch sm:items-center gap-3 mt-6">
                <a href="{{ route('poruchy.index') }}" class="text-center py-2 text-sm text-gray-600 hover:underline">Zpět</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 sm:py-2 rounded-lg text-sm font-medium hover:bg-blue-700 cursor-pointer">
                    Odeslat hlášení
                </button>
            </div>
        </form>
    </div>
</body>
</html>
