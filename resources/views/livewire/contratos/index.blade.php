<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Contratos
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap gap-3">
                    <input wire:model.live.debounce.400ms="search" type="text" placeholder="Buscar por cliente o documento..."
                        class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 shadow-sm w-64" />

                    <select wire:model.live="statusFilter" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 shadow-sm">
                        <option value="">Todos los estados</option>
                        <option value="active">Activo</option>
                        <option value="suspended">Suspendido</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>

                <x-primary-button wire:click="create">Nuevo contrato</x-primary-button>
            </div>

            @if ($deleteError)
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded-md text-sm">
                    {{ $deleteError }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">N° contrato</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tarifa</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Día de cobro</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($contratos as $contrato)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $contrato->contract_number }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $contrato->client->full_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $contrato->serviceType->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ config('cobranzas.currency_symbol') }} {{ number_format($contrato->effectiveMonthlyFee(), 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $contrato->billing_day }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $badge = match ($contrato->status) {
                                            'active' => 'bg-green-100 text-green-800',
                                            'suspended' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-200 text-gray-700',
                                        };
                                        $label = match ($contrato->status) {
                                            'active' => 'Activo',
                                            'suspended' => 'Suspendido',
                                            default => 'Cancelado',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $badge }}">{{ $label }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right space-x-2">
                                    <button wire:click="edit({{ $contrato->id }})" class="text-indigo-600 hover:underline">Editar</button>
                                    <button wire:click="delete({{ $contrato->id }})" wire:confirm="¿Eliminar este contrato?" class="text-red-600 hover:underline">Eliminar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">Sin contratos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $contratos->links() }}
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" wire:click="$set('showModal', false)"></div>

            <div class="relative mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl sm:max-w-xl sm:mx-auto">
                <form wire:submit="save" class="p-6 space-y-4 max-h-[85vh] overflow-y-auto">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $editingId ? 'Editar contrato' : 'Nuevo contrato' }}
                    </h2>

                    <div>
                        <x-input-label value="Buscar cliente" />
                        <x-text-input wire:model.live.debounce.300ms="clientSearch" class="block mt-1 w-full" placeholder="Nombre o documento..." />

                        <x-input-label for="client_id" value="Cliente" class="mt-2" />
                        <select wire:model="client_id" id="client_id" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm">
                            <option value="">-- Seleccione --</option>
                            @foreach ($clientesOpciones as $cliente)
                                <option value="{{ $cliente->id }}">{{ $cliente->full_name }} ({{ $cliente->document_number }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="service_type_id" value="Tipo de servicio" />
                        <select wire:model="service_type_id" id="service_type_id" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm">
                            <option value="">-- Seleccione --</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->id }}">{{ $servicio->name }} ({{ config('cobranzas.currency_symbol') }} {{ number_format($servicio->monthly_price, 2) }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('service_type_id')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="contract_number" value="N° de contrato" />
                            <x-text-input wire:model="contract_number" id="contract_number" class="block mt-1 w-full" />
                            <x-input-error :messages="$errors->get('contract_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="monthly_fee" value="Tarifa personalizada (opcional)" />
                            <x-text-input wire:model="monthly_fee" id="monthly_fee" type="number" step="0.01" min="0" class="block mt-1 w-full" placeholder="Usa la del servicio si se deja vacío" />
                            <x-input-error :messages="$errors->get('monthly_fee')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="start_date" value="Fecha de inicio" />
                            <x-text-input wire:model="start_date" id="start_date" type="date" class="block mt-1 w-full" />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="billing_day" value="Día de cobro (1-28)" />
                            <x-text-input wire:model="billing_day" id="billing_day" type="number" min="1" max="28" class="block mt-1 w-full" />
                            <x-input-error :messages="$errors->get('billing_day')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="status" value="Estado" />
                        <select wire:model="status" id="status" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm">
                            <option value="active">Activo</option>
                            <option value="suspended">Suspendido</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <x-secondary-button type="button" wire:click="$set('showModal', false)">Cancelar</x-secondary-button>
                        <x-primary-button type="submit">Guardar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
