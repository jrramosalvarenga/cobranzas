<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Estado de cuenta
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('contabilidad.index') }}" wire:navigate class="text-sm text-indigo-600 hover:underline">
                    &larr; Ver movimientos contables
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 flex flex-wrap items-end gap-4">
                <div>
                    <x-input-label for="desde" value="Desde" />
                    <x-text-input wire:model.live="desde" id="desde" type="date" class="mt-1 text-sm" />
                </div>

                <div>
                    <x-input-label for="hasta" value="Hasta" />
                    <x-text-input wire:model.live="hasta" id="hasta" type="date" class="mt-1 text-sm" />
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total ingresos</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ config('cobranzas.currency_symbol') }} {{ number_format($totalIngresos, 2) }}</div>
                    <div class="text-xs text-gray-400 mt-1">Cobranza {{ config('cobranzas.currency_symbol') }} {{ number_format($ingresosCobranza, 2) }} &middot; Otros {{ config('cobranzas.currency_symbol') }} {{ number_format($ingresosOtros, 2) }}</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-red-500">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total egresos</div>
                    <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ config('cobranzas.currency_symbol') }} {{ number_format($totalEgresos, 2) }}</div>
                    <div class="text-xs text-gray-400 mt-1">Gastos registrados</div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 {{ bccomp($balance, '0.00', 2) >= 0 ? 'border-sky-500' : 'border-amber-500' }}">
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">Balance (ingresos - egresos)</div>
                    <div class="mt-1 text-2xl font-semibold {{ bccomp($balance, '0.00', 2) >= 0 ? 'text-gray-900 dark:text-gray-100' : 'text-red-600' }}">{{ config('cobranzas.currency_symbol') }} {{ number_format($balance, 2) }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ \Illuminate\Support\Carbon::parse($desde)->format('d/m/Y') }} - {{ \Illuminate\Support\Carbon::parse($hasta)->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200">Ingresos por cobranza</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($pagosCobranza as $pago)
                            <tr>
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $pago->payment_date->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $pago->cuota->contract->client->full_name }}</td>
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $pago->cuota->contract->serviceType->name }} &middot; {{ $pago->cuota->periodLabel() }}</td>
                                <td class="px-5 py-3 text-sm font-medium text-emerald-700 text-right">+{{ config('cobranzas.currency_symbol') }} {{ number_format($pago->amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-sm text-gray-500">Sin pagos de cobranza en el rango seleccionado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200">Otros ingresos y gastos</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($movimientos as $mov)
                            <tr>
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $mov->entry_date->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $mov->category }}</td>
                                <td class="px-5 py-3 text-sm text-gray-500">{{ $mov->description }}</td>
                                <td class="px-5 py-3 text-sm font-medium text-right {{ $mov->type === 'ingreso' ? 'text-emerald-700' : 'text-red-700' }}">
                                    {{ $mov->type === 'ingreso' ? '+' : '-' }}{{ config('cobranzas.currency_symbol') }} {{ number_format($mov->amount, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-sm text-gray-500">Sin movimientos contables en el rango seleccionado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
