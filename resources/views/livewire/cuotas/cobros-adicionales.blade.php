<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Cobros adicionales
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('cuotas.index') }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    Cuotas mensuales
                </a>
            </div>

            @if ($message)
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded-md text-sm">
                    {{ $message }}
                </div>
            @endif

            @if ($error)
                <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded-md text-sm">
                    {{ $error }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 space-y-4">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200">Generar cobro adicional</h3>

                <div>
                    <x-input-label value="Alcance" />
                    <div class="mt-2 flex gap-4">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="radio" wire:model.live="alcance" value="general"
                                class="text-indigo-600 border-gray-300 shadow-sm">
                            Todos los clientes con contrato activo
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="radio" wire:model.live="alcance" value="especifico"
                                class="text-indigo-600 border-gray-300 shadow-sm">
                            Clientes específicos
                        </label>
                    </div>
                </div>

                @if ($alcance === 'especifico')
                    <div class="space-y-3">
                        <div>
                            <x-input-label value="Buscar y agregar clientes" />
                            <div class="relative mt-1">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </div>
                                <x-text-input wire:model.live.debounce.300ms="clientSearch" class="block w-full pl-10" placeholder="Nombre o documento..." />
                            </div>
                        </div>

                        @if ($clientSearch !== '')
                            <div class="divide-y divide-gray-200 dark:divide-gray-700 border rounded-md dark:border-gray-700">
                                @forelse ($clientesEncontrados as $cliente)
                                    <button type="button" wire:click="agregarCliente({{ $cliente->id }})"
                                        class="w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center gap-3">
                                        <div class="shrink-0 w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $cliente->full_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $cliente->document_number }}</div>
                                        </div>
                                    </button>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500">Sin resultados.</div>
                                @endforelse
                            </div>
                        @endif

                        @if ($clientesSeleccionados->isNotEmpty())
                            <div>
                                <x-input-label value="Clientes seleccionados ({{ $clientesSeleccionados->count() }})" />
                                <div class="mt-1 flex flex-wrap gap-2">
                                    @foreach ($clientesSeleccionados as $cliente)
                                        <span class="inline-flex items-center gap-1.5 bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-sm px-3 py-1.5 rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                            {{ $cliente->full_name }}
                                            <button type="button" wire:click="quitarCliente({{ $cliente->id }})" class="text-indigo-500 hover:text-indigo-700 dark:hover:text-indigo-300" title="Quitar">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-1">
                        <x-input-label for="description" value="Concepto" />
                        <x-text-input wire:model="description" id="description" class="block mt-1 w-full" placeholder="Ej: Reconexión, Multa..." />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="amount" value="Monto" />
                        <x-text-input wire:model="amount" id="amount" type="number" step="0.01" min="0.01" class="block mt-1 w-full" placeholder="0.00" />
                        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="due_date" value="Fecha de vencimiento" />
                        <x-text-input wire:model="due_date" id="due_date" type="date" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    @php
                        $confirmMsg = $alcance === 'general'
                            ? '¿Generar este cobro adicional para TODOS los contratos activos?'
                            : '¿Generar este cobro adicional para los ' . count($selectedClientIds) . ' cliente(s) seleccionado(s)?';
                    @endphp
                    <x-primary-button wire:click="generar" wire:confirm="{{ $confirmMsg }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        Generar cobro
                    </x-primary-button>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 text-sm text-gray-500 dark:text-gray-400">
                <p><strong>General:</strong> genera una cuota pendiente en cada contrato activo (todos los clientes).</p>
                <p><strong>Específico:</strong> busque y agregue varios clientes; el cobro se aplica a los contratos activos de cada uno.</p>
                <p class="mt-1">Las cuotas generadas aparecen en la cobranza y se cobran igual que las cuotas mensuales.</p>
            </div>
        </div>
    </div>
</div>
