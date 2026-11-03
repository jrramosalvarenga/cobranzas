<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    {{-- ===== NAVBAR ===== --}}
    <nav class="fixed top-0 w-full z-50 bg-[#0f2a4a]/95 backdrop-blur-sm border-b border-white/10" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center font-extrabold text-white text-lg">K</div>
                    <span class="text-white font-bold text-xl tracking-tight">KONTANOS</span>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-gray-300 hover:text-white text-sm transition">Funcionalidades</a>
                    <a href="#sectors" class="text-gray-300 hover:text-white text-sm transition">Sectores</a>
                    <a href="#benefits" class="text-gray-300 hover:text-white text-sm transition">Beneficios</a>
                    <a href="#login-section" class="btn-kontanos text-white px-5 py-2 rounded-lg text-sm font-semibold transition shadow-lg shadow-emerald-900/30">Iniciar sesión</a>
                </div>
                <button @click="mobileMenu = !mobileMenu" class="md:hidden text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            <div x-show="mobileMenu" x-transition class="md:hidden pb-4 space-y-2">
                <a href="#features" class="block text-gray-300 hover:text-white text-sm py-2">Funcionalidades</a>
                <a href="#sectors" class="block text-gray-300 hover:text-white text-sm py-2">Sectores</a>
                <a href="#benefits" class="block text-gray-300 hover:text-white text-sm py-2">Beneficios</a>
                <a href="#login-section" class="block btn-kontanos text-white text-center px-5 py-2 rounded-lg text-sm font-semibold mt-2">Iniciar sesión</a>
            </div>
        </div>
    </nav>

    {{-- ===== HERO ===== --}}
    <section class="relative min-h-screen flex items-center overflow-hidden">
        {{-- Video de fondo --}}
        <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
            <source src="{{ asset('images/hero-video.mp4') }}" type="video/mp4">
        </video>
        <div class="hero-overlay absolute inset-0"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                {{-- Texto hero --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-1.5 mb-6">
                        <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                        <span class="text-emerald-300 text-sm font-medium">Plataforma disponible 24/7</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight">
                        Sistema de
                        <span class="text-gradient">Cobranzas</span>
                        <br>Todo Incluido
                    </h1>

                    <p class="mt-6 text-lg text-gray-300 max-w-xl mx-auto lg:mx-0">
                        Gestiona clientes, contratos, cuotas y pagos desde un solo lugar.
                        Reportes en tiempo real, recibos digitales y control total de tu organización.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3 justify-center lg:justify-start">
                        <span class="bg-white/10 backdrop-blur-sm text-white text-sm px-4 py-2 rounded-full border border-white/20">Juntas de Agua</span>
                        <span class="bg-white/10 backdrop-blur-sm text-white text-sm px-4 py-2 rounded-full border border-white/20">Tren de Aseo</span>
                        <span class="bg-white/10 backdrop-blur-sm text-white text-sm px-4 py-2 rounded-full border border-white/20">Comerciales</span>
                        <span class="bg-white/10 backdrop-blur-sm text-white text-sm px-4 py-2 rounded-full border border-white/20">Financieras</span>
                        <span class="bg-white/10 backdrop-blur-sm text-white text-sm px-4 py-2 rounded-full border border-white/20">Servicios Básicos</span>
                    </div>

                    <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#login-section" class="btn-kontanos text-white px-8 py-3.5 rounded-xl text-base font-bold transition shadow-lg shadow-emerald-900/40 text-center">
                            Comenzar ahora
                        </a>
                        <a href="https://wa.me/50433445678" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 text-white px-8 py-3.5 rounded-xl text-base font-semibold hover:bg-white/20 transition text-center">
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Contáctanos
                        </a>
                    </div>
                </div>

                {{-- Imagen hero --}}
                <div class="hidden lg:flex justify-center">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-r from-emerald-500/20 to-teal-500/20 rounded-3xl blur-2xl"></div>
                        <img src="{{ asset('images/hero-kontanos.jpeg') }}" alt="Kontanos - Sistema de Cobranzas" class="relative rounded-2xl shadow-2xl max-h-[500px] object-cover border border-white/10">
                    </div>
                </div>
            </div>
        </div>

        {{-- Wave separator --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full"><path d="M0 40L48 35C96 30 192 20 288 25C384 30 480 50 576 55C672 60 768 50 864 40C960 30 1056 20 1152 22.5C1248 25 1344 40 1392 47.5L1440 55V80H1392C1344 80 1248 80 1152 80C1056 80 960 80 864 80C768 80 672 80 576 80C480 80 384 80 288 80C192 80 96 80 48 80H0V40Z" fill="white"/></svg>
        </div>
    </section>

    {{-- ===== FEATURES ===== --}}
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Todo lo que necesitas para <span class="text-gradient">cobrar eficientemente</span></h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Herramientas diseñadas para simplificar la gestión de cobranzas de tu organización</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature cards --}}
                <div class="group p-6 rounded-2xl border border-gray-200 hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gestión de clientes</h3>
                    <p class="text-gray-600 text-sm">Registra y administra todos tus clientes con sus datos de contacto, dirección y geolocalización.</p>
                </div>

                <div class="group p-6 rounded-2xl border border-gray-200 hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Contratos y servicios</h3>
                    <p class="text-gray-600 text-sm">Crea contratos asociados a distintos tipos de servicio con tarifas personalizables por cliente.</p>
                </div>

                <div class="group p-6 rounded-2xl border border-gray-200 hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Registro de pagos</h3>
                    <p class="text-gray-600 text-sm">Registra pagos parciales o totales, genera recibos al instante y lleva un historial completo.</p>
                </div>

                <div class="group p-6 rounded-2xl border border-gray-200 hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Reportes de morosos</h3>
                    <p class="text-gray-600 text-sm">Identifica clientes con pagos atrasados y genera reportes PDF para tu junta directiva.</p>
                </div>

                <div class="group p-6 rounded-2xl border border-gray-200 hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Contabilidad integrada</h3>
                    <p class="text-gray-600 text-sm">Lleva control de ingresos y egresos, genera estados de resultados y balances financieros.</p>
                </div>

                <div class="group p-6 rounded-2xl border border-gray-200 hover:border-emerald-300 hover:shadow-lg transition-all duration-300">
                    <div class="feature-icon w-12 h-12 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Multi-organización</h3>
                    <p class="text-gray-600 text-sm">Gestiona múltiples organizaciones desde una sola plataforma con datos completamente aislados.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== GALLERY ===== --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Cobros en <span class="text-gradient">cualquier lugar</span></h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Lleva tu sistema de cobranzas a donde estén tus clientes con pagos presenciales rápidos y seguros</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="relative group overflow-hidden rounded-2xl aspect-[3/4] md:aspect-auto md:row-span-2">
                    <img src="{{ asset('images/pexels-payment-1.jpeg') }}" alt="Cobro con terminal POS" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white font-bold text-sm">Cobro en punto de venta</span>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-2xl">
                    <img src="{{ asset('images/pexels-payment-2.jpeg') }}" alt="Pago con tarjeta" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white font-bold text-sm">Pago con tarjeta</span>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-2xl">
                    <img src="{{ asset('images/pexels-payment-3.jpeg') }}" alt="Pago digital en comercio" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white font-bold text-sm">Pago digital en comercio</span>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-2xl">
                    <img src="{{ asset('images/pexels-payment-4.jpeg') }}" alt="Atención al cliente" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white font-bold text-sm">Atención personalizada</span>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-2xl">
                    <img src="{{ asset('images/pexels-payment-5.jpeg') }}" alt="Pago contactless con celular" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white font-bold text-sm">Pago contactless</span>
                    </div>
                </div>
                <div class="relative group overflow-hidden rounded-2xl">
                    <img src="{{ asset('images/pexels-payment-6.jpeg') }}" alt="Cobro con terminal" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-white font-bold text-sm">Cobro con terminal</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== SECTORS ===== --}}
    <section id="sectors" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900">Diseñado para <span class="text-gradient">tu sector</span></h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">Una solución flexible que se adapta a las necesidades de distintos tipos de organización</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center group">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center transition">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Juntas de Agua</h3>
                    <p class="mt-2 text-sm text-gray-500">Cobro mensual de tarifa de agua potable</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center group">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-green-50 group-hover:bg-green-100 flex items-center justify-center transition">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Tren de Aseo</h3>
                    <p class="mt-2 text-sm text-gray-500">Recolección y disposición de desechos</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center group">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-purple-50 group-hover:bg-purple-100 flex items-center justify-center transition">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Comerciales</h3>
                    <p class="mt-2 text-sm text-gray-500">Cobro de servicios comerciales recurrentes</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center group">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center transition">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Financieras</h3>
                    <p class="mt-2 text-sm text-gray-500">Gestión de cuotas y préstamos</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-md transition text-center group">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-rose-50 group-hover:bg-rose-100 flex items-center justify-center transition">
                        <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900">Servicios Básicos</h3>
                    <p class="mt-2 text-sm text-gray-500">Electricidad, cable, internet y más</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== BENEFITS + STATS ===== --}}
    <section id="benefits" class="py-20 gradient-brand text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight">¿Por qué elegir <span class="text-emerald-300">Kontanos</span>?</h2>
                    <div class="mt-10 space-y-6">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">Fácil de usar</h3>
                                <p class="text-gray-300 text-sm mt-1">Interfaz intuitiva que cualquier persona puede usar sin capacitación técnica.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">Recibos al instante</h3>
                                <p class="text-gray-300 text-sm mt-1">Genera e imprime recibos de pago inmediatamente después de cada cobro.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">Control de morosos</h3>
                                <p class="text-gray-300 text-sm mt-1">Detecta automáticamente los clientes con pagos pendientes y genera reportes.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">Seguro y confiable</h3>
                                <p class="text-gray-300 text-sm mt-1">Datos protegidos, respaldos automáticos y acceso controlado por roles.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -inset-4 bg-emerald-500/10 rounded-3xl blur-2xl"></div>
                    <div class="relative rounded-2xl overflow-hidden border border-white/10">
                        <img src="{{ asset('images/pexels-payment-5.jpeg') }}" alt="Pago contactless" class="w-full h-64 sm:h-80 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0f2a4a]/90 to-transparent"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 text-center border border-white/10">
                            <div class="text-3xl font-extrabold text-emerald-300">24/7</div>
                            <div class="text-xs text-gray-300 mt-1">Disponibilidad</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 text-center border border-white/10">
                            <div class="text-3xl font-extrabold text-emerald-300">100%</div>
                            <div class="text-xs text-gray-300 mt-1">En la nube</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 text-center border border-white/10">
                            <div class="text-3xl font-extrabold text-emerald-300">
                                <svg class="w-7 h-7 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <div class="text-xs text-gray-300 mt-1">Datos seguros</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 text-center border border-white/10">
                            <div class="text-3xl font-extrabold text-emerald-300">
                                <svg class="w-7 h-7 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div class="text-xs text-gray-300 mt-1">Rápido</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== LOGIN SECTION ===== --}}
    <section id="login-section" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-md mx-auto">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center font-extrabold text-white text-xl">K</div>
                        <span class="text-2xl font-bold text-gray-900">KONTANOS</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">Ingresa a tu cuenta</h2>
                    <p class="text-gray-500 mt-1">Accede al sistema de cobranzas de tu organización</p>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form wire:submit="login" class="space-y-5">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                            <input wire:model="form.email" id="email" type="email" required autofocus autocomplete="username"
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4"
                                placeholder="tu@correo.com">
                            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                            <input wire:model="form.password" id="password" type="password" required autocomplete="current-password"
                                class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 px-4"
                                placeholder="••••••••">
                            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember" class="inline-flex items-center">
                                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500">
                                <span class="ms-2 text-sm text-gray-600">Recordarme</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="w-full btn-kontanos text-white py-3 rounded-xl font-bold text-sm transition shadow-lg shadow-emerald-900/20">
                            Iniciar sesión
                        </button>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                            <div class="relative flex justify-center text-sm"><span class="bg-white px-4 text-gray-500">o continuar con</span></div>
                        </div>

                        <a href="{{ route('auth.google') }}" class="mt-4 w-full inline-flex items-center justify-center gap-3 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                            <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                            Iniciar sesión con Google
                        </a>
                    </div>
                </div>

                <p class="text-center text-sm text-gray-500 mt-6">
                    ¿No tienes cuenta? <a href="https://wa.me/50433445678" target="_blank" rel="noopener" class="text-emerald-600 hover:text-emerald-700 font-semibold">Contáctanos por WhatsApp</a>
                </p>
            </div>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="gradient-brand text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center font-extrabold text-white text-lg">K</div>
                        <span class="text-white font-bold text-xl">KONTANOS</span>
                    </div>
                    <p class="text-gray-400 text-sm">Sistema de cobranzas todo incluido para organizaciones de servicios.</p>
                </div>

                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider text-gray-400 mb-4">Funcionalidades</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li>Gestión de clientes</li>
                        <li>Contratos y cuotas</li>
                        <li>Registro de pagos</li>
                        <li>Reportes y morosos</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider text-gray-400 mb-4">Sectores</h4>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li>Juntas de Agua</li>
                        <li>Tren de Aseo</li>
                        <li>Comerciales</li>
                        <li>Financieras</li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider text-gray-400 mb-4">Contacto</h4>
                    <ul class="space-y-3 text-sm">
                        <li>
                            <a href="https://wa.me/50433445678" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-gray-300 hover:text-emerald-300 transition">
                                <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                +504 3344-5678
                            </a>
                        </li>
                        <li class="text-gray-300">Kontanos.com</li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 pt-8 border-t border-white/10 text-center">
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} Kontanos Soft. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</div>
