<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Hlášení poruch - Přehled</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Přehled hlášených poruch</h1>
            <a href="{{ route('poruchy.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Nahlásit poruchu
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="p-3">ID</th>
                    <th class="p-3">Kategorie</th>
                    <th class="p-3">Lokace</th>
                    <th class="p-3">Priorita</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Datum hlášení</th>
                    <th class="p-3">Akce</th>
                </tr>
            </thead>
            <tbody>
                @forelse($poruchy as $porucha)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">#{{ $porucha->IDPorucha }}</td>
                        <td class="p-3">{{ $porucha->kategorie->Nazev ?? 'Nespecifikováno' }}</td>
                        <td class="p-3">{{ $porucha->Lokace }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs rounded font-semibold
                                {{ $porucha->Priorita === 'Vysoká' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $porucha->Priorita }}
                            </span>
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs rounded font-semibold bg-yellow-100 text-yellow-800">
                                {{ $porucha->Status_poruchy }}
                            </span>
                        </td>
                        <td class="p-3">{{ $porucha->Datum_nahlaseni }}</td>
                        <td class="p-3">
                            <a href="{{ route('poruchy.show', $porucha->IDPorucha) }}" class="text-blue-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">Zatím nebyly nahlášeny žádné poruchy.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
