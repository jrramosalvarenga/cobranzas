<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Cobranza — Historial por cliente
            </h2>
            <x-tour-button id="historial" :steps="[
                ['title' => 'Historial de pagos', 'description' => 'Busca un cliente para ver todo su historial de pagos organizados por fecha.'],
                ['title' => 'Reimprimir recibos', 'description' => 'Desde el historial puedes reimprimir cualquier recibo de pago anterior.'],
            ]" />
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('cobranza.registrar') }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    Registrar pago
                </a>
            </div>

            @if (! $clienteSeleccionado)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-4">
                    <x-input-label value="Buscar cliente por nombre o documento" />
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <x-text-input wire:model.live.debounce.300ms="clientSearch" class="block w-full pl-10" placeholder="Escriba para buscar..." autofocus />
                    </div>

                    @if ($clientSearch !== '')
                        <div class="divide-y divide-gray-200 dark:divide-gray-700 border rounded-md dark:border-gray-700">
                            @forelse ($clientesEncontrados as $cliente)
                                <button type="button" wire:click="seleccionarCliente({{ $cliente->id }})"
                                    class="w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-3">
                                    <div class="shrink-0 w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cliente->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $cliente->document_number }} &middot; {{ $cliente->address_line }}</div>
                                    </div>
                                </button>
                            @empty
                                <div class="px-4 py-3 text-sm text-gray-500 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                    Sin resultados.
                                </div>
                            @endforelse
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <div class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $clienteSeleccionado->full_name }}</div>
                                <div class="text-sm text-gray-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0" /></svg>
                                    {{ $clienteSeleccionado->document_number }}
                                    &middot;
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    {{ $clienteSeleccionado->phone }}
                                </div>
                                <div class="text-sm text-gray-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    {{ $clienteSeleccionado->address_line }}
                                </div>
                            </div>
                        </div>
                        <x-secondary-button wire:click="cambiarCliente" class="inline-flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                            Cambiar cliente
                        </x-secondary-button>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200">Pagos registrados ({{ $pagos->count() }})</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Servicio / Periodo</th>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
                                <th class="px-5 py-2 text-left text-xs font-medium text-gray-500 uppercase">Recibo</th>
                                <th class="px-5 py-2 text-right text-xs font-medium text-gray-500 uppercase">Monto</th>
                                <th class="px-5 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($pagos as $pago)
                                <tr class="{{ $pago->isReversed() ? 'bg-red-50/50 dark:bg-red-900/10' : '' }}">
                                    <td class="px-5 py-3 text-sm text-gray-500 flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="{{ $pago->isReversed() ? 'line-through text-gray-400' : '' }}">{{ $pago->payment_date->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-sm {{ $pago->isReversed() ? 'line-through text-gray-400' : 'text-gray-900 dark:text-gray-200' }}">
                                        {{ $pago->cuota->contract->serviceType->name }}
                                        <span class="text-gray-500">&middot; {{ $pago->cuota->periodLabel() }}</span>
                                        @if ($pago->isReversed())
                                            <div class="no-underline mt-1" style="text-decoration: none;">
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                                    Revertido: {{ $pago->reversed_reason }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-sm text-gray-500">
                                        <span class="inline-flex items-center gap-1 {{ $pago->isReversed() ? 'line-through text-gray-400' : '' }}">
                                            @if ($pago->method === 'efectivo')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            @elseif ($pago->method === 'transferencia')
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 2v8m0 0v2m0-2c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @endif
                                            {{ ucfirst($pago->method) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-sm font-mono {{ $pago->isReversed() ? 'line-through text-gray-400' : 'text-gray-500' }}">{{ $pago->receipt_number }}</td>
                                    <td class="px-5 py-3 text-sm font-medium text-right {{ $pago->isReversed() ? 'line-through text-gray-400' : 'text-gray-900 dark:text-gray-200' }}">{{ config('cobranzas.currency_symbol') }} {{ number_format($pago->amount, 2) }}</td>
                                    <td class="px-5 py-3 text-right">
                                        @if ($pago->isReversed())
                                            <span class="text-xs text-gray-400">{{ $pago->reversed_at->format('d/m/Y') }}</span>
                                        @else
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('recibos.print', $pago->receipt_number) }}" target="_blank"
                                                   class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                   title="Reimprimir recibo">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm0-12V3a1 1 0 011-1h4a1 1 0 011 1v4" />
                                                    </svg>
                                                    Reimprimir
                                                </a>
                                                <button type="button" wire:click="confirmarReversion({{ $pago->id }})"
                                                    class="inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                                    title="Revertir pago">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                    </svg>
                                                    Revertir
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                            Este cliente no tiene pagos registrados.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($pagos->isNotEmpty())
                    @php
                        $pagosActivos = $pagos->filter(fn ($p) => ! $p->isReversed());
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-5 border-l-4 border-emerald-500">
                        <div class="text-xs font-medium text-gray-500 uppercase tracking-wide flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 2v8m0 0v2m0-2c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Total pagado
                        </div>
                        <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ config('cobranzas.currency_symbol') }} {{ number_format($pagosActivos->sum('amount'), 2) }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $pagosActivos->count() }} pago(s) activo(s)
                            @if ($pagos->count() !== $pagosActivos->count())
                                &middot; {{ $pagos->count() - $pagosActivos->count() }} revertido(s)
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @if ($showReversalModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click.self="cancelarReversion">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-md w-full mx-4 p-6 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Revertir pago</h3>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-400">Esta accion revertira el pago y actualizara el estado de la cuota. Esta operacion no se puede deshacer.</p>

                <div>
                    <x-input-label for="reversalReason" value="Motivo de la reversion" />
                    <x-text-input wire:model="reversalReason" id="reversalReason" class="block w-full mt-1" placeholder="Ej: Error en el monto, pago duplicado..." autofocus />
                    @error('reversalReason')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3">
                    <x-secondary-button wire:click="cancelarReversion">Cancelar</x-secondary-button>
                    <x-danger-button wire:click="revertirPago">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 me-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                        Confirmar reversion
                    </x-danger-button>
                </div>
            </div>
        </div>
    @endif
</div>
