<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Clientes morosos
            </h2>
            <x-tour-button id="morosos" :steps="[
                ['title' => 'Clientes morosos', 'description' => 'Lista de clientes con cuotas vencidas. Muestra la deuda total, dias de atraso y detalle de cuotas pendientes.'],
                ['title' => 'Exportar PDF', 'description' => 'Puedes generar un reporte PDF con el listado completo de morosos para impresion o distribucion.'],
            ]" />
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('cobranza.registrar') }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    Registrar pago
                </a>
                <a href="{{ route('cobranza.morosos.pdf') }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Descargar PDF
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-red-500">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>
                        Clientes morosos
                    </div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalMorosos }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-amber-500">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 2v8m0 0v2m0-2c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Deuda total vencida
                    </div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ config('cobranzas.currency_symbol') }} {{ number_format($totalDeudaGlobal, 2) }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-gray-400">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Fecha del reporte</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ now()->translatedFormat('d/m/Y') }}</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <x-text-input wire:model.live.debounce.300ms="search" class="block w-full pl-10" placeholder="Buscar cliente por nombre o documento..." />
                </div>
            </div>

            @forelse ($morosos as $moroso)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $moroso->cliente->full_name }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-2">
                                    {{ $moroso->cliente->document_number }}
                                    &middot;
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    {{ $moroso->cliente->phone }}
                                    &middot;
                                    {{ $moroso->cliente->address_line }}
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-medium">{{ $moroso->diasAtraso }} días de atraso</span>
                            <span class="font-bold text-red-600">{{ config('cobranzas.currency_symbol') }} {{ number_format($moroso->totalDeuda, 2) }}</span>
                        </div>
                    </div>
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Periodo</th>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vencimiento</th>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Días</th>
                                <th class="px-5 py-2 text-right text-xs font-medium text-gray-500 uppercase">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($moroso->cuotas as $cuota)
                                <tr>
                                    <td class="px-5 py-2 text-sm text-gray-900 dark:text-gray-200">{{ $cuota->contract->serviceType->name }}</td>
                                    <td class="px-5 py-2 text-sm text-gray-500">{{ $cuota->periodLabel() }}</td>
                                    <td class="px-5 py-2 text-sm text-gray-500">{{ $cuota->due_date->format('d/m/Y') }}</td>
                                    <td class="px-5 py-2 text-sm">
                                        <span class="text-red-600 font-medium">{{ (int) $cuota->due_date->diffInDays(now()) }}d</span>
                                    </td>
                                    <td class="px-5 py-2 text-sm font-medium text-red-600 text-right">{{ config('cobranzas.currency_symbol') }} {{ number_format($cuota->saldo(), 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-sm text-gray-500">No hay clientes morosos. ¡Todo al día!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
