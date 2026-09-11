<?php

use App\Http\Controllers\RecibosController;
use App\Livewire\Clientes;
use App\Livewire\Cobranza;
use App\Livewire\Contratos;
use App\Livewire\Cuotas;
use App\Livewire\Dashboard;
use App\Livewire\Servicios;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard\Index::class)->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    Route::get('servicios', Servicios\Index::class)->name('servicios.index');
    Route::get('clientes', Clientes\Index::class)->name('clientes.index');
    Route::get('contratos', Contratos\Index::class)->name('contratos.index');
    Route::get('cuotas', Cuotas\Index::class)->name('cuotas.index');
    Route::get('cobranza', Cobranza\Registrar::class)->name('cobranza.registrar');

    Route::get('recibos/{receiptNumber}', [RecibosController::class, 'show'])->name('recibos.print');
});

require __DIR__.'/auth.php';
