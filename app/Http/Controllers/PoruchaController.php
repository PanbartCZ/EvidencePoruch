<?php

namespace App\Http\Controllers;

use App\Models\KategoriePoruchy;
use App\Models\Porucha;
use App\Models\User;
use Illuminate\Http\Request;

class PoruchaController extends Controller
{
    // Přehled všech poruch
    public function index()
    {
        $poruchy = Porucha::with(['uzivatel', 'kategorie', 'technik'])
            ->orderByDesc('Datum_nahlaseni')
            ->orderByDesc('IDPorucha')
            ->get();

        return view('poruchy.index', compact('poruchy'));
    }

    // Formulář pro nahlášení nové poruchy
    public function create()
    {
        $kategorie = KategoriePoruchy::orderBy('IDKategorie')->get();

        return view('poruchy.create', compact('kategorie'));
    }

    // Uložení nové poruchy do databáze (z Dashboardu i z Detailních poruch)
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'Priorita'       => 'required|string|in:Nízká,Střední,Vysoká,Kritická',
            'Lokace'         => 'required|string|max:255',
            'Popis'          => 'nullable|string|max:2000',
            'IDKategorie'    => 'required|exists:kategorie_poruch,IDKategorie',
            'Status_poruchy' => 'nullable|string|in:Nové,V řešení,Vyřešeno',
            'IDTechnik'      => 'nullable|exists:users,id',
        ]);

        // Běžný uživatel může vložit poruchu VŽDY jen jako "Nové" bez přiřazeného technika
        $status = ($user && $user->isStaff())
            ? ($validated['Status_poruchy'] ?? 'Nové')
            : 'Nové';

        $technikId = ($user && $user->isStaff())
            ? ($validated['IDTechnik'] ?: null)
            : null;

        Porucha::create([
            'Status_poruchy'  => $status,
            'Priorita'        => $validated['Priorita'],
            'Lokace'          => $validated['Lokace'],
            'Popis'           => $validated['Popis'] ?? null,
            'Datum_nahlaseni' => now(),
            'IDUzivatel'      => $user->id,
            'IDKategorie'     => $validated['IDKategorie'],
            'IDTechnik'       => $technikId,
        ]);

        if ($request->input('redirect_to') === 'home') {
            return redirect()->route('home')->with('success', 'Porucha byla úspěšně nahlášena.');
        }

        return redirect()->route('poruchy.index')->with('success', 'Porucha byla úspěšně nahlášena.');
    }

    // Detail poruchy včetně řešení
    public function show($id)
    {
        $porucha = Porucha::with(['uzivatel', 'kategorie', 'technik', 'reseni'])->findOrFail($id);
        $technici = User::whereIn('role', ['technik', 'spravce'])
            ->orderBy('surname')
            ->orderBy('name')
            ->get();

        return view('poruchy.show', compact('porucha', 'technici'));
    }

    // Úprava poruchy (z modálu na Dashboardu i z Detailu poruchy - jen technik a správce)
    public function update(Request $request, $id)
    {
        abort_unless($request->user()?->isStaff(), 403, 'Nemáte oprávnění upravovat poruchu.');

        $porucha = Porucha::findOrFail($id);

        $validated = $request->validate([
            'Status_poruchy' => 'required|string|in:Nové,V řešení,Vyřešeno',
            'Priorita'       => 'required|string|in:Nízká,Střední,Vysoká,Kritická',
            'Lokace'         => 'sometimes|required|string|max:255',
            'Popis'          => 'nullable|string|max:2000',
            'IDKategorie'    => 'sometimes|required|exists:kategorie_poruch,IDKategorie',
            'IDTechnik'      => 'nullable|exists:users,id',
        ]);

        $updateData = [
            'Status_poruchy' => $validated['Status_poruchy'],
            'Priorita'       => $validated['Priorita'],
            'IDTechnik'      => $validated['IDTechnik'] ?: null,
        ];

        if (array_key_exists('Lokace', $validated)) {
            $updateData['Lokace'] = $validated['Lokace'];
        }
        if (array_key_exists('Popis', $validated)) {
            $updateData['Popis'] = $validated['Popis'];
        }
        if (array_key_exists('IDKategorie', $validated)) {
            $updateData['IDKategorie'] = $validated['IDKategorie'];
        }

        $porucha->update($updateData);

        if ($request->input('redirect_to') === 'home') {
            return redirect()->route('home')->with('success', 'Porucha byla aktualizována.');
        }

        return redirect()->route('poruchy.show', $porucha->IDPorucha)
            ->with('success', 'Stav a údaje poruchy byly aktualizovány.');
    }

    // Smazání poruchy (jen správce)
    public function destroy(Request $request, $id)
    {
        abort_unless($request->user()?->isAdmin(), 403, 'Smazat poruchu může pouze správce.');

        $porucha = Porucha::findOrFail($id);
        $porucha->delete();

        if ($request->input('redirect_to') === 'home') {
            return redirect()->route('home')->with('success', 'Porucha byla úspěšně smazána.');
        }

        return redirect()->route('poruchy.index')->with('success', 'Porucha byla úspěšně smazána.');
    }
}
