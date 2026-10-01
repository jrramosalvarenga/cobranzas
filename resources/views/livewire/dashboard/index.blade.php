<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Panel principal
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('cobranza.morosos') }}" wire:navigate class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-red-500 hover:shadow-md transition">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Clientes morosos</div>
                    <div class="mt-1 text-2xl font-semibold {{ $totalMorosos > 0 ? 'text-red-600' : 'text-gray-900 dark:text-gray-100' }}">{{ $totalMorosos }}</div>
                    <div class="text-xs text-gray-400 mt-1">con cuotas vencidas</div>
                </a>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Contratos activos</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $contratosActivos }}</div>
                    <div class="text-xs text-gray-400 mt-1">generando cuotas cada mes</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-amber-500">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pendiente de cobro (mes)</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ config('cobranzas.currency_symbol') }} {{ number_format($cuotasPendientesMonto, 2) }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ $cuotasPendientesCount }} cuota(s), {{ $cuotasVencidasCount }} vencida(s)</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-sky-500">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Cobrado este mes</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ config('cobranzas.currency_symbol') }} {{ number_format($cobradoEsteMes, 2) }}</div>
                    <div class="text-xs text-gray-400 mt-1">pagos registrados</div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-teal-500">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ingresos de hoy</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ config('cobranzas.currency_symbol') }} {{ number_format($ingresosHoy, 2) }}</div>
                    <div class="text-xs text-gray-400 mt-1">cobranza + otros ingresos</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-indigo-500">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ingresos últimos 5 días</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ config('cobranzas.currency_symbol') }} {{ number_format($ingresosUltimos5Dias, 2) }}</div>
                    <div class="text-xs text-gray-400 mt-1">cobranza + otros ingresos</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200">Ingresos de los últimos 5 días</h3>
                    <a href="{{ route('contabilidad.estado-cuenta') }}" wire:navigate class="text-xs text-indigo-600 hover:underline">Ver estado de cuenta &rarr;</a>
                </div>
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($ingresosPorDia as $dia)
                            <tr class="{{ $dia['fecha']->isToday() ? 'bg-teal-50 dark:bg-teal-900/20' : '' }}">
                                <td class="px-5 py-3 text-sm text-gray-900 dark:text-gray-200">
                                    {{ $dia['fecha']->translatedFormat('l d/m/Y') }}
                                    @if ($dia['fecha']->isToday())
                                        <span class="ms-2 px-2 py-0.5 text-xs rounded-full bg-teal-100 text-teal-800">Hoy</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-sm font-medium text-gray-900 dark:text-gray-200 text-right">{{ config('cobranzas.currency_symbol') }} {{ number_format($dia['total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Accesos rápidos</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    <a href="{{ route('cobranza.registrar') }}" wire:navigate
                        class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition">
                        <div class="mx-auto w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 2v8m0 0v2m0-2c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-100">Cobranza</div>
                    </a>

                    <a href="{{ route('clientes.index') }}" wire:navigate
                        class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition">
                        <div class="mx-auto w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" /></svg>
                        </div>
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-100">Clientes</div>
                    </a>

                    <a href="{{ route('contratos.index') }}" wire:navigate
                        class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition">
                        <div class="mx-auto w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </div>
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-100">Contratos</div>
                    </a>

                    <a href="{{ route('cuotas.index') }}" wire:navigate
                        class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition">
                        <div class="mx-auto w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-100">Cuotas</div>
                    </a>

                    <a href="{{ route('servicios.index') }}" wire:navigate
                        class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition">
                        <div class="mx-auto w-10 h-10 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-100">Servicios</div>
                    </a>

                    <a href="{{ route('contabilidad.index') }}" wire:navigate
                        class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition">
                        <div class="mx-auto w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3v-6m-3 6v-1m-4 4h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-100">Contabilidad</div>
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200">Últimos pagos registrados</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($ultimosPagos as $pago)
                            <tr>
                                <td class="px-5 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $pago->cuota->contract->client->full_name }}</td>
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $pago->cuota->contract->serviceType->name }} &middot; {{ $pago->cuota->periodLabel() }}</td>
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $pago->payment_date->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 text-sm font-medium text-gray-900 dark:text-gray-200 text-right">{{ config('cobranzas.currency_symbol') }} {{ number_format($pago->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-gray-500">Aún no se han registrado pagos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
