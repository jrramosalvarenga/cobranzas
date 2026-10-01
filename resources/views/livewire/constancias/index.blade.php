<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Constancia de Abonado
            </h2>
            <x-tour-button id="constancias" :steps="[
                ['title' => 'Constancia de Abonado', 'description' => 'Genera constancias oficiales que certifican que un cliente es abonado activo de la empresa.'],
                ['title' => 'Buscar cliente', 'description' => 'Busca por nombre o documento. Solo aparecen clientes con al menos un contrato activo.'],
                ['title' => 'Generar PDF', 'description' => 'Haz clic en Generar PDF para obtener la constancia lista para imprimir con los datos del cliente y contrato.'],
            ]" />
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                    Busque un cliente con contrato activo para generar su constancia de abonado.
                </p>

                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Nombre o documento del cliente..."
                    class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 shadow-sm w-full max-w-md" />
            </div>

            @if ($clientes->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Documento</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Contrato</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($clientes as $cliente)
                                @foreach ($cliente->contracts as $contrato)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cliente->full_name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $cliente->document_number }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $contrato->contract_number }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-200">{{ $contrato->serviceType->name }}</td>
                                        <td class="px-4 py-3 text-sm text-right">
                                            <a href="{{ route('constancias.pdf', $contrato) }}" target="_blank"
                                                class="text-indigo-600 hover:underline font-medium">
                                                Generar PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif (strlen($search) >= 2)
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 text-center text-sm text-gray-500">
                    No se encontraron clientes con contratos activos.
                </div>
            @endif
        </div>
    </div>
</div>
