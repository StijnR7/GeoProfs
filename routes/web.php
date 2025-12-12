<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});


Route::get('/test-users', function () {
    // Haal alle gebruikers op (zonder wachtwoord)
    $users = DB::table('users')->select('id', 'name', 'email')->get();

    // Geef de gebruikers terug als JSON
    return response()->json($users);
});

Route::get('/db-test', function () {
    return DB::connection()->getDatabaseName();
});

require __DIR__.'/settings.php';
