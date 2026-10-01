<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Cobranza — Registrar pago
        </h2>
    </x-slot>

    @once
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endonce

    <div
        x-data
        x-on:recibo-generado.window="window.open($event.detail.url, '_blank')"
    >
        <div class="py-8">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('cobranza.historial') }}" wire:navigate class="text-sm text-indigo-600 hover:underline">
                        Historial de pagos por cliente &rarr;
                    </a>
                    <a href="{{ route('cobranza.morosos') }}" wire:navigate class="text-sm text-red-600 hover:underline">
                        Clientes morosos &rarr;
                    </a>
                </div>

                @if ($error)
                    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded-md text-sm">
                        {{ $error }}
                    </div>
                @endif

                @if ($locationMessage)
                    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-md text-sm">
                        {{ $locationMessage }}
                    </div>
                @endif

                @if (! $clienteSeleccionado)
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-4">
                        <x-input-label value="Buscar cliente por nombre o documento" />
                        <x-text-input wire:model.live.debounce.300ms="clientSearch" class="block w-full" placeholder="Escriba para buscar..." autofocus />

                        @if ($clientSearch !== '')
                            <div class="divide-y divide-gray-200 dark:divide-gray-700 border rounded-md dark:border-gray-700">
                                @forelse ($clientesEncontrados as $cliente)
                                    <button type="button" wire:click="seleccionarCliente({{ $cliente->id }})"
                                        class="w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cliente->full_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $cliente->document_number }} &middot; {{ $cliente->address_line }}</div>
                                    </button>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500">Sin resultados.</div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $clienteSeleccionado->full_name }}</div>
                                <div class="text-sm text-gray-500">{{ $clienteSeleccionado->document_number }} &middot; {{ $clienteSeleccionado->phone }}</div>
                                <div class="text-sm text-gray-500">{{ $clienteSeleccionado->address_line }}</div>
                                @if ($clienteSeleccionado->reference)
                                    <div class="text-sm text-gray-400">Ref: {{ $clienteSeleccionado->reference }}</div>
                                @endif
                                @if ($clienteSeleccionado->latitude && $clienteSeleccionado->longitude)
                                    <a href="https://www.google.com/maps?q={{ $clienteSeleccionado->latitude }},{{ $clienteSeleccionado->longitude }}"
                                       target="_blank" class="text-xs text-indigo-600 hover:underline">Ver ubicación en el mapa</a>
                                @else
                                    <div class="text-xs text-amber-600">Sin ubicación GPS registrada</div>
                                @endif
                            </div>
                            <div class="flex flex-col items-end gap-2 shrink-0">
                                <x-secondary-button wire:click="cambiarCliente">Cambiar cliente</x-secondary-button>
                                @if (! $editingLocation)
                                    <button type="button" wire:click="editarUbicacion" class="text-sm text-indigo-600 hover:underline whitespace-nowrap">
                                        Editar domicilio / ubicación
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if ($editingLocation)
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-4">
                                <div>
                                    <x-input-label for="loc_address_line" value="Dirección" />
                                    <x-text-input wire:model="loc_address_line" id="loc_address_line" class="block mt-1 w-full" />
                                    <x-input-error :messages="$errors->get('loc_address_line')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="loc_reference" value="Punto de referencia (opcional)" />
                                    <x-text-input wire:model="loc_reference" id="loc_reference" class="block mt-1 w-full" />
                                </div>

                                <div>
                                    <x-input-label value="Ubicación exacta (toque el mapa para marcar)" />

                                    <div
                                        wire:ignore
                                        x-data="{
                                            map: null,
                                            marker: null,
                                            init() {
                                                const startLat = {{ $loc_latitude ?: 14.0723 }};
                                                const startLng = {{ $loc_longitude ?: -87.1921 }};
                                                const hasPoint = {{ $loc_latitude && $loc_longitude ? 'true' : 'false' }};

                                                this.map = L.map(this.$refs.locMapEl).setView([startLat, startLng], hasPoint ? 16 : 13);

                                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                                    attribution: '&copy; OpenStreetMap contributors',
                                                    maxZoom: 19,
                                                }).addTo(this.map);

                                                if (hasPoint) {
                                                    this.marker = L.marker([startLat, startLng]).addTo(this.map);
                                                }

                                                this.map.on('click', (e) => this.setPoint(e.latlng.lat, e.latlng.lng));

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
                                        <div x-ref="locMapEl" style="height: 220px;" class="rounded-md border border-gray-300 dark:border-gray-700"></div>
                                        <button type="button" x-on:click="locate()" class="mt-2 text-sm text-indigo-600 hover:underline">
                                            Usar mi ubicación actual
                                        </button>
                                    </div>

                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Lat: {{ $loc_latitude ?? '—' }} / Lng: {{ $loc_longitude ?? '—' }}
                                    </div>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <x-secondary-button type="button" wire:click="cancelarUbicacion">Cancelar</x-secondary-button>
                                    <x-primary-button type="button" wire:click="guardarUbicacion">Guardar domicilio</x-primary-button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2"></th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Periodo</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Saldo</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Monto a pagar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($cuotasPendientes as $cuota)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <input type="checkbox" wire:click="toggleCuota({{ $cuota->id }})" @checked(!empty($seleccion[$cuota->id]))
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cuota->contract->serviceType->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cuota->periodLabel() }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ config('cobranzas.currency_symbol') }} {{ number_format($cuota->saldo(), 2) }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if (!empty($seleccion[$cuota->id]))
                                                <input type="number" step="0.01" min="0.01" wire:model="montos.{{ $cuota->id }}"
                                                    class="w-28 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm" />
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-6 text-center text-sm text-gray-500">Este cliente no tiene cuotas pendientes.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($cuotasPendientes->isNotEmpty())
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label value="Método de pago" />
                                    <select wire:model="method" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm">
                                        <option value="efectivo">Efectivo</option>
                                        <option value="transferencia">Transferencia</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label value="Fecha de pago" />
                                    <x-text-input wire:model="payment_date" type="date" class="mt-1 block w-full" />
                                </div>

                                <div>
                                    <x-input-label value="Notas (opcional)" />
                                    <x-text-input wire:model="notes" class="mt-1 block w-full" />
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <x-primary-button wire:click="confirmarPago">
                                    Registrar pago e imprimir recibo
                                </x-primary-button>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @if ($showConfirmModal)
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" wire:click="$set('showConfirmModal', false)"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl sm:max-w-lg sm:mx-auto">
                <div class="p-6 space-y-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Confirmar pago</h2>

                    <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                        <div><span class="font-medium">Cliente:</span> {{ $clienteSeleccionado?->full_name }}</div>
                        <div><span class="font-medium">Método:</span> {{ ucfirst($method) }}</div>
                        <div><span class="font-medium">Fecha:</span> {{ \Illuminate\Support\Carbon::parse($payment_date)->format('d/m/Y') }}</div>
                        @if ($notes)
                            <div><span class="font-medium">Notas:</span> {{ $notes }}</div>
                        @endif
                    </div>

                    <div class="border rounded-md dark:border-gray-700 overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cuota</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @php
                                    $cuotasConfirm = \App\Models\Cuota::whereIn('id', array_keys(array_filter($seleccion)))->with('contract.serviceType')->get()->keyBy('id');
                                    $totalConfirm = 0;
                                @endphp
                                @foreach ($cuotasConfirm as $cuota)
                                    @php $totalConfirm += (float) ($montos[$cuota->id] ?? 0); @endphp
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-200">
                                            {{ $cuota->contract->serviceType->name }} &middot; {{ $cuota->periodLabel() }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-200 text-right">
                                            {{ config('cobranzas.currency_symbol') }} {{ number_format((float) ($montos[$cuota->id] ?? 0), 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <td class="px-4 py-2 text-sm font-bold text-gray-900 dark:text-gray-100">TOTAL</td>
                                    <td class="px-4 py-2 text-sm font-bold text-gray-900 dark:text-gray-100 text-right">
                                        {{ config('cobranzas.currency_symbol') }} {{ number_format($totalConfirm, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <x-secondary-button type="button" wire:click="$set('showConfirmModal', false)">Cancelar</x-secondary-button>
                        <x-primary-button type="button" wire:click="registrarPago">Confirmar y generar recibo</x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
