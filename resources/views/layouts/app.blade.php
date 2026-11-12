<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Driver.js for interactive tours -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css">
        <script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js" defer></script>

        <!-- Export: SheetJS (Excel) + jsPDF (PDF) -->
        <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js" defer></script>
    </head>
    <body class="font-sans antialiased">
        @php
            $activeTenant = null;
            if (auth()->check()) {
                $activeTenant = auth()->user()->tenant
                    ?? (auth()->user()->isSuperAdmin() && session('tenant_id')
                        ? \App\Models\Tenant::find(session('tenant_id'))
                        : null);
            }
            $brandFrom = $activeTenant?->primaryColor() ?? '#0f2a4a';
            $brandTo = $activeTenant?->secondaryColor() ?? '#0d7c5f';
        @endphp
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-emerald-50/30 dark:from-gray-900 dark:via-gray-900 dark:to-gray-900">
            <livewire:layout.navigation />

            <x-license-banner />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="shadow-md" style="background: linear-gradient(to right, {{ $brandFrom }}, {{ $brandTo }})">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 text-white">
                        @if($activeTenant)
                            <p class="text-sm text-white/70 mb-1">{{ $activeTenant->name }}</p>
                        @endif
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <x-app-footer />
        </div>
    </body>
</html>
