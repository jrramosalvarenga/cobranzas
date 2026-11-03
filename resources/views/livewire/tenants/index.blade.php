<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl leading-tight">
                Organizaciones
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center justify-between gap-4">
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Buscar organización..."
                    class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 shadow-sm w-full max-w-xs" />

                <x-primary-button wire:click="create">Nueva organización</x-primary-button>
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
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Usuarios</th>
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Clientes</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($tenants as $tenant)
                            <tr class="{{ $tenant->id === $currentTenantId ? 'bg-indigo-50 dark:bg-indigo-900/20' : '' }}">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200 font-medium">
                                    {{ $tenant->name }}
                                    @if ($tenant->id === $currentTenantId)
                                        <span class="ml-1 px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">Activa</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $tenant->slug }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200 text-center">{{ $tenant->users_count }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200 text-center">{{ $tenant->clients_count }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($tenant->active)
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Activa</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-700">Inactiva</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-right space-x-2">
                                    @if ($tenant->id !== $currentTenantId)
                                        <button wire:click="switchTenant({{ $tenant->id }})" class="text-green-600 hover:underline">Entrar</button>
                                    @endif
                                    <button wire:click="edit({{ $tenant->id }})" class="text-indigo-600 hover:underline">Editar</button>
                                    <button wire:click="delete({{ $tenant->id }})" wire:confirm="¿Eliminar esta organización?" class="text-red-600 hover:underline">Eliminar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Sin organizaciones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $tenants->links() }}
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" wire:click="$set('showModal', false)"></div>

            <div class="relative mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl sm:max-w-lg sm:mx-auto">
                <form wire:submit="save" class="p-6 space-y-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $editingId ? 'Editar organización' : 'Nueva organización' }}
                    </h2>

                    <div>
                        <x-input-label for="name" value="Nombre" />
                        <x-text-input wire:model.live.debounce.400ms="name" id="name" class="block mt-1 w-full" placeholder="Ej. Junta de Agua El Progreso" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="slug" value="Slug (identificador único)" />
                        <x-text-input wire:model="slug" id="slug" class="block mt-1 w-full" placeholder="junta-de-agua-el-progreso" />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="active" class="rounded border-gray-300 text-indigo-600 shadow-sm">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Organización activa</span>
                    </label>

                    <hr class="border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Personalización</h3>

                    <div>
                        <x-input-label for="logo" value="Logo" />
                        @if($currentLogoUrl && !$removeLogo)
                            <div class="mt-1 flex items-center gap-3">
                                <img src="{{ $currentLogoUrl }}" alt="Logo" class="h-12 w-12 rounded object-cover">
                                <button type="button" wire:click="$set('removeLogo', true)" class="text-xs text-red-600 hover:underline">Quitar logo</button>
                            </div>
                        @endif
                        <input wire:model="logo" type="file" id="logo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                        <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="primary_color" value="Color primario" />
                            <div class="flex items-center gap-2 mt-1">
                                <input wire:model="primary_color" type="color" id="primary_color" class="h-10 w-14 rounded border border-gray-300 cursor-pointer">
                                <x-text-input wire:model="primary_color" class="w-full font-mono text-sm" maxlength="7" />
                            </div>
                            <x-input-error :messages="$errors->get('primary_color')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="secondary_color" value="Color secundario" />
                            <div class="flex items-center gap-2 mt-1">
                                <input wire:model="secondary_color" type="color" id="secondary_color" class="h-10 w-14 rounded border border-gray-300 cursor-pointer">
                                <x-text-input wire:model="secondary_color" class="w-full font-mono text-sm" maxlength="7" />
                            </div>
                            <x-input-error :messages="$errors->get('secondary_color')" class="mt-2" />
                        </div>
                    </div>

                    <div class="rounded-lg p-3 text-white text-sm font-medium text-center" style="background: linear-gradient(to right, {{ $primary_color }}, {{ $secondary_color }})">
                        Vista previa del encabezado
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
