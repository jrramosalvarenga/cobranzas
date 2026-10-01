<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Usuarios
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex flex-wrap items-center justify-between gap-4">
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Buscar por nombre o email..."
                    class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 shadow-sm w-64" />

                <x-primary-button wire:click="create">Nuevo usuario</x-primary-button>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $usuario->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $usuario->email }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $badge = match ($usuario->role->value) {
                                            'admin' => 'bg-purple-100 text-purple-800',
                                            default => 'bg-blue-100 text-blue-800',
                                        };
                                        $label = match ($usuario->role->value) {
                                            'admin' => 'Administrador',
                                            default => 'Cobrador',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full {{ $badge }}">{{ $label }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right space-x-2">
                                    <button wire:click="edit({{ $usuario->id }})" class="text-indigo-600 hover:underline">Editar</button>
                                    @if ($usuario->id !== auth()->id())
                                        <button wire:click="delete({{ $usuario->id }})" wire:confirm="¿Eliminar este usuario?" class="text-red-600 hover:underline">Eliminar</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Sin usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $usuarios->links() }}
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" wire:click="$set('showModal', false)"></div>

            <div class="relative mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl sm:max-w-lg sm:mx-auto">
                <form wire:submit="save" class="p-6 space-y-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $editingId ? 'Editar usuario' : 'Nuevo usuario' }}
                    </h2>

                    <div>
                        <x-input-label for="name" value="Nombre" />
                        <x-text-input wire:model="name" id="name" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input wire:model="email" id="email" type="email" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="role" value="Rol" />
                        <select wire:model="role" id="role" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm">
                            <option value="admin">Administrador</option>
                            <option value="cobrador">Cobrador</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="$editingId ? 'Contraseña (dejar vacío para no cambiar)' : 'Contraseña'" />
                        <x-text-input wire:model="password" id="password" type="password" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Confirmar contraseña" />
                        <x-text-input wire:model="password_confirmation" id="password_confirmation" type="password" class="block mt-1 w-full" />
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
