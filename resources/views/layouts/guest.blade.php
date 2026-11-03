<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Kontanos - Sistema de Cobranzas Todo Incluido</title>
        <meta name="description" content="Sistema de cobranzas integral para Juntas de Agua, Tren de Aseo, Comerciales, Financieras y Servicios Básicos. Cobra fácil y seguro.">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .gradient-brand {
                background: linear-gradient(135deg, #0f2a4a 0%, #1a3f6f 40%, #0d7c5f 100%);
            }
            .gradient-card {
                background: linear-gradient(135deg, #1e3a5f 0%, #0d7c5f 100%);
            }
            .text-gradient {
                background: linear-gradient(135deg, #22d3ee, #10b981);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            .hero-overlay {
                background: linear-gradient(180deg, rgba(15,42,74,0.92) 0%, rgba(13,124,95,0.85) 100%);
            }
            .feature-icon {
                background: linear-gradient(135deg, #0d7c5f 0%, #10b981 100%);
            }
            .btn-kontanos {
                background: linear-gradient(135deg, #0d7c5f 0%, #10b981 100%);
            }
            .btn-kontanos:hover {
                background: linear-gradient(135deg, #0a6b51 0%, #0d9e6f 100%);
            }
            .animate-float {
                animation: float 6s ease-in-out infinite;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
            .scroll-smooth { scroll-behavior: smooth; }
        </style>
    </head>
    <body class="font-sans antialiased scroll-smooth">
        {{ $slot }}
    </body>
</html>
