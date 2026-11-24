<?php

namespace Database\Seeders;

use App\Models\KategoriePoruchy;
use App\Models\Porucha;
use App\Models\Reseni;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Výchozí uživatelé pro přihlášení
        $admin = User::firstOrCreate(
            ['email' => 'dominik@skola.cz'],
            [
                'name'     => 'Dominik',
                'surname'  => 'Málek',
                'phone'    => '+420777111222',
                'role'     => 'spravce',
                'password' => Hash::make('heslo123'),
            ]
        );

        $technik = User::firstOrCreate(
            ['email' => 'technik@skola.cz'],
            [
                'name'         => 'Jan',
                'surname'      => 'Technik',
                'phone'        => '+420777333444',
                'role'         => 'technik',
                'specializace' => 'IT a sítě',
                'password'     => Hash::make('heslo123'),
            ]
        );

        $uzivatel = User::firstOrCreate(
            ['email' => 'uzivatel@skola.cz'],
            [
                'name'     => 'Petr',
                'surname'  => 'Novák',
                'phone'    => '+420777555666',
                'role'     => 'user',
                'password' => Hash::make('heslo123'),
            ]
        );

        // Výchozí kategorie poruch
        $kategorieList = [
            ['Nazev' => 'HW / Počítače', 'Popis' => 'Nefunkční PC, monitory, periférie a tiskárny.'],
            ['Nazev' => 'SW / Aplikace', 'Popis' => 'Chyby v operačním systému, podnikovém software a licencích.'],
            ['Nazev' => 'Síť / Internet', 'Popis' => 'Problémy s Wi-Fi, Ethernet připojením nebo VPN.'],
            ['Nazev' => 'Elektro', 'Popis' => 'Zásuvky, osvětlení, jističe a elektroinstalace.'],
            ['Nazev' => 'Nábytek', 'Popis' => 'Poškozené lavice, židle, dveře a vybavení učeben.'],
            ['Nazev' => 'Ostatní / Údržba', 'Popis' => 'Ostatní provozní a technické závady v budově.'],
        ];

        $kategorieMap = [];
        foreach ($kategorieList as $kat) {
            $record = KategoriePoruchy::firstOrCreate(
                ['Nazev' => $kat['Nazev']],
                ['Popis' => $kat['Popis']]
            );
            $kategorieMap[$kat['Nazev']] = $record->IDKategorie;
        }

        // Výchozí poruchy (jednotná tabulka pro Dashboard i Detailní přehled)
        if (Porucha::count() === 0) {
            Porucha::create([
                'Status_poruchy'  => 'Nové',
                'Priorita'        => 'Vysoká',
                'Lokace'          => '364',
                'Popis'           => 'Nefunguje projektor ani HDMI připojení na katedře.',
                'Datum_nahlaseni' => '2026-11-18 08:15:00',
                'IDUzivatel'      => $uzivatel->id,
                'IDKategorie'     => $kategorieMap['HW / Počítače'],
                'IDTechnik'       => null,
            ]);

            $p2 = Porucha::create([
                'Status_poruchy'  => 'V řešení',
                'Priorita'        => 'Střední',
                'Lokace'          => 'Chodba 3. patro',
                'Popis'           => 'Blikající zářivka u schodiště.',
                'Datum_nahlaseni' => '2026-11-19 10:30:00',
                'IDUzivatel'      => $uzivatel->id,
                'IDKategorie'     => $kategorieMap['Elektro'],
                'IDTechnik'       => $technik->id,
            ]);

            Reseni::create([
                'IDPorucha'       => $p2->IDPorucha,
                'Datum_zahajeni'  => '2026-11-19 13:00:00',
                'Datum_dokonceni' => null,
                'Popis_reseni'    => 'Objednána náhradní LED trubice a startér, výměna proběhne zítra ráno.',
            ]);

            $p3 = Porucha::create([
                'Status_poruchy'  => 'Vyřešeno',
                'Priorita'        => 'Nízká',
                'Lokace'          => '364',
                'Popis'           => 'Uvolněná deska u druhé lavice v prostřední řadě.',
                'Datum_nahlaseni' => '2026-11-15 11:20:00',
                'IDUzivatel'      => $admin->id,
                'IDKategorie'     => $kategorieMap['Nábytek'],
                'IDTechnik'       => $technik->id,
            ]);

            Reseni::create([
                'IDPorucha'       => $p3->IDPorucha,
                'Datum_zahajeni'  => '2026-11-15 14:00:00',
                'Datum_dokonceni' => '2026-11-15 14:35:00',
                'Popis_reseni'    => 'Šrouby dotaženy a doplněny podložky, lavice je stabilní.',
            ]);
        }
    }
}
