<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Tipos de servicio
            </h2>
            <x-tour-button id="servicios" :steps="[
                ['title' => 'Tipos de servicio', 'description' => 'Define los servicios que ofrece la empresa (ej: agua potable, alcantarillado). Cada servicio tiene un precio mensual base.'],
                ['title' => 'Precio mensual', 'description' => 'El precio del servicio se usa como tarifa por defecto en los contratos, aunque cada contrato puede tener una tarifa personalizada.'],
            ]" />
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center justify-between gap-4">
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Buscar servicio..."
                    class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 shadow-sm w-full max-w-xs" />

                <x-primary-button wire:click="create">Nuevo servicio</x-primary-button>
            </div>

            @if ($deleteError)
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded-md text-sm">
                    {{ $deleteError }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tarifa mensual</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($servicios as $servicio)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $servicio->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ config('cobranzas.currency_symbol') }} {{ number_format($servicio->monthly_price, 2) }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($servicio->active)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Activo</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-700">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-right space-x-2">
                                    <button wire:click="edit({{ $servicio->id }})" class="text-indigo-600 hover:underline">Editar</button>
                                    <button wire:click="delete({{ $servicio->id }})" wire:confirm="¿Eliminar este servicio?" class="text-red-600 hover:underline">Eliminar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Sin servicios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $servicios->links() }}
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" wire:click="$set('showModal', false)"></div>

            <div class="relative mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl sm:max-w-lg sm:mx-auto">
                <form wire:submit="save" class="p-6 space-y-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $editingId ? 'Editar servicio' : 'Nuevo servicio' }}
                    </h2>

                    <div>
                        <x-input-label for="name" value="Nombre" />
                        <x-text-input wire:model="name" id="name" class="block mt-1 w-full" placeholder="Ej. Agua" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="monthly_price" value="Tarifa mensual" />
                        <x-text-input wire:model="monthly_price" id="monthly_price" type="number" step="0.01" min="0" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('monthly_price')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Descripción (opcional)" />
                        <textarea wire:model="description" id="description" rows="2" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm"></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="active" class="rounded border-gray-300 text-indigo-600 shadow-sm">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Servicio activo</span>
                    </label>

                    <div class="flex justify-end gap-2 pt-2">
                        <x-secondary-button type="button" wire:click="$set('showModal', false)">Cancelar</x-secondary-button>
                        <x-primary-button type="submit">Guardar</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
