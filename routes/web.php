<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoruchaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReseniController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Přihlášení je úvodní stránka
Route::get('/', fn () => auth()->check() ? redirect()->route('home') : view('prihlaseni'))->name('prihlaseni');
Route::post('/prihlasovani', [UserController::class, 'login'])->name('prihlasovani');

// Registrace
Route::get('/registrace', [UserController::class, 'create'])->name('registrace');
Route::post('/registrace', [UserController::class, 'store'])->name('registrace.store');

// Kontakt vidí všichni
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Jen pro přihlášené (všechny role: user, technik, spravce)
Route::middleware('auth')->group(function () {
    // Hlavní přehled poruch (Dashboard)
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Jednotná správa poruch (čtení a nahlášení nové poruchy)
    Route::get('/poruchy', [PoruchaController::class, 'index'])->name('poruchy.index');
    Route::get('/poruchy/create', [PoruchaController::class, 'create'])->name('poruchy.create');
    Route::post('/poruchy', [PoruchaController::class, 'store'])->name('poruchy.store');
    Route::get('/poruchy/{porucha}', [PoruchaController::class, 'show'])->name('poruchy.show');

    // Profil uživatele
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/heslo', [ProfileController::class, 'updatePassword'])->name('profile.heslo');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/odhlaseni', [UserController::class, 'logout'])->name('odhlaseni');

    Route::get('/zkouska', fn () => view('zkouska'))->name('zkouska');

    // Pouze Technik a Správce (úpravy poruch, změna stavů, přidávání řešení)
    Route::middleware('role:spravce,technik')->group(function () {
        Route::put('/poruchy/{porucha}', [PoruchaController::class, 'update'])->name('poruchy.update');
        Route::post('/poruchy/{porucha}/reseni', [ReseniController::class, 'store'])->name('reseni.store');
        Route::get('/technik', fn () => view('technik.zkouska'))->name('technik.zkouska');
    });

    // Pouze Správce / Admin (mazání poruch a správa uživatelů)
    Route::middleware('role:spravce')->group(function () {
        Route::delete('/poruchy/{porucha}', [PoruchaController::class, 'destroy'])->name('poruchy.destroy');

        Route::get('/spravaUzivatelu', function () {
            return view('sprava-uzivatelu', [
                'users' => User::orderByRaw("CASE role WHEN 'spravce' THEN 1 WHEN 'technik' THEN 2 ELSE 3 END")
                    ->orderBy('surname')
                    ->orderBy('name')
                    ->get(),
            ]);
        })->name('sprava-uzivatelu');

        Route::delete('/sprava-uzivatelu/{user}', [UserController::class, 'destroy'])
            ->name('sprava-uzivatelu.destroy');
        Route::put('/sprava-uzivatelu/{user}', [UserController::class, 'update'])
            ->name('sprava-uzivatelu.update');
    });
});
