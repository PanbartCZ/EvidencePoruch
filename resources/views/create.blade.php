<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Nahlásit poruchu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Nahlásit novou poruchu</h1>

        <form action="{{ route('poruchy.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-medium mb-1">Kategorie poruchy</label>
                <select name="IDKategorie" class="w-full border p-2 rounded" required>
                    <option value="">-- Vyberte kategorii --</option>
                    @foreach($kategorie as $kat)
                        <option value="{{ $kat->IDKategorie }}">{{ $kat->Nazev }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Lokace / Místnost</label>
                <input type="text" name="Lokace" class="w-full border p-2 rounded" placeholder="např. Kancelář 204" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Priorita</label>
                <select name="Priorita" class="w-full border p-2 rounded" required>
                    <option value="Nízká">Nízká</option>
                    <option value="Střední" selected>Střední</option>
                    <option value="Vysoká">Vysoká</option>
                    <option value="Kritická">Kritická</option>
                </select>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('poruchy.index') }}" class="text-gray-600 hover:underline">Zpět</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Odeslat hlášení
                </button>
            </div>
        </form>
    </div>
</body>
</html>
