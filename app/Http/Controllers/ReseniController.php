<?php

namespace App\Http\Controllers;

use App\Models\Porucha;
use App\Models\Reseni;
use Illuminate\Http\Request;

class ReseniController extends Controller
{
    public function store(Request $request, $poruchaId)
    {
        abort_unless($request->user()?->isStaff(), 403, 'Zapisovat řešení může pouze technik nebo správce.');

        $validated = $request->validate([
            'Datum_zahajeni'  => 'nullable|date',
            'Datum_dokonceni' => 'nullable|date',
            'Popis_reseni'    => 'required|string',
        ]);

        Reseni::create([
            'IDPorucha'       => $poruchaId,
            'Datum_zahajeni'  => $validated['Datum_zahajeni'],
            'Datum_dokonceni' => $validated['Datum_dokonceni'],
            'Popis_reseni'    => $validated['Popis_reseni'],
        ]);

        // Automatická aktualizace stavu poruchy a přiřazení technika, pokud ještě není určen
        $porucha = Porucha::findOrFail($poruchaId);
        $novyStatus = $validated['Datum_dokonceni'] ? 'Vyřešeno' : 'V řešení';

        $porucha->update([
            'Status_poruchy' => $novyStatus,
            'IDTechnik'      => $porucha->IDTechnik ?? $request->user()->id,
        ]);

        return redirect()->back()->with('success', 'Záznam řešení byl přidán.');
    }
}
