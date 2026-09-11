<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Clientes
        </h2>
    </x-slot>

    @once
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endonce

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center justify-between gap-4">
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Buscar por nombre o documento..."
                    class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 shadow-sm w-full max-w-xs" />

                <x-primary-button wire:click="create">Nuevo cliente</x-primary-button>
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
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dirección</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ubicación</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($clientes as $cliente)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cliente->full_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cliente->document_number }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cliente->phone }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cliente->address_line }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($cliente->latitude && $cliente->longitude)
                                        <a href="https://www.openstreetmap.org/?mlat={{ $cliente->latitude }}&mlon={{ $cliente->longitude }}#map=17/{{ $cliente->latitude }}/{{ $cliente->longitude }}"
                                           target="_blank" class="text-indigo-600 hover:underline">Ver mapa</a>
                                    @else
                                        <span class="text-gray-400">Sin ubicación</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-right space-x-2">
                                    <button wire:click="edit({{ $cliente->id }})" class="text-indigo-600 hover:underline">Editar</button>
                                    <button wire:click="delete({{ $cliente->id }})" wire:confirm="¿Eliminar este cliente?" class="text-red-600 hover:underline">Eliminar</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Sin clientes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $clientes->links() }}
        </div>
    </div>

    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" wire:click="$set('showModal', false)"></div>

            <div class="relative mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl sm:max-w-2xl sm:mx-auto">
                <form wire:submit="save" class="p-6 space-y-4 max-h-[85vh] overflow-y-auto">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $editingId ? 'Editar cliente' : 'Nuevo cliente' }}
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="full_name" value="Nombre completo" />
                            <x-text-input wire:model="full_name" id="full_name" class="block mt-1 w-full" />
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="document_number" value="Documento / identidad" />
                            <x-text-input wire:model="document_number" id="document_number" class="block mt-1 w-full" />
                            <x-input-error :messages="$errors->get('document_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="phone" value="Teléfono" />
                            <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Correo (opcional)" />
                            <x-text-input wire:model="email" id="email" type="email" class="block mt-1 w-full" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="address_line" value="Dirección" />
                        <x-text-input wire:model="address_line" id="address_line" class="block mt-1 w-full" placeholder="Barrio, calle, número de casa..." />
                        <x-input-error :messages="$errors->get('address_line')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="reference" value="Punto de referencia (opcional)" />
                        <x-text-input wire:model="reference" id="reference" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('reference')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label value="Ubicación exacta (haga clic en el mapa para marcar)" />

                        <div
                            wire:ignore
                            x-data="{
                                map: null,
                                marker: null,
                                init() {
                                    const startLat = {{ $latitude ?: 14.0723 }};
                                    const startLng = {{ $longitude ?: -87.1921 }};
                                    const hasPoint = {{ $latitude && $longitude ? 'true' : 'false' }};

                                    this.map = L.map(this.$refs.mapEl).setView([startLat, startLng], hasPoint ? 16 : 13);

                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '&copy; OpenStreetMap contributors',
                                        maxZoom: 19,
                                    }).addTo(this.map);

                                    if (hasPoint) {
                                        this.marker = L.marker([startLat, startLng]).addTo(this.map);
                                    }

                                    this.map.on('click', (e) => {
                                        this.setPoint(e.latlng.lat, e.latlng.lng);
                                    });

                                    setTimeout(() => this.map.invalidateSize(), 200);
                                },
                                setPoint(lat, lng) {
                                    if (this.marker) {
                                        this.marker.setLatLng([lat, lng]);
                                    } else {
                                        this.marker = L.marker([lat, lng]).addTo(this.map);
                                    }
                                    $wire.setLocation(lat, lng);
                                },
                                locate() {
                                    if (!navigator.geolocation) return;
                                    navigator.geolocation.getCurrentPosition((pos) => {
                                        const { latitude, longitude } = pos.coords;
                                        this.map.setView([latitude, longitude], 17);
                                        this.setPoint(latitude, longitude);
                                    });
                                }
                            }"
                            class="mt-1"
                        >
                            <div x-ref="mapEl" style="height: 260px;" class="rounded-md border border-gray-300 dark:border-gray-700"></div>
                            <button type="button" x-on:click="locate()" class="mt-2 text-sm text-indigo-600 hover:underline">
                                Usar mi ubicación actual
                            </button>
                        </div>

                        <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Lat: {{ $latitude ?? '—' }} / Lng: {{ $longitude ?? '—' }}
                        </div>
                        <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
                        <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="notes" value="Notas (opcional)" />
                        <textarea wire:model="notes" id="notes" rows="2" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm"></textarea>
                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
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
