<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

@php
    $navTenant = auth()->user()?->tenant
        ?? (auth()->user()?->isSuperAdmin() && session('tenant_id')
            ? \App\Models\Tenant::find(session('tenant_id'))
            : null);
    $navFrom = $navTenant?->primaryColor() ?? '#0f2a4a';
    $navTo = $navTenant?->secondaryColor() ?? '#0d7c5f';
    $navLogo = $navTenant?->logoUrl();
@endphp

<nav x-data="{ open: false }" class="shadow-md" style="background: linear-gradient(to right, {{ $navFrom }}, {{ $navTo }})">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
                        @if($navLogo)
                            <img src="{{ $navLogo }}" alt="Logo" class="block h-8 w-8 rounded object-cover">
                        @else
                            <x-application-logo class="block h-8 w-8" />
                        @endif
                        <span class="hidden sm:block font-semibold text-white">{{ config('app.name') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:-my-px sm:ms-6 sm:flex sm:items-center sm:space-x-4 lg:space-x-6 overflow-x-auto scrollbar-hide">
                    @if(auth()->user()->isSuperAdmin())
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-1 px-2 py-1 border-b-2 {{ request()->routeIs('tenants.*') || request()->routeIs('admin.servicios.*') ? 'border-white text-white' : 'border-transparent text-yellow-200 hover:text-white hover:border-white/50' }} text-sm font-semibold leading-5 focus:outline-none transition duration-150 ease-in-out">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75"/></svg>
                                Admin
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('tenants.index')" wire:navigate>
                                Organizaciones
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.servicios.index')" wire:navigate>
                                Servicios (Admin)
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    @if($navTenant)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/20 text-xs font-medium text-white" title="Tenant activo">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/></svg>
                        {{ $navTenant->name }}
                    </span>
                    @endif
                    @endif
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cobranza.registrar')" :active="request()->routeIs('cobranza.*')" wire:navigate>
                        {{ __('Cobranza') }}
                    </x-nav-link>
                    <x-nav-link :href="route('constancias.index')" :active="request()->routeIs('constancias.*')" wire:navigate>
                        {{ __('Constancias') }}
                    </x-nav-link>
                    @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                    <x-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" wire:navigate>
                        {{ __('Clientes') }}
                    </x-nav-link>
                    <x-nav-link :href="route('contratos.index')" :active="request()->routeIs('contratos.*')" wire:navigate>
                        {{ __('Contratos') }}
                    </x-nav-link>
                    <x-nav-link :href="route('cuotas.index')" :active="request()->routeIs('cuotas.*')" wire:navigate>
                        {{ __('Cuotas') }}
                    </x-nav-link>
                    <x-nav-link :href="route('servicios.index')" :active="request()->routeIs('servicios.*')" wire:navigate>
                        {{ __('Servicios') }}
                    </x-nav-link>
                    <x-nav-link :href="route('contabilidad.index')" :active="request()->routeIs('contabilidad.*')" wire:navigate>
                        {{ __('Contabilidad') }}
                    </x-nav-link>
                    <x-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')" wire:navigate>
                        {{ __('Usuarios') }}
                    </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-4 shrink-0">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-white/20 text-sm leading-4 font-medium rounded-md text-white/90 bg-white/10 hover:bg-white/20 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-white/80 hover:text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 focus:text-white transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->isSuperAdmin())
            <div class="px-4 py-2 border-b border-white/20">
                <span class="text-xs font-semibold text-yellow-200 uppercase tracking-wider">Administración</span>
            </div>
            <x-responsive-nav-link :href="route('tenants.index')" :active="request()->routeIs('tenants.*')" wire:navigate>
                {{ __('Organizaciones') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.servicios.index')" :active="request()->routeIs('admin.servicios.*')" wire:navigate>
                {{ __('Servicios (Admin)') }}
            </x-responsive-nav-link>
            @if($navTenant)
            <div class="px-4 py-1">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/20 text-xs font-medium text-white">
                    Tenant: {{ $navTenant->name }}
                </span>
            </div>
            @endif
            <div class="px-4 py-2 border-b border-white/20">
                <span class="text-xs font-semibold text-white/50 uppercase tracking-wider">Operaciones</span>
            </div>
            @endif
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cobranza.registrar')" :active="request()->routeIs('cobranza.*')" wire:navigate>
                {{ __('Cobranza') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('constancias.index')" :active="request()->routeIs('constancias.*')" wire:navigate>
                {{ __('Constancias') }}
            </x-responsive-nav-link>
            @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
            <x-responsive-nav-link :href="route('clientes.index')" :active="request()->routeIs('clientes.*')" wire:navigate>
                {{ __('Clientes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contratos.index')" :active="request()->routeIs('contratos.*')" wire:navigate>
                {{ __('Contratos') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cuotas.index')" :active="request()->routeIs('cuotas.*')" wire:navigate>
                {{ __('Cuotas') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('servicios.index')" :active="request()->routeIs('servicios.*')" wire:navigate>
                {{ __('Servicios') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contabilidad.index')" :active="request()->routeIs('contabilidad.*')" wire:navigate>
                {{ __('Contabilidad') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('usuarios.index')" :active="request()->routeIs('usuarios.*')" wire:navigate>
                {{ __('Usuarios') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-white/20">
            <div class="px-4">
                <div class="font-medium text-base text-white" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-white/70">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
