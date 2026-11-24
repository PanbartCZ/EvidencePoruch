<?php

namespace App\Http\Controllers;

use App\Models\KategoriePoruchy;
use App\Models\Porucha;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Spočítáme stavy pro horní KPI karty z jednotné tabulky poruchy
        $stats = [
            'celkem'   => Porucha::count(),
            'nove'     => Porucha::where('Status_poruchy', 'Nové')->count(),
            'v_reseni' => Porucha::where('Status_poruchy', 'V řešení')->count(),
            'vyreseno' => Porucha::where('Status_poruchy', 'Vyřešeno')->count(),
        ];

        // Seznam unikátních lokací pro rozevírací filtr
        $lokaceList = Porucha::select('Lokace')
            ->distinct()
            ->orderBy('Lokace')
            ->pluck('Lokace');

        $kategorie = KategoriePoruchy::orderBy('IDKategorie')->get();
        $technici = User::whereIn('role', ['technik', 'spravce'])
            ->orderBy('surname')
            ->orderBy('name')
            ->get();

        // Filtrování záznamů v tabulce
        $query = Porucha::with(['kategorie', 'uzivatel', 'technik']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('IDPorucha', $search)
                  ->orWhere('Lokace', 'like', '%' . $search . '%')
                  ->orWhere('Popis', 'like', '%' . $search . '%')
                  ->orWhereHas('kategorie', function ($kq) use ($search) {
                      $kq->where('Nazev', 'like', '%' . $search . '%');
                  });
            });
        }

        $selectedLokace = $request->input('lokace', $request->input('mistnost'));
        if (!empty($selectedLokace)) {
            $query->where('Lokace', $selectedLokace);
        }

        if ($request->filled('status')) {
            $query->where('Status_poruchy', $request->status);
        }

        $poruchy = $query->orderByDesc('Datum_nahlaseni')
            ->orderByDesc('IDPorucha')
            ->get();

        return view('dashboard', compact('stats', 'poruchy', 'lokaceList', 'kategorie', 'technici'));
    }
}
