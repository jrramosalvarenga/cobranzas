<?php

use App\Http\Controllers\ConstanciaAbonadoPdfController;
use App\Http\Controllers\EstadoResultadosPdfController;
use App\Http\Controllers\MorososPdfController;
use App\Http\Controllers\RecibosController;
use App\Livewire\Clientes;
use App\Livewire\Cobranza;
use App\Livewire\Constancias;
use App\Livewire\Contabilidad;
use App\Livewire\Contratos;
use App\Livewire\Cuotas;
use App\Livewire\Dashboard;
use App\Livewire\Servicios;
use App\Livewire\Usuarios;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard\Index::class)->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    // Cobranza — accesible para admin y cobrador
    Route::get('cobranza', Cobranza\Registrar::class)->name('cobranza.registrar');
    Route::get('cobranza/historial', Cobranza\HistorialCliente::class)->name('cobranza.historial');
    Route::get('cobranza/morosos', Cobranza\Morosos::class)->name('cobranza.morosos');
    Route::get('cobranza/morosos/pdf', MorososPdfController::class)->name('cobranza.morosos.pdf');
    Route::get('recibos/{receiptNumber}', [RecibosController::class, 'show'])->name('recibos.print');

    // Constancias — accesible para admin y cobrador
    Route::get('constancias', Constancias\Index::class)->name('constancias.index');
    Route::get('constancias/{contract}/pdf', ConstanciaAbonadoPdfController::class)->name('constancias.pdf');

    // Solo administrador
    Route::middleware('role:admin')->group(function () {
        Route::get('servicios', Servicios\Index::class)->name('servicios.index');
        Route::get('clientes', Clientes\Index::class)->name('clientes.index');
        Route::get('contratos', Contratos\Index::class)->name('contratos.index');
        Route::get('cuotas', Cuotas\Index::class)->name('cuotas.index');
        Route::get('cuotas/cobros-adicionales', Cuotas\CobrosAdicionales::class)->name('cuotas.cobros-adicionales');
        Route::get('contabilidad', Contabilidad\Index::class)->name('contabilidad.index');
        Route::get('contabilidad/estado-cuenta', Contabilidad\EstadoCuenta::class)->name('contabilidad.estado-cuenta');
        Route::get('contabilidad/estado-resultados/pdf', EstadoResultadosPdfController::class)->name('contabilidad.estado-resultados.pdf');
        Route::get('usuarios', Usuarios\Index::class)->name('usuarios.index');
    });
});

require __DIR__.'/auth.php';
