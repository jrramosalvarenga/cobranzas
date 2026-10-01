<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Contabilidad
            </h2>
            <x-tour-button id="contabilidad" :steps="[
                ['title' => 'Contabilidad', 'description' => 'Registra ingresos y egresos adicionales que no provienen de la cobranza regular (ej: venta de materiales, gastos operativos).'],
                ['title' => 'Tipo de movimiento', 'description' => 'Cada entrada se clasifica como ingreso o egreso, con fecha, descripcion y monto.'],
            ]" />
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('contabilidad.estado-cuenta') }}" wire:navigate class="text-sm text-indigo-600 hover:underline">
                    Ver estado de cuenta &rarr;
                </a>

                <x-primary-button wire:click="create">Nuevo registro</x-primary-button>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4 flex flex-wrap items-end gap-4">
                <div>
                    <x-input-label for="filterType" value="Tipo" />
                    <select wire:model.live="filterType" id="filterType" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm text-sm">
                        <option value="">Todos</option>
                        <option value="ingreso">Ingresos</option>
                        <option value="egreso">Gastos</option>
                    </select>
                </div>

                <div>
                    <x-input-label for="filterFrom" value="Desde" />
                    <x-text-input wire:model.live="filterFrom" id="filterFrom" type="date" class="mt-1 text-sm" />
                </div>

                <div>
                    <x-input-label for="filterTo" value="Hasta" />
                    <x-text-input wire:model.live="filterTo" id="filterTo" type="date" class="mt-1 text-sm" />
                </div>
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
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Monto</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($entries as $entry)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $entry->entry_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($entry->type === 'ingreso')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Ingreso</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Gasto</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $entry->category }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $entry->description }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-right {{ $entry->type === 'ingreso' ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $entry->type === 'ingreso' ? '+' : '-' }}{{ config('cobranzas.currency_symbol') }} {{ number_format($entry->amount, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-right space-x-2">
                                    <button wire:click="edit({{ $entry->id }})" class="text-indigo-600 hover:underline">Editar</button>
                                    <button wire:click="delete({{ $entry->id }})" wire:confirm="¿Eliminar este registro?" class="text-red-600 hover:underline">Eliminar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Sin movimientos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $entries->links() }}
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" wire:click="$set('showModal', false)"></div>

            <div class="relative mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl sm:max-w-lg sm:mx-auto">
                <form wire:submit="save" class="p-6 space-y-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $editingId ? 'Editar registro' : 'Nuevo registro contable' }}
                    </h2>

                    <div>
                        <x-input-label for="type" value="Tipo" />
                        <select wire:model="type" id="type" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm">
                            <option value="egreso">Gasto</option>
                            <option value="ingreso">Otro ingreso</option>
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="category" value="Categoría" />
                        <input wire:model="category" id="category" list="categorias" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm" placeholder="Ej. Alquiler, Donación..." />
                        <datalist id="categorias">
                            @foreach (($type === 'ingreso' ? $categoriasIngreso : $categoriasEgreso) as $cat)
                                <option value="{{ $cat }}"></option>
                            @endforeach
                        </datalist>
                        <x-input-error :messages="$errors->get('category')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="amount" value="Monto" />
                        <x-text-input wire:model="amount" id="amount" type="number" step="0.01" min="0" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="entry_date" value="Fecha" />
                        <x-text-input wire:model="entry_date" id="entry_date" type="date" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('entry_date')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Descripción (opcional)" />
                        <textarea wire:model="description" id="description" rows="2" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm"></textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
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
