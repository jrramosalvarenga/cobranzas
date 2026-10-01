<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Cuotas mensuales
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex flex-wrap items-end justify-between gap-4 bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                <div class="flex flex-wrap gap-3 items-end">
                    <div>
                        <x-input-label value="Año" />
                        <select wire:model.live="year" class="mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm">
                            @foreach (range(now()->year - 2, now()->year + 1) as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label value="Mes" />
                        <select wire:model.live="month" class="mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm">
                            @foreach (['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'] as $i => $nombre)
                                <option value="{{ $i + 1 }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label value="Estado" />
                        <select wire:model.live="statusFilter" class="mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm">
                            <option value="">Todos</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="parcial">Parcial</option>
                            <option value="pagada">Pagada</option>
                            <option value="vencida">Vencida</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label value="Cliente" />
                        <input wire:model.live.debounce.400ms="search" type="text" placeholder="Buscar cliente..."
                            class="mt-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm" />
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('cuotas.cobros-adicionales') }}" wire:navigate class="text-sm text-indigo-600 hover:underline whitespace-nowrap">
                        Cobros adicionales
                    </a>
                    <x-primary-button wire:click="generar" wire:confirm="¿Generar las cuotas del periodo seleccionado para todos los contratos activos?">
                        Generar cuotas del mes
                    </x-primary-button>
                </div>
            </div>

            @if ($message)
                <div class="bg-blue-100 border border-blue-300 text-blue-800 px-4 py-2 rounded-md text-sm">
                    {{ $message }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Periodo</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Saldo</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vence</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($cuotas as $cuota)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cuota->contract->client->full_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cuota->contract->serviceType->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cuota->periodLabel() }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ config('cobranzas.currency_symbol') }} {{ number_format($cuota->amount, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ config('cobranzas.currency_symbol') }} {{ number_format($cuota->saldo(), 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cuota->due_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $estado = $cuota->displayStatus();
                                        $badge = match ($estado) {
                                            'pagada' => 'bg-green-100 text-green-800',
                                            'parcial' => 'bg-yellow-100 text-yellow-800',
                                            'vencida' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-200 text-gray-700',
                                        };
                                        $label = match ($estado) {
                                            'pagada' => 'Pagada',
                                            'parcial' => 'Parcial',
                                            'vencida' => 'Vencida',
                                            default => 'Pendiente',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $badge }}">{{ $label }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">No hay cuotas para este periodo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $cuotas->links() }}
        </div>
    </div>
</div>
